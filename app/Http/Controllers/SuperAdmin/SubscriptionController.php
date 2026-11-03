<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\SubscriptionPayment;

class SubscriptionController extends Controller
{
    /**
     * Show the subscription overview.
     */
    public function index(Request $request)
    {
        $query = Subscription::with(['school', 'plan', 'payments']);

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->whereHas('school', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('school_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $subscriptions = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $totalSubscriptions = Subscription::count();

        $activeSubscriptions = Subscription::where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->count();

        $trialSubscriptions = Subscription::where('type', 'trial')
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>', now())
            ->count();

        $expiredSubscriptions = Subscription::where('ends_at', '<=', now())
            ->count();

        $plans = SubscriptionPlan::orderBy('price')->get();

        return view('super-admin.subscriptions.index', compact(
            'subscriptions',
            'totalSubscriptions',
            'activeSubscriptions',
            'trialSubscriptions',
            'expiredSubscriptions',
            'plans'
        ));
    }

    /**
     * Show the form for assigning a paid plan to a school.
     */
    public function create()
    {
        $schools = School::orderBy('name')->get();

        $plans = SubscriptionPlan::where('is_active', true)
            ->where('is_trial', false)
            ->orderBy('price')
            ->get();

        return view('super-admin.subscriptions.assign', compact(
            'schools',
            'plans'
        ));
    }

    /**
     * Create a pending subscription assignment.
     * Paid access is not activated by this action.
     */
    public function storeAssignment(Request $request)
    {
        $validated = $request->validate([
            'school_id' => [
                'required',
                'integer',
                'exists:schools,id',
            ],
            'subscription_plan_id' => [
                'required',
                'integer',
                'exists:subscription_plans,id',
            ],
            'starts_at' => [
                'required',
                'date',
            ],
            'agreed_price' => [
                'required',
                'numeric',
                'min:0',
                'max:999999999',
            ],
            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        // Only active paid plans can be assigned here.
        $plan = SubscriptionPlan::whereKey(
            $validated['subscription_plan_id']
        )
            ->where('is_active', true)
            ->where('is_trial', false)
            ->firstOrFail();

        $startsAt = Carbon::parse($validated['starts_at'])->startOfDay();

        if ($startsAt->lt(today())) {
            return back()
                ->withInput()
                ->withErrors([
                    'starts_at' => 'The subscription start date cannot be in the past.',
                ]);
        }

        // Prevent multiple current subscriptions for the same school.
        $hasCurrentSubscription = Subscription::where(
            'school_id',
            $validated['school_id']
        )
            ->whereIn('status', ['active', 'pending'])
            ->where('ends_at', '>', now())
            ->exists();

        if ($hasCurrentSubscription) {
            return back()
                ->withInput()
                ->withErrors([
                    'school_id' => 'This school already has an active or pending subscription. Review it before assigning another plan.',
                ]);
        }

        DB::transaction(function () use ($validated, $plan, $startsAt) {
            Subscription::create([
                'school_id' => $validated['school_id'],
                'subscription_plan_id' => $plan->id,
                'type' => 'paid',
                'status' => 'pending',
                'starts_at' => $startsAt,
                'ends_at' => $startsAt->copy()->addDays(
                    (int) $plan->duration_days
                ),
                'agreed_price' => $validated['agreed_price'],
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()
            ->route('super-admin.subscriptions.index')
            ->with(
                'success',
                'Plan assigned successfully. Record and confirm payment before activating access.'
            );

            
    }
    
/**
 * Show the form for recording an offline payment.
 */
public function createPayment(Subscription $subscription)
{
    $subscription->load(['school', 'plan']);

    abort_unless($subscription->type === 'paid', 404);

    if ($subscription->status !== 'pending') {
        return redirect()
            ->route('super-admin.subscriptions.index')
            ->with('error', 'Only pending paid subscriptions can receive a payment record.');
    }

    return view('super-admin.subscriptions.payments.create', [
        'subscription' => $subscription,
    ]);
}

/**
 * Record an offline payment.
 * Recording a payment does not activate the subscription.
 */
public function storePayment(Request $request, Subscription $subscription)
{
    abort_unless($subscription->type === 'paid', 404);

    if ($subscription->status !== 'pending') {
        return back()->with('error', 'This subscription is not pending.');
    }

    $validated = $request->validate([
        'amount' => ['required', 'numeric', 'min:0', 'max:999999999'],
        'payment_method' => [
            'required',
            Rule::in(['cash', 'bank_transfer', 'upi', 'cheque', 'other']),
        ],
        'reference_number' => ['nullable', 'string', 'max:255'],
        'paid_at' => ['required', 'date', 'before_or_equal:today'],
        'notes' => ['nullable', 'string', 'max:1000'],
    ]);

    DB::transaction(function () use ($subscription, $validated) {
        $lockedSubscription = Subscription::whereKey($subscription->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($lockedSubscription->status !== 'pending') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'payment' => 'This subscription is no longer pending.',
            ]);
        }

        // Prevent multiple pending or confirmed payment records.
        $existingPayment = SubscriptionPayment::where(
            'subscription_id',
            $lockedSubscription->id
        )->whereIn('status', ['pending', 'confirmed'])->exists();

        if ($existingPayment) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'payment' => 'A pending or confirmed payment already exists for this subscription.',
            ]);
        }

        SubscriptionPayment::create([
            'subscription_id' => $lockedSubscription->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'reference_number' => $validated['reference_number'] ?? null,
            'paid_at' => $validated['paid_at'],
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);
    });

    return redirect()
        ->route('super-admin.subscriptions.index')
        ->with('success', 'Payment recorded as pending. Verify the payment before confirming it.');
}

/**
 * Confirm a recorded payment and activate its subscription.
 */
public function confirmPayment(
    Subscription $subscription,
    SubscriptionPayment $payment
) {
    abort_unless(auth('super_admin')->check(), 403);

    if ((int) $payment->subscription_id !== (int) $subscription->id) {
        abort(404);
    }

    if ($subscription->type !== 'paid'
        || $subscription->status !== 'pending'
        || $payment->status !== 'pending') {
        return redirect()
            ->route('super-admin.subscriptions.index')
            ->with('error', 'This payment cannot be confirmed. Check its current status.');
    }

    $adminId = auth('super_admin')->id();

    DB::transaction(function () use ($subscription, $payment, $adminId) {
        $lockedSubscription = Subscription::whereKey($subscription->id)
            ->lockForUpdate()
            ->firstOrFail();

        $lockedPayment = SubscriptionPayment::whereKey($payment->id)
            ->where('subscription_id', $lockedSubscription->id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($lockedSubscription->status !== 'pending'
            || $lockedPayment->status !== 'pending') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'payment' => 'This payment has already been processed or the subscription is no longer pending.',
            ]);
        }

        if (bccomp(
            (string) $lockedPayment->amount,
            (string) $lockedSubscription->agreed_price,
            2
        ) !== 0) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'payment' => 'The recorded payment amount must match the agreed subscription price.',
            ]);
        }

        $lockedPayment->update([
            'status' => 'confirmed',
            'confirmed_by' => $adminId,
            'confirmed_at' => now(),
        ]);

        $lockedSubscription->update([
            'status' => 'active',
        ]);
    });

    return redirect()
        ->route('super-admin.subscriptions.index')
        ->with('success', 'Payment confirmed and subscription activated successfully.');
}

public function show(Subscription $subscription)
{
    $subscription->load([
        'school',
        'plan',
        'payments',
    ]);

    return view('super-admin.subscriptions.show', compact('subscription'));
}

public function edit(Subscription $subscription)
{
    $subscription->load([
        'school',
        'plan',
    ]);

    $plans = SubscriptionPlan::where('is_active', true)
        ->orderBy('price')
        ->get();

    return view('super-admin.subscriptions.edit', compact(
        'subscription',
        'plans'
    ));
}

public function update(Request $request, Subscription $subscription)
{
    $validated = $request->validate([
        'subscription_plan_id' => [
            'required',
            'integer',
            'exists:subscription_plans,id',
        ],
        'starts_at' => [
            'required',
            'date',
        ],
        'agreed_price' => [
            'required',
            'numeric',
            'min:0',
            'max:999999999.99',
        ],
        'notes' => [
            'nullable',
            'string',
            'max:2000',
        ],
    ]);

    $plan = SubscriptionPlan::query()
        ->where('is_active', true)
        ->where('is_trial', false)
        ->findOrFail($validated['subscription_plan_id']);

    // Do not change the agreed price while a payment is awaiting
    // confirmation or has already been confirmed.
    $hasRecordedPayment = $subscription->payments()
        ->whereIn('status', ['pending', 'confirmed'])
        ->exists();

    if (
        $hasRecordedPayment
        && (int) round((float) $validated['agreed_price'] * 100)
            !== (int) round((float) $subscription->agreed_price * 100)
    ) {
        return back()
            ->withInput()
            ->withErrors([
                'agreed_price' => 'The agreed price cannot be changed while a pending or confirmed payment exists.',
            ]);
    }

    $startDate = \Carbon\Carbon::parse($validated['starts_at'])
        ->startOfDay();

    $subscription->update([
        'subscription_plan_id' => $plan->id,
        'starts_at' => $startDate,
        'ends_at' => $startDate->copy()->addDays(
            (int) $plan->duration_days
        ),
        'agreed_price' => $validated['agreed_price'],
        'notes' => $validated['notes'] ?? null,
    ]);

    return redirect()
        ->route('super-admin.subscriptions.show', $subscription)
        ->with('success', 'Subscription details updated successfully.');
}

}

@php
    use App\Models\Subscription;
    use App\Models\SubscriptionPlan;
    use Illuminate\Support\Str;

    $user = auth()->user();
    $now = now();

    // Current active subscription
    $currentSubscription = null;

    if ($user && $user->school_id) {
        $currentSubscription = Subscription::with('plan')
            ->where('school_id', $user->school_id)
            ->where('status', 'active')
            ->where(function ($query) use ($now) {
                $query->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $now);
            })
            ->latest('id')
            ->first();
    }

    $currentPlan = $currentSubscription?->plan;
    $currentPlanName = $currentPlan?->name ?? 'No Active Plan';

    // Fetch active plans dynamically
    $plans = SubscriptionPlan::where('is_active', true)
        ->orderByRaw("
            CASE LOWER(name)
                WHEN 'free trial' THEN 1
                WHEN 'silver' THEN 2
                WHEN 'gold' THEN 3
                WHEN 'platinum' THEN 4
                ELSE 5
            END
        ")
        ->get();

    $schoolName = data_get($user, 'school.name') ?? 'My School';
    $adminName = $user?->name ?? 'School Admin';
    $adminEmail = $user?->email ?? 'Not available';

    $formatFeature = function ($feature) {
        return ucwords(str_replace(['_', '-'], ' ', $feature));
    };

    // How many features to show on a card before collapsing into "+N more"
    $maxFeatures = 4;

    // Visual styles only; plan values still come from the database.
    $planStyles = [
        'free trial' => ['class' => 'trial',    'icon' => 'bi-stars',  'label' => 'Starter'],
        'silver'     => ['class' => 'silver',   'icon' => 'bi-gem',    'label' => 'Popular'],
        'gold'       => ['class' => 'gold',     'icon' => 'bi-trophy', 'label' => 'Advanced'],
        'platinum'   => ['class' => 'platinum', 'icon' => 'bi-crown',  'label' => 'Premium'],
    ];
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Subscription Upgrade Required</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: 'Manrope', sans-serif;
            color: #283253;
            background:
                radial-gradient(circle at 10% 10%, rgba(91,111,255,.20), transparent 30%),
                radial-gradient(circle at 90% 15%, rgba(157,105,255,.20), transparent 30%),
                radial-gradient(circle at 50% 100%, rgba(80,174,255,.15), transparent 35%),
                #f4f6ff;
            overflow-x: hidden;
            position: relative;
        }

        .blur-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            opacity: .45;
            z-index: 0;
            pointer-events: none;
        }

        .orb-one   { width: 280px; height: 280px; background: #a6b2ff; top: -100px; left: -100px; }
        .orb-two   { width: 320px; height: 320px; background: #d8b4ff; right: -120px; top: 25%; }
        .orb-three { width: 280px; height: 280px; background: #a7ddff; bottom: -120px; left: 35%; }

        .page-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 22px 20px 36px;
        }

        /* ---------- Header ---------- */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 17px;
            font-weight: 800;
            color: #303b75;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 19px;
            background: linear-gradient(135deg, #5268ed, #9270f5);
            box-shadow: 0 8px 20px rgba(82,104,237,.25);
        }

        .secure-label {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 13px;
            border-radius: 30px;
            color: #387c62;
            background: rgba(255,255,255,.7);
            border: 1px solid rgba(255,255,255,.8);
            font-size: 10px;
            font-weight: 800;
            backdrop-filter: blur(15px);
        }

        /* ---------- Hero ---------- */
        .hero {
            text-align: center;
            max-width: 720px;
            margin: 0 auto 18px;
        }

        .hero-icon {
            width: 56px;
            height: 56px;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 18px;
            color: #fff;
            font-size: 24px;
            background: linear-gradient(135deg, #5268ed, #9873f6);
            box-shadow: 0 12px 28px rgba(82,104,237,.25);
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 13px;
            border-radius: 30px;
            color: #5268d9;
            background: rgba(255,255,255,.75);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .8px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .hero h1 {
            font-size: clamp(24px, 3vw, 33px);
            line-height: 1.2;
            font-weight: 800;
            letter-spacing: -1px;
            color: #252d52;
            margin-bottom: 8px;
        }

        .hero h1 span { color: #6172ed; }

        .hero p {
            color: #737d9b;
            font-size: 12px;
            line-height: 1.7;
        }

        /* ---------- Current plan ---------- */
        .current-plan {
            max-width: 720px;
            margin: 18px auto 24px;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            border-radius: 16px;
            background: rgba(255,255,255,.72);
            border: 1px solid rgba(255,255,255,.9);
            box-shadow: 0 10px 30px rgba(60,74,130,.07);
            backdrop-filter: blur(20px);
        }

        .current-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .current-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #5268ed;
            background: #edf0ff;
            font-size: 18px;
        }

        .current-left small {
            display: block;
            font-size: 9px;
            font-weight: 800;
            color: #929bb4;
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 3px;
        }

        .current-left strong {
            font-size: 14px;
            color: #303957;
        }

        .current-status {
            padding: 7px 12px;
            border-radius: 30px;
            background: #e9f8f0;
            color: #25835a;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 16px;
        }

        .section-heading h2 {
            color: #293253;
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .section-heading p {
            color: #8790aa;
            font-size: 11px;
        }

        /* ---------- Compact plan cards ---------- */
        .plans-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            max-width: 1100px;
            margin: auto;
            align-items: start; /* cards size to their own content */
        }

        .plan-card {
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 14px 14px 14px;
            min-width: 0;
            border-radius: 16px;
            background: rgba(255,255,255,.8);
            border: 1px solid rgba(255,255,255,.95);
            box-shadow: 0 8px 24px rgba(60,74,130,.08);
            backdrop-filter: blur(20px);
            transition: transform .25s ease, box-shadow .25s ease;
            overflow: hidden;
        }

        .plan-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(60,74,130,.13);
        }

        .plan-card::before {
            content: "";
            position: absolute;
            height: 3px;
            top: 0;
            left: 0;
            right: 0;
        }

        .trial::before    { background: linear-gradient(90deg, #4c9eff, #77c7ff); }
        .silver::before   { background: linear-gradient(90deg, #8b96ad, #c6cedc); }
        .gold::before     { background: linear-gradient(90deg, #e8a52e, #ffd16d); }
        .platinum::before { background: linear-gradient(90deg, #24a77b, #75d9b0); }

        .current-card { border: 2px solid #6679ee; }

        /* Header row: icon + name + label */
        .plan-head {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 8px;
        }

        .plan-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .trial .plan-icon    { background: #e8f3ff; color: #438de2; }
        .silver .plan-icon   { background: #edf0f5; color: #7d899e; }
        .gold .plan-icon     { background: #fff4dc; color: #d99a1e; }
        .platinum .plan-icon { background: #e3f8ef; color: #219b70; }

        .plan-card h3 {
            flex: 1;
            min-width: 0;
            font-size: 15px;
            font-weight: 800;
            color: #303956;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .plan-label {
            padding: 4px 7px;
            border-radius: 30px;
            font-size: 8px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
            background: #f0f2ff;
            color: #6472d8;
            white-space: nowrap;
        }

        /* Description clamped to 2 lines */
        .plan-description {
            color: #8991a8;
            font-size: 10px;
            line-height: 1.5;
            margin-bottom: 10px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Price + duration on one row */
        .plan-pricing {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 6px;
            padding-bottom: 10px;
            margin-bottom: 10px;
            border-bottom: 1px solid #edf0f6;
        }

        .plan-price {
            display: flex;
            align-items: baseline;
            gap: 2px;
        }

        .plan-price .currency {
            color: #65718e;
            font-size: 13px;
            font-weight: 700;
        }

        .plan-price .amount {
            color: #252f51;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .plan-duration {
            color: #929bb1;
            font-size: 9px;
            white-space: nowrap;
        }

        /* Features */
        .features-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 12px;
        }

        .features-list li {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            color: #707b97;
            font-size: 10px;
            line-height: 1.4;
        }

        .features-list i {
            color: #32a779;
            font-size: 11px;
            margin-top: 1px;
        }

        .features-list li.more {
            color: #6472d8;
            font-weight: 800;
        }

        .features-list li.more i { color: #6472d8; }

        .empty-features {
            color: #929bb1;
            font-size: 10px;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        /* Buttons */
        .contact-button {
            width: 100%;
            min-height: 34px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            padding: 8px;
            border-radius: 10px;
            background: linear-gradient(135deg, #5268ed, #8069ec);
            color: #fff;
            text-decoration: none;
            font-family: inherit;
            font-size: 10px;
            font-weight: 800;
            box-shadow: 0 6px 14px rgba(82,104,237,.18);
            transition: .2s ease;
        }

        .contact-button:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(82,104,237,.27);
        }

        .contact-button i:first-child { font-size: 13px; }

        .current-plan-button {
            background: #e9edf9;
            color: #5263bd;
            box-shadow: none;
            cursor: default;
        }

        .current-plan-button:hover {
            color: #5263bd;
            transform: none;
            box-shadow: none;
        }

        .no-plans {
            grid-column: 1 / -1;
            padding: 28px;
            text-align: center;
            background: rgba(255,255,255,.75);
            border-radius: 16px;
            color: #7b849e;
        }

        .no-plans i {
            display: block;
            font-size: 28px;
            color: #7382e8;
            margin-bottom: 10px;
        }

        /* ---------- Bottom panel ---------- */
        .bottom-panel {
            margin: 26px auto 0;
            max-width: 860px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            border-radius: 16px;
            background: rgba(255,255,255,.68);
            border: 1px solid rgba(255,255,255,.9);
            backdrop-filter: blur(20px);
            box-shadow: 0 10px 28px rgba(60,74,130,.06);
        }

        .bottom-info {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .bottom-info i {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            color: #6575e8;
            background: #edf0ff;
            font-size: 16px;
            flex-shrink: 0;
        }

        .bottom-info strong {
            display: block;
            color: #35405f;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .bottom-info span {
            color: #8992a9;
            font-size: 9px;
            line-height: 1.5;
        }

        .dashboard-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 9px 14px;
            border-radius: 10px;
            background: #303b67;
            color: #fff;
            text-decoration: none;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
            transition: .2s ease;
        }

        .dashboard-button:hover {
            background: #202a50;
            color: #fff;
            transform: translateY(-2px);
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            color: #9aa2b8;
            font-size: 9px;
            line-height: 1.8;
        }

        /* ---------- Responsive ---------- */
        @media (max-width: 1000px) {
            .plans-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 600px) {
            .page-wrapper { padding: 18px 12px 28px; }
            .brand { font-size: 14px; }
            .secure-label { font-size: 8px; padding: 7px 9px; }
            .current-plan { padding: 12px; }
            .plans-grid { grid-template-columns: 1fr; }
            .bottom-panel { align-items: flex-start; flex-direction: column; }
            .dashboard-button { width: 100%; }
        }
    </style>
</head>

<body>

    <div class="blur-orb orb-one"></div>
    <div class="blur-orb orb-two"></div>
    <div class="blur-orb orb-three"></div>

    <main class="page-wrapper">

        <!-- Header -->
        <header class="top-bar">
            <div class="brand">
                <div class="brand-icon">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <span>School Management</span>
            </div>

            <div class="secure-label">
                <i class="bi bi-shield-check"></i>
                Secure Subscription
            </div>
        </header>

        <!-- Hero -->
        <section class="hero">
            <div class="hero-icon">
                <i class="bi bi-lock-fill"></i>
            </div>

            <div class="hero-tag">
                <i class="bi bi-stars"></i>
                Subscription Access
            </div>

            <h1>
                Unlock More With a
                <span>Better Plan</span>
            </h1>

            <p>
                This feature is not included in your current subscription.
                Select a plan and contact the Super Admin to request an upgrade.
            </p>
        </section>

        <!-- Current Plan -->
        <section class="current-plan">
            <div class="current-left">
                <div class="current-icon">
                    <i class="bi bi-card-checklist"></i>
                </div>

                <div>
                    <small>Your Current Subscription</small>
                    <strong>{{ $currentPlanName }}</strong>
                </div>
            </div>

            <div class="current-status">
                <i class="bi bi-info-circle"></i>
                Upgrade Required
            </div>
        </section>

        <!-- Plans Heading -->
        <section class="section-heading">
            <h2>Choose Your Subscription Plan</h2>
            <p>Compare available plans and choose the right one for your school.</p>
        </section>

        <!-- Dynamic Plans -->
        <section class="plans-grid">

            @forelse($plans as $plan)

                @php
                    $planKey = strtolower($plan->name);

                    $style = $planStyles[$planKey] ?? [
                        'class' => 'silver',
                        'icon'  => 'bi-grid',
                        'label' => 'Available',
                    ];

                    $isCurrentPlan = $currentPlan && $currentPlan->id === $plan->id;

                    $features = is_array($plan->features) ? $plan->features : [];
                    $visibleFeatures = array_slice($features, 0, $maxFeatures);
                    $hiddenCount = max(count($features) - $maxFeatures, 0);

                    $price = number_format((float) $plan->price, 0);

                    $duration = $plan->duration_days
                        ? $plan->duration_days . ' days'
                        : 'As per plan terms';

                    $description = Str::limit(
                        $plan->description ?: 'A subscription plan for your school management needs.',
                        70
                    );

                    // WhatsApp message for the selected plan
                    $whatsappMessage =
                        "Hello Super Admin,\n\n" .
                        "I would like to request a subscription upgrade for my school.\n\n" .
                        "School: {$schoolName}\n" .
                        "School ID: " . ($user?->school_id ?? 'Not available') . "\n" .
                        "Admin Name: {$adminName}\n" .
                        "Admin Email: {$adminEmail}\n\n" .
                        "Current Plan: {$currentPlanName}\n" .
                        "Requested Plan: {$plan->name}\n" .
                        "Plan Price: ₹{$price}\n" .
                        "Plan Duration: {$duration}\n\n" .
                        "Please contact me regarding the subscription upgrade.\n\n" .
                        "Thank you.";
                @endphp

                <article class="plan-card {{ $style['class'] }} {{ $isCurrentPlan ? 'current-card' : '' }}">

                    <div class="plan-head">
                        <div class="plan-icon">
                            <i class="bi {{ $style['icon'] }}"></i>
                        </div>

                        <h3 title="{{ $plan->name }}">{{ $plan->name }}</h3>

                        <span class="plan-label">
                            {{ $isCurrentPlan ? 'Current' : $style['label'] }}
                        </span>
                    </div>

                    <p class="plan-description">{{ $description }}</p>

                    <div class="plan-pricing">
                        <div class="plan-price">
                            <span class="currency">₹</span>
                            <span class="amount">{{ $price }}</span>
                        </div>

                        <div class="plan-duration">
                            <i class="bi bi-calendar3"></i>
                            {{ $duration }}
                        </div>
                    </div>

                    @if(count($features))

                        <ul class="features-list">
                            @foreach($visibleFeatures as $feature)
                                <li>
                                    <i class="bi bi-check-circle-fill"></i>
                                    <span>{{ $formatFeature($feature) }}</span>
                                </li>
                            @endforeach

                            @if($hiddenCount > 0)
                                <li class="more">
                                    <i class="bi bi-plus-circle-fill"></i>
                                    <span>{{ $hiddenCount }} more {{ Str::plural('feature', $hiddenCount) }}</span>
                                </li>
                            @endif
                        </ul>

                    @else

                        <div class="empty-features">
                            Contact the Super Admin for feature details.
                        </div>

                    @endif

                    @if($isCurrentPlan)

                        <div class="contact-button current-plan-button">
                            <i class="bi bi-check-circle-fill"></i>
                            Your Current Plan
                        </div>

                    @else

                        <!-- Opens WhatsApp chat/contact chooser without a fixed number -->
                        <a
                            href="https://wa.me/?text={{ rawurlencode($whatsappMessage) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="contact-button"
                        >
                            <i class="bi bi-whatsapp"></i>
                            Contact Super Admin
                            <i class="bi bi-arrow-right"></i>
                        </a>

                    @endif

                </article>

            @empty

                <div class="no-plans">
                    <i class="bi bi-exclamation-circle"></i>
                    <strong>No Subscription Plans Available</strong>
                    <p>Please contact your Super Admin for assistance.</p>
                </div>

            @endforelse

        </section>

        <!-- Bottom Panel -->
        <section class="bottom-panel">

            <div class="bottom-info">
                <i class="bi bi-headset"></i>

                <div>
                    <strong>Need Help With Your Subscription?</strong>
                    <span>
                        Select the Super Admin from your WhatsApp contacts
                        or chats to send your upgrade request.
                    </span>
                </div>
            </div>

            <a
                href="{{ \Illuminate\Support\Facades\Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin/dashboard') }}"
                class="dashboard-button"
            >
                <i class="bi bi-house-door-fill"></i>
                Back to Dashboard
            </a>

        </section>

        <!-- Footer -->
        <footer class="footer">
            <div>
                <i class="bi bi-shield-lock"></i>
                Your school data and subscription information are protected.
            </div>

            <div>
                © {{ date('Y') }} School Management System. All rights reserved.
            </div>
        </footer>

    </main>

</body>
</html>
<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Models\OtherStaff;
use App\Models\Transport\TransportVehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransportVehicleController extends Controller
{
    /**
     * Display a listing of vehicles.
     */
    public function index(Request $request)
    {
        $query = TransportVehicle::with('driver');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'vehicle_number',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'vehicle_type',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'vehicle_model',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'driver_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'driver_contact',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'driver_license_number',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas('driver', function ($driverQuery) use ($search) {
                        $driverQuery
                            ->where(
                                'name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'phone',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'license_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'staff_id',
                                'like',
                                "%{$search}%"
                            );
                    });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        // Vehicle type filter
        if ($request->filled('vehicle_type')) {
            $query->where(
                'vehicle_type',
                $request->vehicle_type
            );
        }

        // Pagination
        $vehicles = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Dashboard statistics
        $totalVehicles = TransportVehicle::count();

        $activeVehicles = TransportVehicle::where(
            'status',
            'active'
        )->count();

        $maintenanceVehicles = TransportVehicle::where(
            'status',
            'maintenance'
        )->count();

        $inactiveVehicles = TransportVehicle::where(
            'status',
            'inactive'
        )->count();

        return view(
            'admin.transport.vehicles.index',
            compact(
                'vehicles',
                'totalVehicles',
                'activeVehicles',
                'maintenanceVehicles',
                'inactiveVehicles'
            )
        );
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        /*
         * Only active staff members whose designation
         * is Driver can be assigned to a vehicle.
         */
        $drivers = OtherStaff::where(
            'designation',
            'Driver'
        )
            ->where(
                'status',
                'Active'
            )
            ->orderBy('name')
            ->get([
                'id',
                'staff_id',
                'name',
                'phone',
                'license_number',
                'license_expiry',
            ]);

        return view(
            'admin.transport.vehicles.create',
            compact('drivers')
        );
    }

    /**
     * Store a newly created vehicle.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_number' => [
                'required',
                'string',
                'max:255',
                'unique:transport_vehicles,vehicle_number',
            ],

            'vehicle_type' => [
                'required',
                'string',
                'max:100',
            ],

            'vehicle_model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'vehicle_color' => [
                'nullable',
                'string',
                'max:100',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
            ],

            'driver_id' => [
                'nullable',
                Rule::exists(
                    'other_staff',
                    'id'
                )->where(function ($query) {
                    $query
                        ->where(
                            'designation',
                            'Driver'
                        )
                        ->where(
                            'status',
                            'Active'
                        );
                }),
            ],

            'insurance_expiry' => [
                'nullable',
                'date',
            ],

            'fitness_expiry' => [
                'nullable',
                'date',
            ],

            'permit_expiry' => [
                'nullable',
                'date',
            ],

            /*
             * Status is now selected from the Add Vehicle page.
             */
            'status' => [
                'required',
                'in:active,inactive,maintenance',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
         * Fetch the selected Driver from Other Staff.
         *
         * We do not trust manually submitted driver details.
         */
        if (!empty($validated['driver_id'])) {

            $driver = OtherStaff::where(
                'id',
                $validated['driver_id']
            )
                ->where(
                    'designation',
                    'Driver'
                )
                ->where(
                    'status',
                    'Active'
                )
                ->firstOrFail();

            $validated['driver_name'] = $driver->name;

            $validated['driver_contact'] = $driver->phone;

            $validated['driver_license_number'] =
                $driver->license_number;
        } else {

            $validated['driver_name'] = null;

            $validated['driver_contact'] = null;

            $validated['driver_license_number'] = null;
        }

        /*
         * Create the vehicle.
         */
        TransportVehicle::create($validated);

        return redirect()
            ->route(
                'admin.transport.vehicles.index'
            )
            ->with(
                'success',
                'Vehicle added successfully.'
            );
    }

    /**
     * Display the specified vehicle.
     */
    public function show(
        TransportVehicle $transportVehicle
    ) {
        $transportVehicle->load('driver');

        return view(
            'admin.transport.vehicles.show',
            compact('transportVehicle')
        );
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(
        TransportVehicle $transportVehicle
    ) {
        $drivers = OtherStaff::where(
            'designation',
            'Driver'
        )
            ->where(
                'status',
                'Active'
            )
            ->orderBy('name')
            ->get([
                'id',
                'staff_id',
                'name',
                'phone',
                'license_number',
                'license_expiry',
            ]);

        $transportVehicle->load('driver');

        return view(
            'admin.transport.vehicles.edit',
            compact(
                'transportVehicle',
                'drivers'
            )
        );
    }

    /**
     * Update the specified vehicle.
     */
    public function update(
        Request $request,
        TransportVehicle $transportVehicle
    ) {
        $validated = $request->validate([
            'vehicle_number' => [
                'required',
                'string',
                'max:255',
                'unique:transport_vehicles,vehicle_number,' .
                    $transportVehicle->id,
            ],

            'vehicle_type' => [
                'required',
                'string',
                'max:100',
            ],

            'vehicle_model' => [
                'nullable',
                'string',
                'max:255',
            ],

            'vehicle_color' => [
                'nullable',
                'string',
                'max:100',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
            ],

            'driver_id' => [
                'nullable',
                Rule::exists(
                    'other_staff',
                    'id'
                )->where(function ($query) {
                    $query
                        ->where(
                            'designation',
                            'Driver'
                        )
                        ->where(
                            'status',
                            'Active'
                        );
                }),
            ],

            'insurance_expiry' => [
                'nullable',
                'date',
            ],

            'fitness_expiry' => [
                'nullable',
                'date',
            ],

            'permit_expiry' => [
                'nullable',
                'date',
            ],

            /*
             * Status can be changed from the Edit Vehicle page.
             */
            'status' => [
                'required',
                'in:active,inactive,maintenance',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
         * Refresh driver details from Other Staff.
         */
        if (!empty($validated['driver_id'])) {

            $driver = OtherStaff::where(
                'id',
                $validated['driver_id']
            )
                ->where(
                    'designation',
                    'Driver'
                )
                ->where(
                    'status',
                    'Active'
                )
                ->firstOrFail();

            $validated['driver_name'] = $driver->name;

            $validated['driver_contact'] = $driver->phone;

            $validated['driver_license_number'] =
                $driver->license_number;
        } else {

            $validated['driver_name'] = null;

            $validated['driver_contact'] = null;

            $validated['driver_license_number'] = null;
        }

        /*
         * Update the vehicle.
         */
        $transportVehicle->update($validated);

        return redirect()
            ->route(
                'admin.transport.vehicles.index'
            )
            ->with(
                'success',
                'Vehicle updated successfully.'
            );
    }

    /**
     * Remove the specified vehicle.
     */
    public function destroy(
        TransportVehicle $transportVehicle
    ) {
        $transportVehicle->delete();

        return redirect()
            ->route(
                'admin.transport.vehicles.index'
            )
            ->with(
                'success',
                'Vehicle deleted successfully.'
            );
    }
}
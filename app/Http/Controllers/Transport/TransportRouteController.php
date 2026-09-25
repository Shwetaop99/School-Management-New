<?php

namespace App\Http\Controllers\Transport;

use App\Http\Controllers\Controller;
use App\Models\OtherStaff;
use App\Models\Transport\TransportRoute;
use App\Models\Transport\TransportVehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransportRouteController extends Controller
{
    /**
     * Display all transport routes.
     */
    public function index(Request $request)
    {
        $query = TransportRoute::with([
            'driver',
            'vehicle',
        ])->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('route_number', 'like', "%{$search}%")
                    ->orWhere('route_name', 'like', "%{$search}%")
                    ->orWhere('starting_point', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%")
                    ->orWhere('assigned_vehicle', 'like', "%{$search}%")

                    // Search vehicle information
                    ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                        $vehicleQuery
                            ->where('vehicle_number', 'like', "%{$search}%")
                            ->orWhere('vehicle_type', 'like', "%{$search}%")
                            ->orWhere('vehicle_model', 'like', "%{$search}%");
                    })

                    // Search driver information
                    ->orWhereHas('driver', function ($driverQuery) use ($search) {
                        $driverQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('staff_id', 'like', "%{$search}%");
                    });
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination
        $routes = $query
            ->paginate(10)
            ->withQueryString();

        // Summary Statistics
        $totalRoutes = TransportRoute::count();

        $activeRoutes = TransportRoute::where(
            'status',
            'active'
        )->count();

        $inactiveRoutes = TransportRoute::where(
            'status',
            'inactive'
        )->count();

        $assignedVehicles = TransportRoute::whereNotNull(
            'vehicle_id'
        )->count();

        return view(
            'admin.transport.routes.index',
            compact(
                'routes',
                'totalRoutes',
                'activeRoutes',
                'inactiveRoutes',
                'assignedVehicles'
            )
        );
    }

    /**
     * Show create route form.
     */
    public function create()
    {
        /*
         * Get only active staff members
         * whose designation is Driver.
         */
        $drivers = OtherStaff::where('designation', 'Driver')
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        /*
         * Get only active transport vehicles.
         */
        $vehicles = TransportVehicle::where('status', 'active')
            ->orderBy('vehicle_number')
            ->get();

        return view(
            'admin.transport.routes.create',
            compact(
                'drivers',
                'vehicles'
            )
        );
    }

    /**
     * Store a new transport route.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'route_number' => [
                'required',
                'string',
                'max:100',
                'unique:transport_routes,route_number',
            ],

            'route_name' => [
                'required',
                'string',
                'max:255',
            ],

            'starting_point' => [
                'required',
                'string',
                'max:255',
            ],

            'destination' => [
                'required',
                'string',
                'max:255',
            ],

            'stops' => [
                'nullable',
                'string',
            ],

            /*
             * Vehicle comes from Transport Vehicles.
             */
            'vehicle_id' => [
                'nullable',
                Rule::exists('transport_vehicles', 'id')->where(
                    function ($query) {
                        $query->where('status', 'active');
                    }
                ),
            ],

            /*
             * Keep assigned_vehicle accepted for
             * backward compatibility with old records.
             */
            'assigned_vehicle' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
             * Driver comes directly from Other Staff.
             */
            'driver_id' => [
                'nullable',
                Rule::exists('other_staff', 'id')->where(
                    function ($query) {
                        $query->where('designation', 'Driver')
                            ->where('status', 'Active');
                    }
                ),
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
         * Automatically keep assigned_vehicle
         * synchronized with the selected vehicle.
         *
         * Only vehicle_id is the real relationship.
         */
        if (!empty($validated['vehicle_id'])) {

            $vehicle = TransportVehicle::find(
                $validated['vehicle_id']
            );

            if ($vehicle) {
                $validated['assigned_vehicle'] =
                    $vehicle->vehicle_number;
            }
        } else {
            $validated['assigned_vehicle'] = null;
        }

        TransportRoute::create($validated);

        return redirect()
            ->route('admin.transport.routes.index')
            ->with(
                'success',
                'Transport route added successfully.'
            );
    }

    /**
     * Display a specific transport route.
     */
    public function show(TransportRoute $transportRoute)
    {
        /*
         * Load driver and vehicle information.
         */
        $transportRoute->load([
            'driver',
            'vehicle',
        ]);

        return view(
            'admin.transport.routes.show',
            compact('transportRoute')
        );
    }

    /**
     * Show edit route form.
     */
    public function edit(TransportRoute $transportRoute)
    {
        /*
         * Get only active drivers.
         */
        $drivers = OtherStaff::where('designation', 'Driver')
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        /*
         * Get only active vehicles.
         */
        $vehicles = TransportVehicle::where('status', 'active')
            ->orderBy('vehicle_number')
            ->get();

        /*
         * Load existing relationships.
         */
        $transportRoute->load([
            'driver',
            'vehicle',
        ]);

        return view(
            'admin.transport.routes.edit',
            compact(
                'transportRoute',
                'drivers',
                'vehicles'
            )
        );
    }

    /**
     * Update an existing transport route.
     */
    public function update(
        Request $request,
        TransportRoute $transportRoute
    ) {
        $validated = $request->validate([

            'route_number' => [
                'required',
                'string',
                'max:100',
                'unique:transport_routes,route_number,' .
                $transportRoute->id,
            ],

            'route_name' => [
                'required',
                'string',
                'max:255',
            ],

            'starting_point' => [
                'required',
                'string',
                'max:255',
            ],

            'destination' => [
                'required',
                'string',
                'max:255',
            ],

            'stops' => [
                'nullable',
                'string',
            ],

            /*
             * Vehicle comes from Transport Vehicles.
             */
            'vehicle_id' => [
                'nullable',
                Rule::exists('transport_vehicles', 'id')->where(
                    function ($query) {
                        $query->where('status', 'active');
                    }
                ),
            ],

            /*
             * Backward compatibility.
             */
            'assigned_vehicle' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
             * Driver comes from Other Staff.
             */
            'driver_id' => [
                'nullable',
                Rule::exists('other_staff', 'id')->where(
                    function ($query) {
                        $query->where('designation', 'Driver')
                            ->where('status', 'Active');
                    }
                ),
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'remarks' => [
                'nullable',
                'string',
            ],
        ]);

        /*
         * Automatically synchronize vehicle number.
         */
        if (!empty($validated['vehicle_id'])) {

            $vehicle = TransportVehicle::find(
                $validated['vehicle_id']
            );

            if ($vehicle) {
                $validated['assigned_vehicle'] =
                    $vehicle->vehicle_number;
            }
        } else {
            $validated['assigned_vehicle'] = null;
        }

        $transportRoute->update($validated);

        return redirect()
            ->route('admin.transport.routes.index')
            ->with(
                'success',
                'Transport route updated successfully.'
            );
    }

    /**
     * Delete a transport route.
     */
    public function destroy(TransportRoute $transportRoute)
    {
        $transportRoute->delete();

        return redirect()
            ->route('admin.transport.routes.index')
            ->with(
                'success',
                'Transport route deleted successfully.'
            );
    }
}
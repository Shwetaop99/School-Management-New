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

        /*
         * Search routes, vehicles and drivers.
         */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('route_number', 'like', "%{$search}%")
                    ->orWhere('route_name', 'like', "%{$search}%")
                    ->orWhere('starting_point', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%")
                    ->orWhere('assigned_vehicle', 'like', "%{$search}%")

                    /*
                     * Search vehicle information.
                     */
                    ->orWhereHas('vehicle', function ($vehicleQuery) use ($search) {
                        $vehicleQuery
                            ->where('vehicle_number', 'like', "%{$search}%")
                            ->orWhere('vehicle_type', 'like', "%{$search}%")
                            ->orWhere('vehicle_model', 'like', "%{$search}%");
                    })

                    /*
                     * Search driver information.
                     */
                    ->orWhereHas('driver', function ($driverQuery) use ($search) {
                        $driverQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%")
                            ->orWhere('staff_id', 'like', "%{$search}%");
                    });
            });
        }

        /*
         * Status filter.
         */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
         * Pagination.
         */
        $routes = $query
            ->paginate(10)
            ->withQueryString();

        /*
         * Summary statistics.
         */
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
     *
     * IMPORTANT:
     * Only ACTIVE vehicles are supplied to the form.
     */
    public function create()
    {
        /*
         * Only active staff members whose designation
         * is Driver can be assigned.
         */
        $drivers = OtherStaff::where('designation', 'Driver')
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        /*
         * ONLY ACTIVE VEHICLES.
         *
         * Inactive and maintenance vehicles will not be
         * available in the Add Route form.
         *
         * We fetch the vehicle fields required by the
         * route form so the selected vehicle data can
         * be displayed/fetched immediately.
         */
        $vehicles = TransportVehicle::where('status', 'active')
            ->orderBy('vehicle_number')
            ->get([
                'id',
                'vehicle_number',
                'vehicle_type',
                'vehicle_model',
                'capacity',
                'driver_id',
                'driver_name',
                'driver_contact',
                'driver_license_number',
                'status',
            ]);

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
             * VEHICLE SECURITY:
             *
             * A vehicle must:
             * 1. Exist
             * 2. Have status = active
             *
             * Therefore inactive/maintenance vehicles
             * cannot be assigned even if somebody manually
             * changes the HTML request.
             */
            'vehicle_id' => [
                'nullable',
                'integer',
                Rule::exists('transport_vehicles', 'id')->where(
                    function ($query) {
                        $query->where('status', 'active');
                    }
                ),
            ],

            /*
             * Kept for backward compatibility.
             *
             * The controller will overwrite this value
             * with the selected active vehicle number.
             */
            'assigned_vehicle' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
             * Driver must be an active Driver.
             */
            'driver_id' => [
                'nullable',
                'integer',
                Rule::exists('other_staff', 'id')->where(
                    function ($query) {
                        $query
                            ->where('designation', 'Driver')
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
         * ---------------------------------------------------------
         * FETCH SELECTED VEHICLE
         * ---------------------------------------------------------
         *
         * Do not use find() here because we need to make sure
         * the vehicle is STILL active at the moment of saving.
         */
        if (!empty($validated['vehicle_id'])) {

            $vehicle = TransportVehicle::where(
                'id',
                $validated['vehicle_id']
            )
                ->where('status', 'active')
                ->first();

            /*
             * This protects against a vehicle becoming inactive
             * or maintenance between form loading and submission.
             */
            if (!$vehicle) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'vehicle_id' =>
                            'The selected vehicle is no longer active and cannot be assigned to this route.',
                    ]);
            }

            /*
             * Always fetch the vehicle number from the
             * actual active vehicle record.
             */
            $validated['assigned_vehicle'] =
                $vehicle->vehicle_number;
        } else {
            $validated['assigned_vehicle'] = null;
        }

        /*
         * Create route.
         */
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
         * Load complete driver and vehicle relationships.
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
     *
     * Only currently ACTIVE vehicles are available
     * for a new/replacement assignment.
     */
    public function edit(TransportRoute $transportRoute)
    {
        /*
         * Only active drivers.
         */
        $drivers = OtherStaff::where('designation', 'Driver')
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        /*
         * ONLY ACTIVE VEHICLES.
         *
         * Inactive and maintenance vehicles are not
         * offered as replacement vehicles.
         */
        $vehicles = TransportVehicle::where('status', 'active')
            ->orderBy('vehicle_number')
            ->get([
                'id',
                'vehicle_number',
                'vehicle_type',
                'vehicle_model',
                'capacity',
                'driver_id',
                'driver_name',
                'driver_contact',
                'driver_license_number',
                'status',
            ]);

        /*
         * Load existing route relationships.
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
             * ONLY ACTIVE VEHICLES CAN BE ASSIGNED.
             */
            'vehicle_id' => [
                'nullable',
                'integer',
                Rule::exists('transport_vehicles', 'id')->where(
                    function ($query) {
                        $query->where('status', 'active');
                    }
                ),
            ],

            /*
             * Backward compatibility.
             *
             * Controller synchronizes this automatically.
             */
            'assigned_vehicle' => [
                'nullable',
                'string',
                'max:255',
            ],

            /*
             * Only active Drivers can be assigned.
             */
            'driver_id' => [
                'nullable',
                'integer',
                Rule::exists('other_staff', 'id')->where(
                    function ($query) {
                        $query
                            ->where('designation', 'Driver')
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
         * ---------------------------------------------------------
         * FETCH SELECTED VEHICLE AGAIN
         * ---------------------------------------------------------
         *
         * This makes sure an inactive/maintenance vehicle
         * cannot be assigned through a manipulated request.
         */
        if (!empty($validated['vehicle_id'])) {

            $vehicle = TransportVehicle::where(
                'id',
                $validated['vehicle_id']
            )
                ->where('status', 'active')
                ->first();

            if (!$vehicle) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'vehicle_id' =>
                            'The selected vehicle is no longer active and cannot be assigned to this route.',
                    ]);
            }

            /*
             * Synchronize the old assigned_vehicle field
             * with the real vehicle relationship.
             */
            $validated['assigned_vehicle'] =
                $vehicle->vehicle_number;
        } else {
            $validated['assigned_vehicle'] = null;
        }

        /*
         * Update route.
         */
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
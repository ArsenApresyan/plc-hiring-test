<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Services\VehicleViewService;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    public function __construct(private VehicleViewService $vehicleViews) {}

    public function show(Vehicle $vehicle): JsonResponse
    {
        $this->vehicleViews->recordView($vehicle->id);

        return response()->json($vehicle);
    }

    public function trending(): JsonResponse
    {
        // TODO: return the top 10 most-viewed vehicles in the last 24h,
        //   each with their vehicle data and view count.
        //   The frontend will poll this every 30s.
        return response()->json(['error' => 'Not implemented'], 501);
    }
}

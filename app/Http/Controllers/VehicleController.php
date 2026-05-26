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
        return response()->json($this->vehicleViews->getTrending());
    }
}

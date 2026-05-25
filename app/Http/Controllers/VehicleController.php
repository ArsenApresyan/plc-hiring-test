<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;

class VehicleController extends Controller
{
    public function show(Vehicle $vehicle): JsonResponse
    {
        // TODO: return the vehicle and increment its view counter.
        //   This endpoint will be hit ~50 req/sec at peak.
        //   Naive UPDATE … SET views = views + 1 on every request is the wrong answer.
        return response()->json(['error' => 'Not implemented'], 501);
    }

    public function trending(): JsonResponse
    {
        // TODO: return the top 10 most-viewed vehicles in the last 24h,
        //   each with their vehicle data and view count.
        //   The frontend will poll this every 30s.
        return response()->json(['error' => 'Not implemented'], 501);
    }
}

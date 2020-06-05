<?php

namespace App\Http\Controllers;

use App\Models\HighPeople;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class HighPeopleController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            ['throttle'],
            [
                'except' => ['getHighPeople']
            ]
        );
    }

    /**
     * Get high people count
     */
    public function getHighPeople(): JsonResponse
    {
        try {
            $high = HighPeople::find(1);
            return response()->json([
                'count' => $high->count
            ], 200);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json([
                'error' => 'Database error'
            ], 500);
        }
    }

    /**
     * Increment high people counter
     */
    public function incrementHighPeople(): JsonResponse
    {
        try {
            $high = HighPeople::find(1);
            $high->count += 1;
            $high->save();
            return response()->json([
                'count' => $high->count
            ], 200);
        } catch (\Throwable $e) {
            Log::error($e);
            return response()->json([
                'error' => 'Database error'
            ], 500);
        }
    }
}

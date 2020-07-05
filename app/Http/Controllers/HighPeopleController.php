<?php

namespace App\Http\Controllers;

use App\Models\HighPeople;
use Illuminate\Http\JsonResponse;
use Log;

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
     * @api {get} /high/people      Get high people counter.
     * @apiName GetHighPeople
     * @apiGroup HighPeople
     *
     * @apiSuccess {Int} count      High people counter
     *
     * @apiError (Error 500) DatabaseError
     *
     * @return JsonResponse
     */
    public function getHighPeople(): JsonResponse
    {
        try
        {
            $high = HighPeople::find(1);
            return response()->json([
                'count' => $high->count
            ], 200);
        }
        catch (\Throwable $e)
        {
            return response()->json([
                'error' => 'DatabaseError'
            ], 500);
        }
    }

    /**
     * @api {put} /high/people      Increment high people counter.
     * @apiName IncrementHighPeople
     * @apiGroup HighPeople
     *
     * @apiSuccess {Int} count      High people counter
     *
     * @apiError (Error 500) DatabaseError
     *
     * @return JsonResponse
     */
    public function incrementHighPeople(): JsonResponse
    {
        try
        {
            $high = HighPeople::find(1);
            $high->count += 1;
            $high->save();
            return response()->json([
                'count' => $high->count
            ], 200);
        }
        catch (\Throwable $e)
        {
            return response()->json([
                'error' => 'DatabaseError'
            ], 500);
        }
    }
}

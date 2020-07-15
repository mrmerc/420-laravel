<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        return response()->json([
            'message' => 'test from base api',
            'ip' => request()->ip()
        ]);
    }

    public function authTest()
    {
        return response()->json([
            'message' => 'test from auth protected api'
        ]);
    }
}

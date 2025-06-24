<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function test(){
        dd(1);
        Log::info('Test log message', [
            'request' => request()->all(),
            'session' => session()->all(),
        ]);
    }
}

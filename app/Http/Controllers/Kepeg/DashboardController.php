<?php

namespace App\Http\Controllers\Kepeg;

use App\Http\Controllers\Controller;

class DashboardController
extends Controller
{
    public function index()
    {
        return view(
            'kepeg.dashboard'
        );
    }
}
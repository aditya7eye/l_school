<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Plan_Controller extends Controller
{
    function addplan()
    {
        return view('AdminContent.planview');
    }
}

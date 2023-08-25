<?php

namespace App\Http\Controllers\Works;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimeEntryController extends Controller
{
    public function myTimeEntry()
    {
        return view('time-entry.myTimeEntry');
    }

    public function allEmployeeTimeEntry()
    {
        return view('time-entry.allEmployeeTimeEntry');
    }
}

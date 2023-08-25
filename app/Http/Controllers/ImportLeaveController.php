<?php

namespace App\Http\Controllers;

use App\Imports\LeaveImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportLeaveController extends Controller
{
    public function index(Request $request)
    {
        $filename = Storage::path('public/inex_leave_manager.csv');
        Log::info("Start: import old leave");
        Excel::import(new LeaveImport, $filename);
        Log::info("End: import old leave");
        echo "All Done!";
    }
}

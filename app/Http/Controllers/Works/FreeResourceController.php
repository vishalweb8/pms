<?php

namespace App\Http\Controllers\Works;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Http\Controllers\commonController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\DailyTask;
use App\Models\User;
use App\Models\UserOfficialDetail;
use App\Models\FreeResource;
use Spatie\Permission\Exceptions\UnauthorizedException;

class FreeResourceController extends Controller
{
    //

    public function index() {
        
        $teams = (new commonController)->getTeams();
        $loggedInUser = Auth::user();

        if(!Helper::hasAnyPermission('free-resource.list')) {
            $exception = new UnauthorizedException(403, trans('messages.PERMISSION_ERROR'));
            return view('errors.403')->with(['exception'=>$exception]);
        }
        $date_filters = [
            'today' => 'Today',
            'week' => 'This Week',
            'this_month' => 'This Month',
            'last_month' => 'Last Month',
            'this_year' => 'This Year',
            'last_year' => 'Last Year',
        ];

        // For not show the last year option if current year is 2022
        if(now()->year == "2022") {
            unset($date_filters['last_year']);
        }
        return view('works.free-resource', compact('teams', 'date_filters', 'loggedInUser'));
    }
    public function list(Request $request) {
        if($request->filled('date')) {

            $from_date = now();
            $to_date = now();
            $software_start_date = Carbon::parse('2022-01-31');
            if($request->date == 'week') {
                $from_date = $from_date->startOfWeek(Carbon::MONDAY);
                $to_date = $to_date->endOfWeek(Carbon::SUNDAY);
            }elseif($request->date == 'this_month') {
                $from_date = $from_date->startOfMonth();
                $to_date = $to_date->endOfMonth();
            }elseif($request->date == 'last_month') {
                $from_date = $from_date->subMonth()->startOfMonth();
                $to_date = $to_date->subMonth()->endOfMonth();
            }elseif($request->date == 'this_year') {
                $from_date = $from_date->startOfYear();
                $to_date = $to_date->endOfYear();
            }elseif($request->date == 'last_year') {
                $from_date = $from_date->subYear()->startOfYear();
                $to_date = $to_date->subYear()->endOfYear();
            }

            if($from_date->lessThan($software_start_date)){
                $from_date = $software_start_date;
            }

            $fromDate = $from_date->format('Y-m-d');
            $toDate  = $to_date->format('Y-m-d');
            $teamID = 0;
            if($request->filled('team')) {
                $teamID = $request->team;
            }
            $freeResourceRepo = FreeResource::with(['user'])
                        ->where('task_date', '>=', DB::raw("'$fromDate'"))
                        ->where('task_date', '<=', DB::raw("'$toDate'"))
                        ->wherehas('user.officialUser.userTeam', function($query) use($teamID) {
                            if($teamID != 0) {
                                $query->where('id', $teamID);
                            }
                        });
            $freeResourceRepo = $freeResourceRepo->orderby('task_date','desc')->get()->groupBy('task_date');

            return view('works.partials.free-resource-row', compact('freeResourceRepo'));
        }
        return false; // Default return blank as html;
    }
}

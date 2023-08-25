<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        view()->share('theme', "layouts.index");
    }

    /**
     * for get external projects
     *
     * @return void
     */
    public function getExternalProjects()
    {
        $select[''] = 'Please select';

        $projects = Project::select('name','id', 'project_code')->orderBy('project_code', 'desc')->where('project_type',1)->get();
        if(!empty($projects)) {
            $projects->each(function ($entry) {
                $entry->name = $entry->project_code . " - " . $entry->name;
                return $entry;
            });
            $projects = $projects->pluck('name', 'id')->toArray();
            $select = $select + $projects;
        }
        return $select;
    }
}

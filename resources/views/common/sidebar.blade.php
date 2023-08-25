@php
    $route_name = request()->route()->getName();
@endphp
<div class="vertical-menu">

    <div data-simplebar class="sidebar-scroll h-100">
        <div id="sidebar-menu">
            <ul class="metismenu list-unstyled mm-show" id="side-menu">
                @if( Helper::hasAnyPermission(['user-dashboard.view']))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="false">
                        <i class="bx bx-home-circle"></i>
                        <span class="text-truncate" key="t-dashboards">Dashboard</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false">
                        @if (Helper::hasAnyPermission(['user-dashboard.view']))
                        <li><a href="{{ route('user-dashboard') }}">My Dashboard</a></li>
                        @endif
                        <li><a href="{{ route('task-dashboard') }}">Task Dashboard</a></li>
                        {{-- @if (Helper::hasAnyPermission(['admin-dashboard.view']))
                        <li><a href="{{ route('admin-dashboard') }}" key="t-products">Admin Dashboard</a></li>
                        @endif --}}
                    </ul>
                </li>
                @endif
                @if(Helper::hasAnyPermission(['all-employee.list','all-project.list', 'all-BDEs.list', 'all-teams.list', 'free-resource.list', 'worklog.list', 'worklog-defaulters.list']) && Helper::isMentor())
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="false">
                        <i class="bx bx-home-circle"></i>
                        <span class="text-truncate" key="t-dashboards">Resource Managment</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false">
                        @if (Helper::hasAnyPermission(['all-employee.list']))
                            <li><a href="{{ route('resource-mgt-all-employee') }}">All Employee</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['all-project.list']))
                            <li><a href="{{ route('resource-mgt-all-projects') }}">All Projects</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['all-BDEs.list']))
                            <li><a href="{{ route('resource-mgt-all-bdes') }}">All BDEs</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['all-teams.list']))
                            <li><a href="{{ route('resource-mgt-total-teams') }}">All Teams</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['free-resource.list']))
                            <li><a href="{{ route('freeResource.index') }}" key="t-products">Free Resources</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['worklog.list']))
                            <li><a href="{{ route('resource-mgt-monthly-worklog') }}" key="t-products">Employee Worklogs</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['all-emp-time-entry.list']))
                            <li><a href="{{ route('resource-mgt-monthly-time-entry') }}" key="t-products">Employee Time Entry</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['employee-exp.list']))
                            <li><a href="{{ route('resource-mgt-employee-exp') }}" key="t-products">Employee Experience</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['worklog-defaulters.list']))
                            <li><a href="{{ route('work-log-defaulters') }}" key="t-products">Worklog Defaulters</a></li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(Helper::hasAnyPermission(['organization-chart.show']))
                <li class="{{ (request()->route()->getName() === 'organizationChart.show') ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="false">
                        <i class="mdi mdi-clipboard-text-search-outline"></i>
                        <span key="t-analytic">Analytics</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false">
                        @if (Helper::hasAnyPermission(['company-revenue.list']))
                        <li><a href="{{route('company.revenue')}}" key="t-org-report">Company Revenue</a></li>
                        @endif
                        @if (Auth::user()->email == "admin@inexture.in")
                        <li><a href="{{route('expectedRevenue.index')}}" key="t-org-report">Manage Expected Revenue</a></li>
                        @endif
                        {{-- @if (Helper::hasAnyPermission(['expected-revenue.view']))
                        <li><a href="{{route('expectedRevenue.show',1)}}" key="t-org-report">Expected Revenue</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['actual-revenue.list']))
                        <li><a href="{{route('actual.revenue')}}" key="t-org-report">Actual Revenue</a></li>
                        @endif --}}
                        @if (Helper::hasAnyPermission(['actual-revenue.list']))
                        <li><a href="{{route('actual.vs.expected')}}" key="t-org-report">Actual v/s Expected Revenue</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['organization-chart.show']))
                        <li><a href="{{route('organizationChart.show')}}" key="t-org-report">Organization Chart</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['performer.show']))
                        <li><a href="{{route('performer.show')}}" key="t-org-report">Performers</a></li>
                        @endif

                    </ul>
                </li>
                @endif
                @if(Helper::hasAnyPermission(['daily-tasks.list','daily-tasks-all-emp.list']))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="false">
                        <i class="mdi mdi-clipboard-check-outline"></i>
                        <span class="text-truncate" key="t-ecommerce">Daily SOD</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false">
                    @if(Helper::hasAnyPermission('daily-tasks.list') && Auth::user()->sod_eod_enabled == 1)
                        <li
                            class="{{ (in_array(request()->route()->getName(),['work-index','work-create','work-edit','add-sod'])) ? 'mm-active' : '' }}">
                            <a class="{{ (in_array(request()->route()->getName(),['work-index','work-create','work-edit','add-sod'])) ? 'mm-active' : '' }}"
                                href="{{ route('work-index') }}" key="t-products">My SOD</a>
                        </li>

                        @endif
                        @if(Helper::hasAnyPermission('daily-tasks-all-emp.list') || (count((new App\Http\Controllers\commonController)->getMentorUsers())) > 0)
                        <li
                            class="{{ (in_array(request()->route()->getName(),['edit-daily-task'])) ? 'mm-active' : '' }}">
                            <a class="{{ (in_array(request()->route()->getName(),['edit-daily-task'])) ? 'mm-active' : '' }}"
                                href="{{ route('admin-index') }}" key="t-products">All Employees</a>
                        </li>
                        @endif
                        {{-- <li><a href="javascript:void(0)" key="t-product-detail">Time entry</a></li> --}}
                        <li><a href="{{ route('defaulters') }}" key="t-products">Defaulters</a></li>
                    </ul>
                </li>
                @endif
                @if(Helper::hasAnyPermission(['my-time-entry.list','all-emp-time-entry.list']))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="false">
                        <i class="bx bx-stopwatch"></i>
                        <span class="text-truncate" key="t-dashboards">Time Entry's</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false">
                        @if(Helper::hasAnyPermission(['my-time-entry.list']))
                        <li><a href="{{ route('my-time-entry') }}">My Time Entry</a></li>
                        @endif
                        @if(Helper::hasAnyPermission(['all-emp-time-entry.list']))
                        <li><a href="{{ route('all-employee-time-entry') }}">All EMP Time Entry</a></li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(Helper::hasAnyPermission(['project.list']))
                <li class="{{ (request()->is('project*')) ? 'mm-active' : '' }}">
                    <a href="{{ route('project-dashboard') }}" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi-briefcase-variant-outline"></i>
                        <span key="t-calendar">My Projects</span>
                    </a>
                </li>
                @endif
                @if(Helper::hasAnyPermission(['super-admin-project.list']))
                <li class="{{ (request()->is('super-admin/project*')) ? 'mm-active' : '' }}">
                    <a href="{{ route('super-admin-project-dashboard') }}" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi-briefcase-account-outline"></i>
                        <span key="t-calendar">Project Management</span>
                    </a>
                </li>
                @endif

                @if(Helper::hasAnyPermission(['lead.list']))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="false">
                        <i class="mdi mdi-shape-rectangle-plus"></i>
                        <span key="t-ecommerce">Leads</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false">
                        @if(Helper::hasAnyPermission(['lead.list']))
                        <li
                            class="{{ (in_array(request()->route()->getName(),['lead.list','lead.add','lead.edit', 'lead.view'])) ? 'mm-active' : '' }}">
                            <a href="{{ route('lead.list') }}"
                                class="{{ (in_array(request()->route()->getName(),['lead.list','lead.add','lead.edit', 'lead.view'])) ? 'active' : '' }}">My
                                Leads</a>
                        </li>
                        <li
                            class="{{ (in_array(request()->route()->getName(),['lead.all'])) ? 'mm-active' : '' }}">
                            <a href="{{ route('lead.all') }}"
                                class="{{ (in_array(request()->route()->getName(),['lead.all'])) ? 'active' : '' }}">All Leads</a>
                        </li>
                        @endif
                        @if(Helper::hasAnyPermission(['lead-statistics.list']))
                        <li
                            class="{{ (in_array(request()->route()->getName(),['lead-statistics','lead.add','lead.edit', 'lead.view'])) ? 'mm-active' : '' }}">
                            <a href="{{ route('lead-statistics') }}"
                                class="{{ (in_array(request()->route()->getName(),['lead-statistics'])) ? 'active' : '' }}">Lead Statistics</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{-- <li>
                    <a href="javascript: void(0);" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi-clipboard-text-search-outline"></i>
                        <span key="t-chat">Analytics reports</span>
                    </a>
                </li> --}}
                @if(Helper::hasAnyPermission(['leave.list','leaveteam.list','leaveallemp.list','leavestatisticsallemp.list']))
                {{-- @dd(in_array(request()->route()->getName(),['leave-team','leave-team-view','leave-add-team']));
                --}}
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="false">
                        <i class="mdi mdi-calendar-today"></i>
                        <span key="t-ecommerce">Leaves</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false">
                        @if(Helper::hasAnyPermission(['leave.list']))
                        <li
                            class="{{ (in_array(request()->route()->getName(),['leave-dashboard','leave-add-view','leave-view'])) ? 'mm-active' : '' }}">
                            <a href="{{ route('leave-dashboard') }}"
                                class="{{ (in_array(request()->route()->getName(),['leave-dashboard','leave-add-view','leave-view'])) ? 'active' : '' }}">My
                                Leaves</a>
                        </li>
                        @endif
                        @if(Helper::hasAnyPermission(['leaveteam.list']))
                        <li
                            class="{{ (in_array(request()->route()->getName(),['leave-team','leave-team-view','leave-add-team'])) ? 'mm-active' : '' }}">
                            <a href="{{ route('leave-team') }}"
                                class="{{ (in_array(request()->route()->getName(),['leave-team','leave-team-view','leave-add-team'])) ? 'active' : '' }}">Leave
                                Requests</a>
                        </li>
                        @endif
                        @if(Helper::hasAnyPermission(['leaveallemp.list']))
                        <li
                            class="{{ (in_array(request()->route()->getName(),['leave-add-all','leave-view-all','leave-all-employee'])) ? 'mm-active' : '' }}">
                            <a class="{{ (in_array(request()->route()->getName(),['leave-add-all','leave-view-all','leave-all-employee'])) ? 'mm-active' : '' }}"
                                href="{{ route('leave-all-employee') }}" key="t-product-detail">All Employee Leaves</a>
                        </li>
                        @endif
                        @if(Helper::hasAnyPermission(['leavestatisticsallemp.list']))
                        <li
                            class="{{ (in_array(request()->route()->getName(),['leave-statistics-all-employee'])) ? 'mm-active' : '' }}">
                            <a class="{{ (in_array(request()->route()->getName(),['leave-statistics-all-employee'])) ? 'mm-active' : '' }}"
                                href="{{ route('leave-statistics-all-employee') }}" key="t-product-detail">Leaves Statistics</a>
                        </li>
                        @endif
                        @if(Auth::user()->role_id != "1")
                            @if(Helper::hasAnyPermission(['leavecompensation.list']))
                            <li
                                class="{{ (in_array(request()->route()->getName(),['leave-compensation-dashboard','leave-add-compensation-view','leave-compensation-view'])) ? 'mm-active' : '' }}">
                                <a href="{{ route('leave-compensation-dashboard') }}"
                                    class="{{ (in_array(request()->route()->getName(),['leave-compensation-dashboard','leave-add-compensation-view','leave-compensation-view'])) ? 'active' : '' }}">Leave Compensation</a>
                            </li>
                            @endif
                        @endif
                        @if(Helper::hasAnyPermission(['leavecompensationallemp.list']))
                        <li
                            class="{{ (in_array(request()->route()->getName(),['leave-add-compensation-all','leave-view-compensation-all','leave-compensation-all-employee'])) ? 'mm-active' : '' }}">
                            <a class="{{ (in_array(request()->route()->getName(),['leave-add-compensation-all','leave-view-compensation-all','leave-compensation-all-employee'])) ? 'mm-active' : '' }}"
                                href="{{ route('leave-compensation-all-employee') }}" key="t-product-detail">Leave Compensation Requests</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(Helper::hasAnyPermission(['wfh.list','wfhteam.list','wfhallemp.list']))
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="false">
                        <i class="mdi mdi-folder-home-outline"></i>
                        <span key="t-ecommerce">Work From Home</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false">
                        @if(Helper::hasAnyPermission('wfh.list'))
                        <li
                            class="{{ (in_array(request()->route()->getName(),['wfh-dashboard','wfh-add-view','wfh-view','wfh-edit-view'])) ? 'mm-active' : '' }}">
                            <a class="{{ (in_array(request()->route()->getName(),['wfh-dashboard','wfh-add-view','wfh-view','wfh-edit-view'])) ? 'mm-active' : '' }}"
                                href="{{ route('wfh-dashboard') }}" key="t-products">My WFH Requests</a>
                        </li>
                        @endif
                        @if(Helper::hasAnyPermission(['wfhteam.list']))
                        <li class="{{ (in_array(request()->route()->getName(),['wfh-team','wfh-add-team','wfh-team-view','wfh-edit-team'])) ? 'mm-active' : '' }}"
                            href="{{ route('wfh-dashboard') }}">
                            <a class="{{ (in_array(request()->route()->getName(),['wfh-team','wfh-add-team','wfh-team-view','wfh-edit-team'])) ? 'mm-active' : '' }}"
                                href="{{ route('wfh-team') }}" key="t-product-detail">WFH Requests</a>
                        </li>
                        @endif
                        @if(Helper::hasAnyPermission(['wfhallemp.list']))
                        <li class="{{ (in_array(request()->route()->getName(),['wfh-all-emp-index','wfh-all-emp-edit','wfh-all-emp-add','wfh-all-emp-view'])) ? 'mm-active' : '' }}"
                            href="{{ route('wfh-dashboard') }}">
                            <a class="{{ (in_array(request()->route()->getName(),['wfh-all-emp-index','wfh-all-emp-edit','wfh-all-emp-add','wfh-all-emp-view'])) ? 'mm-active' : '' }}"
                                href="{{ route('wfh-all-emp-index') }}" key="t-product-detail">All Employee WFH</a>
                        </li>
                        @endif
                        @if(Helper::hasAnyPermission(['wfhstatisticsallemp.list']))
                        <li
                            class="{{ (in_array(request()->route()->getName(),['wfh-statistics-all-employee'])) ? 'mm-active' : '' }}">
                            <a class="{{ (in_array(request()->route()->getName(),['wfh-statistics-all-employee'])) ? 'mm-active' : '' }}"
                                href="{{ route('wfh-statistics-all-employee') }}" key="t-product-detail">WFH Statistics</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if (Helper::hasAnyPermission(['user.list']))
                <li
                    class="{{ (in_array(request()->route()->getName(),['user.index','user.create','user.edit','user.destroy'])) ? 'mm-active' : '' }}">
                    <a href="{{ route('user.index') }}" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi-account-group"></i>
                        <span key="t-calendar">Employees</span>
                    </a>
                </li>
                @endif

                {{-- <li>
                    <a href="calendar.html" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi-file-clock-outline"></i>
                        <span key="t-calendar">Interview Schedule</span>
                    </a>
                </li> --}}
                @if(Helper::hasAnyPermission(['event.list']))
                <li>
                    <a href="{{route('event.index')}}" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi-drama-masks"></i>
                        <span key="t-calendar">Events</span>
                    </a>
                </li>
                @endif
                @if (Helper::hasAnyPermission(['client.list']))
                <li>
                    <a href="{{route('client.index')}}" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi mdi-account-tie"></i>
                        <span key="t-client">Clients</span>
                    </a>
                </li>
                @endif
                @if(Helper::hasAnyPermission(['designation.list','department.list','project-priority.list','project-payment.list','project-status.list','allocation.list','technology.list','roles.list','teams.list']))
                <li class="{{ (request()->route()->getName() === 'roles.permission') ? 'mm-active' : '' }}">
                    <a href="javascript: void(0);" class="has-arrow waves-effect" aria-expanded="false">
                        <i class="mdi mdi-alpha-m-circle-outline"></i>
                        <span key="t-master">Masters</span>
                    </a>
                    <ul class="sub-menu mm-collapse" aria-expanded="false">
                        @if (Helper::hasAnyPermission(['designation.list']))
                        <li><a href="{{route('designation.index')}}" key="t-designation">User Designations</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['department.list']))
                        <li><a href="{{route('department.index')}}" key="t-departments">Departments</a></li>
                        @endif
                        @if(Helper::hasAnyPermission(['project-priority.list']))
                        <li><a href="{{route('project-priority.index')}}" key="t-project-prorities">Project
                                Priorities</a></li>
                        @endif
                        @if(Helper::hasAnyPermission(['project-payment.list']))
                        <li><a href="{{route('project-payment.index')}}" key="t-payment-type">Project Payment Types</a>
                        </li>
                        @endif
                        @if(Helper::hasAnyPermission(['project-status.list']))
                        <li><a href="{{route('project-status.index')}}" key="t-status">Project Statuses</a></li>
                        @endif
                        @if(Helper::hasAnyPermission(['allocation.list']))
                        <li><a href="{{route('allocation.index')}}" key="t-allocation">Project Allocations</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['technology.list']))
                        <li><a href="{{route('technology.index')}}" key="t-techonologies">Technologies</a></li>
                        @endif
                        @if(Helper::hasAnyPermission(['roles.list']))
                        <li class="{{ (request()->route()->getName() === 'roles.permission') ? 'mm-active' : '' }}"><a
                                href="{{route('roles.index')}}" key="t-roles">Roles</a></li>
                        @endif
                        @if(Helper::hasAnyPermission(['teams.list']))
                        <li><a href="{{route('teams.index')}}" key="t-teams">Teams</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['consultancy.list']))
                        <li><a href="{{ route('consultancy.index') }}" key="t-consultancy">Consultancies</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['organization-chart.list']))
                        <li><a href="{{ route('organizationChart.index') }}" key="t-consultancy">Organization Chart</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['performer.list']))
                        <li><a href="{{ route('performer.index') }}" key="t-performer">Performers</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['lead-status.list']))
                        <li><a href="{{ route('lead-status.index') }}" key="t-lead-status">Lead Statuses</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['lead-source.list']))
                        <li><a href="{{ route('lead-source.index') }}" key="t-lead-source">Lead Sources</a></li>
                        @endif
                        @if (Helper::hasAnyPermission(['industry.list']))
                        <li><a href="{{ route('industry.index') }}" key="t-industry">Industries</a></li>
                        @endif
                    </ul>
                </li>
                @endif


                {{-- <li>
                    <a href="javascript: void(0);" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi-lock-check-outline"></i>
                        <span key="t-calendar">Permission</span>
                    </a>
                </li> --}}
                @if (Helper::hasAnyPermission(['holiday.list']))
                <li>
                    <a href="{{ route('holiday.index') }}" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi-island"></i>
                        <span key="t-calendar">Holidays</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{route('user.profile')}}" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi-account-circle-outline"></i>
                        <span key="t-calendar">My Profile</span>
                    </a>
                </li>
                @if (Helper::hasAnyPermission(['policy.list']))
                <li>
                    <a href="{{ route('policy.index') }}" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi-gavel"></i>
                        <span key="t-calendar">Policies</span>
                    </a>
                </li>
                @endif
                {{-- @if (Helper::hasAnyPermission(['policy.list']))
                <li>
                    <a href="{{ route('setting.index') }}" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi-table-settings"></i>
                        <span key="t-calendar">Setting</span>
                    </a>
                </li>
                @endif --}}
                {{-- @if (Helper::hasAnyPermission(['interview-detail.list']))
                <li>
                    <a href="{{ route('interview-detail.index') }}" class="waves-effect" aria-expanded="false">
                        <i class="mdi mdi-gavel"></i>
                        <span key="t-calendar">Interview Details</span>
                    </a>
                </li>
                @endif --}}
            </ul>
        </div>
    </div>
</div>

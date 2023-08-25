<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\commonController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\TechnologyController;
use App\Http\Controllers\Master\UserDesignationController;
use App\Http\Controllers\Master\ProjectAllocationController;
use App\Http\Controllers\Master\DepartmentController;
use App\Http\Controllers\Master\ProjectStatusController;
use App\Http\Controllers\Master\ProjectPriorityController;
use App\Http\Controllers\Master\ProjectPaymentTypeController;
use App\Http\Controllers\Master\RolesController;
use App\Http\Controllers\Master\TeamsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Works\DailyTaskManagementController;
use App\Http\Controllers\Works\TimeEntryController;
use App\Http\Controllers\Project\ProjectController;
use App\Http\Controllers\SuperAdmin\SuperProjectController;
use App\Http\Controllers\Leave\LeaveController;
use App\Http\Controllers\Master\ClientController;
use App\Http\Controllers\Master\HolidayController;
use App\Http\Controllers\Master\PolicyController;
use App\Http\Controllers\Master\EventController;
use App\Http\Controllers\Master\RolePermissionController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\Interview\InterviewdetailController;
use App\Http\Controllers\Master\ConsultancyController;
use App\Http\Controllers\WFH\WorkFromHomeController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AutomationController;
use App\Http\Controllers\ExpectedRevenueController;
use App\Http\Controllers\ImportLeaveController;
use App\Http\Controllers\Master\OrganizationChartController;
use App\Http\Controllers\TimeLogEntry\EmpTimeLogEntryController;
use App\Http\Controllers\Works\DefaulterController;
use App\Http\Controllers\Master\LeadStatusController;
use App\Http\Controllers\Master\LeadSourceController;
use App\Http\Controllers\Master\IndustryController;
use App\Http\Controllers\Master\PerformerController;
use App\Http\Controllers\Lead\LeadController;
use App\Http\Controllers\Master\SettingController;
use App\Http\Controllers\SuperAdmin\PaymentHistoryController;
use App\Http\Controllers\Works\FreeResourceController;
use App\Http\Controllers\ResourceMgt\ResourceManagmentController;
use App\Http\Controllers\TaskDashboardController;
use App\Models\User;
use App\Models\UserOfficialDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return view('index');
});


Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('/user-dashboard', [DashboardController::class, 'userDashboard'])->name('user-dashboard');
    Route::get('/update-pending-entries', [DashboardController::class, 'updatePendingEntries'])->name('pending');
    Route::get('/admin-dashboard', [AdminDashboardController::class, 'index'])->name('admin-dashboard');
    Route::get('/resource-mgt-all-employee',[ResourceManagmentController::class, 'allEmployeeView'])->name('resource-mgt-all-employee');
    Route::get('/all-resource-list',[ResourceManagmentController::class, 'allEmployeeList']);
    Route::get('/add-resource-project/{user_id}',[ResourceManagmentController::class, 'addResourceProject'])->name('add-resource-project');
    Route::post('/store-resource-project',[ResourceManagmentController::class, 'storeResourceProject'])->name('store-resource-project');
    Route::post('/unassign-resource-project',[ResourceManagmentController::class, 'unassignProjects'])->name('unassign-resource-project');
    Route::get('/resource-mgt-employee-exp',[ResourceManagmentController::class, 'employeeExp'])->name('resource-mgt-employee-exp');
    Route::get('/resource-mgt-total-teams',[ResourceManagmentController::class, 'totalTeamsView'])->name('resource-mgt-total-teams');
    Route::get('/resource-mgt-all-projects',[ResourceManagmentController::class, 'allProjectsView'])->name('resource-mgt-all-projects');
    Route::get('/resource-mgt-all-bdes',[ResourceManagmentController::class, 'allBDEsView'])->name('resource-mgt-all-bdes');
    Route::get('/resource-mgt-monthly-worklog',[ResourceManagmentController::class, 'getMonthlyWorkLog'])->name('resource-mgt-monthly-worklog');
    Route::get('/resource-mgt-monthly-time-entry',[ResourceManagmentController::class, 'monthlyTimeEntry'])->name('resource-mgt-monthly-time-entry');

    Route::get('/admin-monthly-time-entry',[AdminDashboardController::class, 'monthlyTimeEntry'])->name('admin-monthly-time-entry');
    Route::get('/admin-monthly-worklog',[AdminDashboardController::class, 'adminMonthlyWorkLog'])->name('admin-monthly-worklog');
    Route::get('/admin-filtered-worklog',[AdminDashboardController::class, 'adminFilteredWorkLog'])->name('admin-filtered-worklog');
    Route::get('/admin-employee', [AdminDashboardController::class, 'adminEmployeeList'])->name('employee-list');
    Route::get('/admin-employee-experience', [AdminDashboardController::class, 'adminEmployeeExp'])->name('employee-experience');
    Route::get('/admin-projects', [AdminDashboardController::class, 'adminProjectList'])->name('project-list');
    Route::get('/admin-projects-bde', [AdminDashboardController::class, 'adminProjectBDEList'])->name('project-list-bde');
    Route::get('/admin-all-team', [AdminDashboardController::class, 'adminAllTeam'])->name('all-team');
    Route::get('/modal', [DashboardController::class, 'addModal'])->name('admin-modal');
    Route::get('/view-myleave-modal', [DashboardController::class, 'viewLeaveModal'])->name('view-myleave-modal');
    Route::get('/view-emp-leave-modal/{id}/{fyear?}', [DashboardController::class, 'viewEmpLeaveModal'])->name('view-emp-leave-modal');
    Route::get('/view-todayLeave-modal', [DashboardController::class, 'viewtodayLeaveModal'])->name('view-todayLeave-modal');
    Route::get('/view-upcomingLeave-modal', [DashboardController::class, 'viewUpcomingLeave'])->name('view-upcomingLeave-modal');
    Route::get('/view-todayWFH-modal', [DashboardController::class, 'viewtodayWFHModal'])->name('view-todayWFH-modal');
    Route::get('/view-timeentry-modal', [DashboardController::class, 'viewTimeentryModal'])->name('view-timeentry-modal');
    Route::get('/view-today-time-entry/{emp_code?}/{date?}', [DashboardController::class, 'viewTodayTimeEntryModal'])->name('view-today-time-entry');
    Route::get('/check-leave-allocation', [DashboardController::class, 'checkLeaveAllocationCalculation']);

    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('fetch-states', [App\Http\Controllers\commonController::class, 'fetchState'])->name('get-state');
    Route::post('fetch-cities', [App\Http\Controllers\commonController::class, 'fetchCity'])->name('get-city');
    Route::post('fetch-leaves', [App\Http\Controllers\commonController::class, 'fetchUserLeaves'])->name('get-leaves');
    Route::get('get-user-designation-team', [commonController::class, 'getUserDesigTeam'])->name('getUserDesigTeam');
    Route::get('makeEodEntry', [AutomationController::class, 'makeEodEntry'])->name('makeEodEntry');
    Route::get('/calculate-experience', function (Request $request) {
        $users = UserOfficialDetail::whereNull('experience')->get();
        $userController = new UsersController();
        foreach ($users as $user) {
            $userController->countAndUpdateExpYears($user->user_id);
        }
    });

    Route::group(['prefix' => 'works'], function () {
        Route::get('index', [DailyTaskManagementController::class, 'index'])->name('work-index');
        Route::get('createtask/{date?}', [DailyTaskManagementController::class, 'create'])->name('work-create');
        Route::post('create', [DailyTaskManagementController::class, 'store']);
        Route::get('edittask/{id}', [DailyTaskManagementController::class, 'edit'])->name('work-edit');
        Route::post('update', [DailyTaskManagementController::class, 'store']);
        Route::delete('delete/{id}', [DailyTaskManagementController::class, 'delete']);
        Route::post('/verified-by-admin', [DailyTaskManagementController::class, 'taskVerifiedByAdmin'])->name('task-verified-by-admin');
        Route::get('admin-index', [DailyTaskManagementController::class, 'adminIndex'])->name('admin-index');
        Route::post('/add-sod', [DailyTaskManagementController::class, 'addTask'])->name('add-sod');
        Route::post('fetch/record', [DailyTaskManagementController::class, 'fetchRecord'])->name('fetch-record');
        Route::get('edit-daily-task/{id}', [DailyTaskManagementController::class, 'editDailyTask'])->name('edit-daily-task');
        Route::post('edit-daily-task/store', [DailyTaskManagementController::class, 'editDailyTaskStore'])->name('edit-daily-task-store');
        Route::get('filter-daily-task', [DailyTaskManagementController::class, 'filterDailyTask'])->name('filter-daily-task');
        // Route::get('/daily-task/more',[DailyTaskManagementController::class, 'filterDailyTask'])->name('filter-daily-task');
        Route::get('/employee-time-entry', [DailyTaskManagementController::class, 'employeeEntryList'])->name('employee-entry-list');
        Route::get('/defaulters', [DefaulterController::class, 'defaulters'])->name('defaulters');
        Route::get('/get-defaulters', [DefaulterController::class, 'getDefaulterData'])->name('defaulter-list');
        Route::get('/free-resources', [FreeResourceController::class, 'index'])->name('freeResource.index');
        Route::get('/get-free-resources', [FreeResourceController::class, 'list'])->name('freeResource.list');
        Route::get('/work-log-defaulters', [DefaulterController::class, 'defaulters'])->name('work-log-defaulters');
    });


    // Time enteries routes
    // Route::group(['prefix' => 'time-entries'], function () {
    //     Route::get('/', [TimeEntryController::class, 'myTimeEntry'])->name('my-time-entry');
    //     Route::get('/all-employee', [TimeEntryController::class, 'allEmployeeTimeEntry'])->name('employee-time-entry');
    // });

    Route::group(['prefix' => 'project'], function () {
        Route::get('/', [ProjectController::class, 'dashboard'])->name('project-dashboard');
        // Route::get('/{id}', [ProjectController::class, 'view'])->name('project-view');
        Route::get('/{id}/all-member', [ProjectController::class, 'allMembers'])->name('project-all-members');
        Route::get('/{id}/details',[ProjectController::class, 'projectDetails'])->name('project-details');
        Route::post('/save-project-members',[ProjectController::class, 'saveProjectMembers'])->name('save-project-members');
        Route::post('/remove-project-members',[ProjectController::class, 'removeProjectMembers'])->name('remove-project-members');
        Route::post('/save-project-files-link',[ProjectController::class, 'saveFileLinks'])->name('save-project-files-link');
        Route::post('/change-project-priority',[ProjectController::class, 'changeProjectPriority'])->name('change-project-priority');
        Route::post('/delete-project-files-link',[ProjectController::class, 'deleteFileLinks'])->name('delete-project-files-link');
        Route::get('/set-data-in-pivot', [ProjectController::class, 'setDataInPivot'])->name('set-data-in-pivot');

        Route::group(['prefix' => '{id}/tasks'], function () {
            Route::get('/add', [ProjectController::class, 'addTask'])->name('add-project-task');
            Route::get('/edit/{task_id}', [ProjectController::class, 'addTask'])->name('edit-project-task');
            Route::get('/view/{task_id}', [ProjectController::class, 'viewTask'])->name('view-project-task');
            Route::delete('/delete', [ProjectController::class, 'deleteTask'])->name('delete-project-task');
            Route::post('/store', [ProjectController::class, 'storeTask'])->name('store-task-details');
            Route::post('/save-log', [ProjectController::class, 'saveWorkLogOfTask'])->name('save-work-log-of-task');
        });
        Route::get('/get-work-log', [ProjectController::class, 'getWorkLogOfTask'])->name('get-work-log-of-task');
        Route::post('/delete-work-log', [ProjectController::class, 'deleteWorkLogOfTask'])->name('delete-work-log-of-task');
        Route::post('/change-tab', [ProjectController::class, 'changeValueOfSelectedTab'])->name('change-selected-tab-value');
        Route::get('/{id}/updated-task-details', [ProjectController::class, 'getNewDetailsOfTask'])->name('get-new-details-of-task');
        Route::post('/worklog-user/{task_id}', [ProjectController::class, 'worklogOfParticularUser'])->name('worklog-of-selected-user');
    });

    // employee (Users)
    Route::group(['prefix' => 'user'], function () {
        Route::get('index', [UsersController::class, 'index'])->name('user.index');
        Route::get('create', [UsersController::class, 'create'])->name('user.create');
        Route::get('edit/{id}', [UsersController::class, 'edit'])->name('user.edit');
        Route::delete('delete/{id}', [UsersController::class, 'destroy'])->name('user.destroy');
        Route::any('download/{id}', [UsersController::class, 'download'])->name('user.download');
        Route::post('status/{id}',[UsersController::class, 'statusUpdate'])->name('user.status');
        Route::post('remove-image',[UsersController::class,'removeImage'])->name('user.removeimage');

        Route::any('personal-details', [UsersController::class, 'storePersonalDetail'])->name('user.personal-details');
        Route::match(['patch', 'post'],'official-details', [UsersController::class, 'storeOfficialDetail'])->name('user.official-details');
        Route::any('education-details', [UsersController::class, 'storeEducationDetail'])->name('user.education-details');
        Route::any('bank-details', [UsersController::class, 'storeBankDetail'])->name('user.bank-details');
        Route::any('experience-details', [UsersController::class, 'storeExperienceDetail'])->name('user.experience-details');
        Route::any('family-details', [UsersController::class, 'storeFamilyDetail'])->name('user.family-details');
        Route::delete('users-education/{id}', [UsersController::class, 'deleteEducationDetail'])->name('delete-user.education_details');
        Route::delete('users-experience/{id}', [UsersController::class, 'deleteExperienceDetail'])->name('delete-user.experience_details');
        Route::delete('users-family/{id}', [UsersController::class, 'deleteFamilyDetail'])->name('delete-user.family_details');
        Route::get('team-leader-members', [UsersController::class, 'getTeamLeaderMembers'])->name('user.team-leader-members');

        Route::get('known-technologies/{id}', [UsersController::class, 'getKnownTechnology'])->name('user.getKnownTechnology');
        Route::get('employee-information', [UsersController::class, 'getEmployeeInfo'])->name('user.getInfo');


        Route::group(['prefix' => 'profile'], function () {
            Route::get('/', [UsersController::class, 'userProfile'])->name('user.profile');
            Route::get('/edit-office-detail', [UsersController::class, 'editOfficeDetail'])->name('user.profile.edit.office');
            Route::get('/edit-bank', [UsersController::class, 'editUserBank'])->name('user.profile.edit.bank');
            Route::get('/edit-education', [UsersController::class, 'editUserEducation'])->name('user.profile.edit.education');
            Route::get('/edit-family', [UsersController::class, 'editUserFamily'])->name('user.profile.edit.family');
            Route::get('/edit-experience', [UsersController::class, 'editUserExperience'])->name('user.profile.edit.experience');
            Route::get('/edit-user', [UsersController::class, 'editUserPersonal'])->name('user.profile.edit');
            Route::post('/profile-upload', [UsersController::class, 'profileUploadImage'])->name('profileimage-upload');
            Route::post('/change-password', [UsersController::class, 'changePassword'])->name('user.change.password');
        });
    });

    Route::group(['prefix' => 'leave'], function () {
        Route::get('/', [LeaveController::class, 'dashboard'])->name('leave-dashboard');
        Route::get('/add/{id?}', [LeaveController::class, 'addLeaveView'])->name('leave-add-view');
        Route::post('/save', [LeaveController::class, 'saveLeaveData'])->name('leave-save');
        Route::get('/team', [LeaveController::class, 'teamLeave'])->name('leave-team');
        Route::get('/manage', [LeaveController::class, 'manageLeave'])->name('leave-manage');
        Route::get('/view/{id}', [LeaveController::class, 'viewLeave'])->name('leave-view');
        Route::get('/teamview/{id}', [LeaveController::class, 'viewTeamLeave'])->name('leave-team-view');
        Route::get('/team/add/{id?}', [LeaveController::class, 'addLeaveTeamView'])->name('leave-add-team');

        Route::get('/{id}/comments/show', [LeaveController::class, 'showLeaveComments'])->name('leaves-comments-view');
        Route::post('/comment-request', [LeaveController::class, 'leaveRequest'])->name('comment-request');
        Route::post('/cancel/{id}', [LeaveController::class, 'cancelLeave'])->name('leave-cancel');
        Route::post('/set', [LeaveController::class, 'setTotalLeave'])->name('leave-set-total');
        Route::get('/all/employee', [LeaveController::class, 'allEmployeeLeave'])->name('leave-all-employee');
        Route::get('all/employee/{id}', [LeaveController::class, 'viewTeamLeave'])->name('leave-view-all');
        Route::get('all/leaves/statistics', [LeaveController::class, 'allEmployeeLeaveStatistics'])->name('leave-statistics-all-employee');
        Route::get('/all/add/{id?}', [LeaveController::class, 'addLeaveTeamView'])->name('leave-add-all');
        Route::post('/all/cancel/{id}', [LeaveController::class, 'cancelLeave'])->name('leave-cancel-all');
        Route::post('/fetch-request-from', [LeaveController::class, 'fetchRequestFrom'])->name('get-request-from');

        Route::get('/compensation/dashboard', [LeaveController::class, 'leaveCompensationDashboard'])->name('leave-compensation-dashboard');
        Route::get('/compensation/add/{id?}', [LeaveController::class, 'addLeaveCompensationView'])->name('leave-add-compensation-view');
        Route::post('/compensation/save', [LeaveController::class, 'saveLeaveCompensationData'])->name('leave-compensation-save');
        Route::get('/compensation/view/{id}', [LeaveController::class, 'viewLeaveCompensation'])->name('leave-compensation-view');
        Route::get('/compensation/teamview/{id}', [LeaveController::class, 'viewTeamLeaveCompensation'])->name('leave-compensation-team-view');
        Route::post('/compensation/cancel/{id}', [LeaveController::class, 'cancelLeaveCompensation'])->name('leave-compensation-cancel');

        Route::get('/compensation/all/employee', [LeaveController::class, 'allEmployeeCompensationLeave'])->name('leave-compensation-all-employee');
        Route::get('compensation/all/employee/{id}', [LeaveController::class, 'viewAllLeaveCompensation'])->name('leave-view-compensation-all');
        Route::get('/compensation/all/add/{id?}', [LeaveController::class, 'addAllLeaveCompensationView'])->name('leave-add-compensation-all');
        Route::post('/compensation/comment-request', [LeaveController::class, 'leaveRequestCompensation'])->name('compensation-comment-request');

    });
    Route::group(['prefix' => 'work-from-home'], function () {
        Route::get('/', [WorkFromHomeController::class, 'dashboard'])->name('wfh-dashboard');
        Route::get('/add', [WorkFromHomeController::class, 'addWfhView'])->name('wfh-add-view');
        Route::get('/edit/{id}', [WorkFromHomeController::class, 'addWfhView'])->name('wfh-edit-view');
        Route::post('/save', [WorkFromHomeController::class, 'saveWfhData'])->name('wfh-save');
        Route::get('/view/{id}', [WorkFromHomeController::class, 'viewWfh'])->name('wfh-view');
        Route::post('/comment-request', [WorkFromHomeController::class, 'wfhRequest'])->name('wfh-comment-request');
        Route::post('/cancel/{id}', [WorkFromHomeController::class, 'cancelWfh'])->name('wfh-cancel');
        Route::get('/team', [WorkFromHomeController::class, 'teamWfh'])->name('wfh-team');
        Route::get('/team/add', [WorkFromHomeController::class, 'addWfhTeamView'])->name('wfh-add-team');
        Route::get('/team/edit/{id}', [WorkFromHomeController::class, 'addWfhTeamView'])->name('wfh-edit-team');
        // Route::get('/add', [WorkFromHomeController::class, 'addWfhView'])->name('wfh-add-team');
        // Route::get('/edit/{id}', [WorkFromHomeController::class, 'addWfhView'])->name('wfh-edit-team');
        Route::get('/{id}/comments/show', [WorkFromHomeController::class, 'showComments'])->name('wfh-comments-view');
        Route::get('/teamview/{id}', [WorkFromHomeController::class, 'viewTeamWfh'])->name('wfh-team-view');
        Route::get('/all/employee', [WorkFromHomeController::class, 'AllEmployeeWorkFromHome'])->name('wfh-all-emp-index');
        Route::get('/all/employee/add', [WorkFromHomeController::class, 'addWfhTeamView'])->name('wfh-all-emp-add');
        Route::get('/all/employee/edit/{id}', [WorkFromHomeController::class, 'addWfhTeamView'])->name('wfh-all-emp-edit');
        Route::get('/all/employee/view/{id}', [WorkFromHomeController::class, 'viewTeamWfh'])->name('wfh-all-emp-view');
        Route::post('/all/employee/cancel/{id}', [WorkFromHomeController::class, 'cancelWfh'])->name('wfh-all-emp-cancel');
        Route::post('/wfh-fetch-request-from', [WorkFromHomeController::class, 'wfhFetchRequestFrom'])->name('wfh-get-request-from');
        Route::get('all/wfh/statistics', [WorkFromHomeController::class, 'allEmployeeWFHStatistics'])->name('wfh-statistics-all-employee');
    });

    Route::group(['prefix' => 'leads'], function () {
        Route::get('/', [LeadController::class, 'index'])->name('lead.list');
        Route::get('/add', [LeadController::class, 'add'])->name('lead.add');
        Route::get('/edit/{id}', [LeadController::class, 'edit'])->name('lead.edit');
        Route::post('/save', [LeadController::class, 'save'])->name('lead.save');
        Route::get('/view/{id}', [LeadController::class, 'view'])->name('lead.view');
        Route::get('/all', [LeadController::class, 'all'])->name('lead.all');
        Route::delete('delete/{id}', [LeadController::class, 'destroy'])->name('lead.destroy');
        Route::post('/save-comment', [LeadController::class, 'saveComment'])->name('lead.save.comment');
        Route::get('/statistics', [LeadController::class, 'statistics'])->name('lead-statistics');
    });

    Route::group(['prefix' => 'super-admin'], function () {
        Route::group(['prefix' => 'project'], function () {
            Route::get('/', [SuperProjectController::class, 'dashboard'])->name('super-admin-project-dashboard');
            Route::get('/activity/{id}', [SuperProjectController::class, 'activityBoard'])->name('super-admin-project-activity');
            Route::get('/add', [SuperProjectController::class, 'addProjectDetails'])->name('super-admin-add-project');
            Route::post('/stores', [SuperProjectController::class, 'storeProjectDetails'])->name('super-admin-store-project');
            Route::post('/edit', [SuperProjectController::class, 'editProjectDetails'])->name('super-admin-edit-project');
            Route::post('/save-activity', [SuperProjectController::class, 'saveActivity'])->name('super-admin-save-activity');
            Route::get('/{project_id}/activity/{id}/edit', [SuperProjectController::class, 'editActivity'])->name('super-admin-edit-activity');
            Route::patch('/{project_id}/activity/{id}', [SuperProjectController::class, 'updateActivity'])->name('super-admin-update-activity');
            Route::delete('/{project_id}/activity/{id}', [SuperProjectController::class, 'deleteActivity'])->name('super-admin-delete-activity');
            // Route::get('/{id}', [ProjectController::class, 'view'])->name('project-view');
            // Route::get('/{id}/all-member', [ProjectController::class, 'allMembers'])->name('project-all-members');
            Route::post('/checkcode', [SuperProjectController::class, 'checkProjectCode'])->name('check-project-code');
            Route::get('/manage-billable-resource/{id}', [SuperProjectController::class, 'manageBillableResource'])->name('manage-billable-resource');
            Route::patch('/update-billable-resource', [SuperProjectController::class, 'updateBillableResource'])->name('update-billable-resource');

        });
        Route::group(['prefix' => 'project/{project_id}'], function () {
            Route::resource('paymentHistory', PaymentHistoryController::class)->except('show');
        });
        Route::get('company-revenue', [PaymentHistoryController::class, 'companyRevenue'])->name('company.revenue')->middleware('permission:company-revenue.list');
        Route::get('expected-revenue', [PaymentHistoryController::class, 'expectedRevenue'])->name('expected.revenue')->middleware('permission:expected-revenue.list');
        Route::get('actual-revenue', [PaymentHistoryController::class, 'actualRevenue'])->name('actual.revenue')->middleware('permission:actual-revenue.list');
        Route::get('actual-vs-expected-revenue', [PaymentHistoryController::class, 'actualVsExpected'])->name('actual.vs.expected')->middleware('permission:actual-revenue.list');
    });

    // For Task Dashboard
    Route::group(['prefix' => 'task-dashboard'], function () {
        Route::get('/', [TaskDashboardController::class, 'index'])->name('task-dashboard');
        Route::get('/task-table', [TaskDashboardController::class, 'taskTableDetails'])->name('task-dashboard-table');
        Route::get('/getMonthlyWorkingHours', [TaskDashboardController::class, 'getMonthlyWorkingHours'])->name('getMonthlyWorkingHours');
        Route::get('/getDateWiseTaskDetails', [TaskDashboardController::class, 'getDateWiseTaskDetails'])->name('get-date-wise-task-details');
    });

    //Holiday route
    Route::post('/holiday/get-all-date', [HolidayController::class, 'getAllHolidayList'])->name('holiday-get-all-dates');
    Route::resource('holiday', HolidayController::class)
        ->except('show')
        ->names([
            'index' => 'holiday.index',
            'create' => 'holiday.create',
            'store' => 'holiday.store',
            'edit' => 'holiday.edit',
            'update' => 'holiday.update',
            'destroy' => 'holiday.destroy',
        ]);

    //Policy route
    Route::resource('policy', PolicyController::class)
        ->except('show')
        ->names([
            'index' => 'policy.index',
            'create' => 'policy.create',
            'store' => 'policy.store',
            'edit' => 'policy.edit',
            'update' => 'policy.update',
            'destroy' => 'policy.destroy',
        ]);


     //Event route
     Route::group(['prefix' => 'settings'], function () {
        Route::get('/', [SettingController::class, 'index'])->name('setting.index');
        Route::get('create', [SettingController::class, 'create'])->name('setting.create');
        Route::post('store', [SettingController::class, 'store'])->name('setting.store');
        Route::get('{id}/edit', [SettingController::class, 'edit'])->name('setting.edit');
        Route::patch('{id}/update', [SettingController::class, 'update'])->name('setting.update');
        Route::delete('delete/{id}', [SettingController::class, 'destroy'])->name('setting.delete');

    });
    //Event route
    Route::group(['prefix' => 'event'], function () {
        Route::get('/', [EventController::class, 'index'])->name('event.index');
        Route::post('create', [EventController::class, 'store'])->name('event.store');
        Route::get('edit', [EventController::class, 'getEvent'])->name('event.edit');
        Route::post('update', [EventController::class, 'update'])->name('event.update');
        Route::post('delete/{id}', [EventController::class, 'delete'])->name('event.destroy');
        Route::get('advancefilter', [EventController::class, 'advancefilter'])->name('event.advancefilter');
        Route::get('description', [EventController::class, 'getDescription'])->name('event.description');
    });

    // User Designation
    Route::resource('designation', UserDesignationController::class)
        ->except('show')
        ->names([
            'index' => 'designation.index',
            'create' => 'designation.create',
            'store' => 'designation.store',
            'edit' => 'designation.edit',
            'update' => 'designation.update',
            'destroy' => 'designation.destroy',
        ]);

    // Project allocation
    Route::resource('allocation', ProjectAllocationController::class)
        ->except('show')
        ->names([
            'index' => 'allocation.index',
            'create' => 'allocation.create',
            'store' => 'allocation.store',
            'edit' => 'allocation.edit',
            'update' => 'allocation.update',
            'destroy' => 'allocation.destroy',
        ]);

    // Department
    Route::resource('department', DepartmentController::class)
        ->except('show')
        ->names([
            'index' => 'department.index',
            'create' => 'department.create',
            'store' => 'department.store',
            'edit' => 'department.edit',
            'update' => 'department.update',
            'destroy' => 'department.destroy',
        ]);

    // Project status
    Route::resource('project-status', ProjectStatusController::class)
        ->except('show')
        ->names([
            'index' => 'project-status.index',
            'create' => 'project-status.create',
            'store' => 'project-status.store',
            'edit' => 'project-status.edit',
            'update' => 'project-status.update',
            'destroy' => 'project-status.destroy',
        ]);

    // Project Priority
    Route::resource('project-priority', ProjectPriorityController::class)
        ->except('show')
        ->names([
            'index' => 'project-priority.index',
            'create' => 'project-priority.create',
            'store' => 'project-priority.store',
            'edit' => 'project-priority.edit',
            'update' => 'project-priority.update',
            'destroy' => 'project-priority.destroy',
        ]);

    // Project Payment Type
    Route::resource('project-payment', ProjectPaymentTypeController::class)
        ->except('show')
        ->names([
            'index' => 'project-payment.index',
            'create' => 'project-payment.create',
            'store' => 'project-payment.store',
            'edit' => 'project-payment.edit',
            'update' => 'project-payment.update',
            'destroy' => 'project-payment.destroy',
        ]);

    // Role
    Route::resource('roles', RolesController::class)
        ->except('show')
        ->names([
            'index' => 'roles.index',
            'create' => 'roles.create',
            'store' => 'roles.store',
            'edit' => 'roles.edit',
            'update' => 'roles.update',
            'destroy' => 'roles.destroy',
        ]);
    Route::prefix('roles')->group(function () {
        Route::get('/{role}/permissions', [RolePermissionController::class, 'create'])->name('roles.permission');
        Route::post('/{role}/permissions', [RolePermissionController::class, 'store'])->name('roles.permission.store');
    });

    // Teams
    Route::resource('teams', TeamsController::class)
        ->except('show')
        ->names([
            'index' => 'teams.index',
            'create' => 'teams.create',
            'store' => 'teams.store',
            'edit' => 'teams.edit',
            'update' => 'teams.update',
            'destroy' => 'teams.destroy',
        ]);

    Route::resource('technology', TechnologyController::class)
        ->except('show')
        ->names([
            'index' => 'technology.index',
            'create' => 'technology.create',
            'store' => 'technology.store',
            'edit' => 'technology.edit',
            'update' => 'technology.update',
            'destroy' => 'technology.destroy',
        ]);

    //Client module route
    Route::resource('clients', ClientController::class)
        ->except('show')
        ->names([
            'index' => 'client.index',
            'create' => 'client.create',
            'store' => 'client.store',
            'edit' => 'client.edit',
            'update' => 'client.update',
            'destroy' => 'client.destroy',
        ]);

    //Interview Detail route
    Route::resource('interview-detail', InterviewdetailController::class)
        ->except('show')
        ->names([
            'index' => 'interview-detail.index',
            'create' => 'interview-detail.create',
            'store' => 'interview-detail.store',
            'edit' => 'interview-detail.edit',
            'update' => 'interview-detail.update',
            'destroy' => 'interview-detail.destroy'
        ]);

    //Consultancy route
    Route::resource('consultancy', ConsultancyController::class)
        ->except('show')
        ->names([
            'index' => 'consultancy.index',
            'create' => 'consultancy.create',
            'store' => 'consultancy.store',
            'edit' => 'consultancy.edit',
            'update' => 'consultancy.update',
            'destroy' => 'consultancy.destroy'
        ]);

    Route::resource('organizationChart', OrganizationChartController::class)->except('show');
    Route::get('/organizationChart/show', [OrganizationChartController::class, 'show'])->name('organizationChart.show');
    // performer routes
    Route::resource('performer', PerformerController::class)->except('show');
    Route::get('/performer/show', [PerformerController::class, 'show'])->name('performer.show');

    Route::resource('expectedRevenue', ExpectedRevenueController::class);

    Route::group(['prefix' => 'time-entry'], function () {
        Route::get('/my', [EmpTimeLogEntryController::class, 'index'])->name('my-time-entry');
        Route::get('/allemployee', [EmpTimeLogEntryController::class, 'allEmpTimeEntry'])->name('all-employee-time-entry');
        Route::post('/import', [EmpTimeLogEntryController::class, 'importTimeLog'])->name('import-time-log-entries');
    });

    Route::get('/import-leaves', [ImportLeaveController::class, 'index'])->name('import-leave');

    // lead status
    Route::resource('lead-status', LeadStatusController::class)
        ->except('show')
        ->names([
            'index' => 'lead-status.index',
            'create' => 'lead-status.create',
            'store' => 'lead-status.store',
            'edit' => 'lead-status.edit',
            'update' => 'lead-status.update',
            'destroy' => 'lead-status.destroy',
        ]);

    // lead source
    Route::resource('lead-source', LeadSourceController::class)
        ->except('show')
        ->names([
            'index' => 'lead-source.index',
            'create' => 'lead-source.create',
            'store' => 'lead-source.store',
            'edit' => 'lead-source.edit',
            'update' => 'lead-source.update',
            'destroy' => 'lead-source.destroy',
        ]);
    // lead source
    Route::resource('industry', IndustryController::class)
        ->except('show')
        ->names([
            'index' => 'industry.index',
            'create' => 'industry.create',
            'store' => 'industry.store',
            'edit' => 'industry.edit',
            'update' => 'industry.update',
            'destroy' => 'industry.destroy',
        ]);

    Route::get('sync-eod-data', [AutomationController::class, 'syncEODWithWorkLogEntry']);
    Route::post('sync-leave-statics', [LeaveController::class, 'syncLeaveStatics'])->name('sync-leave-statics');
});

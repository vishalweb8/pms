<?php

// Home

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

// Daily Task
Breadcrumbs::for('daily-task', function ($trail) {
    $trail->parent('home');
    $trail->push('Daily SOD', route('work-index'));
});

Breadcrumbs::for('daily-task-create', function($trail){
    $trail->parent('daily-task');
    $trail->push('Create');
});

Breadcrumbs::for('daily-task-edit', function($trail){
    $trail->parent('daily-task');
    $trail->push('Edit');
});

// Daily Task All employees
Breadcrumbs::for('all-employees', function($trail){
    $trail->parent('home');
    $trail->push('All Employees', route('admin-index'));
});

// Project Dashboard
Breadcrumbs::for('project-dashboard', function ($trail) {
    $trail->parent('home');
    $trail->push('My Projects', route('project-dashboard'));
});

// Project Dashboard
Breadcrumbs::for('project-details', function ($trail, $project, $project_id) {
    $trail->parent('project-dashboard');
    $trail->push($project, route('project-details', $project_id));
});

Breadcrumbs::for('add-project-task', function ($trail, $project, $project_id) {
    $trail->parent('project-details', $project, $project_id);
    $trail->push('Add Task');
});
Breadcrumbs::for('edit-project-task', function ($trail, $project, $project_id) {
    $trail->parent('project-details', $project, $project_id);
    $trail->push('Edit Task');
});
Breadcrumbs::for('view-project-task', function ($trail, $project, $project_id,$task_name) {
    $trail->parent('project-details', $project, $project_id);
    $trail->push($task_name);
});

// Project superadmin
Breadcrumbs::for('project-superadmin', function ($trail) {
    $trail->parent('home');
    $trail->push('Project Management', route('super-admin-project-dashboard'));
});

Breadcrumbs::for('project-superadmin-create', function ($trail) {
    $trail->parent('project-superadmin');
    $trail->push('Create');
});

Breadcrumbs::for('project-superadmin-edit', function ($trail) {
    $trail->parent('project-superadmin');
    $trail->push('Edit');
});

Breadcrumbs::for('add-payment-history', function ($trail, $project, $project_id) {
    $trail->parent('project-superadmin', $project, $project_id);
    $trail->push('Add Payment History');
});
Breadcrumbs::for('edit-payment-history', function ($trail, $project, $project_id) {
    $trail->parent('project-superadmin', $project, $project_id);
    $trail->push('Edit Payment History');
});

// My Leave
Breadcrumbs::for('my-leave', function($trail){
    $trail->parent('home');
    $trail->push('My Leave', route('leave-dashboard'));
});

Breadcrumbs::for('my-leave-create', function($trail){
    $trail->parent('my-leave');
    $trail->push('Create');
});

Breadcrumbs::for('my-leave-edit', function($trail){
    $trail->parent('my-leave');
    $trail->push('Edit');
});

Breadcrumbs::for('my-leave-view', function($trail){
    $trail->parent('my-leave');
    $trail->push('View');
});

// Team leave
Breadcrumbs::for('team-leave', function($trail){
    $trail->parent('home');
    $trail->push('Leave Requests', route('leave-team'));
});

Breadcrumbs::for('team-leave-create', function($trail){
    $trail->parent('team-leave');
    $trail->push('Create');
});

Breadcrumbs::for('team-leave-view', function($trail){
    $trail->parent('team-leave');
    $trail->push('View');
});

// Team leave
Breadcrumbs::for('all-leave', function($trail){
    $trail->parent('home');
    $trail->push('All Employee Leave', route('leave-all-employee'));
});

// leave statistics
Breadcrumbs::for('all-leave-statistics', function($trail){
    $trail->parent('home');
    $trail->push('Leaves Statistics', route('leave-statistics-all-employee'));
});


Breadcrumbs::for('all-leave-create', function($trail){
    $trail->parent('all-leave');
    $trail->push('Create');
});

Breadcrumbs::for('all-leave-edit', function($trail){
    $trail->parent('all-leave');
    $trail->push('Edit');
});

Breadcrumbs::for('all-leave-view', function($trail){
    $trail->parent('all-leave');
    $trail->push('View');
});

// Leave compensation
Breadcrumbs::for('leave-compensation', function($trail){
    $trail->parent('home');
    $trail->push('Leave Compensation', route('leave-compensation-dashboard'));
});

Breadcrumbs::for('my-leave-compensation-create', function($trail){
    $trail->parent('leave-compensation');
    $trail->push('Create');
});

Breadcrumbs::for('my-leave-compensation-edit', function($trail){
    $trail->parent('leave-compensation');
    $trail->push('Edit');
});

Breadcrumbs::for('my-leave-compensation-view', function($trail){
    $trail->parent('leave-compensation');
    $trail->push('View');
});

// Leave compensation requests
Breadcrumbs::for('all-leave-compensation', function($trail){
    $trail->parent('home');
    $trail->push('All Employee Leave Compensation', route('leave-compensation-all-employee'));
});


// My WFH Request
Breadcrumbs::for('my-wfh-request', function($trail){
    $trail->parent('home');
    $trail->push('My WFH Requests', route('wfh-dashboard'));
});

Breadcrumbs::for('my-wfh-request-create', function($trail){
    $trail->parent('my-wfh-request');
    $trail->push('Create');
});

Breadcrumbs::for('my-wfh-request-edit', function($trail){
    $trail->parent('my-wfh-request');
    $trail->push('Edit');
});

Breadcrumbs::for('my-wfh-request-view', function($trail){
    $trail->parent('my-wfh-request');
    $trail->push('View');
});

// Team WFH Request
Breadcrumbs::for('team-wfh-request', function($trail){
    $trail->parent('home');
    $trail->push('WFH Requests', route('wfh-team'));
});

Breadcrumbs::for('team-wfh-request-create', function($trail){
    $trail->parent('team-wfh-request');
    $trail->push('Create');
});

Breadcrumbs::for('team-wfh-request-view', function($trail){
    $trail->parent('team-wfh-request');
    $trail->push('View');
});

// All Employee WFH Request
Breadcrumbs::for('allemp-wfh-request', function($trail){
    $trail->parent('home');
    $trail->push('All Employee Work From Home Requests', route('wfh-all-emp-index'));
});

Breadcrumbs::for('allemp-wfh-request-create', function($trail){
    $trail->parent('allemp-wfh-request');
    $trail->push('Create');
});

Breadcrumbs::for('allemp-wfh-request-view', function($trail){
    $trail->parent('allemp-wfh-request');
    $trail->push('View');
});

Breadcrumbs::for('allemp-wfh-request-edit', function($trail){
    $trail->parent('allemp-wfh-request');
    $trail->push('Edit');
});

// wfh statistics
Breadcrumbs::for('all-wfh-statistics', function($trail){
    $trail->parent('home');
    $trail->push('WFH Statistics', route('wfh-statistics-all-employee'));
});

// Employees
Breadcrumbs::for('employee', function($trail){
    $trail->parent('home');
    $trail->push('Employees', route('user.index'));
});

Breadcrumbs::for('employee-create', function($trail){
    $trail->parent('employee');
    $trail->push('Create', route('user.index'));
});

Breadcrumbs::for('employee-edit', function($trail){
    $trail->parent('employee');
    $trail->push('Edit', route('user.index'));
});

// Event
Breadcrumbs::for('event', function ($trail) {
    $trail->parent('home');
    $trail->push('Events', route('event.index'));
});


// Client
Breadcrumbs::for('clients', function($trail){
    $trail->parent('home');
    $trail->push('Clients', route('client.index'));
});

// User designation
Breadcrumbs::for('user-designation', function ($trail) {
    $trail->parent('home');
    $trail->push('User Designation', route('designation.index'));
});

// Department
Breadcrumbs::for('department', function ($trail) {
    $trail->parent('home');
    $trail->push('Department', route('department.index'));
});

// Project Priorities
Breadcrumbs::for('project-priorities', function ($trail) {
    $trail->parent('home');
    $trail->push('Project Priorities', route('project-priority.index'));
});

// Project Payment Type
Breadcrumbs::for('project-payment-type', function ($trail) {
    $trail->parent('home');
    $trail->push('Project Payment Type', route('project-payment.index'));
});

// Project Status
Breadcrumbs::for('project-status', function ($trail) {
    $trail->parent('home');
    $trail->push('Project Status', route('project-status.index'));
});

// Project Allocation
Breadcrumbs::for('project-allocation', function ($trail) {
    $trail->parent('home');
    $trail->push('Project Allocation', route('allocation.index'));
});

// Technology
Breadcrumbs::for('technology', function ($trail) {
    $trail->parent('home');
    $trail->push('Technology', route('technology.index'));
});

// Role
Breadcrumbs::for('role', function ($trail) {
    $trail->parent('home');
    $trail->push('Role', route('roles.index'));
});

// Role-Permission
Breadcrumbs::for('roles-permission', function ($trail) {
    $trail->parent('role');
    $trail->push('Role & Permission', route('roles.index'));
});

// Team
Breadcrumbs::for('team', function ($trail) {
    $trail->parent('home');
    $trail->push('Team', route('teams.index'));
});

// Consultancy
Breadcrumbs::for('consultancy', function($trail){
    $trail->parent('home');
    $trail->push('Consultancies', route('consultancy.index'));
});

// Profile
Breadcrumbs::for('profile', function($trail){
    $trail->parent('home');
    $trail->push('Profile', route('user.profile'));
});

// Holiday
Breadcrumbs::for('holiday', function ($trail) {
    $trail->parent('home');
    $trail->push('Holiday', route('holiday.index'));
});

// Policy
Breadcrumbs::for('policy', function ($trail) {
    $trail->parent('home');
    $trail->push('Policy', route('policy.index'));
});
// Setting
Breadcrumbs::for('setting', function ($trail) {
    $trail->parent('home');
    $trail->push('Setting', route('setting.index'));
});
// organizationChart
Breadcrumbs::for('organizationChart', function ($trail) {
    $trail->parent('home');
    $trail->push('Organization Chart', route('organizationChart.index'));
});

// Lead Status
Breadcrumbs::for('lead-status', function ($trail) {
    $trail->parent('home');
    $trail->push('Lead Status', route('lead-status.index'));
});

// Lead Source
Breadcrumbs::for('lead-source', function ($trail) {
    $trail->parent('home');
    $trail->push('Lead Source', route('lead-source.index'));
});

// Industry
Breadcrumbs::for('industry', function ($trail) {
    $trail->parent('home');
    $trail->push('Industries', route('industry.index'));
});

// leads
Breadcrumbs::for('leads', function ($trail) {
    $trail->parent('home');
    $trail->push('Leads', route('lead.list'));
});
Breadcrumbs::for('my-leads', function ($trail) {
    $trail->parent('home');
    $trail->push('My Leads', route('lead.list'));
});

Breadcrumbs::for('all-leads', function ($trail) {
    $trail->parent('home');
    $trail->push('All Leads', route('lead.all'));
});

Breadcrumbs::for('create-lead', function ($trail) {
    $trail->parent('leads');
    $trail->push('Create', route('lead.add'));
});
Breadcrumbs::for('edit-lead', function ($trail) {
    $trail->parent('leads');
    $trail->push('Edit', route('lead.add'));
});
Breadcrumbs::for('lead-detail', function ($trail) {
    $trail->parent('leads');
    $trail->push('View');
});

Breadcrumbs::for('free-reource',function($trail){
    $trail->parent('home');
    $trail->push('Free Resources', route('freeResource.list'));
});
Breadcrumbs::for('all-employee',function($trail){
    $trail->parent('home');
    $trail->push('All Employee', route('resource-mgt-all-employee'));
});
Breadcrumbs::for('all-projects',function($trail){
    $trail->parent('home');
    $trail->push('All Projects', route('resource-mgt-all-projects'));
});
Breadcrumbs::for('all-BDEs',function($trail){
    $trail->parent('home');
    $trail->push('All BDEs', route('resource-mgt-all-bdes'));
});
Breadcrumbs::for('all-teams',function($trail){
    $trail->parent('home');
    $trail->push('All Teams', route('resource-mgt-total-teams'));
});
Breadcrumbs::for('lead-statistics', function ($trail) {
    $trail->parent('home');
    $trail->push('Lead Statistics');
});
Breadcrumbs::for('monthly-worklog', function ($trail) {
    $trail->parent('home');
    $trail->push('Monthly Worklog');
});
Breadcrumbs::for('monthly-time-entry', function ($trail) {
    $trail->parent('home');
    $trail->push('Monthly Time Entry');
});
Breadcrumbs::for('task-dashboard', function ($trail) {
    $trail->parent('home');
    $trail->push('Task Dashboard');
});
Breadcrumbs::for('performer', function ($trail) {
    $trail->parent('home');
    $trail->push('Performers');
});
Breadcrumbs::for('company-revenue', function ($trail) {
    $trail->parent('home');
    $trail->push('Company Revenue');
});
Breadcrumbs::for('actual-vs-expected', function ($trail) {
    $trail->parent('home');
    $trail->push('Actual V/s Expected');
});
Breadcrumbs::for('expected-revenue', function ($trail) {
    $trail->parent('home');
    $trail->push('Expected Revenue');
});
Breadcrumbs::for('actual-revenue', function ($trail) {
    $trail->parent('home');
    $trail->push('Actual Revenue');
});

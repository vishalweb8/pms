<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'project_code',
        'project_type',
        'payment_type_id',
        'client_id',
        'amount',
        'currency',
        'allocation_id',
        'priority_id',
        'status',
        'team_lead_id',
        'reviewer_id',
        'technologies_ids',
        'members_ids',
        'description',
        'description_for_all',
        'created_by',
        'bde_id',
        'start_date',
        'end_date'
    ];
    public function activities()
    {
        return $this->hasMany(ProjectActivity::class, 'project_id');
    }
    public function projectClient()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function projectAllocation()
    {
        return $this->belongsTo(ProjectAllocation::class, 'allocation_id', 'id');
    }

    public function projectTeamLead()
    {
        return $this->belongsTo(User::class, 'team_lead_id', 'id')->withBlocked();
    }

    public function projectReviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id', 'id')->withBlocked();
    }

    public function projectStatus()
    {
        return $this->belongsTo(ProjectStatus::class, 'status', 'id');
    }

    public function projectPriority()
    {
        return $this->belongsTo(ProjectPriority::class, 'priority_id', 'id');
    }

    public function projectPaymentType()
    {
        return $this->belongsTo(ProjectPaymentType::class, 'payment_type_id', 'id');
    }

    public function projectTechnology()
    {
        return $this->belongsToMany(Technology::class,'project_technology','project_id','technology_id');
    }

    public function projectCurrency()
    {
        return $this->belongsTo(Currency::class,'currency');
    }

    public function projectUser()
    {
        return $this->belongsToMany(user::class,'user_projects','project_id','user_id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class,'user_projects','project_id','user_id');
    }

    public function bde() {
        return $this->hasOne(User::class, 'id', 'bde_id');
    }

    public function tasks()
    {
        return $this->hasMany(ProjectTask::class, 'project_id', 'id');
    }

    public function resources()
    {
        return $this->hasMany(ProjectBillableResources::class);
    }

    public function billable_resources()
    {
        return $this->belongsToMany(User::class, 'project_billable_resources', 'project_id', 'user_id')->withTimestamps();
        // ->whereNull('project_billable_resources.deleted_at')->withTimestamps();
    }


}

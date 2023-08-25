<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectBillableResources extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "project_billable_resources";

    protected $fillable = [
        'project_id','user_id', 'amount','payment_type_id','currency_id'
    ];

    public function insertUpdate($data)
    {
         if (isset($data['id']) && $data['id'] != '' && $data['id'] > 0) {
            $updateData = [];
            foreach ($this->fillable as $field) {
                if (array_key_exists($field, $data)) {
                    $updateData[$field] = $data[$field];
                }
            }
            $billable_resource_id = ProjectBillableResources::where('id', $data['id'])->first();
            $billable_resource_id->update($updateData);
        } else {
			$billable_resource_id = ProjectBillableResources::create($data);
        }
        return $billable_resource_id;
    }

    public function syncBillableResourceProject($data, $project_id) {
        $project= Project::find($project_id);

        if($data == '') {
            $project->billable_resources()->detach();
        }else {
            // dump($data); exit();
            $project->billable_resources()->sync($data);
        }
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function paymentType()
    {
        return $this->belongsTo(ProjectPaymentType::class,'payment_type_id');
    }
}

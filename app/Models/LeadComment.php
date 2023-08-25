<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadComment extends Model
{
    use HasFactory, SoftDeletes;

    public $table = "lead_comments"; 
    
    protected $fillable = [
        'lead_id', 'user_id', 'description'
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
            $leadComment = LeadComment::where('id', $data['id'])->first();
            $leadComment->update($updateData);
        } else {
			$leadComment = LeadComment::create($data);
        }
        return $leadComment;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }
}

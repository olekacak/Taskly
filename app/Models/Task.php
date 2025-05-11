<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';
    protected $primaryKey = 'taskId';
    public $incrementing = true; 
    protected $keyType = 'int';
    public $timestamps = false; //because Laravel automatically expects created_at and updated_at columns to exist unless you tell it otherwise.

    protected $fillable = [
        'workspaceId', 
        'title', 
        'description', 
        'deadline',
        'status', 
        'is_active', 
        'is_delete',
        'created_date', 
        'modified_date', 
        'deleted_date',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspaceId');
    }
}

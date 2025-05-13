<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    protected $table = 'workspace'; // because it's not plural
    protected $primaryKey = 'workspaceId';
    public $incrementing = true; 
    protected $keyType = 'int';
    public $timestamps = false; //because Laravel automatically expects created_at and updated_at columns to exist unless you tell it otherwise.


    protected $fillable = [
        'name', 
        'description',
        'userId',
        'is_active', 
        'is_delete',
        'created_date',
        'modified_date',
        'deleted_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'workspaceId');
    }
}

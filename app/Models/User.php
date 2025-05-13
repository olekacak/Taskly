<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * Laravel expects 'users' by default.
     * Use this if your table name is actually 'user'.
     */
    protected $table = 'user';
    protected $primaryKey = 'userId';
    public $incrementing = true; // if userId is auto-incrementing
    protected $keyType = 'int';
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'userId',
        'name',
        'image',
        'username',
        'email',
        'password',
        'is_active', 
        'is_delete',
        'created_date',
        'modified_date',
        'deleted_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function workspaces()
    {
        return $this->hasMany(Workspace::class, 'userId');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TUser extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 't_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'public_user_id',
        'user_name',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the user logs for the user.
     */
    public function userLogs()
    {
        return $this->hasMany(LUserLog::class, 't_user_id');
    }

    /**
     * Get the user roles for the user.
     */
    public function userRoles()
    {
        return $this->hasMany(TUserRole::class, 't_user_id');
    }

    /**
     * Get the user settings for the user.
     */
    public function userSettings()
    {
        return $this->hasMany(TUserSetting::class, 't_user_id');
    }

    /**
     * Get the roles for the user.
     */
    public function roles()
    {
        return $this->belongsToMany(MUserRole::class, 't_user_roles', 't_user_id', 'm_user_role_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $appends = ['avatar_full_path'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    protected $fillable = [
        'store_id', 'branch_id', 'first_name', 'last_name', 'full_name', 'slug', 'email', 'phone',
        'password', 'address', 'area', 'city', 'state', 'country', 'country_code',
        'zipcode', 'latitude', 'longitude', 'timezone', 'avatar', 'bio',
        'device_token', 'device_type', 'status', 'role'
    ];

    public function getAvatarFullPathAttribute()
    {
        if ($this->avatar != '') {
            return asset($this->avatar);
        }
        return "";
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
}

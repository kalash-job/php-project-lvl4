<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tasksCreatedByMe()
    {
        return $this->hasMany(Task::class, 'created_by_id');
    }

    public function tasksAssignedToMe()
    {
        return $this->hasMany(Task::class, 'assigned_to_id');
    }

    public function labels()
    {
        return $this->hasMany(Label::class);
    }

    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public static function getWorkersForForm(): array
    {
        return self::all()
            ->mapWithKeys(function ($item) {
                return [$item['id'] => $item['name']];
            })
            ->toArray();
    }

    public static function getCreatorsForForm(): array
    {
        return self::all()
            ->mapWithKeys(function ($item) {
                return [$item['id'] => $item['name']];
            })
            ->toArray();
    }
}

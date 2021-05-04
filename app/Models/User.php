<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Label[] $labels
 * @property-read int|null $labels_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Status[] $statuses
 * @property-read int|null $statuses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Task[] $tasksAssignedToMe
 * @property-read int|null $tasks_assigned_to_me_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Task[] $tasksCreatedByMe
 * @property-read int|null $tasks_created_by_me_count
 * @method static \Illuminate\Database\Eloquent\Builder|User create($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User find($value)
 * @method static User|null findOrFail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User first()
 * @method static User|null firstOrFail()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User pluck($valueFirst, $valueSecond)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    /**
     * Tasks are created by a user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasksCreatedByMe()
    {
        return $this->hasMany(Task::class, 'created_by_id');
    }

    /**
     * Tasks are assigned to a user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasksAssignedToMe()
    {
        return $this->hasMany(Task::class, 'assigned_to_id');
    }

    /**
     * Labels are created by a user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function labels()
    {
        return $this->hasMany(Label::class);
    }

    /**
     * Statuses are created by a user.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public static function getWorkersForForm(): array
    {
        return self::pluck('name', 'id')->toArray();
    }

    public static function getCreatorsForForm(): array
    {
        return self::pluck('name', 'id')->toArray();
    }
}

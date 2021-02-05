<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

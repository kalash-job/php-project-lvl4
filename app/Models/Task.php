<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
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
        'status_id',
        'assigned_to_id',
        'created_by_id',
    ];

    public function labels()
    {
        return $this->belongsToMany(Label::class)->withTimestamps();
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'assigned_to_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }
}

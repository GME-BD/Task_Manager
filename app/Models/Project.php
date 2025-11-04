<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'budget',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Project creator (admin)
    // Project creator (admin) - ONE TO ONE
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Team members assigned to this project - MANY TO MANY  
    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'project_teams', 'project_id', 'user_id')
            ->withTimestamps();
    }

    // Tasks in this project - ONE TO MANY
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    // Helper method to check if user is assigned
    public function isAssignedTo($userId)
    {
        return $this->teamMembers()->where('user_id', $userId)->exists();
    }

    // Get assigned users count
    public function getAssignedUsersCountAttribute()
    {
        return $this->teamMembers()->count();
    }

    // Dynamic status calculation
    public function getCalculatedStatusAttribute()
    {
        $today = Carbon::now();

        if ($this->start_date && $today->lt($this->start_date)) {
            return 'pending';
        }

        if ($this->end_date && $this->end_date->lt($today)) {
            $unfinishedTasks = $this->tasks()->where('status', '!=', 'completed')->count();
            return $unfinishedTasks > 0 ? 'unfinished' : 'finished';
        }

        return 'on_going';
    }
}

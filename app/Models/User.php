<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relationships
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    // FIXED: Projects assigned to this user (as team member)
    public function assignedProjects()
    {
        return $this->belongsToMany(Project::class, 'project_teams', 'user_id', 'project_id')
            ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function routines()
    {
        return $this->hasMany(Routine::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    // Role checks
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEmployee()
    {
        return $this->role === 'user';
    }

    // Scopes
    public function scopeEmployees($query)
    {
        return $query->where('role', 'user');
    }

    public function scopeExcludeCurrent($query)
    {
        return $query->where('id', '!=', auth()->id());
    }

    // Helper: Get all projects user has access to (created + assigned)
    public function accessibleProjects()
    {
        if ($this->isAdmin()) {
            return Project::query();
        }
        
        return Project::where('user_id', $this->id)
            ->orWhereHas('teamMembers', function ($query) {
                $query->where('user_id', $this->id);
            });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    protected $appends = [
        'status_color',
        'priority_color',
        'is_overdue',
        'due_date_formatted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function checklistItems()
    {
        return $this->hasMany(ChecklistItem::class);
    }

    // Status color for UI
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case 'to_do':
                return 'secondary';
            case 'in_progress':
                return 'warning';
            case 'completed':
                return 'success';
            default:
                return 'secondary';
        }
    }

    // Priority color for UI
    public function getPriorityColorAttribute()
    {
        switch ($this->priority) {
            case 'low':
                return 'success';
            case 'medium':
                return 'warning';
            case 'high':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    // Check if task is overdue
    public function getIsOverdueAttribute()
    {
        if (!$this->due_date || $this->status === 'completed') {
            return false;
        }

        return $this->due_date->isPast();
    }

    // Formatted due date
    public function getDueDateFormattedAttribute()
    {
        if (!$this->due_date) {
            return 'No due date';
        }

        return $this->due_date->format('M d, Y');
    }

    // Scope for overdue tasks
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', 'completed');
    }

    // Scope for tasks due today
    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today())
                    ->where('status', '!=', 'completed');
    }

    // Scope for high priority tasks
    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high')
                    ->where('status', '!=', 'completed');
    }

    // Scope for user's tasks
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Check if task can be edited by user
    public function canBeEditedBy($user)
    {
        if ($user->isAdmin()) {
            return true;
        }

        // User can edit if they're assigned to the task or a team member of the project
        return $this->user_id === $user->id || 
               $this->project->teamMembers()->where('user_id', $user->id)->exists();
    }

    // Get elapsed time since creation
    public function getTimeElapsedAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    // Get days until due
    public function getDaysUntilDueAttribute()
    {
        if (!$this->due_date) {
            return null;
        }

        return now()->diffInDays($this->due_date, false);
    }
}
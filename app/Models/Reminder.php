<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'priority',
        'is_completed',
        'reminder_type',
        'recurring_pattern'
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'date' => 'date',
    ];

    protected $appends = [
        'formatted_date',
        'formatted_time',
        'is_overdue',
        'priority_color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', today())
                    ->where('is_completed', false)
                    ->orderBy('date')
                    ->orderBy('time');
    }

    public function scopeOverdue($query)
    {
        return $query->where('date', '<', today())
                    ->where('is_completed', false)
                    ->orderBy('date')
                    ->orderBy('time');
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopeToday($query)
    {
        return $query->where('date', today())
                    ->where('is_completed', false);
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high')
                    ->where('is_completed', false);
    }

    // Accessors
    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('M d, Y') : 'No date set';
    }

    public function getFormattedTimeAttribute()
    {
        return $this->time ? date('g:i A', strtotime($this->time)) : 'No time set';
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->date || $this->is_completed) {
            return false;
        }

        return $this->date->isPast();
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'info',
            default => 'secondary'
        };
    }

    public function getFullDateTimeAttribute()
    {
        if ($this->date && $this->time) {
            return $this->date->format('Y-m-d') . ' ' . $this->time;
        }
        return null;
    }

    // Check if reminder is due soon (within 1 hour)
    public function getIsDueSoonAttribute()
    {
        if (!$this->date || !$this->time || $this->is_completed) {
            return false;
        }

        $reminderDateTime = Carbon::parse($this->date->format('Y-m-d') . ' ' . $this->time);
        return $reminderDateTime->diffInHours(now()) <= 1 && $reminderDateTime->isFuture();
    }
}
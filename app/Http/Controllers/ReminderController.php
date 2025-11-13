<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReminderController extends Controller
{
    public function index()
    {
        $upcomingReminders = Auth::user()->reminders()
            ->upcoming()
            ->get();
            
        $overdueReminders = Auth::user()->reminders()
            ->overdue()
            ->get();
            
        $todayReminders = Auth::user()->reminders()
            ->today()
            ->get();

        return view('reminders.index', compact('upcomingReminders', 'overdueReminders', 'todayReminders'));
    }

    public function create()
    {
        return view('reminders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date|after_or_equal:today',
            'time' => 'nullable|date_format:H:i',
            'priority' => 'required|in:low,medium,high',
            'reminder_type' => 'required|in:one_time,recurring',
            'recurring_pattern' => 'nullable|required_if:reminder_type,recurring|in:daily,weekly,monthly'
        ]);

        Auth::user()->reminders()->create($request->all());

        return redirect()->route('reminders.index')->with('success', 'Reminder created successfully.');
    }

    public function edit(Reminder $reminder)
    {
        // Authorization - user can only edit their own reminders
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('reminders.edit', compact('reminder'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        // Authorization
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
            'priority' => 'required|in:low,medium,high',
            'reminder_type' => 'required|in:one_time,recurring',
            'recurring_pattern' => 'nullable|required_if:reminder_type,recurring|in:daily,weekly,monthly'
        ]);

        $reminder->update($request->all());

        return redirect()->route('reminders.index')->with('success', 'Reminder updated successfully.');
    }

    public function destroy(Reminder $reminder)
    {
        // Authorization
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }
        
        $reminder->delete();
        return redirect()->route('reminders.index')->with('success', 'Reminder deleted successfully.');
    }

    // Mark reminder as completed
    public function markCompleted(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $reminder->update(['is_completed' => true]);

        return redirect()->back()->with('success', 'Reminder marked as completed.');
    }

    // Mark reminder as incomplete
    public function markIncomplete(Reminder $reminder)
    {
        if ($reminder->user_id !== Auth::id()) {
            abort(403);
        }

        $reminder->update(['is_completed' => false]);

        return redirect()->back()->with('success', 'Reminder marked as incomplete.');
    }

    // Get reminders for dashboard
    public function getDashboardReminders()
    {
        $todayReminders = Auth::user()->reminders()
            ->today()
            ->where('is_completed', false)
            ->orderBy('time')
            ->get();

        $dueSoonReminders = Auth::user()->reminders()
            ->where('is_completed', false)
            ->get()
            ->filter(function ($reminder) {
                return $reminder->is_due_soon;
            });

        return compact('todayReminders', 'dueSoonReminders');
    }
}
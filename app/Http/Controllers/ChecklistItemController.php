<?php

namespace App\Http\Controllers;

use App\Models\ChecklistItem;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChecklistItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'task_id' => 'required|exists:tasks,id',
        ]);

        // Get the task and check authorization
        $task = Task::findOrFail($request->task_id);
        $user = Auth::user();

        // Authorization check - user must be admin or assigned to the task's project
        if (!$user->isAdmin() && !$task->project->teamMembers()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $checklistItem = ChecklistItem::create([
            'task_id' => $request->task_id,
            'name' => $request->name,
            'completed' => false,
        ]);

        return response()->json([
            'success' => true,
            'id' => $checklistItem->id,
            'name' => $checklistItem->name,
            'completed' => $checklistItem->completed
        ]);
    }

    public function updateStatus(Request $request, ChecklistItem $checklistItem)
    {
        // Authorization check
        $user = Auth::user();
        if (!$user->isAdmin() && !$checklistItem->task->project->teamMembers()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'completed' => 'required|boolean',
        ]);

        $checklistItem->update([
            'completed' => $request->completed,
        ]);

        return response()->json([
            'success' => true,
            'completed' => $checklistItem->completed
        ]);
    }

    public function update(Request $request, ChecklistItem $checklistItem)
    {
        // Authorization check
        $user = Auth::user();
        if (!$user->isAdmin() && !$checklistItem->task->project->teamMembers()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'completed' => 'sometimes|boolean',
        ]);

        $checklistItem->update([
            'name' => $request->name,
            'completed' => $request->completed ?? $checklistItem->completed,
        ]);

        return response()->json([
            'success' => true,
            'data' => $checklistItem
        ]);
    }

    public function destroy(ChecklistItem $checklistItem)
    {
        // Authorization check
        $user = Auth::user();
        if (!$user->isAdmin() && !$checklistItem->task->project->teamMembers()->where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $checklistItem->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
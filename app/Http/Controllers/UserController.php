<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



//  * User can view only their assigned tasks.
//  * User can mark tasks as complete and edit their own tasks.
//  * Supports filtering by status and priority with pagination.

class UserController extends Controller
{
    // User Dashboard — only tasks assigned to this user
    public function dashboard(Request $request)
    {
        $tasks = Task::where('assigned_to', Auth::id())
            ->ofStatus($request->query('status'))
            ->ofPriority($request->query('priority'))
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        return view('user.dashboard', compact('tasks'));
    }

    // Mark task as complete
    public function markComplete(int $id)
    {
        $task = Task::where('assigned_to', Auth::id())->findOrFail($id);
        $task->update(['status' => 'completed']);

        return redirect()->route('user.dashboard')->with('success', 'Task marked as complete!');
    }

    // Update their own task
    public function update(Request $request, int $id)
    {
        $task = Task::where('assigned_to', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:pending,in_progress,completed',
            'priority'    => 'in:low,medium,high',
            'due_date'    => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()->route('user.dashboard')->with('success', 'Task updated!');
    }
}

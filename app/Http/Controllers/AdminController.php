<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



//  * Admin can view all tasks, assign tasks to users,
//  * edit any task, and delete any task.
//  * Supports filtering by status, priority, and due date.

class AdminController extends Controller
{
    // Admin Dashboard — show all tasks from all users
    public function dashboard(Request $request)
    {
        $tasks = Task::with(['user', 'assignedTo'])
            ->ofStatus($request->query('status'))
            ->ofPriority($request->query('priority'))
            ->dueBefore($request->query('due_before'))
            ->orderBy('created_at', 'desc')
            ->paginate(3);

        $users = User::where('role', 'user')->get();

        $stats = [
            'total_users'  => User::where('role', 'user')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_tasks'  => Task::count(),
        ];

        return view('admin.dashboard', compact('tasks', 'users', 'stats'));
    }

    // Create task and assign to a user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:pending,in_progress,completed',
            'priority'    => 'in:low,medium,high',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'required|exists:users,id',
        ]);

        Task::create([
            'user_id'     => Auth::id(),  // fixed — use Auth facade
            'assigned_to' => $validated['assigned_to'],
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status'      => $validated['status'] ?? 'pending',
            'priority'    => $validated['priority'] ?? 'medium',
            'due_date'    => $validated['due_date'] ?? null,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Task created and assigned!');
    }

    // Edit any task
    public function update(Request $request, int $id)
    {
        $task = Task::findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:pending,in_progress,completed',
            'priority'    => 'in:low,medium,high',
            'due_date'    => 'nullable|date',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task->update($validated);

        return redirect()->route('admin.dashboard')->with('success', 'Task updated!');
    }

    // Delete any task
    public function destroy(int $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Task deleted!');
    }
}

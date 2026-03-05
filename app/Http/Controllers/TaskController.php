<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

//  * TaskController
//  * REST API for task management.
//  * Supports full CRUD, soft deletes, restore,
//  * filtering by status/priority/due date, and pagination.

class TaskController extends Controller
{
    // ══════════════════════════════════════════════
    // API METHODS (return JSON)
    // ══════════════════════════════════════════════

    /**
     * GET /api/tasks
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 10);

        $tasks = $request->user()
            ->tasks()
            ->ofStatus($request->query('status'))
            ->ofPriority($request->query('priority'))
            ->dueBefore($request->query('due_before'))
            ->orderBy('due_date')
            ->paginate($perPage);

        return response()->json($tasks);
    }

    /**
     * POST /api/tasks
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:pending,in_progress,completed',
            'priority'    => 'in:low,medium,high',
            'due_date'    => 'nullable|date',
        ]);

        $task = $request->user()->tasks()->create($validated);

        return response()->json([
            'message' => 'Task created successfully.',
            'task'    => $task,
        ], 201);
    }

    /**
     * GET /api/tasks/{id}
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $task = $request->user()->tasks()->findOrFail($id);

        return response()->json($task);
    }

    /**
     * PUT /api/tasks/{id}
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $validated = $request->validate([
            'title'       => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'sometimes|in:pending,in_progress,completed',
            'priority'    => 'sometimes|in:low,medium,high',
            'due_date'    => 'nullable|date',
        ]);

        $task->update($validated);

        return response()->json([
            'message' => 'Task updated successfully.',
            'task'    => $task,
        ]);
    }

    /**
     * DELETE /api/tasks/{id}
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $task = $request->user()->tasks()->findOrFail($id);
        $task->delete();

        return response()->json([
            'message' => 'Task deleted. It can be restored later.',
        ]);
    }

    /**
     * GET /api/tasks/trashed
     */
    public function trashed(Request $request): JsonResponse
    {
        $tasks = $request->user()
            ->tasks()
            ->onlyTrashed()
            ->paginate(10);

        return response()->json($tasks);
    }

    /**
     * POST /api/tasks/{id}/restore
     */
    public function restore(Request $request, int $id): JsonResponse
    {
        $task = $request->user()
            ->tasks()
            ->onlyTrashed()
            ->findOrFail($id);

        $task->restore();

        return response()->json([
            'message' => 'Task restored successfully.',
            'task'    => $task,
        ]);
    }

    // ══════════════════════════════════════════════
    // WEB METHODS (return Blade views)
    // ══════════════════════════════════════════════

    /**
     * GET /tasks
     */
    public function webIndex(Request $request)
    {
        $tasks = $request->user()
            ->tasks()
            ->ofStatus($request->query('status'))
            ->ofPriority($request->query('priority'))
            ->dueBefore($request->query('due_before'))
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * POST /tasks
     */
    public function webStore(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:pending,in_progress,completed',
            'priority'    => 'in:low,medium,high',
            'due_date'    => 'nullable|date',
        ]);

        $request->user()->tasks()->create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created!');
    }

    /**
     * PUT /tasks/{id}
     */
    public function webUpdate(Request $request, int $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'in:pending,in_progress,completed',
            'priority'    => 'in:low,medium,high',
            'due_date'    => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', 'Task updated!');
    }

    /**
     * DELETE /tasks/{id}
     */
    public function webDestroy(Request $request, int $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted!');
    }
}

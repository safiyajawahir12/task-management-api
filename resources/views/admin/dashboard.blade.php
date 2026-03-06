@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')

<style>
  .btn { padding:0.5rem 1rem; border:none; border-radius:6px; cursor:pointer; font-size:0.85rem; font-weight:600; text-decoration:none; display:inline-block; }
  .btn-primary  { background:#4f46e5; color:white; }
  .btn-danger   { background:#ef4444; color:white; }
  .btn-warning  { background:#f59e0b; color:white; }
  .btn:hover    { opacity:0.85; }
  .form-control { width:100%; padding:0.6rem 0.9rem; border:1.5px solid #d1d5db; border-radius:8px; font-size:0.9rem; outline:none; }
  .form-control:focus { border-color:#4f46e5; }
  .badge { display:inline-block; padding:0.2rem 0.6rem; border-radius:999px; font-size:0.75rem; font-weight:600; }
  .badge-pending     { background:#fef9c3; color:#854d0e; }
  .badge-in_progress { background:#dbeafe; color:#1e40af; }
  .badge-completed   { background:#dcfce7; color:#166534; }
  .badge-high   { background:#fee2e2; color:#991b1b; }
  .badge-medium { background:#ffedd5; color:#9a3412; }
  .badge-low    { background:#f3f4f6; color:#374151; }
  table { width:100%; border-collapse:collapse; }
  th, td { padding:0.85rem 1rem; text-align:left; border-bottom:1px solid #e5e7eb; font-size:0.88rem; }
  th { background:#f8fafc; font-weight:700; color:#374151; }
  tr:hover { background:#fafafa; }
  .modal-overlay { display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:999; align-items:center; justify-content:center; }
  .modal-overlay.active { display:flex; }
  .modal { background:white; border-radius:12px; padding:2rem; width:100%; max-width:500px; box-shadow:0 20px 60px rgba(0,0,0,0.2); }
  .modal h3 { margin-bottom:1.2rem; color:#4f46e5; }
  .field { margin-bottom:1rem; }
  .field label { display:block; font-size:0.85rem; font-weight:600; color:#374151; margin-bottom:0.3rem; }
  .modal-actions { display:flex; gap:0.75rem; justify-content:flex-end; margin-top:1.2rem; }
</style>

{{-- Header --}}
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
  <div>
    <h1 style="font-size:1.6rem; color:#1e293b;">🛡️ Admin Dashboard</h1>
    <p style="color:#64748b; font-size:0.9rem;">Welcome, <strong>{{ Auth::user()->name }}</strong></p>
  </div>
  <button class="btn btn-primary" onclick="openCreateModal()">+ Assign New Task</button>
</div>

{{-- Flash messages --}}
{{-- @if(session('success'))
  <div style="background:#dcfce7;color:#166534;border:1px solid #86efac;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1rem;">
    ✅ {{ session('success') }}
  </div>
@endif --}}

{{-- Stats --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem;">
  <div class="card" style="text-align:center; padding:1.2rem;">
    <div style="font-size:2rem;">👥</div>
    <div style="font-size:1.8rem; font-weight:700; color:#4f46e5;">{{ $stats['total_users'] }}</div>
    <div style="font-size:0.82rem; color:#64748b;">Users</div>
  </div>
  <div class="card" style="text-align:center; padding:1.2rem;">
    <div style="font-size:2rem;">🛡️</div>
    <div style="font-size:1.8rem; font-weight:700; color:#7c3aed;">{{ $stats['total_admins'] }}</div>
    <div style="font-size:0.82rem; color:#64748b;">Admins</div>
  </div>
  <div class="card" style="text-align:center; padding:1.2rem;">
    <div style="font-size:2rem;">📋</div>
    <div style="font-size:1.8rem; font-weight:700; color:#0891b2;">{{ $stats['total_tasks'] }}</div>
    <div style="font-size:0.82rem; color:#64748b;">Total Tasks</div>
  </div>
</div>

{{-- Filter Bar --}}
<div class="card" style="padding:1rem; margin-bottom:1rem;">
  <form method="GET" action="{{ route('admin.dashboard') }}" style="display:flex; gap:0.75rem; flex-wrap:wrap; align-items:flex-end;">
    <div>
      <label style="font-size:0.8rem;font-weight:600;color:#374151;">Status</label>
      <select name="status" class="form-control" style="width:140px;">
        <option value="">All</option>
        <option value="pending"     {{ request('status') == 'pending'     ? 'selected' : '' }}>Pending</option>
        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
        <option value="completed"   {{ request('status') == 'completed'   ? 'selected' : '' }}>Completed</option>
      </select>
    </div>
    <div>
      <label style="font-size:0.8rem;font-weight:600;color:#374151;">Priority</label>
      <select name="priority" class="form-control" style="width:140px;">
        <option value="">All</option>
        <option value="high"   {{ request('priority') == 'high'   ? 'selected' : '' }}>High</option>
        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
        <option value="low"    {{ request('priority') == 'low'    ? 'selected' : '' }}>Low</option>
      </select>
    </div>
    <div>
      <label style="font-size:0.8rem;font-weight:600;color:#374151;">Due Before</label>
      <input type="date" name="due_before" class="form-control" style="width:170px;" value="{{ request('due_before') }}"/>
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="{{ route('admin.dashboard') }}" class="btn" style="background:#6b7280;color:white;">Clear</a>
  </form>
</div>

{{-- All Tasks Table --}}
<div class="card" style="padding:0; overflow:hidden;">
  <table>
    <thead>
      <tr>
        <th>Title</th>
        <th>Assigned To</th>
        <th>Status</th>
        <th>Priority</th>
        <th>Due Date</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse($tasks as $task)
        <tr>
          <td>
            <strong>{{ $task->title }}</strong>
            @if($task->description)
              <p style="font-size:0.78rem;color:#6b7280;margin-top:0.2rem;">{{ Str::limit($task->description, 40) }}</p>
            @endif
          </td>
          <td>{{ $task->assignedTo->name ?? '—' }}</td>
          <td><span class="badge badge-{{ $task->status }}">{{ str_replace('_',' ',$task->status) }}</span></td>
          <td><span class="badge badge-{{ $task->priority }}">{{ $task->priority }}</span></td>
          <td>{{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}</td>
          <td>
            <div style="display:flex;gap:0.4rem;">
              <button class="btn btn-warning" onclick="openEditModal(
                {{ $task->id }},
                '{{ addslashes($task->title) }}',
                '{{ addslashes($task->description) }}',
                '{{ $task->status }}',
                '{{ $task->priority }}',
                '{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}',
                '{{ $task->assigned_to }}'
              )">Edit</button>

              <form method="POST" action="{{ route('admin.tasks.destroy', $task->id) }}"
                    onsubmit="return confirm('Delete this task?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="6" style="text-align:center;color:#9ca3af;padding:2rem;">
            No tasks found.
          </td>
        </tr>
      @endforelse
    </tbody>
  </table>
  <div style="padding:1rem;">{{ $tasks->withQueryString()->links() }}</div>
</div>

{{-- CREATE MODAL --}}
<div class="modal-overlay" id="createModal">
  <div class="modal">
    <h3>➕ Assign New Task</h3>
    <form method="POST" action="{{ route('admin.tasks.store') }}">
      @csrf
      <div class="field">
        <label>Assign To User *</label>
        <select name="assigned_to" class="form-control" required>
          <option value="">Select a user</option>
          @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
          @endforeach
        </select>
      </div>
      <div class="field">
        <label>Title *</label>
        <input type="text" name="title" class="form-control" placeholder="Task title" required />
      </div>
      <div class="field">
        <label>Description</label>
        <textarea name="description" class="form-control" rows="3"></textarea>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
        <div class="field">
          <label>Status</label>
          <select name="status" class="form-control">
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
          </select>
        </div>
        <div class="field">
          <label>Priority</label>
          <select name="priority" class="form-control">
            <option value="medium">Medium</option>
            <option value="high">High</option>
            <option value="low">Low</option>
          </select>
        </div>
      </div>
      <div class="field">
        <label>Due Date</label>
        <input type="date" name="due_date" class="form-control" />
      </div>
      <div class="modal-actions">
        <button type="button" class="btn" style="background:#e5e7eb;color:#374151;" onclick="closeCreateModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">Assign Task</button>
      </div>
    </form>
  </div>
</div>

{{-- EDIT MODAL --}}
<div class="modal-overlay" id="editModal">
  <div class="modal">
    <h3>✏️ Edit Task</h3>
    <form method="POST" id="editForm">
      @csrf
      @method('PUT')
      <div class="field">
        <label>Assign To User</label>
        <select name="assigned_to" id="editAssignedTo" class="form-control">
          @foreach($users as $user)
            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
          @endforeach
        </select>
      </div>
      <div class="field">
        <label>Title *</label>
        <input type="text" name="title" id="editTitle" class="form-control" required />
      </div>
      <div class="field">
        <label>Description</label>
        <textarea name="description" id="editDescription" class="form-control" rows="3"></textarea>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
        <div class="field">
          <label>Status</label>
          <select name="status" id="editStatus" class="form-control">
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
          </select>
        </div>
        <div class="field">
          <label>Priority</label>
          <select name="priority" id="editPriority" class="form-control">
            <option value="medium">Medium</option>
            <option value="high">High</option>
            <option value="low">Low</option>
          </select>
        </div>
      </div>
      <div class="field">
        <label>Due Date</label>
        <input type="date" name="due_date" id="editDueDate" class="form-control" />
      </div>
      <div class="modal-actions">
        <button type="button" class="btn" style="background:#e5e7eb;color:#374151;" onclick="closeEditModal()">Cancel</button>
        <button type="submit" class="btn btn-primary">Update Task</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openCreateModal() { document.getElementById('createModal').classList.add('active'); }
  function closeCreateModal() { document.getElementById('createModal').classList.remove('active'); }

  function openEditModal(id, title, description, status, priority, dueDate, assignedTo) {
    document.getElementById('editTitle').value       = title;
    document.getElementById('editDescription').value = description;
    document.getElementById('editStatus').value      = status;
    document.getElementById('editPriority').value    = priority;
    document.getElementById('editDueDate').value     = dueDate;
    document.getElementById('editAssignedTo').value  = assignedTo;
    document.getElementById('editForm').action       = '/admin/tasks/' + id;
    document.getElementById('editModal').classList.add('active');
  }
  function closeEditModal() { document.getElementById('editModal').classList.remove('active'); }

  document.getElementById('createModal').addEventListener('click', function(e) { if(e.target===this) closeCreateModal(); });
  document.getElementById('editModal').addEventListener('click', function(e) { if(e.target===this) closeEditModal(); });
</script>

@endsection

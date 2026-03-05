<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


//  Handles task data, relationships, and query scopes.
//  Uses soft deletes so tasks can be restored after deletion.
class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'assigned_to',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // Task belongs to the creator
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Task is assigned to a user
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Filter scopes
    public function scopeOfStatus($query, ?string $status)
    {
        if ($status) $query->where('status', $status);
    }

    public function scopeOfPriority($query, ?string $priority)
    {
        if ($priority) $query->where('priority', $priority);
    }

    public function scopeDueBefore($query, ?string $date)
    {
        if ($date) $query->whereDate('due_date', '<=', $date);
    }
}


<?php

namespace App\Models\Portal;

use App\Enums\Portal\TaskStatus;
use Database\Factories\Portal\TaskUpdateFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * One entry in a task's progress timeline. Append-only: updates are never
 * edited, so the history of who moved what and when stays intact.
 */
class TaskUpdate extends Model
{
    /** @use HasFactory<TaskUpdateFactory> */
    use HasFactory;

    protected $table = 'portal_task_updates';

    protected $fillable = [
        'task_id',
        'employee_id',
        'note',
        'progress',
        'status_from',
        'status_to',
        'logged_at',
    ];

    protected function casts(): array
    {
        return [
            'progress' => 'integer',
            'status_from' => TaskStatus::class,
            'status_to' => TaskStatus::class,
            'logged_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Task, $this> */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    /** @return BelongsTo<Employee, $this> */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /** @return MorphMany<Attachment, $this> */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * A timeline entry has no visibility of its own — files hanging off it are
     * authorized against the task the entry belongs to.
     */
    public function attachmentAuthority(): Task
    {
        return $this->task;
    }
}

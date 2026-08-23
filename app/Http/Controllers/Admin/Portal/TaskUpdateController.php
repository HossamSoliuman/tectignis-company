<?php

namespace App\Http\Controllers\Admin\Portal;

use App\Enums\Portal\TaskStatus;
use App\Http\Controllers\Admin\Portal\Concerns\StoresPrivateFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Portal\StoreTaskUpdateRequest;
use App\Models\Portal\Task;
use App\Models\Portal\TaskUpdate;
use Illuminate\Http\RedirectResponse;

/**
 * Progress entries on a task's timeline. Append-only by design: there is no
 * edit or delete route, so the record of who moved what and when survives.
 */
class TaskUpdateController extends Controller
{
    use StoresPrivateFiles;

    public function store(StoreTaskUpdateRequest $request, Task $task): RedirectResponse
    {
        $data = $request->validated();
        $statusBefore = $task->status;
        $statusAfter = isset($data['status']) ? TaskStatus::from($data['status']) : $statusBefore;

        $update = TaskUpdate::create([
            'task_id' => $task->id,
            'employee_id' => $request->user()->portalEmployee()->id,
            'note' => $data['note'],
            'progress' => $data['progress'] ?? $task->progress,
            'status_from' => $statusBefore,
            'status_to' => $statusAfter,
            'logged_at' => now(),
        ]);

        $task->update([
            'progress' => $data['progress'] ?? $task->progress,
            'status' => $statusAfter,
            'completed_at' => $statusAfter === TaskStatus::Completed ? ($task->completed_at ?? now()) : null,
            'reopened_count' => $statusBefore === TaskStatus::Completed && $statusAfter !== TaskStatus::Completed
                ? $task->reopened_count + 1
                : $task->reopened_count,
        ]);

        $this->storeOptionalUpload($update, 'tasks/'.$task->id);

        return redirect()
            ->route('admin.portal.tasks.show', $task)
            ->with('status', 'Update logged.');
    }
}

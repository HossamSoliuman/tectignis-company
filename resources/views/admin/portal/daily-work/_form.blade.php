@php $u = $update ?? null; @endphp

{{-- Deliberately one column, large controls, and pre-filled where possible:
     this form must take an employee one to two minutes on a phone (§5). --}}
<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-5">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Date *</label>
            <input type="date" name="work_date" required max="{{ now()->toDateString() }}"
                value="{{ old('work_date', $u?->work_date?->toDateString() ?? now()->toDateString()) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Employee</label>
            @if ($canLogForOthers)
                <select name="employee_id"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                    @foreach ($employees as $option)
                        <option value="{{ $option->id }}" @selected((string) old('employee_id', $u?->employee_id ?? $employee->id) === (string) $option->id)>{{ $option->name }}</option>
                    @endforeach
                </select>
            @else
                <p class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm text-slate-600">{{ $employee->name }}</p>
            @endif
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">What did you work on? *</label>
        <textarea name="activity" rows="3" required placeholder="e.g. Prepared technical compliance sheet for GeM tender"
            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('activity', $u?->activity) }}</textarea>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Category *</label>
            <select name="related_category" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach ($categories as $value => $label)
                    <option value="{{ $value }}" @selected(old('related_category', $u?->related_category?->value ?? 'other') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Related Task</label>
            <select name="related_task_id"
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">None</option>
                @foreach ($openTasks as $openTask)
                    <option value="{{ $openTask->id }}" @selected((string) old('related_task_id', $u?->related_id) === (string) $openTask->id)>
                        {{ $openTask->code }} — {{ Str::limit($openTask->title, 40) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">From</label>
            <input type="time" name="start_time" value="{{ old('start_time', $u?->start_time ? Str::substr($u->start_time, 0, 5) : '') }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">To</label>
            <input type="time" name="end_time" value="{{ old('end_time', $u?->end_time ? Str::substr($u->end_time, 0, 5) : '') }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Progress (%)</label>
            <input type="number" name="progress" min="0" max="100" value="{{ old('progress', $u?->progress) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Status *</label>
            <select name="status" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $u?->status?->value ?? 'completed') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Remarks</label>
        <textarea name="remarks" rows="2" placeholder="Blockers, help needed, anything management should know"
            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('remarks', $u?->remarks) }}</textarea>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Attachment</label>
        <input type="file" name="attachment"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-slate-700 focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>
</div>

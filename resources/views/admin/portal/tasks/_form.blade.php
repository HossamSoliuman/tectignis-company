@php $t = $task ?? null; @endphp

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Title *</label>
        <input type="text" name="title" value="{{ old('title', $t?->title) }}" required
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Description</label>
        <textarea name="description" rows="4"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('description', $t?->description) }}</textarea>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Assigned To</label>
            @if ($canAssignOthers)
                <select name="assigned_to_id"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                    <option value="">Unassigned</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" @selected((string) old('assigned_to_id', $t?->assigned_to_id) === (string) $employee->id)>{{ $employee->name }}</option>
                    @endforeach
                </select>
            @else
                <p class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-500">
                    {{ $t?->assignee?->name ?? auth()->user()->name }}
                    <span class="text-xs text-slate-400">— only managers can reassign work</span>
                </p>
            @endif
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Priority *</label>
            <select name="priority" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach ($priorities as $value => $label)
                    <option value="{{ $value }}" @selected(old('priority', $t?->priority?->value ?? 'medium') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Status *</label>
            <select name="status" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $t?->status?->value ?? 'not_started') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Progress (%)</label>
            <input type="number" name="progress" min="0" max="100" value="{{ old('progress', $t?->progress ?? 0) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Start</label>
            <input type="datetime-local" name="start_date" value="{{ old('start_date', $t?->start_date?->format('Y-m-d\TH:i')) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            {{-- Date *and* time: deadlines in this business are hour-sensitive. --}}
            <label class="mb-1 block text-sm font-medium text-slate-700">Deadline</label>
            <input type="datetime-local" name="due_date" value="{{ old('due_date', $t?->due_date?->format('Y-m-d\TH:i')) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Attachment</label>
        <input type="file" name="attachment"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm file:mr-3 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-slate-700 focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        <p class="mt-1 text-xs text-slate-400">Stored privately — readable only by people who can view this task.</p>
    </div>
</div>

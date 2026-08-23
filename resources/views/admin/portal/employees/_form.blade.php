@php $e = $employee ?? null; @endphp

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Full Name *</label>
            <input type="text" name="name" value="{{ old('name', $e?->name) }}" required
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Employee Code</label>
            <input type="text" name="employee_code" value="{{ old('employee_code', $e?->employee_code) }}" placeholder="Auto-generated if left blank"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $e?->email) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Phone</label>
            <input type="text" name="phone" value="{{ old('phone', $e?->phone) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Department</label>
            <select name="department_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">Unassigned</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected((string) old('department_id', $e?->department_id) === (string) $department->id)>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Designation</label>
            <input type="text" name="designation" value="{{ old('designation', $e?->designation) }}" placeholder="e.g. Tender Executive"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Date of Joining</label>
            <input type="date" name="date_of_joining" value="{{ old('date_of_joining', $e?->date_of_joining?->toDateString()) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Reports To</label>
            <select name="reports_to_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">Nobody</option>
                @foreach ($managers as $manager)
                    @continue($e && $manager->id === $e->id)
                    <option value="{{ $manager->id }}" @selected((string) old('reports_to_id', $e?->reports_to_id) === (string) $manager->id)>{{ $manager->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium text-slate-700">Status *</label>
            <select name="status" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(old('status', $e?->status?->value ?? 'active') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    </div>

    @if ($canManageAccess)
        {{-- Director-only: this block is what actually grants somebody the
             ability to sign in and act inside the portal. --}}
        <div class="grid grid-cols-1 gap-4 border-t border-slate-100 pt-5 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Linked Login</label>
                <select name="user_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                    <option value="">No login account</option>
                    @foreach ($linkableUsers as $user)
                        <option value="{{ $user->id }}" @selected((string) old('user_id', $e?->user_id) === (string) $user->id)>{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Portal Role</label>
                <select name="portal_role" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                    <option value="">No portal access</option>
                    @foreach ($portalRoles as $value => $label)
                        <option value="{{ $value }}" @selected(old('portal_role', $e?->user?->portal_role) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-slate-400">Applies only when a login is linked.</p>
            </div>
        </div>
    @endif

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Notes</label>
        <textarea name="notes" rows="3"
            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">{{ old('notes', $e?->notes) }}</textarea>
    </div>
</div>

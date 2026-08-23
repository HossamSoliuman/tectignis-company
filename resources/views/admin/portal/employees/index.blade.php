@extends('layouts.admin')

@section('title', 'Employees')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="users" class="h-5 w-5 text-fuchsia-600" />
            Employees
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $employees->total() }}</span>
        </h2>
        @can('create', \App\Models\Portal\Employee::class)
            <a href="{{ route('admin.portal.employees.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Employee
            </a>
        @endcan
    </div>

    <form method="GET" action="{{ route('admin.portal.employees.index') }}"
        class="mb-4 grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm sm:grid-cols-2 lg:grid-cols-4">
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Search</label>
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Name, code, email…"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Department</label>
            <select name="department" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">All</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected((string) request('department') === (string) $department->id)>{{ $department->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-xs font-medium text-slate-500">Status</label>
            <select name="status" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                <option value="">All</option>
                @foreach ($statuses as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-slate-800 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-900">
                <x-admin.icon name="search" class="h-4 w-4" /> Filter
            </button>
            <a href="{{ route('admin.portal.employees.index') }}" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">Reset</a>
        </div>
    </form>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Employee</th>
                        <th class="px-4 py-3">Department</th>
                        <th class="px-4 py-3">Reports To</th>
                        <th class="px-4 py-3">Portal Access</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($employees as $employee)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.portal.employees.show', $employee) }}" class="font-medium text-slate-800 hover:text-fuchsia-700">{{ $employee->name }}</a>
                                <p class="text-xs text-slate-400">{{ $employee->employee_code }} · {{ $employee->designation ?? '—' }}</p>
                            </td>
                            <td class="px-4 py-3 text-slate-500">{{ $employee->department?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $employee->manager?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if ($role = $employee->user?->portalRole())
                                    <x-portal.badge :color="$role->badgeColor()" :label="$role->label()" />
                                @else
                                    <span class="text-xs text-slate-400">No login</span>
                                @endif
                            </td>
                            <td class="px-4 py-3"><x-portal.badge :color="$employee->status->badgeColor()" :label="$employee->status->label()" /></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('update', $employee)
                                        <x-admin.edit-link :href="route('admin.portal.employees.edit', $employee)" />
                                    @endcan
                                    @can('delete', $employee)
                                        <x-admin.delete-button :action="route('admin.portal.employees.destroy', $employee)" confirm="Remove this employee?" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"><x-portal.empty-state message="No employees match these filters." icon="users" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $employees->links() }}</div>
@endsection

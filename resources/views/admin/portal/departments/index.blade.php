@extends('layouts.admin')

@section('title', 'Departments')

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="office" class="h-5 w-5 text-fuchsia-600" />
            Departments
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $departments->count() }}</span>
        </h2>
        <div class="flex items-center gap-2">
            <x-admin.table-search target="#portal-departments-table" placeholder="Search departments…" />
            @can('create', \App\Models\Portal\Department::class)
                <a href="{{ route('admin.portal.departments.create') }}"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                    <x-admin.icon name="plus" class="h-4 w-4" /> New Department
                </a>
            @endcan
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="portal-departments-table">
                <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Code</th>
                        <th class="px-4 py-3">Head</th>
                        <th class="px-4 py-3">Employees</th>
                        <th class="px-4 py-3">Active</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($departments as $department)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-4 py-3 font-medium text-slate-800">{{ $department->name }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $department->code }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $department->head?->name ?? '—' }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $department->employees_count }}</td>
                            <td class="px-4 py-3"><x-admin.status-badge :active="$department->is_active" /></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @can('update', $department)
                                        <x-admin.edit-link :href="route('admin.portal.departments.edit', $department)" />
                                    @endcan
                                    @can('delete', $department)
                                        <x-admin.delete-button :action="route('admin.portal.departments.destroy', $department)" confirm="Delete this department?" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"><x-portal.empty-state message="No departments yet." icon="office" /></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

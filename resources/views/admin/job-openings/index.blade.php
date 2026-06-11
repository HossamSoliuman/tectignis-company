@extends('layouts.admin')

@section('title', 'Job Openings')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="briefcase" class="h-5 w-5 text-fuchsia-600" />
            Job Openings
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $jobOpenings->count() }}</span>
        </h2>
        <div class="flex items-center gap-2">
            <x-admin.table-search target="#job-openings-table" placeholder="Search openings…" />
            <a href="{{ route('admin.job-openings.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Job Opening
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm" id="job-openings-table">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Title</th>
                    <th class="px-4 py-3">Department</th>
                    <th class="px-4 py-3">Location</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($jobOpenings as $job)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $job->title }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $job->department ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $job->location ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $job->employment_type }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$job->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.job-openings.edit', $job)" />
                                <x-admin.delete-button :action="route('admin.job-openings.destroy', $job)" confirm="Delete this job opening?" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-400">No job openings yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

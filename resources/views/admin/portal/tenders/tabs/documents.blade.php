@extends('admin.portal.tenders.workspace')

@section('tab')
    <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <x-portal.kpi-card label="On File" tone="emerald" icon="check-circle"
            :value="$summary['documents']['settled'].' / '.$summary['documents']['total']" hint="ready or uploaded" />
        <x-portal.kpi-card label="Mandatory Missing" :tone="$summary['documents']['mandatory_missing'] > 0 ? 'rose' : 'slate'" icon="document-text"
            :value="$summary['documents']['mandatory_missing']" hint="blocks submission" />
        <x-portal.kpi-card label="Expired" :tone="$summary['documents']['expired'] > 0 ? 'amber' : 'slate'" icon="clock"
            :value="$summary['documents']['expired']" hint="on file but out of date" />
    </div>

    <div class="space-y-5">
        @foreach ($categories as $value => $label)
            @php $rows = $grouped[$value] ?? collect(); @endphp
            <x-portal.panel :title="$label" icon="folder" :count="$rows->count()">
                <div class="divide-y divide-slate-100">
                    @forelse ($rows as $document)
                        @include('admin.portal.tenders.tabs._document-row', ['document' => $document])
                    @empty
                        <div><x-portal.empty-state message="Nothing listed under {{ $label }}." icon="folder" /></div>
                    @endforelse
                </div>
            </x-portal.panel>
        @endforeach

        @can('update', $tender)
            <x-portal.panel title="Add a Document Requirement" icon="plus">
                <form action="{{ route('admin.portal.tenders.documents.store', $tender) }}" method="POST" class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-12">
                    @csrf
                    <div class="sm:col-span-4">
                        <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Name *</label>
                        <input type="text" name="name" required
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Category *</label>
                        <select name="category" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            @foreach ($categories as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Responsible</label>
                        <select name="responsible_employee_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                            <option value="">Nobody</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="mb-1 block text-[10px] uppercase tracking-wide text-slate-400">Needed by</label>
                        <input type="datetime-local" name="required_by"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                    </div>
                    <div class="flex items-end gap-3 sm:col-span-2">
                        <label class="flex items-center gap-2 text-sm text-slate-700">
                            <input type="hidden" name="is_required" value="0">
                            <input type="checkbox" name="is_required" value="1" checked class="rounded border-slate-300">
                            Mandatory
                        </label>
                        <button type="submit" class="rounded-lg bg-fuchsia-600 px-3 py-2 text-sm font-medium text-white transition hover:bg-fuchsia-700">Add</button>
                    </div>
                </form>
            </x-portal.panel>
        @endcan
    </div>
@endsection

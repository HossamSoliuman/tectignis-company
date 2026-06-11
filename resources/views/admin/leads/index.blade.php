@extends('layouts.admin')

@section('title', 'Leads (Inbox)')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="inbox" class="h-5 w-5 text-fuchsia-600" />
            Leads
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $leads->total() }} total</span>
        </h2>
        <x-admin.table-search target="#leads-table" placeholder="Search this page…" />
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm" id="leads-table">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3"></th>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Source</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($leads as $lead)
                    <tr class="transition hover:bg-slate-50 {{ !$lead->is_read ? 'bg-fuchsia-50/40' : '' }}">
                        <td class="px-4 py-3">
                            @if ($lead->is_read)
                                <x-admin.icon name="check-circle" class="h-4 w-4 text-emerald-500" />
                            @else
                                <span class="inline-block h-2.5 w-2.5 rounded-full bg-fuchsia-500 ring-4 ring-fuchsia-100" title="Unread"></span>
                            @endif
                        </td>
                        <td class="px-4 py-3 {{ !$lead->is_read ? 'font-semibold text-slate-900' : 'text-slate-700' }}">{{ $lead->name }}</td>
                        <td class="px-4 py-3">
                            <a href="mailto:{{ $lead->email }}" class="inline-flex items-center gap-1 text-fuchsia-600 hover:underline">
                                <x-admin.icon name="envelope" class="h-3.5 w-3.5" /> {{ $lead->email }}
                            </a>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $lead->phone ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">{{ $lead->source ?? 'contact' }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $lead->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.leads.show', $lead) }}"
                                    class="inline-flex items-center gap-1 rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-medium text-slate-600 transition hover:border-fuchsia-300 hover:bg-fuchsia-50 hover:text-fuchsia-700">
                                    <x-admin.icon name="eye" class="h-3.5 w-3.5" /> View
                                </a>
                                <x-admin.delete-button :action="route('admin.leads.destroy', $lead)" confirm="Delete this lead?" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-10 text-center text-slate-400">No leads yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $leads->links() }}
    </div>
@endsection

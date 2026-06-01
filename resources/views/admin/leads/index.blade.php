@extends('layouts.admin')

@section('title', 'Leads (Inbox)')

@section('content')
    <div class="mb-4">
        <h2 class="text-lg font-semibold">Leads — {{ $leads->total() }} total</h2>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Phone</th>
                    <th class="px-4 py-3">Source</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Read</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($leads as $lead)
                    <tr class="hover:bg-slate-50 {{ !$lead->is_read ? 'font-semibold' : '' }}">
                        <td class="px-4 py-3">{{ $lead->name }}</td>
                        <td class="px-4 py-3">{{ $lead->email }}</td>
                        <td class="px-4 py-3">{{ $lead->phone ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs bg-slate-100 text-slate-600">{{ $lead->source ?? 'contact' }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $lead->created_at->format('M d, Y H:i') }}</td>
                        <td class="px-4 py-3">
                            @if ($lead->is_read)
                                <span class="text-emerald-600 text-xs">Read</span>
                            @else
                                <span class="inline-block h-2 w-2 rounded-full bg-fuchsia-500"></span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.leads.show', $lead) }}" class="text-fuchsia-600 hover:underline">View</a>
                                <form action="{{ route('admin.leads.destroy', $lead) }}" method="POST"
                                    onsubmit="return confirm('Delete this lead?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-slate-400">No leads yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $leads->links() }}
    </div>
@endsection

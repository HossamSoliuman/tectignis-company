@extends('layouts.admin')

@section('title', 'Redirects')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold">Redirects ({{ $redirects->count() }})</h2>
        <a href="{{ route('admin.redirects.create') }}"
            class="rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white hover:bg-fuchsia-700">+ New</a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">From</th>
                    <th class="px-4 py-3">To</th>
                    <th class="px-4 py-3">Code</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($redirects as $r)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-mono text-xs">{{ $r->from_path }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $r->to_path }}</td>
                        <td class="px-4 py-3">{{ $r->status_code ?? 301 }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs {{ $r->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                {{ $r->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.redirects.edit', $r) }}" class="text-fuchsia-600 hover:underline">Edit</a>
                                <form action="{{ route('admin.redirects.destroy', $r) }}" method="POST"
                                    onsubmit="return confirm('Delete?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-slate-400">No redirects configured.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

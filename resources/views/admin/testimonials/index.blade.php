@extends('layouts.admin')

@section('title', 'Testimonials')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="chat-alt" class="h-5 w-5 text-fuchsia-600" />
            Testimonials
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $testimonials->count() }}</span>
        </h2>
        <div class="flex items-center gap-2">
            <x-admin.table-search target="#testimonials-table" placeholder="Search testimonials…" />
            <a href="{{ route('admin.testimonials.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New Testimonial
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm" id="testimonials-table">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Quote</th>
                    <th class="px-4 py-3">Rating</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($testimonials as $t)
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $t->name }}</td>
                        <td class="max-w-xs truncate px-4 py-3 text-slate-500">{{ $t->quote }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 text-amber-500">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="{{ $i <= $t->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5">
                                        <path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.36 4.18a1 1 0 0 0 .95.69h4.4c.97 0 1.37 1.24.59 1.81l-3.56 2.59a1 1 0 0 0-.36 1.12l1.36 4.18c.3.92-.76 1.69-1.54 1.12l-3.56-2.59a1 1 0 0 0-1.18 0l-3.56 2.59c-.78.57-1.84-.2-1.54-1.12l1.36-4.18a1 1 0 0 0-.36-1.12L1.1 9.61c-.78-.57-.38-1.81.59-1.81h4.4a1 1 0 0 0 .95-.69L9.05 2.93Z" />
                                    </svg>
                                @endfor
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $t->sort_order }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$t->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.testimonials.edit', $t)" />
                                <x-admin.delete-button :action="route('admin.testimonials.destroy', $t)" confirm="Delete this testimonial?" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-400">No testimonials yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

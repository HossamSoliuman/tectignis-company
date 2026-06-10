@extends('layouts.admin')

@section('title', 'FAQs')

@section('content')
    <div class="mb-5 flex items-center justify-between">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="question-mark-circle" class="h-5 w-5 text-fuchsia-600" />
            FAQs
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $faqs->count() }}</span>
        </h2>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.faq-categories.index') }}"
                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                <x-admin.icon name="folder" class="h-4 w-4" /> Categories
            </a>
            <a href="{{ route('admin.faqs.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New FAQ
            </a>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Question</th>
                    <th class="px-4 py-3">Category</th>
                    <th class="px-4 py-3">Order</th>
                    <th class="px-4 py-3">Active</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($faqs as $faq)
                    <tr class="transition hover:bg-slate-50">
                        <td class="max-w-md truncate px-4 py-3 font-medium text-slate-800">{{ $faq->question }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $faq->category?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $faq->sort_order }}</td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$faq->is_active" />
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.faqs.edit', $faq)" />
                                <x-admin.delete-button :action="route('admin.faqs.destroy', $faq)" confirm="Delete this FAQ?" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-400">No FAQs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

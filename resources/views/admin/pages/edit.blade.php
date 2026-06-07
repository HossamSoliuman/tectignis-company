@extends('layouts.admin')

@section('title', 'Edit Page')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline"><x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to Pages</a>
    </div>

    <form action="{{ route('admin.pages.update', $page) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_18rem] gap-6 items-start">
            <div>
                @include('admin.pages._form')
            </div>
            <div class="space-y-4">
                <div class="rounded-xl border border-slate-200 bg-white p-5 space-y-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b border-slate-100 pb-3">Details</h3>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $page->sort_order) }}" min="0"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400">
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                            {{ old('is_active', $page->is_active) ? 'checked' : '' }}
                            class="rounded border-slate-300">
                        <label for="is_active" class="text-sm font-medium text-slate-700">Active</label>
                    </div>
                </div>
                <div class="flex flex-col gap-2">
                    <button type="submit"
                        class="w-full inline-flex justify-center items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                        Update Page
                    </button>
                    <a href="{{ route('admin.pages.index') }}"
                        class="w-full inline-flex justify-center items-center rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

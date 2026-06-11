@extends('layouts.admin')

@section('title', 'Add Case Study Category')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.case-study-categories.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline"><x-admin.icon name="arrow-left" class="h-4 w-4" /> Back</a>
    </div>
    <form action="{{ route('admin.case-study-categories.store') }}" method="POST" class="max-w-2xl space-y-6">
        @csrf
        @include('admin.case-study-categories._form')
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.case-study-categories.index') }}" class="inline-flex items-center rounded-lg border border-slate-300 px-5 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Cancel</a>
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">Create Category</button>
        </div>
    </form>
@endsection

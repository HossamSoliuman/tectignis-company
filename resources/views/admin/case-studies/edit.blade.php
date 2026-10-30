@extends('layouts.admin')

@section('title', 'Edit Case Study')

@section('content')
    <div class="mb-4 flex max-w-3xl items-center justify-between gap-3">
        <a href="{{ route('admin.case-studies.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline"><x-admin.icon name="arrow-left" class="h-4 w-4" /> Back</a>
        <a href="{{ route('admin.case-studies.show', $caseStudy) }}" target="_blank" rel="noopener"
            class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
            <x-admin.icon name="eye" class="h-4 w-4" /> Preview
        </a>
    </div>
    <form action="{{ route('admin.case-studies.update', $caseStudy) }}" method="POST" enctype="multipart/form-data" class="max-w-3xl space-y-6">
        @csrf
        @method('PUT')
        @include('admin.case-studies._form')
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">Update</button>
        </div>
    </form>
@endsection

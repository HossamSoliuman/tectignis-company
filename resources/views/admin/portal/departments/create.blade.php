@extends('layouts.admin')
@section('title', 'New Department')
@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.portal.departments.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
            <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to departments
        </a>
    </div>
    <form action="{{ route('admin.portal.departments.store') }}" method="POST" class="max-w-2xl space-y-6">
        @csrf
        @include('admin.portal.departments._form')
        <div class="flex justify-end">
            <button type="submit" class="rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">Create Department</button>
        </div>
    </form>
@endsection

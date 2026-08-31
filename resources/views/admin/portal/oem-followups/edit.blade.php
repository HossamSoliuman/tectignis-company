@extends('layouts.admin')

@section('title', 'Edit Follow-up')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.portal.oem-followups.show', $followup) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-fuchsia-600 hover:underline">
            <x-admin.icon name="arrow-left" class="h-4 w-4" /> Back to {{ $followup->oem_name }}
        </a>
    </div>

    <form action="{{ route('admin.portal.oem-followups.update', $followup) }}" method="POST" class="max-w-3xl space-y-6">
        @csrf
        @method('PUT')
        @include('admin.portal.oem-followups._form')
        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">Save Changes</button>
        </div>
    </form>
@endsection

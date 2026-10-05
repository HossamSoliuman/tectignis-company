@extends('layouts.admin')

@section('title', 'Users & Roles')

@section('content')
    @php
        $roleBadges = [
            'violet' => 'bg-violet-100 text-violet-700',
            'sky' => 'bg-sky-100 text-sky-700',
            'emerald' => 'bg-emerald-100 text-emerald-700',
            'amber' => 'bg-amber-100 text-amber-700',
            'slate' => 'bg-slate-100 text-slate-600',
        ];
    @endphp

    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <h2 class="flex items-center gap-2 text-lg font-semibold text-slate-900">
            <x-admin.icon name="users" class="h-5 w-5 text-fuchsia-600" />
            Users &amp; Roles
            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500">{{ $users->total() }}</span>
        </h2>
        <div class="flex items-center gap-2">
            <x-admin.search-form placeholder="Search users…" />
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center gap-1.5 rounded-lg bg-fuchsia-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-fuchsia-700">
                <x-admin.icon name="plus" class="h-4 w-4" /> New User
            </a>
        </div>
    </div>

    <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full min-w-[40rem] text-sm">
            <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3">Name</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Role</th>
                    <th class="px-4 py-3">Lead export</th>
                    <th class="px-4 py-3">Portal</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($users as $user)
                    @php $role = $user->userRole(); @endphp
                    <tr class="transition hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">
                            {{ $user->name }}
                            @if ($user->is(auth()->user()))
                                <span class="ml-1 text-xs font-normal text-slate-400">(you)</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $user->email }}</td>
                        <td class="px-4 py-3">
                            <span class="rounded-full px-2 py-0.5 text-xs font-medium {{ $roleBadges[$role?->badgeColor() ?? 'slate'] }}">{{ $role?->label() ?? ucfirst((string) $user->role) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <x-admin.status-badge :active="$user->canExportLeads()" activeLabel="Allowed" inactiveLabel="No" />
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ $user->portalRole()?->label() ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <x-admin.edit-link :href="route('admin.users.edit', $user)" />
                                @unless ($user->is(auth()->user()))
                                    <x-admin.delete-button :action="route('admin.users.destroy', $user)" confirm="Delete this user? They will no longer be able to sign in." />
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-10 text-center text-slate-400">No users match your search.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <x-admin.pagination :paginator="$users" />
@endsection

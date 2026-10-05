@php
    $input = 'w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-fuchsia-400';
    $isNew = ! $user->exists;
@endphp

<div class="space-y-5 rounded-xl border border-slate-200 bg-white p-6">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div>
            <label for="name" class="mb-1 block text-sm font-medium text-slate-700">Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255" class="{{ $input }}">
        </div>
        <div>
            <label for="email" class="mb-1 block text-sm font-medium text-slate-700">Email *</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255" class="{{ $input }}">
        </div>
        <div>
            <label for="password" class="mb-1 block text-sm font-medium text-slate-700">Password {{ $isNew ? '*' : '' }}</label>
            <input type="password" id="password" name="password" autocomplete="new-password" @if ($isNew) required @endif
                placeholder="{{ $isNew ? 'At least 8 characters' : 'Leave blank to keep the current password' }}" class="{{ $input }}">
        </div>
        <div>
            <label for="password_confirmation" class="mb-1 block text-sm font-medium text-slate-700">Confirm password {{ $isNew ? '*' : '' }}</label>
            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" @if ($isNew) required @endif class="{{ $input }}">
        </div>
    </div>

    <fieldset>
        <legend class="mb-2 block text-sm font-medium text-slate-700">Website admin role *</legend>
        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
            @foreach (\App\Enums\UserRole::cases() as $role)
                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-slate-200 p-3 transition hover:border-fuchsia-300 has-[:checked]:border-fuchsia-400 has-[:checked]:bg-fuchsia-50">
                    <input type="radio" name="role" value="{{ $role->value }}" class="mt-1" @checked(old('role', $user->role) === $role->value) required>
                    <span>
                        <span class="block text-sm font-semibold text-slate-800">{{ $role->label() }}</span>
                        <span class="block text-xs text-slate-500">{{ $role->description() }}</span>
                    </span>
                </label>
            @endforeach
        </div>
    </fieldset>

    <div class="flex items-start gap-2 border-t border-slate-100 pt-5">
        <input type="hidden" name="can_export_leads" value="0">
        <input type="checkbox" id="can_export_leads" name="can_export_leads" value="1" class="mt-1 rounded border-slate-300"
            @checked(old('can_export_leads', $user->can_export_leads))>
        <label for="can_export_leads" class="text-sm text-slate-700">
            <span class="font-medium">Can export leads to CSV</span>
            <span class="block text-xs text-slate-500">Export is a separate permission from viewing leads. Super Admins can always export.</span>
        </label>
    </div>

    @if ($user->exists && $user->portalRole())
        <p class="rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-500">
            Operations portal access ({{ $user->portalRole()->label() }}) is managed separately under Operations → Employees.
        </p>
    @endif
</div>

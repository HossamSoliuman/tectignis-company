@props(['record', 'action', 'canResend' => false])

{{-- Delivery state of a lead's or application's internal notification email,
     with a resend button for when the mailbox or SMTP was unavailable. --}}
<div {{ $attributes->merge(['class' => 'rounded-2xl border bg-white p-6 shadow-sm '.($record->notificationFailed() ? 'border-red-200' : 'border-slate-200')]) }}>
    <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-800">
        <x-admin.icon name="envelope" class="h-4 w-4 text-fuchsia-600" /> Notification email
    </h3>

    @if ($record->notificationFailed())
        <p class="text-sm font-medium text-red-700">Failed {{ $record->notification_failed_at->diffForHumans() }}</p>
        @if ($record->notification_error)
            <p class="mt-1 break-words rounded-lg bg-red-50 px-3 py-2 font-mono text-xs text-red-700">{{ $record->notification_error }}</p>
        @endif
    @elseif ($record->notified_at)
        <p class="flex items-center gap-1.5 text-sm text-slate-600">
            <x-admin.icon name="check-circle" class="h-4 w-4 text-emerald-600" />
            Sent {{ $record->notified_at->diffForHumans() }}
        </p>
    @else
        <p class="text-sm text-slate-500">No delivery recorded.</p>
    @endif

    @if ($canResend)
        <form action="{{ $action }}" method="POST" class="mt-4">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                <x-admin.icon name="envelope" class="h-4 w-4" /> Resend notification
            </button>
        </form>
    @endif
</div>

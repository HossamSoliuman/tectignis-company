{{--
    Shared body of the Technical and Commercial preparation tabs: the slice of
    the document checklist that team owns, plus the open work alongside it.
    $heading, $blurb and $categories come from the including tab.
--}}
<div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
    <div class="space-y-5 lg:col-span-2">
        @foreach ($categories as $category)
            @php $rows = $documents->where('category', $category); @endphp
            <x-portal.panel :title="$category->label().' Documents'" icon="folder" :count="$rows->count()"
                :action="route('admin.portal.tenders.documents.index', $tender)" action-label="All documents">
                <div class="divide-y divide-slate-100">
                    @forelse ($rows as $document)
                        @include('admin.portal.tenders.tabs._document-row', ['document' => $document])
                    @empty
                        <div><x-portal.empty-state message="Nothing listed under {{ $category->label() }}." icon="folder" /></div>
                    @endforelse
                </div>
            </x-portal.panel>
        @endforeach
    </div>

    <div class="space-y-5">
        <x-portal.panel :title="$heading" icon="light-bulb">
            <p class="px-4 py-3 text-sm text-slate-600">{{ $blurb }}</p>
        </x-portal.panel>

        <x-portal.panel title="Open Work" icon="clipboard-list" :count="$tasks->count()"
            :action="route('admin.portal.tenders.tasks.index', $tender)">
            <ul class="divide-y divide-slate-100">
                @forelse ($tasks as $task)
                    <li class="flex items-center justify-between gap-3 px-4 py-3 {{ $task->isOverdue() ? 'bg-rose-50/40' : '' }}">
                        <div class="min-w-0">
                            <a href="{{ route('admin.portal.tasks.show', $task) }}" class="truncate text-sm font-medium text-slate-800 hover:text-fuchsia-700">{{ $task->title }}</a>
                            <p class="text-xs text-slate-400">{{ $task->assignee?->name ?? 'Unassigned' }}</p>
                        </div>
                        <x-portal.due-date :date="$task->due_date" :closed="$task->status->isClosed()" />
                    </li>
                @empty
                    <li><x-portal.empty-state message="No open tasks." icon="clipboard-list" /></li>
                @endforelse
            </ul>
        </x-portal.panel>
    </div>
</div>

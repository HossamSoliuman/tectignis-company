<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStatRequest;
use App\Http\Requests\Admin\UpdateStatRequest;
use App\Models\Stat;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StatController extends Controller
{
    use PaginatesTables;

    public function index(Request $request): View
    {
        $stats = $this->paginateTable($request, Stat::ordered(), ['label', 'key', 'value']);

        return view('admin.stats.index', compact('stats'));
    }

    public function create(): View
    {
        return view('admin.stats.create');
    }

    public function store(StoreStatRequest $request): RedirectResponse
    {
        Stat::create($request->validated());

        return redirect()->route('admin.stats.index')->with('status', 'Stat created.');
    }

    public function edit(Stat $stat): View
    {
        return view('admin.stats.edit', compact('stat'));
    }

    public function update(UpdateStatRequest $request, Stat $stat): RedirectResponse
    {
        $stat->update($request->validated());

        return redirect()->route('admin.stats.index')->with('status', 'Stat updated.');
    }

    public function destroy(Stat $stat): RedirectResponse
    {
        $stat->delete();

        return redirect()->route('admin.stats.index')->with('status', 'Stat deleted.');
    }
}

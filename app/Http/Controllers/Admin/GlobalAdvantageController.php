<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGlobalAdvantageRequest;
use App\Http\Requests\Admin\UpdateGlobalAdvantageRequest;
use App\Models\GlobalAdvantage;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class GlobalAdvantageController extends Controller
{
    public function index(): View
    {
        $advantages = GlobalAdvantage::ordered()->get();

        return view('admin.global-advantages.index', compact('advantages'));
    }

    public function create(): View
    {
        return view('admin.global-advantages.create');
    }

    public function store(StoreGlobalAdvantageRequest $request): RedirectResponse
    {
        GlobalAdvantage::create($request->validated());

        return redirect()->route('admin.global-advantages.index')->with('status', 'Advantage created.');
    }

    public function edit(GlobalAdvantage $globalAdvantage): View
    {
        return view('admin.global-advantages.edit', ['advantage' => $globalAdvantage]);
    }

    public function update(UpdateGlobalAdvantageRequest $request, GlobalAdvantage $globalAdvantage): RedirectResponse
    {
        $globalAdvantage->update($request->validated());

        return redirect()->route('admin.global-advantages.index')->with('status', 'Advantage updated.');
    }

    public function destroy(GlobalAdvantage $globalAdvantage): RedirectResponse
    {
        $globalAdvantage->delete();

        return redirect()->route('admin.global-advantages.index')->with('status', 'Advantage deleted.');
    }
}

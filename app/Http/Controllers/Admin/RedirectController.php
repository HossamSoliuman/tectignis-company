<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRedirectRequest;
use App\Http\Requests\Admin\UpdateRedirectRequest;
use App\Models\Redirect;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class RedirectController extends Controller
{
    public function index(): View
    {
        $redirects = Redirect::latest()->get();

        return view('admin.redirects.index', compact('redirects'));
    }

    public function create(): View
    {
        return view('admin.redirects.create');
    }

    public function store(StoreRedirectRequest $request): RedirectResponse
    {
        Redirect::create($request->validated());

        return redirect()->route('admin.redirects.index')->with('status', 'Redirect created.');
    }

    public function edit(Redirect $redirect): View
    {
        return view('admin.redirects.edit', compact('redirect'));
    }

    public function update(UpdateRedirectRequest $request, Redirect $redirect): RedirectResponse
    {
        $redirect->update($request->validated());

        return redirect()->route('admin.redirects.index')->with('status', 'Redirect updated.');
    }

    public function destroy(Redirect $redirect): RedirectResponse
    {
        $redirect->delete();

        return redirect()->route('admin.redirects.index')->with('status', 'Redirect deleted.');
    }
}

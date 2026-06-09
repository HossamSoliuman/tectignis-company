<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProcessStepRequest;
use App\Http\Requests\Admin\UpdateProcessStepRequest;
use App\Models\ProcessStep;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProcessStepController extends Controller
{
    public function index(): View
    {
        $steps = ProcessStep::ordered()->get();

        return view('admin.process-steps.index', compact('steps'));
    }

    public function create(): View
    {
        return view('admin.process-steps.create');
    }

    public function store(StoreProcessStepRequest $request): RedirectResponse
    {
        ProcessStep::create($request->validated());

        return redirect()->route('admin.process-steps.index')->with('status', 'Step created.');
    }

    public function edit(ProcessStep $processStep): View
    {
        return view('admin.process-steps.edit', ['step' => $processStep]);
    }

    public function update(UpdateProcessStepRequest $request, ProcessStep $processStep): RedirectResponse
    {
        $processStep->update($request->validated());

        return redirect()->route('admin.process-steps.index')->with('status', 'Step updated.');
    }

    public function destroy(ProcessStep $processStep): RedirectResponse
    {
        $processStep->delete();

        return redirect()->route('admin.process-steps.index')->with('status', 'Step deleted.');
    }
}

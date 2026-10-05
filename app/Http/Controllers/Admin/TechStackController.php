<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTechStackRequest;
use App\Http\Requests\Admin\UpdateTechStackRequest;
use App\Models\TechStack;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TechStackController extends Controller
{
    use PaginatesTables;
    use UploadsFiles;

    public function index(Request $request): View
    {
        $techStacks = $this->paginateTable($request, TechStack::ordered(), ['name', 'category']);

        return view('admin.tech-stacks.index', compact('techStacks'));
    }

    public function create(): View
    {
        return view('admin.tech-stacks.create');
    }

    public function store(StoreTechStackRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'logo', null, 'tech-stacks');

        TechStack::create($data);

        return redirect()->route('admin.tech-stacks.index')->with('status', 'Technology created.');
    }

    public function edit(TechStack $techStack): View
    {
        return view('admin.tech-stacks.edit', compact('techStack'));
    }

    public function update(UpdateTechStackRequest $request, TechStack $techStack): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'logo', $techStack->logo, 'tech-stacks');

        $techStack->update($data);

        return redirect()->route('admin.tech-stacks.index')->with('status', 'Technology updated.');
    }

    public function destroy(TechStack $techStack): RedirectResponse
    {
        $this->deleteUpload($techStack->logo);
        $techStack->delete();

        return redirect()->route('admin.tech-stacks.index')->with('status', 'Technology deleted.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSolutionRequest;
use App\Http\Requests\Admin\UpdateSolutionRequest;
use App\Models\Solution;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SolutionController extends Controller
{
    use UploadsFiles;

    public function index(): View
    {
        $solutions = Solution::ordered()->get();

        return view('admin.solutions.index', compact('solutions'));
    }

    public function create(): View
    {
        return view('admin.solutions.create');
    }

    public function store(StoreSolutionRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'banner_image', null, 'solutions');

        Solution::create($data);

        return redirect()->route('admin.solutions.index')->with('status', 'Solution created.');
    }

    public function edit(Solution $solution): View
    {
        return view('admin.solutions.edit', compact('solution'));
    }

    public function update(UpdateSolutionRequest $request, Solution $solution): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'banner_image', $solution->banner_image, 'solutions');

        $solution->update($data);

        return redirect()->route('admin.solutions.index')->with('status', 'Solution updated.');
    }

    public function destroy(Solution $solution): RedirectResponse
    {
        $this->deleteUpload($solution->banner_image);
        $solution->delete();

        return redirect()->route('admin.solutions.index')->with('status', 'Solution deleted.');
    }
}

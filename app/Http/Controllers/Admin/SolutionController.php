<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesSolutionContent;
use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSolutionRequest;
use App\Http\Requests\Admin\UpdateSolutionRequest;
use App\Models\Solution;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SolutionController extends Controller
{
    use ManagesSolutionContent, UploadsFiles;
    use PaginatesTables;

    public function index(Request $request): View
    {
        $solutions = $this->paginateTable($request, Solution::ordered(), ['title', 'slug']);

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
        $this->prepareContent($request, $data);

        $solution = Solution::create($data);

        return redirect()->route('admin.solutions.edit', $solution)->with('status', 'Solution created.');
    }

    public function edit(Solution $solution): View
    {
        return view('admin.solutions.edit', compact('solution'));
    }

    public function update(UpdateSolutionRequest $request, Solution $solution): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'banner_image', $solution->banner_image, 'solutions');
        $this->prepareContent($request, $data, $solution);

        $solution->update($data);

        return redirect()->route('admin.solutions.edit', $solution)->with('status', 'Solution updated.');
    }

    public function destroy(Solution $solution): RedirectResponse
    {
        $this->deleteUpload($solution->banner_image);
        $solution->delete();

        return redirect()->route('admin.solutions.index')->with('status', 'Solution deleted.');
    }
}

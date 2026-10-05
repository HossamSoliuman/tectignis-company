<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBrandRequest;
use App\Http\Requests\Admin\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    use PaginatesTables;
    use UploadsFiles;

    public function index(Request $request): View
    {
        $brands = $this->paginateTable($request, Brand::ordered(), ['name', 'url']);

        return view('admin.brands.index', compact('brands'));
    }

    public function create(): View
    {
        return view('admin.brands.create');
    }

    public function store(StoreBrandRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'logo', null, 'brands');

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('status', 'Brand created.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'logo', $brand->logo, 'brands');

        $brand->update($data);

        return redirect()->route('admin.brands.index')->with('status', 'Brand updated.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        $this->deleteUpload($brand->logo);
        $brand->delete();

        return redirect()->route('admin.brands.index')->with('status', 'Brand deleted.');
    }
}

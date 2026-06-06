<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ManagesServiceContent;
use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreServiceRequest;
use App\Http\Requests\Admin\UpdateServiceRequest;
use App\Models\Industry;
use App\Models\Service;
use App\Models\TechStack;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

class ServiceController extends Controller
{
    use ManagesServiceContent, UploadsFiles;

    public function index(): View
    {
        $services = Service::ordered()->get();

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create', $this->catalogs());
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'icon', null, 'services');
        $this->syncUpload($request, $data, 'banner_image', null, 'services');
        $this->prepareContent($request, $data);

        [$techStacks, $industries] = $this->pullAttachments($data);

        $service = Service::create($data);
        $service->techStacks()->sync($techStacks);
        $service->industries()->sync($industries);

        return redirect()->route('admin.services.edit', $service)->with('status', 'Service created.');
    }

    public function edit(Service $service): View
    {
        $service->load('techStacks', 'industries');

        return view('admin.services.edit', array_merge(compact('service'), $this->catalogs()));
    }

    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'icon', $service->icon, 'services');
        $this->syncUpload($request, $data, 'banner_image', $service->banner_image, 'services');
        $this->prepareContent($request, $data, $service);

        [$techStacks, $industries] = $this->pullAttachments($data);

        $service->update($data);
        $service->techStacks()->sync($techStacks);
        $service->industries()->sync($industries);

        return redirect()->route('admin.services.edit', $service)->with('status', 'Service updated.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $this->deleteUpload($service->icon);
        $this->deleteUpload($service->banner_image);
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', 'Service deleted.');
    }

    /**
     * Shared catalogs available for per-service attachment (Sections E/F).
     *
     * @return array{techStacks: Collection<int, TechStack>, industries: Collection<int, Industry>}
     */
    private function catalogs(): array
    {
        return [
            'techStacks' => TechStack::ordered()->get(),
            'industries' => Industry::ordered()->get(),
        ];
    }

    /**
     * Extract and remove the pivot id arrays from the persisted data.
     *
     * @param  array<string, mixed>  $data
     * @return array{0: array<int, int>, 1: array<int, int>}
     */
    private function pullAttachments(array &$data): array
    {
        $techStacks = $data['tech_stacks'] ?? [];
        $industries = $data['industries'] ?? [];
        unset($data['tech_stacks'], $data['industries']);

        return [$techStacks, $industries];
    }
}

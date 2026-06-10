<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDownloadRequest;
use App\Http\Requests\Admin\UpdateDownloadRequest;
use App\Models\Download;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class DownloadController extends Controller
{
    use UploadsFiles;

    public function index(): View
    {
        $downloads = Download::ordered()->get();

        return view('admin.downloads.index', compact('downloads'));
    }

    public function create(): View
    {
        return view('admin.downloads.create');
    }

    public function store(StoreDownloadRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'file', null, 'downloads');

        Download::create($data);

        return redirect()->route('admin.downloads.index')->with('status', 'Download created.');
    }

    public function edit(Download $download): View
    {
        return view('admin.downloads.edit', compact('download'));
    }

    public function update(UpdateDownloadRequest $request, Download $download): RedirectResponse
    {
        $data = $request->validated();

        $this->syncUpload($request, $data, 'file', $download->file, 'downloads');

        $download->update($data);

        return redirect()->route('admin.downloads.index')->with('status', 'Download updated.');
    }

    public function destroy(Download $download): RedirectResponse
    {
        $this->deleteUpload($download->file);
        $download->delete();

        return redirect()->route('admin.downloads.index')->with('status', 'Download deleted.');
    }
}

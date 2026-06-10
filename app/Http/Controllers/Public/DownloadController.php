<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\Lead;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    public function index(): View
    {
        $downloads = Download::active()->ordered()->get();

        return view('public.downloads', compact('downloads'));
    }

    public function request(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'download_id' => ['required', 'integer', 'exists:downloads,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
        ], [], [
            'download_id' => 'resource',
        ]);

        $download = Download::active()->findOrFail($data['download_id']);

        Lead::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'subject' => 'Download: '.$download->title,
            'message' => 'Requested the "'.$download->title.'" resource from the downloads page.',
            'source' => 'download',
        ]);

        return back()
            ->with('download_status', 'Thank you! Your download is starting.')
            ->with('download_url', $download->file ? asset('uploads/'.$download->file) : null);
    }
}

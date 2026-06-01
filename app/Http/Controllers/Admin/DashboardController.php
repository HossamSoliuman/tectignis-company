<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\CaseStudy;
use App\Models\Lead;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use UploadsFiles;

    public function __invoke(): View
    {
        $stats = [
            'Leads' => Lead::count(),
            'Blog Posts' => BlogPost::count(),
            'Services' => Service::count(),
            'Case Studies' => CaseStudy::count(),
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
            'uploads' => $this->recentUploads(),
        ]);
    }

    /**
     * Store an uploaded image into public/uploads and report back the public URL.
     */
    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,svg,gif', 'max:5120'],
        ]);

        $filename = $this->storeUpload($request->file('image'));

        return back()->with('status', 'Image uploaded successfully: '.asset('uploads/'.$filename));
    }

    /**
     * The most recently uploaded files in public/uploads, newest first.
     *
     * @return array<int, array{name: string, url: string}>
     */
    protected function recentUploads(int $limit = 8): array
    {
        $directory = public_path('uploads');

        if (! is_dir($directory)) {
            return [];
        }

        $files = glob($directory.'/*.{jpg,jpeg,png,webp,svg,gif}', GLOB_BRACE) ?: [];

        usort($files, fn (string $a, string $b): int => filemtime($b) <=> filemtime($a));

        return array_map(
            fn (string $path): array => [
                'name' => basename($path),
                'url' => asset('uploads/'.basename($path)),
            ],
            array_slice($files, 0, $limit),
        );
    }
}

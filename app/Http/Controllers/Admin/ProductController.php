<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Admin\Concerns\UploadsFiles;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\CaseStudy;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use PaginatesTables;
    use UploadsFiles;

    private const UPLOAD_FOLDER = 'products';

    public function index(Request $request): View
    {
        $products = $this->paginateTable($request, Product::ordered(), ['name', 'description']);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create', ['caseStudies' => $this->caseStudyOptions()]);
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        Product::create($this->prepare($request, $request->validated()));

        return redirect()->route('admin.products.index')->with('status', 'Product created.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', ['product' => $product, 'caseStudies' => $this->caseStudyOptions()]);
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $product->update($this->prepare($request, $request->validated(), $product));

        return redirect()->route('admin.products.index')->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        foreach ($product->galleryImages() as $item) {
            $this->deleteUpload($item['image']);
        }

        foreach ($product->videos ?? [] as $video) {
            $this->deleteVideoFile($video['source'] ?? null);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Product deleted.');
    }

    /**
     * Published case studies a product can link to.
     *
     * @return array<int, string> id => title
     */
    private function caseStudyOptions(): array
    {
        return CaseStudy::published()->orderBy('title')->pluck('title', 'id')->all();
    }

    /**
     * Normalise validated input: the screenshot gallery and the videos (kept
     * items with their captions, removed items deleted, new ones appended).
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function prepare(Request $request, array $data, ?Product $product = null): array
    {
        $data['gallery'] = $this->syncGallery($request, $data['gallery'] ?? [], $product);
        $data['videos'] = $this->syncVideos($request, $data['videos'] ?? [], $data['new_video'] ?? [], $product);

        unset($data['gallery_uploads'], $data['new_video'], $data['new_video_file']);

        return $data;
    }

    /**
     * @param  array<int, array{image: string, alt?: ?string, remove?: bool}>  $submitted
     * @return list<array{image: string, alt: ?string}>
     */
    private function syncGallery(Request $request, array $submitted, ?Product $product): array
    {
        $existing = collect($product?->gallery ?? [])->pluck('image')->filter()->all();
        $gallery = [];

        foreach ($submitted as $item) {
            // Only images already stored on this product can be kept.
            if (! in_array($item['image'], $existing, true)) {
                continue;
            }

            if (! empty($item['remove'])) {
                $this->deleteUpload($item['image']);

                continue;
            }

            $gallery[] = ['image' => $item['image'], 'alt' => $item['alt'] ?? null];
        }

        foreach ((array) $request->file('gallery_uploads', []) as $file) {
            $gallery[] = ['image' => $this->storeUpload($file, self::UPLOAD_FOLDER), 'alt' => null];
        }

        return $gallery;
    }

    /**
     * @param  array<int, array{source: string, title?: ?string, remove?: bool}>  $submitted
     * @param  array{title?: ?string, link?: ?string}  $newVideo
     * @return list<array{source: string, title: ?string}>
     */
    private function syncVideos(Request $request, array $submitted, array $newVideo, ?Product $product): array
    {
        $existing = collect($product?->videos ?? [])->pluck('source')->filter()->all();
        $videos = [];

        foreach ($submitted as $item) {
            // Only videos already stored on this product can be kept.
            if (! in_array($item['source'], $existing, true)) {
                continue;
            }

            if (! empty($item['remove'])) {
                $this->deleteVideoFile($item['source']);

                continue;
            }

            $videos[] = ['source' => $item['source'], 'title' => $item['title'] ?? null];
        }

        $title = $newVideo['title'] ?? null;

        if ($request->hasFile('new_video_file')) {
            $videos[] = ['source' => $this->storeUpload($request->file('new_video_file'), self::UPLOAD_FOLDER), 'title' => $title];
        } elseif (filled($newVideo['link'] ?? null)) {
            $videos[] = ['source' => $newVideo['link'], 'title' => $title];
        }

        return $videos;
    }

    /**
     * Uploaded videos live in uploads/; YouTube and Vimeo links have no file.
     */
    private function deleteVideoFile(?string $source): void
    {
        if ($source && ! Str::startsWith($source, ['http://', 'https://'])) {
            $this->deleteUpload($source);
        }
    }
}

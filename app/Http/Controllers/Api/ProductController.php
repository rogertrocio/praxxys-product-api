<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250415 - Created
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $products = Product::commonFilters($request->only('filter'))
            ->with('category')
            ->orderBy('created_at')
            ->paginate();

        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ProductRequest $request
     * @return \App\Http\Resources\ProductResource
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250415 - Created
     */
    public function store(ProductRequest $request): ProductResource
    {
        $validated = $request->validated();

        $product = Product::create($validated);

        if (isset($validated['images']) && count($validated['images']) >  0) {
            $this->uploadImage($validated['images'], $product);
        }

        $product->refresh();

        return new ProductResource($product->load('category', 'images'));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \App\Http\Resources\ProductResource
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250415 - Created
     */
    public function show(Product $product): ProductResource
    {
        return new ProductResource($product->load('category', 'images'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ProductRequest $request
     * @param \App\Models\Product $product
     * @return \App\Http\Resources\ProductResource
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250415 - Created
     */
    public function update(ProductRequest $request, Product $product): ProductResource
    {
        $validated = $request->validated();

        $product->update($validated);

        /**
         * Remove existing associated images of the product.
         */
        if (isset($validated['old_images']) && $product->images->count() > 0) {
            foreach ($product->images as $image) {
                $exists = in_array($image->id, $validated['old_images']);

                if (!$exists) {
                    Storage::delete($image->path);
                    $image->delete();
                }
            }
        }

        /**
         * Create new images for the product.
         */
        if (isset($validated['images']) && count($validated['images']) >  0) {
            $this->uploadImage($validated['images'], $product);
        }

        $product->refresh();

        return new ProductResource($product->load('category', 'images'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250415 - Created
     */
    public function destroy(Product $product): Response
    {
        Storage::deleteDirectory("products/{$product->id}");

        $product->images()->delete();

        $product->delete();

        return response()->noContent();
    }

    /**
     * Save images of the product.
     *
     * @param array|null $images
     * @param \App\Models\Product $product
     * @return void
     */
    private function uploadImage(?array $images, Product $product): void
    {
        foreach ($images as $image) {
            $properties = [
                'client_original_name' => $image->getClientOriginalName(),
                'client_original_extension' => $image->getClientOriginalExtension(),
                'hash_name' => $image->hashName(),
                'extension' => $image->extension(),
                'size' => $image->getSize(),
            ];

            $path = Storage::putFile("products/{$product->id}", $image);

            $product->images()->save(
                Image::make([
                    'path' => $path,
                    'file_name' => $image->hashName(),
                    'properties' => $properties
                ])
            );
        }
    }
}

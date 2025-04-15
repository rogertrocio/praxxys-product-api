<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

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

        // Todo: Attach multiple images

        $product->refresh();

        return new ProductResource($product->load('category'));
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
        return new ProductResource($product->load('category'));
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

        // Todo: Update multiple images

        $product->refresh();

        return new ProductResource($product->load('category'));
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
        $product->delete();

        // Todo: Delete associated images

        return response()->noContent();
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250414 - Created
     */
    public function index(): AnonymousResourceCollection
    {
        $categories = Category::orderBy('name', 'asc')->paginate();

        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CategoryRequest $request
     * @return \App\Http\Resources\CategoryResource
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250414 - Created
     */
    public function store(CategoryRequest $request): CategoryResource
    {
        $validated = $request->validated();

        $category = Category::create($validated);

        $category->refresh();

        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \App\Http\Resources\CategoryResource
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250414 - Created
     */
    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\CategoryRequest $request
     * @param \App\Models\Category $category
     * @return \App\Http\Resources\CategoryResource
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250414 - Created
     */
    public function update(CategoryRequest $request, Category $category): CategoryResource
    {
        $validated = $request->validated();

        $category->update($validated);

        $category->refresh();

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250414 - Created
     */
    public function destroy(Category $category): Response
    {
        abort_if(
            $category->products->count() > 0, 403,
            "The {$category->name} is assigned to some products. Category cannot be deleted."
        );

        $category->delete();

        return response()->noContent();
    }
}

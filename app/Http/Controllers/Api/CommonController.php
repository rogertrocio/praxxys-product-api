<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommonController extends Controller
{
    /**
     * Get a collection of categories.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     * @author Roger A. Trocio <rogertrocio29@gmail.com>
     * @mods
     *  RAT 20250415 - Created
     */
    public function categories(): AnonymousResourceCollection
    {
        $categories = Category::orderBy('name', 'asc')->get();

        return CategoryResource::collection($categories);
    }
}

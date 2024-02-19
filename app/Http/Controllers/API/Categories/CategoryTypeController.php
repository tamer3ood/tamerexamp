<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\Categories\CategoryTypeResource;

class CategoryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($sub_category_id)
    {
        return CategoryTypeResource::collection(Category::where("parent_id", $sub_category_id)
            ->where('category_lv', 3)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}

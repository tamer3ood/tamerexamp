<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\Controller;
use App\Models\CategoryService;
use Illuminate\Http\Request;
use App\Http\Resources\Categories\CategoryServiceResource;

class CategoryServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($category_id)
    {
        return CategoryServiceResource::collection(CategoryService::where('category_id', '=', $category_id)->get());

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
    public function show(CategoryService $categoryService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryService $categoryService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryService $categoryService)
    {
        //
    }
}

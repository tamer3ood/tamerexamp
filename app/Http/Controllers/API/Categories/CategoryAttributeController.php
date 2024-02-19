<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\Controller;
use App\Models\CategoryAttribute;
use Illuminate\Http\Request;
use App\Http\Resources\Categories\CategoryAttributeResource;

class CategoryAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getAttributes($category_id)
    {
        return CategoryAttributeResource::collection(CategoryAttribute::where('category_id', $category_id)->get());
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
    public function show(CategoryAttribute $categoryAttribute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryAttribute $categoryAttribute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryAttribute $categoryAttribute)
    {
        //
    }
}

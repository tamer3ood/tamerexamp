<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\Controller;
use App\Models\CategoryElement;
use Illuminate\Http\Request;
use App\Http\Resources\Categories\CategoryElementResource;

class CategoryElementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($category_id)
    {
        return CategoryElementResource::collection(CategoryElement::where('category_id', '=', $category_id)->get());
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
    public function show(CategoryElement $categoryElement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryElement $categoryElement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryElement $categoryElement)
    {
        //
    }
}

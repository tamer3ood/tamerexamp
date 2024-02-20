<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\Controller;
use App\Models\CategoryElementAddOnAttribute;
use App\Http\Resources\Categories\CategoryElementAddOnAttributeResource;
use Illuminate\Http\Request;

class CategoryElementAddOnAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($category_element_id)
    {
        // dd(CategoryElementAddOnAttribute::all());
        return CategoryElementAddOnAttributeResource::collection(CategoryElementAddOnAttribute::where("category_element_id", $category_element_id)->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(CategoryElementAddOnAttribute $categoryElementAddOnAttribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryElementAddOnAttribute $categoryElementAddOnAttribute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryElementAddOnAttribute $categoryElementAddOnAttribute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryElementAddOnAttribute $categoryElementAddOnAttribute)
    {
        //
    }
}

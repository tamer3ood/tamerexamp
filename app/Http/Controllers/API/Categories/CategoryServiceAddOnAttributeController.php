<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\Controller;
use App\Models\CategoryServiceAddOnAttribute;
use Illuminate\Http\Request;
use App\Http\Resources\Categories\CategoryServiceAddOnAttributeResource;

class CategoryServiceAddOnAttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($category_service_id)
    {
        return CategoryServiceAddOnAttributeResource::collection(CategoryServiceAddOnAttribute::where("category_service_id", $category_service_id)->get());
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
    public function show(CategoryServiceAddOnAttribute $categoryServiceAddOnAttribute)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CategoryServiceAddOnAttribute $categoryServiceAddOnAttribute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CategoryServiceAddOnAttribute $categoryServiceAddOnAttribute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryServiceAddOnAttribute $categoryServiceAddOnAttribute)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;



class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::get();
//        using created_at column value add
//        dynamically created_from column, the value of it must be like (2 hours ago, two hours
//        ago, etc..).

        foreach ($categories as $category) {
            $category->created_from = Carbon::parse($category->created_at)->diffForHumans();
        }

        return response()->json(['message' => 'Categories retrieved successfully', 'data' => $categories], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Show the form for creating a new category
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $category = new Category;
        $category->name = $request->name;
        $category->save();
        return response()->json(['message' => 'Category created successfully', 'data' => $category], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
//        using created_at column value add
//        dynamically created_from column, the value of it must be like (2 hours ago, two hours
//        ago, etc..).
            $category->created_from = Carbon::parse($category->created_at)->diffForHumans();

        return response()->json(['message' => 'Category retrieved successfully', 'data' => $category], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Show the form for editing the category
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->name = $request->name;
        $category->save();

        return response()->json(['message' => 'Category updated successfully', 'data' => $category], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
}

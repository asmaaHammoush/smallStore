<?php

namespace App\Http\Controllers;

use App\Http\Requests\categories\UpdateCategoryRequest;
use App\Models\Category;
use App\Http\Requests\categories\StoreCategoryRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\processImageTrait;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use processImageTrait;
    public function index()
    {
        $categories = Category::get();
        $productsByCategory = [];

        foreach ($categories as $category) {
            $productsByCategory[$category->id] = $category->product()
                ->with('user')
                ->whereHas('user', function ($query) {
                $query->where('name', 'like', '%a%');
            })->priceFilter()->get();
            $category->load('image:imageable_id,photo');
        }
        if (!$categories) {
            return response()->json([
                'message' => 'categories not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Categories retrieved successfully',
            'data' => $productsByCategory
        ], 200);
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

    public function store(StoreCategoryRequest $request)
    {
        $request->validate([
            'photo' => '
             max:1
            |array',
        ]);
        $imageName = $this->photo($request,'categories');

        $category = Category::create([
            'name' => $request->name,
            'description'=>$request->description,
        ])->image()->create([
            'photo' => $imageName[0]
        ])->save();

        if (!$category) {
            return response()->json([
                'message' => 'Category failed'
            ], 404);
        }

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
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
        $category->load('image:imageable_id,photo');
        $category= $category->product()
           ->with('user')
           ->whereHas('user',function ($query){
            $query->where('name','like','%a%');
        })->priceFilter()->get();

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Category retrieved successfully',
            'data' => $category
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Show the form for editing the category
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
        $imageName = $this->photo($request,'categories');
        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

            $category->image()->update([
            'photo' => $imageName[0]
        ]);

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $category
        ], 200);
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
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
        $category->image()->delete();
        $category->delete();
        $category = Category::find($id);
        if (!$category) {
        return response()->json([
            'message' => 'Category deleted successfully'
        ], 200);
        }

        return response()->json([
            'message' => 'deleted failed'
        ], 400);
    }
}

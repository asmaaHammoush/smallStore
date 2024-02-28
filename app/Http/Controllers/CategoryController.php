<?php

namespace App\Http\Controllers;

use App\Http\Requests\categories\UpdateCategoryRequest;
use App\Models\Category;
use App\Http\Requests\categories\StoreCategoryRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\processImageTrait;
use Illuminate\Support\Facades\DB;

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
        $categories = Category::categoryWithSub()
            ->containLittera()
            ->get();

        if (!$categories) {
            return response()->json([
                'message' => 'categories not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Categories retrieved successfully',
            'data' => $categories
        ], 201);
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
        $imageName = $this->uploadPhoto($request, 'categories');
        $success = false;
       $category =new Category();

        DB::transaction(function () use ($request, $imageName, &$success, &$category) {
        $category = Category::create([
            'name' => $request->name,
            'description'=>$request->description,
            'parent_id' =>$request->parent_id,
        ])->image()->create([
            'photo' => $imageName[0]
        ])->save();
            if (!$category) {
                $success = false;
                return;
            }
            $success = true;
        });


        if (!$success) {
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
//        global scope
        $category = Category::categoryWithSub()
            ->containLittera()
            ->find($id);

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
        DB::transaction(function () use ($request,&$category) {
            $category->update([
             'name' => $request->name,
             'description' => $request->description,
             'parent_id' => $request->parent_id
          ]);
            $imageName = $this->updatePhoto($request,$category->image->photo,'categories');
            $category->image()->update([
            'photo' => $imageName[0]
          ]);
        });
        $updatedCtegory = Category::with('image:imageable_id,photo')->find($id);

        return response()->json([
            'message' => 'Category updated successfully',
            'data' => $updatedCtegory
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
        $success = false;
        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        DB::transaction(function () use (&$category,$id,&$success) {
            $this->deletePhoto($category->image->photo);
            $category->image()->delete();
            $category->delete();
            $category = Category::find($id);
            if (!$category) {
                $success = true;
                return;
            }
            $success = false;
        });
            if ($success) {
             return response()->json([
              'message' => 'Category deleted successfully'
               ], 200);
        }

        return response()->json([
            'message' => 'deleted failed'
        ], 400);
    }
}

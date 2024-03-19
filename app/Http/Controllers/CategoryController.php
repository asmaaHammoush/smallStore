<?php

namespace App\Http\Controllers;

use App\Http\Requests\categories\UpdateCategoryRequest;
use App\Models\Category;
use App\Http\Requests\categories\StoreCategoryRequest;
use App\Models\Product;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Traits\processImageTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use processImageTrait,HttpResponses;
    public function index()
    {
        abort_if(!$this->authorize('showAll_category'),403,'Unauthorized');
        $categories = Category::categoryWithSub()
            ->whereNull('parent_id')
            ->containLittera()
            ->get();
        return $this->success($categories,'Categories retrieved successfully');
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
        abort_if(!$this->authorize('create_category'),403,'Unauthorized');
        $imageName = $this->uploadPhoto($request, 'categories');
       $category =new Category();

        DB::transaction(function () use ($request, $imageName, &$category) {
        $category = Category::create([
            'name' => $request->name,
            'description'=>$request->description,
            'parent_id' =>$request->parent_id,
        ])->image()->create([
            'photo' => $imageName[0]
        ])->save();
            if (!$category) {
                return $this->responseError('Category failed',404);
            }
        });
        return $this->success($category,'Category created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(!$this->authorize('view_category'),403,'Unauthorized');
//        global scope
        $category = Category::categoryWithSub()
            ->containLittera()
            ->find($id);
        return $this->success($category,'Category retrieved successfully');
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

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        abort_if(!$this->authorize('update_category'),403,'Unauthorized');
        DB::transaction(function () use ($request,$category) {
            $category->fill([$request->all()])->update();
            $current_images = $category->image()->pluck('photo')->toArray();
            $imageName = $this->updatePhoto($request,$current_images,'categories');
            $category->image()->update(['photo' => $imageName[0]]);
        });
       return $this->responseSuccess( 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        abort_if(!$this->authorize('delete_category'),403,'Unauthorized');
        DB::transaction(function () use ($category) {
            if ($category->image()->exists()) {
                $this->deletePhoto($category->image->photo);
                $category->image()->delete();
            }
            $category->delete();
            if ($category) {
                return $this->responseError('deleted failed',400);
            }
        });
        return $this->responseSuccess('Category deleted successfully');
    }
}

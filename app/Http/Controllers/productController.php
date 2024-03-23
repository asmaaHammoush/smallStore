<?php

namespace App\Http\Controllers;

use App\Http\Requests\products\StoreProductRequest;
use App\Http\Requests\products\UpdateProductRequest;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Notifications\ProductNotification;
use App\Traits\HttpResponses;
use App\Traits\processImageTrait;
use App\Traits\Products;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use processImageTrait,HttpResponses,Products;
    public function index()
    {
        throw_if(!$this->authorize('viewAny',Product::class),new AuthorizationException);
        $products = Product::with(['user:id,name', 'images:imageable_id,photo'])->get();
        return $this->success($products,'ok');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        throw_if(!$this->authorize('create',Product::class),new AuthorizationException);
        $product =new Product();
        DB::transaction(function () use ($request, $product) {
           $product->fill($request->all())->save();
           $imageName = $this->uploadPhoto($request,'products');
           foreach ($imageName as $image)
              $product->images()->create(['photo' => $image])->save();
           if (!$product)
                return $this->responseError('Product failed',404);
           $admin =Role::firstWhere('name','Admin');
           $admin= $admin->users()->first();
           $user =$product->user;
           $admin->notify(new ProductNotification($product->name,$user,null,'database'));
        });
        return $this->success($product,
            'The product added successfully, please wait to accept it by admin.');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        throw_if(!$this->authorize('view',$product),new AuthorizationException);
        return $this->success(
            $product->load('user:id,name','images:imageable_id,photo')
            ,'Product retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request,Product $product)
    {
        throw_if(!$this->authorize('update',$product),new AuthorizationException);
        DB::transaction(function () use ($request, $product) {
            $product->fill([$request->all()])->update();
            $oldImages = $product->images()->pluck('id')->toArray();
            $product->images()->delete();
            $imageName = [];
            foreach ($request->file('photo') as $photo) {
                $imageName[] = $this->updatePhotoProduct($photo,$oldImages, 'products');
            }
            foreach ($imageName as $picture) {
                $product->images()->create(['photo' => $picture]);
            }
        });
         return $this->success($product,'Product updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        throw_if(!$this->authorize('delete',$product),new AuthorizationException);
        DB::transaction(function () use ($product) {
            if ($product->images->exists()) {
                foreach ($product->images as $pic) {
                    $this->deletePhoto($pic->photo);
                }
                $product->images()->delete();
            }
            $product->delete();
            if ($product)
                return $this->responseError('deleted failed',404);
        });
        return $this->responseSuccess('Product deleted successfully');
    }

    public function acceptProduct($id){
        return $this->statusProduct($id,'accept');
    }

    public function rejectProduct($id){
        return $this->statusProduct($id,'reject');
    }
}

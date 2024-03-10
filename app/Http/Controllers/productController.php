<?php

namespace App\Http\Controllers;

use App\Http\Requests\products\StoreProductRequest;
use App\Http\Requests\products\UpdateProductRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Notifications\ProductNotification;
use App\Notifications\UserNotification;
use App\Traits\HttpResponses;
use App\Traits\processImageTrait;
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
    use processImageTrait,HttpResponses;
    public function index()
    {
        $products = Product::with(['user:id,name', 'images:imageable_id,photo'])->get();
        if (!$products) {
            return $this->responseError('Products not found',404);
        }
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
        $success = false;
        $product =new Product();

        DB::transaction(function () use ($request, &$success, &$product) {
        $product =Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
            'user_id' => $request->user_id,
            'description' => $request->description,
        ]);
        $imageName = $this->uploadPhoto($request,'products');
        foreach ($imageName as $image)
            $product->images()->create([
                'photo' => $image
            ])->save();
            if (!$product) {
                $success = false;
                return;
            }
            $success = true;
        });
        if (!$product) {
            return $this->responseError('Product failed',404);
        }

        $admin =User::where('role','admin')->first();
        $user =User::find($product->user_id);
        $adminNotification =new ProductNotification($product->name,$user,null,'database');
        $admin->notify($adminNotification);
        return $this->success($product,
            'The product added successfully, please wait to accept it by admin.');

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::with('user:id,name','images:imageable_id,photo')
                    ->find($id);

        if (!$product) {
            return $this->responseError('Product not found',404);
        }
        return $this->success($product,'Product retrieved successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->responseError('Product not found',404);
        }

        DB::transaction(function () use ($request, &$product) {
            $product->update([
                'name' => $request->name,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'category_id' => $request->category_id,
                'description' => $request->description,
                'user_id' => $request->user_id
            ]);

            // حذف الصور القديمة
            $oldImages = $product->images()->pluck('id')->toArray();
            $product->images()->delete();

            $imageName = [];
            foreach ($request->file('photo') as $photo) {
                $imageName[] = $this->updatePhotoProduct($photo,$oldImages, 'products');
            }

            foreach ($imageName as $picture) {
                $product->images()->create([
                    'photo' => $picture
                ]);
            }
        });
        $updatedProduct = Product::with('images:imageable_id,photo')->find($id);
         return $this->success($updatedProduct,'Product updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $success = false;
        if (!$product) {
            return $this->responseError( 'Product not found',404);
        }
        DB::transaction(function () use (&$product,$id,&$success) {
            foreach ($product->images as $pic) {
                $this->deletePhoto($pic->photo);
            }
            $product->images()->delete();
           $product->delete();

            $product = Product::find($id);
            if (!$product) {
                $success = true;
                return;
            }
            $success = false;
        });
        if ($success) {
            return $this->responseSuccess('Product deleted successfully');
        }
        return $this->responseError('deleted failed',404);
    }

    public function acceptProduct($id){
        if (Auth::user()->role == 'admin'){
            $product =Product::find($id);
            $product->status ='accept';
            $product->save();

            $user =User::find($product->user_id);
            $user->notify(new ProductNotification($product->name,null,$product->status,'email'));
            return $this->responseSuccess('you accept this product');
        }
        return $this->responseError('only admin can reject or accept the products',401);
        }



    public function rejectProduct($id){
        if (Auth::user()->role == 'admin'){
            $product =Product::find($id);
            $product->status ='reject';
            $product->save();

            $user =User::find($product->user_id);
            $user->notify(new ProductNotification($product->name,null,$product->status,'email'));
            return $this->responseSuccess('you reject this product');
        }
        return $this->responseError('only admin can reject or accept the products',401);
    }
}

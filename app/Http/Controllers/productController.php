<?php

namespace App\Http\Controllers;

use App\Http\Requests\products\StoreProductRequest;
use App\Http\Requests\products\UpdateProductRequest;
use App\Models\Order;
use App\Models\Product;
use App\Traits\processImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class productController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    use processImageTrait;
    public function index()
    {
        $products = Product::with(['user:id,name', 'images:imageable_id,photo'])->get();

        if (!$products) {
            return response()->json([
                'message' => 'Products not found'
            ], 404);
        }
        return response()->json([
            'message' => 'ok', 'data' => $products
        ], 200);
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
            return response()->json([
                'message' => 'Product failed'
            ], 404);
        }

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ], 201);
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
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Product retrieved successfully',
            'data' => $product
        ], 200);
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
            return response()->json([
                'message' => 'Product not found'
            ], 404);
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
        // استعلام جديد للحصول على المعلومات المحدثة للمنتج
        $updatedProduct = Product::with('images:imageable_id,photo')->find($id);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $updatedProduct
        ], 200);
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
            return response()->json([
                'message' => 'Product not found'
            ], 404);
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
            return response()->json([
                'message' => 'Product deleted successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'deleted failed'
        ], 400);
    }
}

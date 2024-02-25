<?php

namespace App\Http\Controllers;

use App\Http\Requests\products\StoreProductRequest;
use App\Http\Requests\products\UpdateProductRequest;
use App\Models\Order;
use App\Models\Product;
use App\Traits\processImageTrait;
use Illuminate\Http\Request;

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
        $products = Product::get();
        $productsPrice=[];
        foreach ($products as $product)
        {
            $productsPrice[$product->id] = $product
            ->load('user:id,name','images:imageable_id,photo')
             ->priceFilter()->get();

        }
        if (!$products) {
            return response()->json([
                'message' => 'Products not found'
            ], 404);
        }
        return response()->json([
            'message' => 'ok', 'data' => $productsPrice
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
        $request->validate([
            'photo' => '
            min:2
            |array',
        ]);
        $imageName = $this->photo($request,'products');
        $product =Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'category_id' => $request->category_id,
        ]);
        foreach ($imageName as $image)
            $product ->images()->create([
                'photo' => $image
            ])->save();

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
        $product = Product::find($id);
        $product =$product
            ->load('user:id,name','images:imageable_id,photo')
            ->priceFilter()->get();

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
        $imageName = $this->photo($request,'products');
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

      $product->update([
          'name' => $request->name,
          'price' => $request->price,
          'quantity' => $request->quantity,
          'category_id' => $request->category_id,
          'description' => $request->description
      ]);

        foreach ($imageName as $image)
            $product ->images()->update([
                'photo' => $image
            ]);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product
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
        if (!$product) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }

        $product->delete();
        if (!$product) {
            return response()->json([
                'message' => 'Product deleted successfully'
            ], 200);
        }

        return response()->json([
            'message' => 'deleted failed'
        ], 400);
    }
}

<?php

namespace App\Traits;

use App\Models\Product;
use App\Notifications\ProductNotification;

trait Products{
    use HttpResponses;
    public function statusProduct($id,$status){
        $product =Product::findOrFail($id);
        $product->forceFill([
            'status' => $status
        ])->update();
        $user =$product->user;
        $user->notify(new ProductNotification($product->name,null,$status,'email'));
        return $this->responseSuccess($status.'ed this product');
    }

}

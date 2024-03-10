<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Console\Command;

class DeleteProductsRejected extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reject_product:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete for product that reject by admin every day ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = Product::where('status','reject')->get();
        foreach ($products as $product) {
            $product->delete();
            $user = User::find($product->user_id);
            $user->notify(new UserNotification($product->name));
        }
    }
}

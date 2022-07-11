<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WebhookProductCreate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $data;
    private $shop_name;

    public function __construct($data,$shop_name)
    {
        //

        $this->data = $data;
        $this->shop_name = $shop_name;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = $this->data;
        $shop = Shop::where('domain',$this->shop_name)->first();
        $data = [
            'name'          => $product['title'],
            'description'   => $product['body_html'],
            'price'         => $product['variants']['0']['price'],
            'quantity'      => $product['variants']['0']['inventory_quantity'],
            'status'        => $product['status'],
            'shop_id'       => $shop->id
        ];
        if(isset($product->image) && !empty($product->image)) // Nếu sản phẩm có hình thì thêm hình
        {
            $data['image'] = $product->image->src;
        }
        Product::updateOrCreate(['id' => $product['id']],$data); // Update or create
        dump($data);
        Log::info('Created product from webhook');
    }
}

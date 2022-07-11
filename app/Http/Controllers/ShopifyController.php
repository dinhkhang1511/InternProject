<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ShopifyController extends Controller
{
    //
    private  $version;
    private  $ngrok_url;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        $this->version = config('shopify.shopify_api_version');
        $this->ngrok_url = config('shopify.ngrok_url');
    }

    public function getShopInput()
    {
        return view('shopify');
    }

    public function logout()
    {
        session()->flush();
        return redirect()->route('shopify');
    }

    public function postShopInput(Request $request)
    {
        $request->validate([
            'shopName' => 'required|min:4'
        ],[
            'shopName.required' => 'Vui lòng nhập tên shop',
            'shopName.min'      => 'Tên shop phải lớn hơn 4 ký tự'
        ]);
        $apikey = env('API_SHOPIFY_KEY');
        $shopName = $request->shopName;

        $this->verifiedToken($shopName);

        $scopes = 'write_orders,read_customers,read_products,write_products,read_orders,write_orders';
        $url = route('authenticate');
        return redirect("https://$shopName.myshopify.com/admin/oauth/authorize?client_id=$apikey&scope=$scopes&redirect_uri=$url");
    }

    public function installApp(Request $request)
    {
        $shop = $request->shop;
        $hmac = $request->hmac;

        $name = explode($shop,'.')[0];
        $this->verifiedToken($shop);

        $apikey = env('API_SHOPIFY_KEY');
        $url = route('authenticate');
        $scopes = 'write_orders,read_customers,read_products,write_products,read_orders,write_orders';
        return redirect("https://$shop/admin/oauth/authorize?client_id=$apikey&scope=$scopes&redirect_uri=$url");
    }

    public function authenticate(Request $request)
    {
        $shopify_domain = $request->shop;
        $authorCode = $request->code;
        $clientKey = env('API_SHOPIFY_KEY');
        $clientSecret = env('API_SECRET_SHOPIFY_KEY');
        $access_token = $this->getAccessToken($shopify_domain,$clientKey,$clientSecret,$authorCode);
        $payload = $this->getShopDetail($shopify_domain,$access_token);
        $shop = Shop::where('name',$payload->name)->first();
        if(!$shop)
        {
            $topics = ['products/create','products/update','products/delete'];
            $this->createListProducts($shopify_domain,$access_token,$payload->id);
            $this->registerWebhook($payload->name,$access_token,$topics);
            $shop = new \App\Models\Shop();
        }
        $shop->id             = $payload->id;
        $shop->domain         = $payload->domain;
        $shop->shopify_domain = $shopify_domain;
        $shop->name           = $payload->name; // vd: shop.myshopify.com
        $shop->email          = $payload->customer_email;
        $shop->access_token   = $access_token;
        $shop->plan           = $payload->plan_display_name;
        $shop->created_at     = $payload->created_at;
        $shop->save();
        // todo: lưu shop vào session -> redirect quản lý product -> middleware check session shop
        session()->put('shop',$shop); // Lưu access token vào session;
        return redirect()->route('shopifyProduct.index');

    }



    public function getAccessToken($shopifyDomain,$clientKey,$clientSecret,$code) : String //access token
    {
    $response = Http::post("https://$shopifyDomain/admin/oauth/access_token", [
        'code' =>  $code,
        'client_id' => $clientKey,
        'client_secret' => $clientSecret
    ]);

    if($response->successful())
        return json_decode($response->getBody())->access_token;
    else
        $response->throw();

    }

    public function getShopDetail($shopifyDomain,$access_token)  // Shop
    {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $access_token
        ],)->get("https://$shopifyDomain/admin/api/$this->version/shop.json", [
            'fields' =>  'id,name,customer_email,domain,plan_display_name,created_at']);
        if($response->successful())
            return json_decode($response->getBody())->shop;
        else
            $response->throw();

    }

    /**
     * createListProducts - when a new shop install app
     *
     * @param  mixed $shopifyDomain
     * @param  mixed $access_token
     * @return void
     */
    public function createListProducts($shopifyDomain,$access_token,$shopId)
    {
        $fields = 'id,title,body_html,description,status,variants,image';

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $access_token
        ],)->get("https://$shopifyDomain/admin/api/$this->version/products.json", [
            'fields' =>  $fields]);
        if($response->successful())
        {
            $products = json_decode($response->getBody()->getContents())->products;
            foreach($products as $product)
            {
                $data = [
                    'name'          => $product->title,
                    'description'   => $product->body_html,
                    'price'         => $product->variants[0]->price,
                    'quantity'      => $product->variants[0]->inventory_quantity,
                    'status'        => $product->status,
                    'shop_id'       => $shopId
                ];
                if(isset($product->image) && !empty($product->image)) // Nếu sản phẩm có hình thì thêm hình
                {
                    $data['image'] = $product->image->src;
                }
                    Product::updateOrCreate(['id' => $product->id,],$data);
            }
            return redirect()->route('shopifyProduct.index');
        }
        else
        {
            $response->throw();
        }
    }

    public function callGraphQL() // Test graphQL
    {
        $shop = session('shop');
        $shopify_domain = $shop->shopify_domain;
        $id = Product::where('shop_id',$shop->id)->first()->id;
        $query = <<<QUERY
        {
            product(id: "gid://shopify/Product/$id")
            {
              title
              description
              onlineStoreUrl
            }
        }
      QUERY;
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-Shopify-Access-Token' => $shop->access_token
        ],)->post("https://$shopify_domain/admin/api/$this->version/graphql.json", [
            'query' =>  $query]);
        if($response->successful())
        {
            $payload = json_decode($response->getBody());
            dd($payload);
        }
        else
        {
            $payload = json_decode($response->getBody());
            dd($payload);
        }
    }

    public function verifiedToken($shopName)
    {
        $token = session('access_token');
        if(!empty($token))
        {
            $count = Shop::where('access_token',$token)->where('name',$shopName)->count();
            if($count != 0)
            {
                return redirect()->route('shopifyProduct.index');
            }
        }
    }

    public function registerWebhook($shopName,$access_token,$topics)
    {
        $url = "https://$shopName.myshopify.com/admin/api/$this->version/webhooks.json";
        $ngrok_url = $this->ngrok_url; // ! Thay đổi mỗi lần start nrgok trong file config shopify.php
        foreach($topics as $topic)
        {
            $headers=[
                        "X-Shopify-Access-Token"    => $access_token,
                        "Content-Type"  => "application/json"
            ];
            $body =
            [
                "webhook" => [
                    "topic" => $topic,
                    "address" => "$ngrok_url/shopify/webhook/$topic",
                    "format"    => "json",
                ]
            ];
            try //dùng để test install app lại mà ko cần phải xóa webhook cũ nếu bỏ cmt chạy sẽ ko bị lỗi topic already taken
            {
                $response = Http::withHeaders($headers)->post($url,$body);
                if($response->successful())
                {
                    Log::info('Create Webhook: ');
                    Log::info($response->json());
                }else
                    $response->throw();
            }catch(Exception $ex){}
        }
    }

    public function destroy()
    {
        $shop = Shop::where('name','luongdinhkhang')->first();
        $this->destroyAllWebhook($shop->name,$shop->access_token);
    }

    public function destroyAllWebhook($shopName,$access_token) // test only
    {
        $url = "https://$shopName.myshopify.com/admin/api/$this->version/webhooks.json";
        $headers=[
            "X-Shopify-Access-Token"    => $access_token,
            "Content-Type"              => "application/json"
        ];
        $response = Http::withHeaders($headers)->get($url);
        if($response->successful())
        {
            $webhooks = json_decode($response->getBody()->getContents())->webhooks;
            foreach($webhooks as $webhook)
            {
            $headers=[
                        "X-Shopify-Access-Token"    => $access_token,
                        "Content-Type"  => "application/json"
            ];
            // try //dùng để test install app lại mà ko cần phải xóa webhook cũ nếu bỏ cmt chạy sẽ ko bị lỗi topic already taken

            // {https://luongdinhkhang.myshopify.com/admin/api/2022-04/webhooks/1149185163477.json
                $url = "https://$shopName.myshopify.com/admin/api/$this->version/webhooks/$webhook->id.json";
                $response = Http::withHeaders($headers)->delete($url);
                if($response->successful())
                {
                    Log::info('Deleted Webhook: ');
                    dump($webhook->id);
                }else
                    $response->throw();
            // }
            // catch(Exception $ex){}
            }
        }else
            $response->throw();



    }
}


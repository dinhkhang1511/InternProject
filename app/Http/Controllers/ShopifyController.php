<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShopifyController extends Controller
{
    //

    public function getShopInput()
    {
        return view('shopify');
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
        $scopes = 'write_orders,read_customers';
        $url = route('url');
        return redirect("https://$shopName.myshopify.com/admin/oauth/authorize?client_id=$apikey&scope=$scopes&redirect_uri=$url");
    }

    public function installApp(Request $request)
    {
        $shop = $request->shop;
        $hmac = $request->hmac;
        $apikey = env('API_SHOPIFY_KEY');
        $url = route('url');
        $scopes = 'write_orders,read_customers,';
        return redirect("https://$shop/admin/oauth/authorize?client_id=$apikey&scope=$scopes&redirect_uri=$url");
    }

    public function generateCode(Request $request)
    {
        $shopify_domain = $request->shop;
        $authorCode = $request->code;
        $clientKey = env('API_SHOPIFY_KEY');
        $clientSecret = env('API_SECRET_SHOPIFY_KEY');

        $response = Http::post("https://$shopify_domain/admin/oauth/access_token", [
            'code' =>  $authorCode,
            'client_id' => $clientKey,
            'client_secret' => $clientSecret
        ]);

        $access_token = json_decode($response->getBody())->access_token;
      if($response->successful())
      {
        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $access_token
        ],)->get("https://$shopify_domain/admin/api/2022-04/shop.json", [
            'fields' =>  'name,customer_email,domain']);
        if($response->successful())
        {
            $payload = json_decode($response->getBody())->shop;
            $shop = Shop::where('name',$payload->name)->first();
            if(!$shop)
            {
                $shop = new \App\Models\Shop();
            }
            $shop->domain = $payload->domain;
            $shop->shopify_domain = $shopify_domain;
            $shop->name = $payload->name; // vd: shop.myshopify.com
            $shop->email = $payload->customer_email;
            $shop->access_token = $access_token;
            $shop->plan = 'free';
            $shop->save();
            dd('Lưu thành công');
        }
        else
        {
            $response->throw();
        }
      }
      else
      {
        $response->throw();
      }
    }


    public function shopDetail()
    {
//         https://your-development-store.myshopify.com/admin/api/2022-04/shop.json?fields=address1%2Caddress2%2Ccity%2Cprovince%2Ccountry" \
// // -H "X-Shopify-Access-Token: {access_token}

//     $response = Http::post("https://$shop/admin/oauth/access_token", [


    }
}

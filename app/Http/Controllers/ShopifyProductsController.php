<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShopifyProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $id = $request->get('id');
        $shop = Shop::where('id',$id)->first();
        if($shop)
        {
            $products = Product::where('shop_id',$shop->id)->orderBy('updated_at','desc')->simplePaginate(5);
            return view('products')->with('products',$products);
        }
        else
            return redirect()->route('shopify');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('product');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate
        $shop = session('shop');
        $product = $this->createOrUpdateProduct($request,'create',$shop);
        if($product)
        {
            Product::updateOrCreate(['id' => $product->id],
            [
                'name'       =>  $product->title,
                'price'      =>  $product->variants[0]->price,
                'quantity'   =>  $product->variants[0]->inventory_quantity,
                'image'      =>  $product->images ? $product->images[0]->src : '',
                'status'     =>  $product->status,
                'description'=>  $product->body_html,
                'shop_id'    =>  session('shop')->id
            ]);
            return redirect()->route('shopifyProduct.index')->with('message','Lưu thành công');
        }
            return redirect(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shopId = session('shop')->id;
        $product = Product::where('id',$id)->where('shop_id',$shopId)->first();
        return view('product')->with('product',$product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $shop = session('shop');
        $product = $this->createOrUpdateProduct($request,'update',$shop,$id);

        if($product)
        {
            Product::updateOrCreate(['id' => $product->id],
            [
                'name'       =>  $product->title,
                'price'      =>  $product->variants[0]->price,
                'quantity'   =>  $product->variants[0]->inventory_quantity,
                'image'      =>  $product->images ? $product->images[0]->src : '',
                'status'     =>  $product->status,
                'description'=>  $product->body_html,
                'shop_id'    =>  session('shop')->id
            ]);
            return redirect()->route('shopifyProduct.index')->with('message','Lưu thành công');
        }
        else
        {
            return redirect(404);
        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $shop = session('shop');
        Product::destroy($id);
        $headers = [
            'X-Shopify-Access-Token' => session('shop')->access_token,
            'Content-Type' => 'application/json',
        ];
        $url = "https://$shop->shopify_domain/admin/api/2022-07/products/$id.json";
        $response = Http::withHeaders($headers)->delete($url);
        if($response->successful())
            return redirect()->back()->with('message','Xóa thành công');
        else
            $response->throw();

    }

    private function createOrUpdateProduct(Request $request,$action,$shop,$id = null)
    {
        $request->validate([
            'name'     => 'required',
            'price'    => 'numeric|min:0',
            'quantity' => 'numeric|min:0',
            'image'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'

        ],[
            'name.required'      => 'Vui lòng nhập tên sản phẩm',
            'price.numeric'      => 'Giá sản phẩm chỉ được nhập số',
            'price.min'          => 'Giá sản phẩm nhỏ nhất là 1',
            'quantity.numeric'   => 'Số lượng sản phẩm chỉ được nhập số',
            'quantity.min'       => 'Số lượng sản phẩm nhỏ nhất là 1',
            'image'              => 'Ảnh không hợp lệ',
        ]);

        $headers = [
            'X-Shopify-Access-Token' => $shop->access_token,
            'Content-Type' => 'application/json',
        ];
        $body =
            [
                "product" => [
                    "title" => $request->name,
                    // "body_html" => $request->description,
                    // "vendor"    => $shop->name,
                    // "variants"  => [['price'              => $request->price,
                    //                  'inventory_quantity' => $request->quantity]]
                ]
        ];
        if($request->hasFile('image'))
        {
            $encode_image = base64_encode(file_get_contents($request->file('image')));
            $body['image'] = [[
                            'filename' => $request->file('image')->getClientOriginalName(),
                            'attachment' => $encode_image]];
        }

        if($action === 'create')
        {
            $url = "https://$shop->shopify_domain/admin/api/2022-07/products.json";
            $response = Http::withHeaders($headers)->post($url,$body);
        }
        else if($action === 'update')
        {
            $url = "https://$shop->shopify_domain/admin/api/2022-07/products/$id.json";
            $body['product']['id'] = $id;
            $response = Http::withHeaders($headers)->put($url,$body);
        }
        else
            return null;

        if($response->successful())
        {
            $product = json_decode($response->getBody()->getContents())->product;
            return $product;
        }
        else
        {
            $response->throw();
        }
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products_ = DB::table('products')->simplePaginate(3);
        return view('products')->with('products',$products_);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        //
        $request->validate([
            'name'     => 'unique:products,name|required',
            'price'    => 'numeric|required|min:1',
            'quantity' => 'numeric|required|min:1'

        ],[
            'name.required'      => 'Vui lòng nhập tên sản phẩm',
            'name.unique'        => 'Tên sản phẩm đã tồn tại',
            'price.required'     => 'Vui lòng nhập giá sản phẩm',
            'price.numeric'      => 'Giá sản phẩm chỉ được nhập số',
            'price.min'          => 'Giá sản phẩm nhỏ nhất là 1',
            'quantity.numeric'   => 'Số lượng sản phẩm chỉ được nhập số',
            'quantity.min'       => 'Số lượng sản phẩm nhỏ nhất là 1',
            'quantity.required'  => 'Vui lòng nhập số lượng sản phẩm',
        ]);

        DB::table('products')->insert([
            'name' =>  $request->name,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'status' => 'pending'
        ]);

        return redirect()->route('product.index')->with('message','Thêm thành công');
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
        //
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
        //
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
        DB::table('products')->where('id',$id)->delete();
        return redirect()->back()->with('message','Xóa thành công');

    }

    public function showByStatus($status)
    {
        $array = ['approve','reject','pending'];

        if(in_array($status, $array))
        {
            $products_ = DB::table('products')->where('status', $status)->simplePaginate(3);
            return view('products')->with('products',$products_);
        }
        else
        {
            return redirect('products');
        }
    }
}

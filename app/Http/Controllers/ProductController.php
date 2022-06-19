<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $products = DB::table('products')->orderBy('id', 'desc')->simplePaginate(3);

        $approve = DB::table('products')->where('status','approve')->count('*');
        $reject = DB::table('products')->where('status','reject')->count('*');
        $pending = DB::table('products')->where('status','pending')->count('*');

        return view('products')->with(['products' => $products,
                                       'approve' => $approve,
                                       'reject' => $reject,
                                       'pending' => $pending]);
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
            'price'    => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'image'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'

        ],[
            'name.required'      => 'Vui lòng nhập tên sản phẩm',
            'name.unique'        => 'Tên sản phẩm đã tồn tại',
            'price.required'     => 'Vui lòng nhập giá sản phẩm',
            'price.numeric'      => 'Giá sản phẩm chỉ được nhập số',
            'price.min'          => 'Giá sản phẩm nhỏ nhất là 1',
            'quantity.numeric'   => 'Số lượng sản phẩm chỉ được nhập số',
            'quantity.min'       => 'Số lượng sản phẩm nhỏ nhất là 1',
            'quantity.required'  => 'Vui lòng nhập số lượng sản phẩm',
            'image'              => 'Ảnh không hợp lệ'
        ]);
        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            try
            {
                $imageName=time().'.'.$request->image->extension();
                $request->image->move(public_path('/dist/img/product_img'),$imageName);

                DB::table('products')->insert([
                                    'name'       =>  $request->name,
                                    'price'      =>  $request->price,
                                    'quantity'   =>  $request->quantity,
                                    'image'      =>  $imageName,
                                    'status'     => 'pending'
                ]);
                return redirect()->route('product.index')->with('message', 'Thêm thành công');
            }
            catch(Exception $ex)
            {
                die($ex->getMessage());
                throw new $ex;
            }
        }
        return back()->with('message', 'Ảnh không hợp lệ');
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
        if($id === 'approval')
        {
            $products =  DB::table('products')->where('status','pending')->orderBy('id','desc')->simplePaginate(3);
            return view('approve')->with('products', $products);
        }
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

        $product = DB::table('products')->find($id);
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
        //
        $request->validate([
            'name'     => 'required|unique:products,name,' .$id,
            'price'    => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'image'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'


        ],[
            'name.required'      => 'Vui lòng nhập tên sản phẩm',
            'name.unique'        => 'Tên sản phẩm đã tồn tại',
            'price.required'     => 'Vui lòng nhập giá sản phẩm',
            'price.numeric'      => 'Giá sản phẩm chỉ được nhập số',
            'price.min'          => 'Giá sản phẩm nhỏ nhất là 1',
            'quantity.numeric'   => 'Số lượng sản phẩm chỉ được nhập số',
            'quantity.min'       => 'Số lượng sản phẩm nhỏ nhất là 1',
            'quantity.required'  => 'Vui lòng nhập số lượng sản phẩm',
            'image'              => 'Ảnh không hợp lệ'

        ]);

        if($request->hasFile('image') && $request->file('image')->isValid())
        {
            try
            {
                $imageName=time().'.'.$request->image->extension();
                $request->image->move(public_path('dist/img/product_img'),$imageName);
                $affected = DB::table('products')
                            ->where('id', $id)
                            ->update([
                            'name'      =>  $request->name,
                            'price'     => $request->price,
                            'quantity'  => $request->quantity,
                            'image'     => $imageName
                            ]);

                return redirect()->route('product.index')->with('message', 'Sửa thành công');
            }
            catch(Exception $ex)
            {
                die($ex->getMessage());
                throw new $ex;
            }
        }
        return back()->with('message', 'Ảnh không hợp lệ');
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
            $approve = DB::table('products')->where('status','approve')->count('*');
            $reject = DB::table('products')->where('status','reject')->count('*');
            $pending = DB::table('products')->where('status','pending')->count('*');

            $products = DB::table('products')->where('status', $status)->orderBy('id','desc')->simplePaginate(3);
            return view('products')->with(['products' => $products,
                                           'approve' => $approve,
                                           'reject' => $reject,
                                           'pending' => $pending]);
        }
        else
        {
            return redirect()->route('product.index');
        }
    }

    public function approveProduct(Request $request)
    {
        if($request->action === 'approve')
            $action = 'approve';
        else if ($request->action === 'reject')
            $action = 'reject';

        if(is_numeric($request->id))
        {
            if(DB::table('products')->find($request->id))
            {
                DB::table('products')
                    ->where('id', $request->id)
                    ->update([
                    'status' =>  $action]);
            }
            return back()->with('message', 'Duyệt thành công');
        }
            return back();
    }

    public function searCustomer(Request $request)
    {
        $result=DB::table('products')->where('name', 'like', "%{$request->search}%")->orderBy('id','desc')->simplePaginate(3);
        return view('products')->with('products', $result);
    }




}

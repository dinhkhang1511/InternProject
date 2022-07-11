@include('partials.header')
@php
$shop = session('shop');
@endphp
  <div class="row">
      <div class="col-lg-8">
          <div class="card card-default">
                <div class="card-header card-header-border-bottom">
                    <h2>{{isset($product) ? 'Sửa sản phẩm' : 'Thêm sản phẩm'}}</h2>
                </div>

                <div class="card-body">
                    @if(Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{Session::get('success')}}
                        </div>
                    @endif
                    @if(Session::has('error'))
                        <div class="alert alert-error" role="alert">
                            {{Session::get('error')}}
                        </div>
                    @endif
                    <form method="POST" enctype="multipart/form-data" action="<?= isset($product) ? route('shopifyProduct.update',['shopifyProduct' => $product->id]) : route('shopifyProduct.store') ?>" >
                        @csrf
                        @if(isset($product))
                            @method('PUT')
                        @endif
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <p class="alert alert-danger">{{$error}}</p>
                            @endforeach
                        @endif
                        <input type="text" class="form-control d-none" id="shop_id" name='shop_id' value={{$shop->id}}>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="name" placeholder="Tên sản phẩm" name='name' required
                            value="<?= isset($product->name) ? $product->name : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Mô tả</label>
                            <textarea type="text" class="form-control" id="description" placeholder="Tên sản phẩm" name="description" required>
                                <?= isset($product->description) ? $product->description : '' ?>
                            </textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlPassword">Giá </label>
                            <input type="number" class="form-control" id="price" placeholder="Giá" name="price"
                            value="<?= isset($product->price) ? $product->price : '0' ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlPassword">Số lượng </label>
                            <input type="number" class="form-control" id="quantity" placeholder="Số lượng" name="quantity"
                            value="<?= isset($product->quantity) ? $product->quantity : '0' ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlPassword">Ảnh </label>
                            <input type="file" class="form-control" id="image" onchange="readURL(this)" name="image">
                            @if(isset($product->image))
                            <img id="image-preview" class="image" src="{{$product->image}}" style="width: 150px; height: 150px">
                            @else
                            <img id="image-preview" class="image">
                            @endif
                        </div>

                        <div class="form-footer pt-4 pt-5 mt-4 border-top">
                            <button type="submit" class="btn btn-primary btn-default">Lưu</button>
                            <a type="button" href="{{route('shopifyProduct.index')}}" class="btn btn-secondary btn-default">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4 ">

        </div>
    </div>
    @include('partials.footer')


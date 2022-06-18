@include('partials.header')
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
                    <form method="POST" enctype="multipart/form-data" action="<?= isset($product) ? route('product.update',['product' => $product->id]) : route('product.store') ?>" >
                        @csrf
                        @if(isset($product))
                            @method('PUT');
                        @endif
                        @if($errors->any())
                            @foreach($errors->all() as $error)
                                <p class="alert alert-danger">{{$error}}</p>
                            @endforeach
                        @endif
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Tên sản phẩm</label>
                            <input type="text" class="form-control" id="name" placeholder="Tên sản phẩm" name='name' required
                            value="<?= isset($product) ? $product->name : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="exampleFormControlPassword">Giá </label>
                            <input type="number" class="form-control" id="price" placeholder="Giá" name="price" required
                            value="<?= isset($product) ? $product->price : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlPassword">Số lượng </label>
                            <input type="number" class="form-control" id="quantity" placeholder="Số lượng" name="quantity" required
                            value="<?= isset($product) ? $product->quantity : '' ?>">
                        </div>

                        <div class="form-footer pt-4 pt-5 mt-4 border-top">
                            <button type="submit" class="btn btn-primary btn-default">Submit</button>
                            <a type="button" href="{{route('product.index')}}" class="btn btn-secondary btn-default">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4 ">

        </div>
    </div>
    @include('partials.footer')


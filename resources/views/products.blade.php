@include('partials.header')

  <div class="row">
    <div class="col-12">
      <!-- Recent Order Table -->
      <div class="card card-table-border-none" id="recent-orders">
        <div class="card-header justify-content-between">
          <h2>Sản phẩm</h2>

          <div class="button-group">
              <a href="{{route('status',['status' => 'pending'])}}" class="text-white float-right btn btn-warning m-2">Pending ({{isset($pending)? $pending:''}})</a>
              <a href="{{route('status',['status' => 'reject'])}}" class="text-white float-right btn btn-danger m-2">Reject ({{isset($reject)? $reject : ''}})</a>
              <a href="{{route('status',['status' => 'approve'])}}" class="text-white float-right btn btn-success m-2">Approve ({{isset($approve)? $approve : ''}})</a>

        </div>
        </div>
        <div class="card-body pt-0 pb-5">
            @if(Session::has('message'))
                <div class="alert alert-success" role="alert">
                    {{Session::get('message')}}
                </div>
            @endif
            @if($products->count() == 0)
                <h4>Không có sản phẩm để hiển thị</h4>
            @else
            <table class="table card-table table-responsive table-responsive-large" style="width:100%">
                <thead>
                <tr>
                    <th>Sản phẩm </th>
                    <th>Tên sản phẩm</th>
                    <th class="d-none d-md-table-cell">Số lượng</th>
                    <th class="d-none d-md-table-cell">Giá</th>
                    <th class="d-none d-md-table-cell">Tình trạng</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                    @foreach($products as $product)
                    <tr>
                        <td class="ml-5 d-none d-md-table-cell">
                            <image style="height:100px; width:100px;" src="{{$product->image ? asset('dist/img/product_img/'). '/' . $product->image : asset('dist/img/product_img/no-image-found.jpg')}}"/>
                        </td>
                        <td >
                        <a class="text-dark" href=""> {{ $product->name }}</a>
                        </td>
                        <td class="d-none d-md-table-cell">{{$product->quantity}}</td>
                        <td class="d-none d-md-table-cell">{{$product->price }} </td>

                        @if($product->status === 'approve')
                            <td>
                                <span class="badge badge-success">{{$product->status}}</span>
                            </td>
                        @endif
                        @if($product->status === 'pending')
                            <td>
                                <span class="badge badge-warning">{{$product->status}}</span>
                            </td>
                        @endif
                        @if($product->status === 'reject')
                            <td>
                                <span class="badge badge-danger">{{$product->status}}</span>
                            </td>
                        @endif
                        <td class="text-right">
                        <div class="dropdown show d-inline-block widget-dropdown">
                            <a class="dropdown-toggle icon-burger-mini" href="#" role="button" id="dropdown-recent-order2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static"></a>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-recent-order2">
                            <li class="dropdown-item">
                                    <a href="{{route('product.index')}}" class="text-dark">Chi tiết</a>
                            </li>
                            <li class="dropdown-item">
                                <a href="{{route('product.edit',['product' => $product->id])}}" class="text-dark">Sửa</a>
                            </li>
                            <li class="dropdown-item">
                                <form method="POST" action="{{route('product.destroy',['product' => $product->id])}}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Bạn có chắc bạn muốn xóa sản phẩm này?');" class="text-dark">Xóa</button>
                                </form>
                            </li>
                            </ul>
                        </div>
                        </td>
                    </tr>
                     @endforeach
            @endif
            </tbody>
          </table>
          {{!empty($products->links()) ? $products->links() : ''}}
        </div>
      </div>
</div>
  </div>

  @include('partials.footer')


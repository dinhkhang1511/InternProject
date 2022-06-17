@include('partials.header')

  <div class="row">
    <div class="col-12">
      <!-- Recent Order Table -->
      <div class="card card-table-border-none" id="recent-orders">
        <div class="card-header justify-content-between">
          <h2>Sản phẩm</h2>

          <div class="button-group">
              <a href="{{route('status',['status' => 'pending'])}}" class="text-white float-right btn btn-warning m-2">Pending</a>
              <a href="{{route('status',['status' => 'reject'])}}" class="text-white float-right btn btn-danger m-2">Reject</a>
              <a href="{{route('status',['status' => 'approve'])}}" class="text-white float-right btn btn-success m-2">Approve</a>

        </div>
        </div>
        <div class="card-body pt-0 pb-5">
            @if(Session::has('message'))
                <div class="alert alert-success" role="alert">
                {{Session::get('message')}}
                </div>
            @endif
          <table class="table card-table table-responsive table-responsive-large" style="width:100%">
            <thead>
              <tr>
                <th>ID </th>
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
                <td >{{ $product->id }}</td>
                <td >
                  <a class="text-dark" href=""> {{ $product->name }}</a>
                </td>
                <td class="d-none d-md-table-cell">{{$product->quantity}}</td>
                <td class="d-none d-md-table-cell">{{$product->price }} </td>
                {{-- <td class="d-none d-md-table-cell">$550</td> --}}
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

                            <button class="text-dark">View</button>


                      </li>
                      <li class="dropdown-item">
                        <form method="POST" action="{{route('product.destroy',['product' => $product->id])}}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-dark">Remove</button>
                        </form>
                      </li>
                    </ul>
                  </div>
                </td>
              </tr>

              @endforeach
            </tbody>
          </table>
          {{$products->links()}}
        </div>
      </div>
</div>
  </div>

  @include('partials.footer')


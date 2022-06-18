@include('partials.header')

  <div class="row">
    <div class="col-12">
      <!-- Recent Order Table -->
      <div class="card card-table-border-none" id="recent-orders">
        <div class="card-header justify-content-between">
          <h2>Sản phẩm chờ duyệt</h2>
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
                <th class="d-none d-md-table-cell">Hành động</th>
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
                <td>
                    <span class="badge badge-warning">{{$product->status}}</span>
                </td>
                <td>
                    <form method="POST" action="{{route('approve')}}">
                        @csrf
                        <input type="hidden" value="{{$product->id}}" name="id">
                        <input type="hidden" value="approve" name="action">
                        <button type="submit" class="m-2 btn btn-success">Duyệt</button>
                    </form>
                    <form method="POST" action="{{route('approve')}}">
                        @csrf
                        <input type="hidden" value="{{$product->id}}" name="id">
                        <input type="hidden" value="reject" name="action">
                        <button type="submit" class="m-2 btn btn-danger">Từ chối</button>
                    </form>
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


@php
    $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
@endphp
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>Shopify App Name</title>

  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet"/>
  <link href="https://cdn.materialdesignicons.com/3.0.39/css/materialdesignicons.min.css" rel="stylesheet" />



  <!-- SLEEK CSS -->
  <link id="sleek-css" rel="stylesheet" href="{{asset('dist/css/sleek.css')}}">


  <!-- FAVICON -->
  <link href="{{asset('dist/img/favicon.png')}}" rel="icon">

  <!--
    HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
  -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
 <body class="header-fixed sidebar-fixed sidebar-dark header-light" id="body">
  <div class="row">
    <div class="col-12">
      <!-- Recent Order Table -->
        <div class="card card-default">
            <div class="card-header card-header-border-bottom">
                <h2>Basic Shop App</h2>
            </div>
            <div class="card-body">
                <form action="{{route('shopify')}}" method="POST" >
                    @csrf
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Tên shop</label>
                        <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Tên shop" name="shopName" required>
                        <span class="mt-2 d-block">We'll never share your shop with anyone else.</span>
                    </div>
                        <button type="submit" class="btn btn-primary btn-default" >Submit</button>
                        {{-- <button type="submit" class="btn btn-secondary btn-default">Cancel</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>


</div>
</div>

<script src="{{asset('dist/js/jquery.min.js')}}"></script>
<script src="{{asset('dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dist/js/sleek.js')}}"></script>
<script src="{{asset('dist/js/custom.js')}}"></script>


  </body>
</html>



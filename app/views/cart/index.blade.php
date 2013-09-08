@extends('layouts.master')

@section('head')
@parent
  {{ HTML::style('css/modal_img.css') }}
@stop

@section('content')

  <!-- NAVBAR
================================================== -->

        <nav class="navbar navbar-inverse navbar-fixed-top"  role="navigation"> 
          <div class="container">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
            <a class="navbar-brand" href="{{url()}}">Online Store</a>
            <div class="nav-collapse collapse">
              <ul class="nav navbar-nav">
                <li><a href="{{url()}}"><i class="icon-home icon-white"></i>&nbsp;&nbsp;Home</a></li>
                <li><a href="{{url('movies')}}"><i class="icon-film icon-white"></i>&nbsp;&nbsp;Movies</a></li>
                <li><a href="{{url('ebooks')}}"><i class="icon-book icon-white"></i>&nbsp;&nbsp;Ebooks</a></li>
                <li><a href="#contact"><i class="icon-envelope icon-white"></i>&nbsp;&nbsp;Contact</a></li>
                {{--If cart is empty we don't activate the link--}}
                @if (!$cart_products)
                  <li class="active"><a href="#"><i class="icon-shopping-cart"></i>&nbsp;&nbsp;Cart (you have 0 items)</a></li>
                @else   
                    <li class="active"><a data-toggle="modal" href="#Cart_Modal"><i class="icon-shopping-cart"></i>&nbsp;&nbsp;
                    @if ($cart_items_count == 1)
                       Cart (you have {{$cart_items_count}} item)</a></li>
                    @else
                       Cart (you have {{$cart_items_count}} items)</a></li>
                    @endif
                @endif    
                </ul>

                <ul class="nav navbar-nav pull-right"> 
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i>&nbsp;&nbsp;Your Account  <b class="caret"></b></a>
                  <ul class="dropdown-menu">
                   @if ( !Auth::check() )
                    <li><a href="{{url('login')}}"><i class="icon-signin"></i>&nbsp;&nbsp;<strong>Login</strong></a></li>
                   @else
                    <li><a href="{{url('logout')}}"><i class="icon-off"></i>&nbsp;&nbsp;<strong>Logout</strong></a></li>
                   @endif 
                    <li><a href="{{url('account')}}"><i class="icon-cog"></i>&nbsp;&nbsp;<strong>Profile</strong></a></li>
                    <li><a href="{{url('cart-index')}}"><i class="icon-shopping-cart"></i>&nbsp;&nbsp;<strong>Cart</strong></a></li>
                  </ul>
                </li> 
                    @if (Auth::check())
                      <p class="navbar-text pull-right">
                      <a href="{{url('logout')}}"><i class="icon-off"></i>&nbsp;Logout</a>
                      ( Signed in as {{Auth::user()->firstname}} ) 
                     </p>
                    @else
                      <p class="navbar-text pull-right">
                      <a href="{{url('login')}}"><i class="icon-signin"></i>&nbsp;Login</a>
                      </p>
                    @endif       
                </ul>
                
              </ul>  
              
            </div>
          </div>
        </nav>

      
    <!-- Cart Modal -->
                    <div class="modal fade" id="Cart_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="icon-shopping-cart"></i>&nbsp;&nbsp;My Cart</h4>
                          </div>
                          <div class="modal-body">
                          <ul class="list-unstyled">
                          @foreach ($cart_products as $cart_product)
                          <li>
                           @if ($cart_product->product_type == 2)
                             {{'<i class="icon-film"></i>&nbsp;&nbsp<strong>'.$cart_product->product_name.'</strong>&nbsp;&nbsp;<small><em class="muted">x '.$cart_product->quantity.'</em></small>'}}
                           @else
                             {{'<i class="icon-book"></i>&nbsp;&nbsp<strong>'.$cart_product->product_name.'</strong>&nbsp;&nbsp;<small><em class="muted">x '.$cart_product->quantity.'</em></small>'}}
                           @endif  
                          </li>
                          @endforeach
                          <li>&nbsp;</li>
                          <li><strong>Total:</strong> {{$total}}&nbsp;<i class="icon-euro"></i></li>
                          <li>&nbsp;</li>
                          <li><a href="{{url('cart-index')}}" type="button" class="btn btn-primary btn-xs">View Cart In Details</button>&nbsp;&nbsp;</a>
                          <a href="{{url('empty_cart')}}" type="button" class="btn btn-danger btn-xs"><i class="icon-trash"></i>&nbsp;&nbsp;Empty Cart</a></li>
                         </ul>
                          
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->


    <!-- Carousel
    ================================================== --> 
    <div id="myCarousel" class="carousel slide">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
      </ol>
      <div class="carousel-inner">
  
        <div class="item active">
          <img src="{{url()}}/images/products_images/inception3_wp.jpg" alt="" width="1100" height="500" alt="">
          <div class="container">
            <div class="carousel-caption">
              <p><a class="btn btn-large btn-primary" href="#">Learn more</a></p>
            </div>
          </div>
        </div>

        <div class="item">
          <img src="{{url()}}/images/products_images/laravel_wp.jpg" alt="" width="1100" height="500" alt="">
          <div class="container">
            <div class="carousel-caption">
              <p><a class="btn btn-large btn-primary" href="#">Learn more</a></p>
            </div>
          </div>
        </div>

      </div>      
      
      
      <a class="left carousel-control" href="#myCarousel" data-slide="prev"></a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next"></a>
    </div><!-- /.carousel -->


    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->
    
    <div class="container">

      <h1><i class="icon-shopping-cart"></i>&nbsp;&nbsp;My Cart</h1>
      <br />

      <div class="row">

       <table class="table table-hover">
       <thead>
       <tr>
          <th>Item(s)</th>
          <th>Name</th>
          <th>Price</th>
          <th>Quantity</th>
       </tr>
       </thead>
       </tbody>
      @foreach ($cart_products as $cart_item)
        <tr>
        <td><img src="{{url()}}/images/products_images/{{$cart_item->id}}.jpg" width="70" height="100"></td>
        <td>{{$cart_item->product_name}}</td>
        <td>{{$cart_item->product_price}}</td>
        <td>{{$cart_item->quantity}}</td>
        </tr>
         
  
          
      @endforeach
      </tbody>
      </table>
      
      </div>
      
      <hr>
      
    @stop
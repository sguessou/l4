@extends('layouts.master')

@section('head')
@parent
@stop

@section('content')
<!-- NAVBAR
================================================== -->
 

        <div class="navbar navbar-inverse navbar-fixed-top">
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

                <li><a data-toggle="modal" href="#Cart_Modal"><i class="icon-shopping-cart"></i>&nbsp;&nbsp;
                @if ($cart_items_count == 0 || $cart_items_count > 1)
                   Cart (you have {{$cart_items_count}} items)</a></li>
                @else
                   Cart (you have {{$cart_items_count}} item)</a></li>
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
                    <li><a href="#"><i class="icon-shopping-cart"></i>&nbsp;&nbsp;<strong>Cart</strong></a></li>
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
                    @endif              
                </ul>
                
              </ul>
            </div>
          </div>
        </div>

     

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
                          <li><strong>Total:</strong> {{$total}}</li>
                          <li>&nbsp;</li>
                          <li><button type="button" class="btn btn-primary btn-xs">View Cart In Details</button>&nbsp;&nbsp;
                          <a href="{{url('empty_cart')}}/ebooks" type="button" class="btn btn-danger btn-xs"><i class="icon-trash"></i>&nbsp;&nbsp;Empty Cart</a></li>
                         </ul>
                          
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                          </div>
                        </div><!-- /.modal-content -->
                      </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

  <!-- CAROUSEL
================================================== -->
<div id="myCarousel" class="carousel slide">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      </ol>
      <div class="carousel-inner">
        <div class="item active">
          <img src="{{url()}}/images/products_images/laravel_wp.jpg" alt="" width="1100" height="500" alt="">
          <div class="container">
            <div class="carousel-caption">
              
              <p></p>
            </div>
          </div>
        </div>
        
       </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div><!-- /.carousel -->

    <!-- CONTAINER
================================================== -->
<div class="container marketing">
<h3><i class="icon-wrench icon-2x"></i>&nbsp;&nbsp;Admin Menu / Add Product</h3><br />

<div class="row">
	
  <nav class="col-lg-3 sidebar-offcanvas" id="sidebar" role="navigation">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Admin Menu</li>
              <li><a href="{{url('account')}}">home</a></li>
              <li class="nav-header">Users</li>
              <li><a href="#">manage users</a></li>
              <li class="nav-header">Product Types</li>
              <li><a href="{{url('admin-ptypes')}}">manage product types</a></li>
              <li class="nav-header"><strong>Products</strong></li>
              <li><a href="{{url('add-product')}}"><strong>add product</strong></a></li>
              <li><a href="#">update or remove product</a></li>
              <li class="nav-header">Orders</li>
              <li><a href="#">manage orders</a></li>
              <li class="nav-header">Access Logs</li>
              <li><a href="{{url('admin-view_log')}}">view access logs</a></li>
              <li><a href="#">remove access logs</a></li>
              <li class="nav-header">Logout</li>
              <li><a href="{{url('logout')}}">logout</a></li>
            </ul>
          </div><!--/.well -->
        </nav><!--/span-->
  
  <div class="col-lg-9">

<form action="{{route('insert_prod')}}" method="post" class="form-horizontal" role="form" enctype="multipart/form-data"> 

      @if ( Session::has('add_success') )
        <div class="alert alert-success">
            <strong>Well done!</strong> {{Session::get('add_success')}}
        </div>
      @endif
       
       {{ Form::token() }}

     <div class="form-group">
       <label for="product_name" class="col-lg-3 control-label">Title</label> 
      <div class="col-lg-4">    
        <input type="text" class="form-control" name="product_name" placeholder="Enter product name">           
        </div>
     </div>  

      <div class="form-group">
       <label for="price" class="col-lg-3 control-label">Price</label> 
      <div class="col-lg-4">    
        <input type="text" class="form-control" name="price" placeholder="Enter price, e.g. 13.90">           
        </div>
     </div>  

     <div class="form-group">
       <label for="language" class="col-lg-3 control-label">Language</label> 
      <div class="col-lg-4">    
        <input type="text" class="form-control" name="language" placeholder="Enter language">           
        </div>
     </div>  

     <div class="form-group">
       <label for="product_type" class="col-lg-3 control-label">Product type</label>         
       <div class="col-lg-4">
          <select name="product_type" class="form-control">
            @foreach ($p_types as $p_type)
              <option value="{{$p_type->id}}">{{$p_type->type_name}}</option>
            @endforeach
          </select>
      </div>
     </div>    

     <div class="form-group">
       <label for="description" class="col-lg-3 control-label">Description</label> 
      <div class="col-lg-4">  
        <textarea class="form-control" name="description" rows="8" placeholder="Enter description"></textarea>  
        </div>
     </div> 

     <div class="form-group">
       <label for="cover" class="col-lg-3 control-label">Cover</label> 
      <div class="col-lg-4">    
        <input type="file" class="form-control" name="cover" placeholder="Enter cover">           
        </div>
     </div>              
       
    <div class="form-group">
       <label for="author" class="col-lg-3 control-label">Author</label> 
      <div class="col-lg-4">    
        <input type="text" class="form-control" name="author" placeholder="Enter author's name">           
        </div>
     </div>  

     <div class="form-group">
       <label for="isbn" class="col-lg-3 control-label">ISBN-10</label> 
      <div class="col-lg-4">    
        <input type="text" class="form-control" name="isbn" placeholder="Enter ISBN-10">           
        </div>
     </div>

      <div class="form-group"> 
      <label for="submit" class="col-lg-3 control-label"></label> 
      <div class="col-lg-3">  
        <button type="submit" class="btn btn-default">Add new product</button>              
      </div>
     </div>
     </form>
      

   </div>
   
</div> <!-- Row -->



<hr class="featurette-divider">

@stop
<?php



Route::get('/', 'HomeController@index');



Route::get('movies/{offset?}', 'MoviesController@index');

Route::get('ebooks/{offset?}', 'EbooksController@index');

Route::get('add_to_cart/{product_id}/{uri?}', 'CartController@add_item');

Route::get('empty_cart/{uri?}', 'CartController@empty_cart');



/*
*
*	Login and registration routes
*
*/

Route::get('login', function() {

	if(Auth::check()) return Redirect::to('account');
	
	$cart_data = new CartItem;
	
	list( $cart_products, $cart_items_count, $total ) = $cart_data->get_cart_data();

	return View::make('login.index', array('cart_items_count' => $cart_items_count,
			                                          'total' => $total,
			                                  'cart_products' => $cart_products
			                                  ));
});

Route::get('registration', function() {
	
	if(Auth::check()) return Redirect::to('account');

	$cart_data = new CartItem;
	
	list( $cart_products, $cart_items_count, $total ) = $cart_data->get_cart_data();

	return View::make('login.register', array('cart_items_count' => $cart_items_count,
			                                          'total' => $total,
			                                  'cart_products' => $cart_products
			                                  ));
});

//This route is accessed through confirmation email sent to new member.
//Upon firing the the new member data is transfered form signups table to users table.
Route::get('confirm/{code?}', function($code = null) {

	$signup = DB::select('SELECT email, password, firstname, lastname FROM signups WHERE confirm_code LIKE ?', array($code));
	 
	if(!$signup) return Redirect::to('not_found');

	$email =  $signup[0]->email;

	DB::insert('INSERT INTO users (email, password, firstname, lastname) VALUES (?, ?, ? , ?)', array($signup[0]->email,
																									 $signup[0]->password,
																									 $signup[0]->firstname,
																									 $signup[0]->lastname
																									 ));

    DB::delete('DELETE FROM signups WHERE confirm_code LIKE ?', array($code));

	return Redirect::to('login')->with('success_message', 'Your account has been confirmed!')
								->with('email', $email);
});

Route::get('logout', function()
{
	//We set logged flag to 0 on logout
	DB::update('UPDATE users SET logged = 0 , updated_at = CURRENT_TIMESTAMP WHERE email LIKE ?', array(Auth::user()->email));
 
    Auth::logout();

    return Redirect::to('login');
});

//------------------ End login and registration routes ----------------------------------


//-------------------Admin routes------------------------------------------------------
Route::get('account', function() {

	if(!Auth::check()) return Redirect::to('login')->with('not_logged', 'You should be logged in!');
	
	$cart_data = new CartItem;	
	list( $cart_products, $cart_items_count, $total ) = $cart_data->get_cart_data();

	if( !Auth::user()->admin )
	{
		return View::make('account.index', array('cart_items_count' => $cart_items_count,
			                                          'total' => $total,
			                                  'cart_products' => $cart_products
			                                  ));
	}

	return View::make('admin.index', array('cart_items_count' => $cart_items_count,
			                                          'total' => $total,
			                                  'cart_products' => $cart_products
			                                  ));
		
});

Route::get('admin-view_log/{offset?}', function($page = 1) {

	//We make sure that user is logged in.
	if(!Auth::check()) return Redirect::to('login')->with('not_logged', 'You should be logged in!');
	
	//If user is not admin we redirect away.
	if(!Auth::user()->admin) return Redirect::to('/');

	return Redirect::action('AccessLogsController@index', array($page));
});

Route::get('admin-viewLogs/{offset?}', 'AccessLogsController@index');

Route::get('admin-ptypes', function() {

		if(!Auth::check()) return Redirect::to('login')->with('not_logged', 'You should be logged in!');

	    //If user is not admin we redirect away.
		if(!Auth::user()->admin) return Redirect::to('/');

		$cart_data = new CartItem;
		list( $cart_products, $cart_items_count, $total ) = $cart_data->get_cart_data();

		$p_types = DB::select('SELECT id, type_name FROM productTypes WHERE parent_id = ? ORDER BY id DESC', array(0));
	    
	    return View::make('admin.manage_ptypes', array('cart_items_count' => $cart_items_count,
				                                          'total' => $total,
				                                  'cart_products' => $cart_products,
				                                  	  'p_types'  => $p_types
				                                  ));
});

Route::get('add-product', function() {

	if(!Auth::check()) return Redirect::to('login')->with('not_logged', 'You should be logged in!');

    //If user is not admin we redirect away.
	if(!Auth::user()->admin) return Redirect::to('/');

	$cart_data = new CartItem;
	list( $cart_products, $cart_items_count, $total ) = $cart_data->get_cart_data();

	$p_types = DB::select('SELECT id, type_name FROM productTypes WHERE parent_id = ? ORDER BY id DESC', array(0));
	    
	return View::make('admin.add_product', array('cart_items_count' => $cart_items_count,
				                                          'total' => $total,
				                                  'cart_products' => $cart_products,
				                                  	  'p_types'  => $p_types
				                                  ));
});


//----------------------- END Admin routes --------------------------------------------------

//----------------------- Routes With CSRF Filter -------------------------------------------

Route::group(array('before' => 'csrf'), function()
{
	Route::post('handle-login', array('as' => 'login', function() {
	
	$data = Input::all();

	//$user = new User;
	

	$rules = array( 'email' => 'required|email|exists:users,email',
					'password' => 'required'
					);

	$validator = Validator::make($data, $rules);

	if ( $validator->passes() )
	{
		$email = $data['email'];
		$password = $data['password'];
	
		if ( Auth::attempt(array('email' => $email, 'password' =>  $password))) 
		{
			//Logged flag is set to 1
			//$user = User();

			DB::update('UPDATE users SET logged = 1 , updated_at = CURRENT_TIMESTAMP WHERE email LIKE ?', array( Auth::user()->email ));

			//$user->touch();

			Redirect::to('account');
		}
		
	}

	return Redirect::to('login')->withErrors($validator)->withInput(Input::except('password'));


	}));

	//we validate registration data. If ok, data is saved in signups table and email is sent to new member.
	Route::post('handle-registration', array('as' => 'register', function() {
		
		$data = Input::all();

		$rules = array( 'email' => 'required|unique:users,email|confirmed|email',
			            'firstname' => 'required|alpha|min:3',
					    'lastname' => 'required|alpha|min:3',
					    'password' => 'required|confirmed|alpha_num|min:6'
					    );

		$validator = Validator::make($data, $rules);

		//$errors = $validator->messages();
		
		if ( $validator->passes() )
		{
			//We save post data to signups table and send email to new member for confirmation.
			$signup = new Signup;
			$signup->email = $data['email'];
			$signup->password = Hash::make( $data['password'] );
			$signup->firstname = $data['firstname'];
			$signup->lastname = $data['lastname'];
	        // Seed random number generator
			srand((double)microtime() * 1000000);
			$conf_code =  md5( $data['email'] . time() . rand(1, 1000000));
			$signup->confirm_code = $conf_code;
			$signup->save();

			
	        //We send confirmation email to new member. 
			Mail::queue('emails.confirmation', array( 'conf_code' => $conf_code ) , function($message)
			{
			    $message->to( Input::get('email'), Input::get('lastname'))->subject('Confirmation');
			});
			
			return Redirect::to('generic-view');
		}
	    //else we redirect to registration form
		return Redirect::to('registration')->withErrors($validator)->withInput(Input::except('password'));
	}));

	
	Route::post('handle-admin-ptypes', array('as' => 'ptypes', function() {

		if(!Auth::check()) return Redirect::to('login')->with('not_logged', 'You should be logged in!');

		//If user is not admin we redirect away.
		if(!Auth::user()->admin) return Redirect::to('/');

		$data = Input::all();
		
		$p_type = new ProductType;
		$p_type->parent_id = $data['parent_id'];
		$p_type->type_name = $data['type_name'];
		$p_type->save();

		return Redirect::to('admin-ptypes');	


	}));

	Route::post('handle-add-product', array('as' => 'insert_prod', function() {

		if(!Auth::check()) return Redirect::to('login')->with('not_logged', 'You should be logged in!');

		//If user is not admin we redirect away.
		if(!Auth::user()->admin) return Redirect::to('/');

		$data = Input::all();
		
		$product = new Product;
		$product->product_type = $data['product_type'];
		$product->product_name = $data['product_name'];
		$product->product_price = $data['price'];
		$product->product_language = $data['language'];
		$product->product_description = $data['description'];
		$product->product_author = $data['author'];
		$product->product_isbn10 = $data['isbn'];
		$product->save();
         
        $name = $product->id.'.jpg'; 
		Input::file('cover')->move('images/products_images', $name);


		return Redirect::to('add-product')->with('add_success', 'Product added successfully!');	


	}));

});

//---------------------- END Routes With CSRF Filter--------------------------------------------------


Route::get('generic-view', function () {
	$cart_data = new CartItem;
	
	list( $cart_products, $cart_items_count, $total ) = $cart_data->get_cart_data();

	return View::make('generic', array('cart_items_count' => $cart_items_count,
			                                          'total' => $total,
			                                  'cart_products' => $cart_products
			                                  ));
});



App::missing(function($exception)
{
	$cart_data = new CartItem;
	
	list( $cart_products, $cart_items_count, $total ) = $cart_data->get_cart_data();

    return Response::view('errors.missing', array('cart_items_count' => $cart_items_count,
			                                          'total' => $total,
			                                  'cart_products' => $cart_products), 404);
});

Route::get('not_found', function() {

	$cart_data = new CartItem;
	
	list( $cart_products, $cart_items_count, $total ) = $cart_data->get_cart_data();

    return Response::view('errors.missing', array('cart_items_count' => $cart_items_count,
			                                          'total' => $total,
			                                  'cart_products' => $cart_products
			                               ));	
});


Route::get('projects', function() {

	return View::make('projects.projects');
});




Route::get('testing', function() {

	//$var = DB::select('SELECT * FROM signups');
	$var = get_browser(null);
	return $var->parent.' '.$var->platform;
});

Route::get('redis', function() {

	$products = DB::select('SELECT * FROM products');

	$redis = Redis::connection();

	
	

	foreach ( $products as $product )
	{			
		$redis->hmset($product->id, 'name', $product->product_name, 'description', $product->product_description);
		//$redis->command('hmset products:'.$product->id.' name '.$product->product_name.'  description '.$product->product_description);
	}	

		

	//$redis->hmset($prod[0]->id, 'name', $prod[0]->product_name, 'description', $prod[0]->product_description);

	return $redis->hgetall('products');
});
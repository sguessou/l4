<?php

class MoviesController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	private $count = 0;
	private $items_per_page = 6;
	private $num_pages = 0;
	
	private $_cart_data;
	private $_log;

	public function __construct()
	{
		$this->initialize();
		$this->_cart_data = new CartItem;
		$this->_log = new AccessLog;
	}

	private function initialize()
	{
		$this->count = Product::where('product_type','=', 11)->count();
		$this->num_pages = (int)($this->count / $this->items_per_page);	
		if ($this->count % $this->items_per_page) $this->num_pages += 1;
	}

	public function index($page = 1)
	{
		
		$this->_log->save_log($this->_log, 'movies.index');

		$skip = ($page - 1) * $this->items_per_page;

		//$movies = Product::where('product_type','=', 11)->skip($skip)->orderBy('id', 'desc')->take($this->items_per_page)->get();

		$movies = DB::select('SELECT products.*, productTypes.type_name
							  FROM products INNER JOIN productTypes
							  WHERE products.product_type = productTypes.id
							  AND productTypes.type_name LIKE ?  
							  ORDER BY products.id DESC LIMIT ?, ?', array('Dvd', $skip, $this->items_per_page));
		
		list( $cart_products, $cart_items_count, $total ) = $this->_cart_data->get_cart_data();

		return View::make('products.movies', array('movies' => $movies, 
			                                      'num_pages' => $this->num_pages,
			                               'cart_items_count' => $cart_items_count,
			                                          'total' => $total,
			                                  'cart_products' => $cart_products, 
			                                           'page' => $page ));
	}



}//End MoviesController
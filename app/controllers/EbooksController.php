<?php

class EbooksController extends BaseController {

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
		$var = DB::select('SELECT id FROM productTypes WHERE type_name LIKE ?', array('Book'));
		
		$this->count = DB::select('SELECT COUNT(*) as cnt FROM products
			                       WHERE product_type = ?', array( $var[0]->id ) );

		$this->num_pages = (int)( $this->count[0]->cnt / $this->items_per_page );	

		if ($this->count[0]->cnt % $this->items_per_page) $this->num_pages += 1;
	}

	public function index($page = 1)
	{
		$this->_log->save_log($this->_log, 'ebooks.index');

		$skip = ($page - 1) * $this->items_per_page;

		//$ebooks = Product::where('product_type','=', 10)->skip($skip)->orderBy('id', 'desc')->take($this->items_per_page)->get();
		$ebooks = DB::select('SELECT products.*, productTypes.type_name
							  FROM products INNER JOIN productTypes
							  WHERE products.product_type = productTypes.id
							  AND productTypes.type_name LIKE ?  
							  ORDER BY products.id DESC LIMIT ?, ?', array('Book', $skip, $this->items_per_page));

		list( $cart_products, $cart_items_count, $total ) = $this->_cart_data->get_cart_data();

		return View::make('products.ebooks', array('ebooks' => $ebooks, 
			                                      'num_pages' => $this->num_pages,
			                               'cart_items_count' => $cart_items_count,
			                                          'total' => $total,
			                                  'cart_products' => $cart_products, 
			                                           'page' => $page ));
	}
	

}//End EbooksController
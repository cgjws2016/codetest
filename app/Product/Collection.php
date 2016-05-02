<?php

namespace SainsBot\Product;

use SainsBot\Product;

class Collection {

	private $_items;

	public function __construct($items = array()) {
		$this->_items = $items;
	}
	public function add(Product $product) {
		$this->_items[] = $product;
	}

	public function total() {

	}

	public function items() {
		return $this->_items;
	}

}
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
		$total = 0;
		foreach($this->items() as $product) {
			$total += $product->getPrice();
		}
		return $total;
	}

	public function items() {
		return $this->_items;
	}

}
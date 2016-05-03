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

	public function toXml() {
		return "<doc><el>test</el></doc>";
	}

	public function toJson() {
		$output = array();
		$output['total'] = $this->total();
		$output['results'] = array();

		foreach($this->items() as $item) {
			$result = array();
			$result['title'] = $item->getName();
			$result['size'] = $item->getPageSize();
			$result['unit_price'] = $item->getPrice();
			$result['description'] = $item->getDescription();

			$output['results'][] = $result;
		}

		return json_encode($output);
	}

}
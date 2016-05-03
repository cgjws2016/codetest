<?php

namespace SainsBot\Product;

use SainsBot\Product;

/**
 *
 */
class Collection {

	/**
	 * Internal array storing product objects
	 * @var array
	 */
	private $_items;

	/**
	 * Create a collection, either empty or with an optional array
	 * @param array $items Array of Products to use as collection
	 */
	public function __construct($items = array()) {
		$this->_items = $items;
	}

	/**
	 * Adds a Product to collection
	 * @param Product $product
	 */
	public function add(Product $product) {
		$this->_items[] = $product;
		return $this;
	}

	/**
	 * Adds the prices of all products in the collection together, and returns
	 * the total amount
	 * @return float Total product costs
	 */
	public function total() {
		$total = 0;
		foreach($this->items() as $product) {
			$total += $product->getPrice();
		}
		return $total;
	}

	/**
	 * Returns array to iterate through collection
	 * @TODO - look at generator here instead?
	 * @return array
	 */
	public function items() {
		return $this->_items;
	}

	/**
	 * Stubbed xml formatter, as an example
	 * @return str XML string
	 */
	public function toXml() {
		return "<doc><el>test</el></doc>";
	}

  /**
   * JSON formatter - returns a JSON-encoded string representing the product
   * collection's data
   * @return str JSON of product data
   */
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

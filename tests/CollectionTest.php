<?php

use SainsBot\Product;
use SainsBot\Product\Collection;

use \Mockery as m;

class CollectionText extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->collection = new Collection();

		$this->product_one = new Product();
		$this->product_one->setPrice(1.20);
		$this->product_two = new Product();
		$this->product_two->setPrice(5.60);
		$this->product_three = new Product();
		$this->product_three->setPrice(3.99);
	}

	public function testsConstructorAddsToArray()
	{
		$array = array($this->product_one, $this->product_two);
		$collection = new Collection($array);
		$this->assertEquals(2, count($collection->items()));
	}

	public function testsAddsToArray() {
		$collection = new Collection();
		$collection->add($this->product_one);
		$collection->add($this->product_two);
		$collection->add($this->product_three);

		$this->assertEquals(3, count($collection->items()));
	}

	public function testsTotals() {
		$collection = $this->collection;
		$collection->add($this->product_one);
		$collection->add($this->product_two);
		$collection->add($this->product_three);

		$this->assertEquals(10.79, $collection->total());
	}

}
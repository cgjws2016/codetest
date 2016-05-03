<?php

namespace SainsBot\OutputFormatter;

class JsonFormatter implements FormatterInterface {

	public function setCollection($collection) {
		$this->_collection = $collection;
		return $this;
	}

	public function getCollection() {
		return $this->_collection;
	}

	public function output() {
		$output = array();
		$output['total'] = $this->getCollection()->total();
		$output['results'] = array();

		foreach($this->getCollection()->items() as $item) {
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
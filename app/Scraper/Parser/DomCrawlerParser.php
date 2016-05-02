<?php

namespace SainsBot\Scraper\Parser;

use Symfony\Component\DomCrawler\Crawler;

use SainsBot\Scraper\ParserInterface;
use SainsBot\Product;
use SainsBot\Product\Collection;

class DomCrawlerParser implements ParserInterface {

	private $_crawler;

	public function __construct() {
		$this->_crawler = new Crawler();
	}

	public function parse($content) {
		$collection = new Collection();

		$this->_crawler->addContent($content);
		$products = $this->_crawler->filter('div.product');
		$this->_crawler->filter('div.product')->each(
			function (Crawler $node, $i) use (&$collection) {

			$name = $node->filter('.productInfo a');
			$href = trim($name->attr('href'));
			$name = trim($name->text());

			$ppu = $node->filter('p.pricePerUnit');
			$ppu = $this->extractPrice($ppu->text());

			$product = new Product();
			$product->setName($name)
				    ->setHref($href)
					->setPrice($ppu);

			$collection->add($product);
			
		});

		return $collection;
	}

	public function parseProduct($content) {
		$result = new Product();
		$result->setPageSize(strlen($content));

		return $result;
	}


	private function extractPrice($priceHtml) {
		$priceHtml = trim($priceHtml);
		$priceHtml = utf8_decode($priceHtml);

		// Strip the per-unit text
		$price = str_replace('/unit', '', $priceHtml);

		// Need to strip both HTML and utf8 representations of pound sign
		$price = str_replace('&pound', '', $price);
		$price = str_replace('£', '', $price);

		return (float)$price;

	}
}
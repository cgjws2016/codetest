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
			$href = $this->trimText($name->attr('href'));
			$name = $this->trimText($name->text());

			$ppu = $node->filter('p.pricePerUnit');
			$ppu = $this->extractPrice(
				$this->trimText($ppu->text())
			);

			$product = new Product();
			$product->setName($name)
				    ->setHref($href)
					->setPrice($ppu);

			$collection->add($product);
			
		});

		return $collection;
	}

	public function parseProduct($content) {
		$crawler = new Crawler();
		$crawler->addContent($content);

		$result = new Product();
		$result->setPageSize(strlen($content));

		$desc = array();
		$information = $crawler->filter('#information .productText')->first();
		$information->filter('p')->each(
			function (Crawler $node, $i) use (&$desc) {
				$text = $this->trimText($node->text());
				if($text != '') {
					$desc[] = $text;
				}
			}
		);

		$desc = implode("\n", $desc);
		
		$result->setDescription($desc);

		return $result;
	}

	private function trimText($text) {
		return utf8_decode(trim($text));
	}


	private function extractPrice($priceHtml) {
		// Strip the per-unit text
		$price = str_replace('/unit', '', $priceHtml);

		// Need to strip both HTML and utf8 representations of pound sign
		$price = str_replace('&pound', '', $price);
		$price = str_replace('£', '', $price);

		return (float)$price;

	}
}
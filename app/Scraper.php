<?php

namespace SainsBot;

use SainsBot\Scraper\StrategyInterface;
use SainsBot\Scraper\ParserInterface;

/**
 * The main class used by the console app, the Scraper pulls together
 * the retrieval of HTML and the subsequent parsing of that HTML. 
 *
 * I've delivered the scraper with a simple Curl fetching strategy, but the 
 * idea behind the Strategy is that it's extensible. For example, it could
 * be extended to add to add proxy support, or in-memory caching of scraped
 * content - especially useful if you're running multiple scrapers over the
 * same content sources.
 *
 * The same applies to the parsing classes - by default it uses the DomCrawler
 * libraries provided by symfony, but it could use more sophisticated methods
 * such as Goutte or Selenium, if any JS interaction was required.
 */
class Scraper {

	/**
	 * Fetch strategy for grabbing/caching content
	 * @var StrategyInterface
	 */
	private $_strategy;

	/**
	 * Target of URI to scrape
	 * @var str
	 */
	private $_target;

	/**
	 * Parser class to handle scraped content
	 * @var ParserInterface
	 */
	private $_parser;

	/**
	 * Content stored from scraper - used for caching
	 * @var string
	 */
	private $_content;

	/**
	 * Creates a Scraper object - used to scrape content from a website, and return
	 * a collection of product objects
	 * @param StrategyInterface $strategy
	 * @param ParserInterface   $parser
	 */
	public function __construct(StrategyInterface $strategy, ParserInterface $parser) {
		$this->setStrategy($strategy);
		$this->setParser($parser);
	}

	/**
	 * Get the current strategy
	 * @return StrategyInterface
	 */
	public function getStrategy() {
		return $this->_strategy;
	}

	/**
	 * Sets the strategy for this Scraper
	 * @param StrategyInterface $strategy
	 */
	public function setStrategy(StrategyInterface $strategy) {
		$this->_strategy = $strategy;
		return $this;
	}


	public function getTarget() {
		return $this->_target;
	}

	public function setTarget($url) {
		$this->_target = $url;
		return $this;
	}

	public function setParser(ParserInterface $parser) {
		$this->_parser = $parser;
	}

	public function getParser() {
		return $this->_parser;
	}

	/**
	 * Fetches the content from the target, using the set strategy. Will cache
	 * content the first time it's called, to save on HTTP requests
	 * @return str HTML of scraped page
	 */
	public function getContent() {
		if($this->_content == NULL) {
			$content = $this->getStrategy()->fetch($this->getTarget());

			$this->_content = $content;
		}

		return $this->_content;
	}

 	/**
 	 * Main function of Scraper class - will use selected scraping strategy
 	 * to fetch HTML, and selected parser class to transform HTML into a collection
 	 * of products
 	 * @return collection A collection of Product objects
 	 */
	public function scrape() {

		// Double check we're actually pointed at a page
		if($this->getTarget() == NULL) {
			throw new \Exception('Scraper has no current target');
		}

		$content = $this->getContent();
		$collection = $this->getParser()->parse($content);

		// For each product, we need page sizes and descriptions. These
		// are only available by scraping/parsing the linked product pages.
		foreach($collection->items() as $product) {
			$this->scrapeProduct($product);
		}

		return $collection;
	}

 	/**
 	 * Scrape product data from a specific product page. This bypasses the target
 	 * set on the scraper, and instead pulls from a passed Product object.
 	 * @param Product $product A product to scrape data for
 	 * @return Product The passed Product, but with additional data added
 	 */
	public function scrapeProduct($product) {
		$content = $this->getStrategy()->fetch($product->getHref());
		$result = $this->getParser()->parseProduct($content);

		$product->setPageSize($result->getPageSize());
		$product->setDescription($result->getDescription());

		return $product;
	}
}

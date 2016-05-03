<?php

use SainsBot\Scraper;
use SainsBot\Product;
use SainsBot\Product\Collection;
use \Mockery as m;

class ScraperTest extends PHPUnit_Framework_TestCase
{
    private $_content;
    private $_product;

    public function setUp()
    {
        $this->_content = file_get_contents('./tests/contains-products.html');
        $this->_product = file_get_contents('./tests/single-product.html');
    }

    public function testCachesContent()
    {
        $strategy = m::mock('SainsBot\Scraper\Strategy\CurlStrategy');
        $strategy->shouldReceive('fetch')
                 ->once()
                 ->andReturn('This is some content');

        $parser = new SainsBot\Scraper\Parser\DomCrawlerParser();
        $scraper = new Scraper($strategy, $parser);

        $scraper->getContent();
        $scraper->getContent();
    }

    public function testFetchesContent()
    {
        $collection = new SainsBot\Product\Collection();
        $strategy = m::mock('SainsBot\Scraper\Strategy\CurlStrategy');
        $strategy->shouldReceive('fetch')
                 ->once()
                 ->with('http://example.com')
                 ->andReturn('This is some content');

        $parser = m::mock('SainsBot\Scraper\Parser\DomCrawlerParser');
        $parser->shouldReceive('parse')
               ->once()
               ->andReturn($collection);

        $scraper = new Scraper($strategy, $parser);
        $scraper->setTarget('http://example.com');
        $scraper->scrape();

        $this->assertEquals($scraper->getContent(), 'This is some content');
    }

    public function testCallsParseProduct()
    {
        $product = new Product();
        $product->setHref('http://example.com');

        $collection = new Collection(array($product, $product));

        $strategy = m::mock('SainsBot\Scraper\Strategy\CurlStrategy');
        $strategy->shouldReceive('fetch')
                 ->with('http://example.com')
                 ->andReturn('This is some content');
        $parser = new SainsBot\Scraper\Parser\DomCrawlerParser();

        $parser = m::mock('SainsBot\Scraper\Parser\DomCrawlerParser');
        $parser->shouldReceive('parse')
               ->once()
               ->andReturn($collection);

        $parser->shouldReceive('parseProduct')
               ->times(2)
               ->andReturn($product);

        $scraper = new Scraper($strategy, $parser);
        $scraper->setTarget('http://example.com');
        $scraper->scrape();
    }

    public function testReturnsCorrectProducts()
    {
        $strategy = m::mock('SainsBot\Scraper\Strategy\CurlStrategy');
        $strategy->shouldReceive('fetch')
                 ->with('http://example.com')
                 ->andReturn($this->_content);

        $strategy->shouldReceive('fetch')
                 ->times(7)
                 ->andReturn($this->_product);
        $parser = new SainsBot\Scraper\Parser\DomCrawlerParser();

        $scraper = new Scraper($strategy, $parser);
        $scraper->setTarget('http://example.com');
        $collection = $scraper->scrape();

        $this->assertEquals(count($collection->items()), 7);
        $this->assertEquals($collection->items()[0]->getName(), "Sainsbury's Apricot Ripe & Ready x5");
        $this->assertEquals($collection->items()[0]->getPageSize(), strlen($this->_product));
        $this->assertEquals($collection->items()[0]->getDescription(), "Apricots\nThis is a second line");
    }

    public function tearDown()
    {
        m::close();
    }
}

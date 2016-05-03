<?php

use SainsBot\Scraper\Parser\DomCrawlerParser;

class DomCrawlerParserTest extends PHPUnit_Framework_TestCase
{
    private $_html;

    public function setUp()
    {
        $this->_html = file_get_contents('./tests/contains-products.html');
    }

    public function testsReturnsCollection()
    {
        $parser = new DomCrawlerParser();
        $collection = $parser->parse($this->_html);
        $this->assertInstanceOf('SainsBot\Product\Collection', $collection);
    }

    public function testFindsProducts()
    {
        $parser = new DomCrawlerParser();
        $collection = $parser->parse($this->_html);
        $this->assertEquals(count($collection->items()), 7);

        foreach ($collection->items() as $product) {
            $this->assertInstanceOf('SainsBot\Product', $product);
        }
    }

    public function testExtractsCorrectData()
    {
        $parser = new DomCrawlerParser();
        $collection = $parser->parse($this->_html);

        $product = $collection->items()[0];

        $this->assertEquals("Sainsbury's Apricot Ripe & Ready x5", $product->getName());
        $this->assertEquals((float) 3.50, $product->getPrice());
        $this->assertEquals('http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/sainsburys-apricot-ripe---ready-320g.html',
                            $product->getHref());
    }

    public function testParsesSingleProductPage()
    {
        $parser = new DomCrawlerParser();

        $html = file_get_contents('./tests/single-product.html');
        $product = $parser->parseProduct($html);

        $this->assertEquals("Apricots\nThis is a second line", $product->getDescription());
        $this->assertEquals(filesize('./tests/single-product.html'), $product->getPageSize());
    }
}

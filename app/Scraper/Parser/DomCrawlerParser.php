<?php

namespace SainsBot\Scraper\Parser;

use Symfony\Component\DomCrawler\Crawler;
use SainsBot\Scraper\ParserInterface;
use SainsBot\Product;
use SainsBot\Product\Collection;

class DomCrawlerParser implements ParserInterface
{
    /**
     * A Symfony DomCrawler object to filter HTML.
     *
     * @var Crawler
     */
    private $_crawler;

    /**
     * Create a new Parser object and initialise a Crawler.
     */
    public function __construct()
    {
        $this->_crawler = new Crawler();
    }

    /**
     * Takes some HTML content, and pulls out product details.
     *
     * @param str $content HTML content from a scraped page
     *
     * @return Collection a collection of Product objects
     */
    public function parse($content)
    {
        $collection = new Collection();

        $this->_crawler->addContent($content);

        // Look for all the product elements on the page
        $products = $this->_crawler->filter('div.product');

        $this->_crawler->filter('div.product')->each(
            function (Crawler $node, $i) use (&$collection) {

            $name = $node->filter('.productInfo a');
            $href = $this->trimText($name->attr('href'));
            $name = $this->trimText($name->text());

            $ppu = $node->filter('p.pricePerUnit');

            // Prices contain some non-numeric data, so we'll filter separately
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

    /**
     * Parse the HTML of a specific product page and return more detailed
     * information about the product.
     *
     * @param str $content HTML of a product page
     *
     * @return Product A product with more details added
     */
    public function parseProduct($content)
    {

        // Re-initialise the crawler so we don't use listing HTML
        $crawler = new Crawler();
        $crawler->addContent($content);

        $result = new Product();
        $result->setPageSize(strlen($content));

        $desc = array();

        // Description tags look simple, but could theoretically contain
        // multiple p tags, or empty p tags. So we'll iterate through and
        // only use the relevant ones.
        $information = $crawler->filter('#information .productText')->first();
        $information->filter('p')->each(
            function (Crawler $node, $i) use (&$desc) {
                $text = $this->trimText($node->text());
                if ($text != '') {
                    $desc[] = $text;
                }
            }
        );

        // We do want to preserve newlines though!
        $desc = implode("\n", $desc);

        $result->setDescription($desc);

        return $result;
    }

    /**
     * Strip out any whitespace chars, and make sure we're using UTF-8.
     *
     * @param str $text A string to trim
     *
     * @return str A trimmed string
     */
    private function trimText($text)
    {
        return utf8_decode(trim($text));
    }

    /**
     * Strip out any non-numeric information from a price string.
     *
     * @param str $priceHtml A string representing a price from the HTML
     *
     * @return float A numeric representation of the price
     */
    private function extractPrice($priceHtml)
    {
        // Strip the per-unit text
        $price = str_replace('/unit', '', $priceHtml);

        // Need to strip both HTML and utf8 representations of pound sign
        $price = str_replace('&pound', '', $price);
        $price = str_replace('£', '', $price);

        return (float) $price;
    }
}

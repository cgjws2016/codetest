<?php

namespace SainsBot\Scraper;

/**
 * A consistent interface for parsing product content from HTML.
 *
 * By default, the Scraper uses the DomCrawler libraries provided by symfony,
 * but it could use more sophisticated methods such as Goutte or Selenium,
 * if any JS interaction was required.
 */
interface ParserInterface
{
    public function parse($content);
    public function parseProduct($content);
}

<?php

namespace SainsBot\Scraper;

/**
 * A Strategy provides a consistent interface for the Scraper object to
 * fetch remote content.
 *
 * I've shipped the scraper with a simple Curl fetching strategy, but the
 * idea behind the Strategy is that it's extensible. For example, it could
 * be extended to add to add proxy support, or in-memory caching of scraped
 * content - especially useful if you're running multiple scrapers over the
 * same content sources.
 */
interface StrategyInterface
{
    public function fetch($url);
}

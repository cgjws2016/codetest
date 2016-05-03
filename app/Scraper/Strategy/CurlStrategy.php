<?php

namespace SainsBot\Scraper\Strategy;

use SainsBot\Scraper\StrategyInterface;

/**
 * A very simple strategy using CURL.
 */
class CurlStrategy implements StrategyInterface
{
    /**
     * Creates a CurlStrategy object and initialises a curl handle.
     */
    public function __construct()
    {

        // Check support!
        if (!function_exists('curl_init')) {
            throw new \Exception('Curl not installed');
        }

        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_TIMEOUT, 10);
    }

    /**
     * Fetches the remote content from the given URL.
     *
     * @param str $url A URL to fetch
     *
     * @return str Content from the curl response
     */
    public function fetch($url)
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        $output = curl_exec($this->ch);

        return $output;
    }
}

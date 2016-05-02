<?php

namespace SainsBot\Scraper\Strategy;

use SainsBot\Scraper\StrategyInterface;

class CurlStrategy implements StrategyInterface {

	public function __construct() {
	    if (!function_exists('curl_init')){
	        throw new \Exception('Curl not installed');
	    }
 
	    $this->ch = curl_init();
 
	    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($this->ch, CURLOPT_TIMEOUT, 10);
 
 
	}
	public function fetch($url) {
    	curl_setopt($this->ch, CURLOPT_URL, $url);
    	$output = curl_exec($this->ch);

    	return $output;
	}

}
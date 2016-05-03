# Sainsbury's Scraper

To install:

`composer install --prefer-dist`

To run:

`./bin/sb scrape <url>`

To run tests:

`./bin/run_tests.sh`

Code is organised as follows:

 - app : Application code
 - tests : PHPUnit tests
 - features : Behat features and contexts
 - bin : executable and test scripts

 CI here: https://travis-ci.org/cgjws2016/codetest

[![Build Status](https://travis-ci.org/cgjws2016/codetest.svg?branch=master)](https://travis-ci.org/cgjws2016/codetest)
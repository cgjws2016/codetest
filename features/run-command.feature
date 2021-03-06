Feature: Run scrape command
  In order to scrape a website
  As a user
  I need to be able to run the application via the console

  Scenario: Running scrape command with arguments
    When I run "scrape" command with arguments:
    | arg | value |
    | url | http://www.example.com |
    Then I should receive json

  Scenario: Running scrape command with test URL
    When I run "scrape" command with arguments:
    | arg | value |
    | url | http://hiring-tests.s3-website-eu-west-1.amazonaws.com/2015_Developer_Scrape/5_products.html |
    Then I should receive json
    Then I should see products json

  Scenario: Running scrape command with no arguments
    When I run "scrape" command
    Then I should receive exception of class "Symfony\Component\Console\Exception\RuntimeException"
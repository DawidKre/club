#@javascript
Feature: Search
  In order to find posts
  As a web user
  I need to be able to search for posts

  Background:
    Given I clear the database


  #@javascript
  Scenario:
    Given There are 2 articles with title "Draco"
    Given I am on the homepage
    Then I should see 2 articles
    When I fill in the search box with "Draco"
    And I press the search button
    Then I should see "Draco"
   
    
  Scenario Outline:
    Given There are 4 articles with title "post"
    Given There are 2 articles with title "<result>"
    Given I am on the homepage
    Then I should see 6 articles
    When I fill in the search box with "<term>"
    And I press the search button
    Then I should see "<result>"
    And I should see 2 articles
    Examples:
      | term   | result             |
      | Draco  | Draco Kowala       |
      | Viking | Viking Lesniczowka |


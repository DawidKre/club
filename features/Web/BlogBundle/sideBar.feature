Feature: Search
  In order to view lastest posts and comments
  As a web user
  In side bar

  Background:
    Given I clear the database
    Given There are 12 articles with title "Article"
    Given There are article with 7 comments with body "comment"


  #@javascript
  Scenario: Show latest 7 posts
    When I am on the homepage
    Then I should see 6 articles
    And I should see 7 posts headlines in side bar
    Then I click "Najnowsze komentarze"
    And I should see "comment"
    Then I click "Aktualności"
    And I should see "Article"
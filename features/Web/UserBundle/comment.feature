Feature: Comment
  In order to use comments posts by logged and no logged user
  As a user I should  be able to coomment, delete my comments
  As an admin I should be able to delete all comments

  Background:
    Given I clear the database

  #@javascript
  Scenario: Adding comments as logged user
    Given there is article with title "Post"
    Given I am logged in as user "user" with password "user"
    Then I click "Post"
    And I should see "Komentarze"
    And I fill in "Komentarz" with "first comment"
    Then I press "Prześlij"
    And I should see "first comment"
    Then I follow "Strona główna"
    And I should see "1"
    Then I follow "1"
    And I should see "Komentarze"
    And I fill in "Komentarz" with "second comment"
    Then I press "Prześlij"
    Then I follow "Strona główna"
    And I should see "2"
    Then I follow "2"


  Scenario: Adding comments as no logged user
    Given there is article with title "Post"
    Given I am on the homepage
    Then I click "Post"
    And I should see "Komentarze"
    And I fill in "Komentarz" with "first comment"
    And I fill in "Autor" with "author"
    And I fill in "Adres E-mail" with "email@com.pl"
    Then I press "Prześlij"
    And I should see "first comment"
    Then I follow "Strona główna"
    And I should see "1"
    Then I follow "1"
    And I should see "Komentarze"
    And I fill in "Komentarz" with "second comment"
    And I fill in "Autor" with "author"
    And I fill in "Adres E-mail" with "email@com.pl"
    Then I press "Prześlij"
    Then I follow "Strona główna"
    And I should see "2"
    Then I follow "2"
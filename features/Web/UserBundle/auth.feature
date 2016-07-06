Feature: User Management
  In order to use the site as me and save data
  As a user
  I need to be able to register and login

  Background:
    Given I clear the database

  Scenario: Registration
    When I am on the homepage
    And I click "Rejestracja"
    And I fill in "E-mail" with "user@user.com"
    And I fill in "Nazwa użytkownika" with "uzytkownik"
    And I fill in "Hasło" with "user"
    And I fill in "Powtórz hasło" with "user"
    And I press "Zarejestruj"
    Then I should see "Stworzono użytkownika"
    
  Scenario: Loggining in
    Given there is a user "admin" with password "admin"
    Given I am on "/"
    When I follow "Logowanie"
    And I should be on "/login"
    And I fill in "Nazwa użytkownika" with "admin"
    And I fill in "Hasło" with "admin"
    And I press "Zaloguj się"
    #And print last response
    And I should be on the homepage
    Then I should see "Profil"
    And I should not see "Zaloguj się"
    And I should see "Wyloguj"
    Then I follow "Profil"
    And I should be on "/profile/edit"
    Then I click "Wyloguj"
    And I should be on the homepage
    And I should not see "Profil"
    And I should see "Logowanie"
    And I should not see "Wyloguj"

  #@javascript
  Scenario: Edit user username
    Given I am logged in as user "user" with password "user"
    Given I am on the homepage
    When I follow "Profil użytkownika user"
    Then I should be on "/profile/edit"
    Then I fill in "Nazwa użytkownika" with "user2"
    And I fill in "Obecne hasło" with "user2"
    And I press "Edytuj użytkownika"
    Then I should see validation error
    Then I fill in "Obecne hasło" with "user"
    And I press "Edytuj użytkownika"
    And I should see "Zapisano zmiany w profilu"
    Then I click "Wyloguj"
    And I click "Logowanie"

    And I fill in "Nazwa użytkownika" with "user"
    And I fill in "Hasło" with "user"
    Then I press "Zaloguj się"
    Then I should see validation error

    And I fill in "Nazwa użytkownika" with "user2"
    And I fill in "Hasło" with "user"
    Then I press "Zaloguj się"
    And I should be logged in
    Then I should see text matching "Profil użytkownika user2"

  Scenario: Edit user email
    Given I am logged in as user "user" with password "user" and email "user@email.com"
    Given I am on the homepage
    When I follow "Profil użytkownika user"
    Then I should be on "/profile/edit"
    Then I fill in "E-mail" with "user2@emil.com"
    And I fill in "Obecne hasło" with "user2"
    And I press "Edytuj użytkownika"
    Then I should see validation error
    Then I fill in "Obecne hasło" with "user"
    And I press "Edytuj użytkownika"
    And I should see "Zapisano zmiany w profilu"
    Then I should see text matching "user2@emil.com"

  #@javascript
  Scenario: Edit user password
    Given I am logged in as user "user" with password "pass"
    Given I am on the homepage
    When I follow "Profil użytkownika user"
    Then I should be on "/profile/edit"
    Then I fill in second "Obecne hasło" with "badpass"
    And I fill in "Nowe hasło" with "newpass"
    And I fill in "Powtórz hasło" with "newpass"
    And I press "Zmień hasło"
    Then I should see validation error
    Then I fill in "Obecne hasło" with "pass"
    And I fill in "Nowe hasło" with "newpass"
    And I fill in "Powtórz hasło" with "newpass"
    And I press "Zmień hasło"
    And I should see "Hasło zostało zmienione"

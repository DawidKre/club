#@javascript
Feature: Index
  In order to test index page

  Background:
    Given I clear the database

  Scenario: Test
    Given there are 5 articles
    
 # @javascript
  Scenario Outline: Testing index
    Given the fixtures file "post.yml" is loaded
    Given I am on the homepage
    Then I should see "AKTUALNOŚCI"
    And I should see "<tekst>"
    Examples:
      | tekst   |
      | Post123 |
      | Dawid   |
      | Post2   |

  Scenario: Testing redirect
    #When I am on the homepage
    Given the fixtures file "post.yml" is loaded
    Given I am on the homepage
    When I click "Post123"
    Then I should be on "/post123"
    And I should see "Post123"
    Then I click "News"
    And I should be on the homepage

#  @javascript
  Scenario: Working 404 page
    When I go to "\dasd\dasd\dasd\das"
    Then I should be on "\dasd\dasd\dasd\das"
    And I should see "404"
    And I should see "Not Found"

  #@javascript
  Scenario: List of articles
    Given there are 4 articles
    And there is 1 article
    Given I am on the homepage
    Then I should see 5 articles

  Scenario: Two pages pagination
    Given there are 2 pages
    Given I am on the homepage
    And  I should see 2 on pagination
    Then I click 2 on pagination
    And I should be on "/2"
    And I should see 2 current
    And I should see page range minus one articles
    And I should see 1 on pagination
    Then I click 1 on pagination
    And I should see 1 current
    And I should see page range articles
    And I should be on the homepage

  #@javascript
  Scenario Outline: Testing pagination more than two pages
    Given there are "<pages>" pages
    Given I am on the homepage
    Then I should see page range articles
    And I should see page range articles
    And I should see 1 current
    And  I should see "<pages>" on pagination
    And  I should see right arrow on pagination
    And  I should not see left arrow on pagination
    Then I click "right" arrow
    And I should be on "/2"
    Then I should see page range articles
    And  I should see right arrow on pagination
    And  I should see left arrow on pagination
    And I should see 1 on pagination
    And I should see 3 on pagination
    And I should see "<pages>" on pagination
    And I should see 2 current
    Then I click "<pages>" on pagination
    And I should see page range minus one articles
    And I should be on "/<pages>"
    And  I should not see right arrow on pagination
    And  I should see left arrow on pagination
    And I should see "<pages-1>" on pagination
    And I should not see "<pages+1>" on pagination
    And I should see "<pages>" current

    Examples:
      | pages | pages-1 |
      | 4     | 3       |
      | 5     | 4       |
      | 7     | 6       |
      | 30    | 29      |

  #@javascript
  Scenario: Testing pagination more than two pages
    Given there are 5 pages
    Given I am on the homepage
    Then I should see page range articles
    And I should see page range articles
    And I should see 1 current
    And  I should see 5 on pagination
    And  I should see right arrow on pagination
    And  I should not see left arrow on pagination

  #@javascript
  Scenario: Test category redirect and showing category list
    Given There are 5 articles with category "mecz"
    Given there are 2 articles
    Given I am on the homepage
    Then I should see 6 articles
    Then I follow "mecz" category link
    Then I should be on "/category/mecz"
    Then I should see 5 articles


  Scenario Outline: Test navigation
    Given I am on the homepage
    Then I should see "<nav>"
    Then I click "<nav>"
    And I should be on "<route>"
    And I should see "<current>" current nav
    Examples:
      | nav           | route       | current       |
      | Strona główna | /           | Strona główna |
      | Terminarz     | /terminarz  | Rozgrywki     |
      | Tabela        | /tabela     | Rozgrywki     |
      | Kadra         | /kadra/draco-kowala      | Rozgrywki     |
      | Stadion       | /stadion    | Klub          |
      | Kontakt       | /contact    | Kontakt       |
      | Wyniki        | /wyniki     | Rozgrywki     |
      | Informacje    | /info/draco-kowala/ | Klub                  |
      | Statystyki    | /statystyki | Rozgrywki     |
      | Galeria       | /galeria    | Galeria       |
      | Logowanie     | /login      | Logowanie/Rejestracja |
      | Rejestracja   | /register/  | Logowanie/Rejestracja |

  Scenario: Test author redirect and showing author posts list
    Given There are 5 articles with author "admin"
    Given There are 2 articles with author "user"
    Given I am on the homepage
    Then I should see 6 articles
    Then I follow "admin"
    Then I should be on "/author/admin"
    Then I should see 5 articles


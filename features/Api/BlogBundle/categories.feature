Feature: Testing API
  In order to blog project
  As an API client
  I need to be able to create category

  Background:
    Given I clear the database
    Given there is a user "role_user" with password "user"
    Given I add "Authorization" header equal to "Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJ1c2VybmFtZSI6InJvbGVfdXNlciIsImlhdCI6IjE0NjQ5NDg1MjQifQ.smpGquJOkj8SjOQafxXK9riuY_PvBFWzIbRQHvhiNIkUe2ndseO8ziVcFxjlCyhJuIfyqojahQihL6LoH6oJ9pPIAIdgR3Jkx19liWTW206Bea3hBFSERl-zsexaVvC55zT26batittN4unO846iocqxEOACANpgU0oa2Gwooi5Z2A48oki9DQzcs8AQoZOTODPQns-lyHeiPp12DXjQYk6NYWhx13lNWdhJ_FMRK8mts8NNtlKz7arr_EczXW00bVpilW3zT3g2DUvHKVHcq6X2U5281ZsoQMOWnKBAoha6luMhHeWkPiA2h_r848fyvQ-bZiFKc-iHvK8MRNtyXamfa1zGZZlXM2XDiW8-4haEku-0-ICSgp9qLHkkYAoKnqr3jiqSRGvE24YQqFWdf1Jy3qQsJaxJNAZVclS8CpYU_MRNp3LuW_rlfLkIFWNqXgsq55UFDCIZ7lf2kdQaknzcLbHhYkVwMAoQBX7CKBEKA5IOg0t-9k5k4_aMQSe31yfv5pS-Jh5XbREWgllrRj6mSzGCr_UgEUovjOA02Dax1u1NomYe0VtPeQ7o-N8WN-XNtkjbqodySXUCnaYiCHZUvtL6Bjabid_FG9yag5tFE_PGSGtgRmTBBPh1ZYM5_TWyT3T3uNKHKKs6ITLtaUlFIleF525xnnaIuQ2Czk4"


  Scenario: Create a category testing POST request
    Given I send a POST request to "/api/categories" with body:
      """
      {
        "name": "Kategoria"
      }
      """
    Then the response status code should be 201
    Then the header Location should be equal to "/app_test.php/api/categories/kategoria"
    Then the response should be in JSON

  
  Scenario: Testing GET collection of categories
    Given There are 2 articles with category "cat1"
    Given There are 3 articles with category "cat2"
    Then I send a GET request to "/api/categories"
    Then the response status code should be 200
    Then the response should be in JSON
    And the JSON node "items" should exist
    Then the JSON node "items" should have "2" elements
    Then the JSON node "items[0]" should have "4" elements
    And the response should contain "cat1"
    And the response should contain "cat2"
    Then the JSON node "items[0].name" should be equal to "cat1"
    Then the JSON node "items[1].name" should be equal to "cat2"
    And the response should be equal to
        """
      │ {
      │     "categories": [
      │         {
      │             "name": "cat1",
      │             "slug": "cat1"
      │         },
      │         {
      │             "name": "cat2",
      │             "slug": "cat2"
      │         }
      │     ]
      │ }
        """
    
  Scenario: Testing GET to view category
    Given There are 2 articles with category "Category"
    Then I send a GET request to "/api/categories/category"
    Then the response status code should be 200
    Then the response should be in JSON
    Then the JSON node "" should have "4" elements
    Then the JSON node "name" should be equal to "Category"
    Then the JSON node "slug" should be equal to "category"
    And print last JSON response
    Then the JSON node "_links.self" should exist
    And the response should be equal to
        """
      │ {
      │   "name": "cat1",
      │   "slug": "cat1"
      │ }
        """

  Scenario: Testing Patch to edit category 
    Given There are 2 articles with category "match"
    Given I send a PATCH request to "/api/categories/match" with body:
      """
      {
        "name": "goals"
      }
      """
    Then the response status code should be 200
    And the response should be in JSON
    Then the JSON node "name" should be equal to "goals"

  Scenario: Testing DELETE to delete category
    Given There are 2 articles with category "match"
    Given I send a DELETE request to "/api/categories/match" 
    Then the response status code should be 204

    
  Scenario: Test validation errors
    Given I send a POST request to "/api/categories" with body:
      """
      {
        "name": ""
      }
      """
    Then the response status code should be 400
    Then the JSON node "type" should exist
    And the JSON node "title" should exist
    And the JSON node "errors" should exist
    Then the JSON node "errors.name[0]" should be equal to "Nazwa kategorii nie może być pusta"
    Then the header "Content-Type" should be equal to "application/problem+json"
    Then print last response headers
    Then print last JSON response

  Scenario: Test invalid JSON
    Given I send a POST request to "/api/categories" with body:
      """
      {
        "name": "cat
      
      """
    Then the response status code should be 400
    And print last response headers
    Then the JSON node "type" should be equal to "about:blank"
    And print last response headers
    Then the response should be in JSON

  Scenario: Test 404 exception
    Given I send a GET request to "/api/categories/fake"
    Then the response status code should be 404
    Then the header "Content-Type" should be equal to "application/problem+json"
    Then the JSON node "type" should be equal to "about:blank"
    And the JSON node "title" should be equal to "Not Found"

  Scenario: Testing GET pagination
    Given There are 13 categories contains name "category"
    Then I send a GET request to "/api/categories"
    And print last JSON response
    Then the response status code should be 200
    Then the response should be in JSON
    Then the JSON node "items[4].name" should be equal to "category4"
    Then the JSON node "items[5]" should not exist
    Then the JSON node "count" should be equal to "5"
    Then the JSON node "total" should be equal to "13"
    And the JSON node "_links.next" should exist

  Scenario: Testing GET filtering
    Given There are 6 categories contains name "category"
    Given There are 3 categories contains name "fake"
    Then I send a GET request to "/api/categories"
    Then the JSON node "count" should be equal to "5"
    Then the JSON node "total" should be equal to "9"
    Then I send a GET request to "/api/categories"
    Then the JSON node "count" should be equal to "5"
    Then the JSON node "total" should be equal to "9"
    Then the response status code should be 200
    Then the response should be in JSON
    Then print last JSON response
    And the JSON node "_links.next" should exist



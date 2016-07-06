Feature: Testing API
  In order to blog project
  As an API client
  I need to be able to create category

  Background:
    Given I clear the database
    Given there is a user "role_user" with password "user"
    Given I add "Authorization" header equal to "Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJ1c2VybmFtZSI6InJvbGVfdXNlciIsImlhdCI6IjE0NjQ5NDg1MjQifQ.smpGquJOkj8SjOQafxXK9riuY_PvBFWzIbRQHvhiNIkUe2ndseO8ziVcFxjlCyhJuIfyqojahQihL6LoH6oJ9pPIAIdgR3Jkx19liWTW206Bea3hBFSERl-zsexaVvC55zT26batittN4unO846iocqxEOACANpgU0oa2Gwooi5Z2A48oki9DQzcs8AQoZOTODPQns-lyHeiPp12DXjQYk6NYWhx13lNWdhJ_FMRK8mts8NNtlKz7arr_EczXW00bVpilW3zT3g2DUvHKVHcq6X2U5281ZsoQMOWnKBAoha6luMhHeWkPiA2h_r848fyvQ-bZiFKc-iHvK8MRNtyXamfa1zGZZlXM2XDiW8-4haEku-0-ICSgp9qLHkkYAoKnqr3jiqSRGvE24YQqFWdf1Jy3qQsJaxJNAZVclS8CpYU_MRNp3LuW_rlfLkIFWNqXgsq55UFDCIZ7lf2kdQaknzcLbHhYkVwMAoQBX7CKBEKA5IOg0t-9k5k4_aMQSe31yfv5pS-Jh5XbREWgllrRj6mSzGCr_UgEUovjOA02Dax1u1NomYe0VtPeQ7o-N8WN-XNtkjbqodySXUCnaYiCHZUvtL6Bjabid_FG9yag5tFE_PGSGtgRmTBBPh1ZYM5_TWyT3T3uNKHKKs6ITLtaUlFIleF525xnnaIuQ2Czk4"


  Scenario: Create a post testing POST
    Given There are 2 articles with category "category"
    Given I send a POST request to "/api/posts" with body:
      """
      {
        "title": "Post",
        "content": "Mensas ridetis in lutetia!Bursa de brevis buxum, demitto agripeta!",
        "category": "category",
        "isMatch":  true
      }
      """
    Then print last JSON response
    And print last response headers
    Then the response status code should be 201
    Then the response should be in JSON

  Scenario: GET test for view single post
    Given there is article with title "Post"
    Then I send a GET request to "/api/posts/post"
    And print last JSON response
    Then the response status code should be 200
    Then the response should be in JSON
    And the JSON node "title" should be equal to "Post"
    Then the JSON node "_links.self" should exist

  Scenario: GET test for collection of posts
    Given there is article with title "Post1"
    Given there is article with title "Post2"
    Given There are 10 articles with title "Post"
    Then I send a GET request to "/api/posts"
    And print last JSON response
    Then the response status code should be 200
    Then the response should be in JSON
    And the JSON node "items" should exist
    And the response should contain "Post1"
    And the response should contain "Post2"
    And the JSON node "count" should contain 5
    Then the JSON node "_links.self" should exist

  Scenario: Testing DELETE to delete post
    Given there is article with title "Post"
    Given I send a DELETE request to "/api/posts/post"
    Then the response status code should be 204

  Scenario: Test validation errors
    Given I send a POST request to "/api/posts" with body:
      """
      {
        "title": ""
      }
      """
    Then the response status code should be 400
    Then the JSON node "type" should exist
    And the JSON node "title" should exist
    And the JSON node "errors" should exist
    Then the JSON node "errors.title" should exist
    Then the header "Content-Type" should be equal to "application/problem+json"

  Scenario: Test invalid JSON
    Given I send a POST request to "/api/posts" with body:
      """
      {
        "title": "post
      
      """
    Then the response status code should be 400
    And print last response headers
    Then print last JSON response
    Then the JSON node "type" should be equal to "about:blank"
    Then the response should be in JSON

  Scenario: Create a comment testing POST
    Given I am logged in as user "user" with password "user" and email "ads@gmail.com"
    Given there is article with title "post"
    Given I send a POST request to "/api/comments" with body:
      """
      {
        "post": 469,
        "comment": "Mensas ridetis in lutetia!Bursa de brevis buxum, demitto agripeta!",
        "email": "email@email.com",
        "user": "user"
      }
      """
    And print last JSON response
    Then the response status code should be 201
    Then the response should be in JSON

  Scenario: Testing DELETE to delete post
    Given There are article with 2 comments with body "comment"
    Given I send a DELETE request to "/api/comments/8"
    And print last response headers
    Then the response status code should be 204

  Scenario: Testing Patch to edit comment
    Given There are article with 2 comments with body "comment"
    Given I send a PATCH request to "/api/comments/28" with body:
      """
      {
        "comment": "goals"
      }
      """
    And print last response headers
    And print last JSON response
    Then the response status code should be 200
    And the response should be in JSON
    Then the JSON node "comment" should be equal to "goals"

  Scenario: Testing GET comments
    Given There are article with title "Article" and with 5 comments with body "Brevis, castus lacteas aliquando prensionem de lotus, domesticus fortis."
    Then I send a GET request to "/api/posts/article/comments"
    And the JSON node "count" should contain 5
    And the JSON node "items" should have 5 elements
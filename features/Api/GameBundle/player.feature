Feature: Testing API
  In order to game projects
  As an API client
  I need to be able to create, update, delete players

  Background:
    Given I clear the database
    Given there is a user "role_user" with password "user"
    Given I add "Authorization" header equal to "Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJ1c2VybmFtZSI6InJvbGVfdXNlciIsImlhdCI6IjE0NjQ5NDg1MjQifQ.smpGquJOkj8SjOQafxXK9riuY_PvBFWzIbRQHvhiNIkUe2ndseO8ziVcFxjlCyhJuIfyqojahQihL6LoH6oJ9pPIAIdgR3Jkx19liWTW206Bea3hBFSERl-zsexaVvC55zT26batittN4unO846iocqxEOACANpgU0oa2Gwooi5Z2A48oki9DQzcs8AQoZOTODPQns-lyHeiPp12DXjQYk6NYWhx13lNWdhJ_FMRK8mts8NNtlKz7arr_EczXW00bVpilW3zT3g2DUvHKVHcq6X2U5281ZsoQMOWnKBAoha6luMhHeWkPiA2h_r848fyvQ-bZiFKc-iHvK8MRNtyXamfa1zGZZlXM2XDiW8-4haEku-0-ICSgp9qLHkkYAoKnqr3jiqSRGvE24YQqFWdf1Jy3qQsJaxJNAZVclS8CpYU_MRNp3LuW_rlfLkIFWNqXgsq55UFDCIZ7lf2kdQaknzcLbHhYkVwMAoQBX7CKBEKA5IOg0t-9k5k4_aMQSe31yfv5pS-Jh5XbREWgllrRj6mSzGCr_UgEUovjOA02Dax1u1NomYe0VtPeQ7o-N8WN-XNtkjbqodySXUCnaYiCHZUvtL6Bjabid_FG9yag5tFE_PGSGtgRmTBBPh1ZYM5_TWyT3T3uNKHKKs6ITLtaUlFIleF525xnnaIuQ2Czk4"

  Scenario: Testing GET collection of players
    Given There are 10 players in team "Draco Kowala"
    Given There are 5 players in team "Viking"
    When I send a GET request to "/api/players"
    Then the response status code should be 200
    Then the response should be in JSON
    And the JSON node "items[0]._links.self" should exist
    And the JSON node "items[0]._links.team" should exist
    And the JSON node "items[0]._links.position" should exist
    And the JSON node "count" should be equal to "5"
    And  the JSON node "total" should be equal to "15"

  Scenario: Testing GET collection of players
    Given There is a player with name "Dawid" and team "Draco"
    When I send a GET request to "/api/players/dawid"
    And the JSON node "name" should be equal to "Dawid"
    And the JSON node "team" should be equal to "Draco"
    Then the response status code should be 200

  Scenario: Create a player
    Given I send a POST request to "/api/player" with body:
      """
      {
        "name":"Dawid"
      }
      """
    And print last response headers
    And print last JSON response
    Then the response status code should be 201
    Then the response should be in JSON

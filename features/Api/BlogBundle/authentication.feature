Feature: Testing API
  In order to blog project
  As an authenticated API client
  I need to be able to create category

  Background:
    Given I clear the database

  Scenario: Required authentication
    Given I send a POST request to "/api/categories" with body:
      """
      {
        "name": "cattt"
      }
      """
    And print last response headers
    Then the response status code should be 403

  Scenario: Token Test
    Given there is a user "role_user" with password "user"
    Given I send a POST request to "/api/tokens" with body:
      """
      {
        "username":"role_user",
        "password":"user"
      }
      """
    Then I should be logged in
    Then print last JSON response
    Then the JSON node "token" should exist
    

  Scenario: Create a category testing POST
    Given there is a user "user" with password "user"
    Given I add "Authorization" header equal to "Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXUyJ9.eyJ1c2VybmFtZSI6InVzZXIiLCJpYXQiOiIxNDY0OTQ4MjcyIn0.CwVfFAl8zFMnl7Z7LpgH1ZRP0ATTwNDIDDBvOl7dguENDovwFznRGQ1enjsmMxaHNBJTT1KaNd9hfSgEDCTo_fl0SYpvbEb4IKDyQ3sQQcxrqEQim40PjB2zxnDW3BqIcV7PQCB9-Nq-wuS6G-2ET3NZ_Ze1zq6R6qArqiA-37n4VENSGB24stIikNA3QIJANp-EFU7mN4ygOElpG2JVbCjkqJeakHt6L92Vu0LaaxhDtvLxocINekM3WFUgrAhqDVQZOsLlLk9Cz75kkiN2Kv4JLz01hyGWugenM8aceXcw9j0aIN1asETEP32GuatxfdyVTVQoZL9uG9lbeh0tHiuIcVyND-ocvEV-rKXthW70t9NxtAZDt-dVunn3NjqZMggg4HVUHaQ1M1kq9UJrJ6PueZsYN2rApYl56ukmRdgAaczAEAjSC_a2XsFMvhNZ1-u495U4LsRjjHCZEjz48OH5gl2MPbJACUT3YKel22sP0hnyjzfnn3tk9hKuYMYpgED_99k1WACvidpJdxJXQpTVre1CseU3tnq8SKlWc58SS33zlgQmFGXZ6qMtmnAPFcNohpK0LGaB5EE6Zb0WKCg2WIb72KHGcqqcZxMdplqM9HQ4WXB7Vuw9Z5g8Dam3oZswu3ZFTB2cfJAZU6FM2bK2jG4NKccW1ZenzMQabrM"
    Given I send a POST request to "/api/categories" with body:
      """
      {
        "name": "Kategoria"
      }
      """
    Given I add "HTTP_AUTHORIZATION" header equal to "Bearer"
    Then print last JSON response
    Then print last response headers
    Then the response status code should be 201
    Then the response should be in JSON
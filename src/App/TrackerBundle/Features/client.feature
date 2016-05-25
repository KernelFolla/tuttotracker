Feature: Api User signup/login
  In order to use the application
  As a visitor
  I need to be able to signup and login

  Background:
    Given there are the following users:
      | username | email       | password | enabled | role      |
      | bar      | bar@foo.com | foo      | yes     | ROLE_USER |
    And there are the following clients:
      | createdBy | name       |
      | bar       | new client |


  Scenario: get client with success
    Given I get a jtw token with "bar" and "foo"
    When I send a "GET" request to "/api/v1/clients/{{ids.clients['new client']}}"
    Then the response should contain json:
    """
{
    "id": "*",
    "name": "new client",
    "created_by": "bar"
}
    """
    And the response status code should be 200

  Scenario: create new client with errors
    Given I get a jtw token with "bar" and "foo"
    When I send a "POST" request to "/api/v1/clients" with json as table:
      | test | foo |
    Then the response should contain json:
    """
{
    "code": 400,
    "message": "Validation Failed",
    "errors": {
        "errors": [
            "This form should not contain extra fields."
        ],
        "children": {
            "name": {
                "errors": [
                    "This value should not be blank."
                ]
            }
        }
    }
}
    """
    And the response status code should be 400

  Scenario: create new client as duplicate
    Given I get a jtw token with "bar" and "foo"
    When I send a "POST" request to "/api/v1/clients" with json as table:
      | name | new client |
    Then the response should contain json:
    """
{
    "code": 400,
    "message": "Validation Failed",
    "errors": {
        "children": {
            "name": {
                "errors": [
                    "This client already exists."
                ]
            }
        }
    }
}
    """
    And the response status code should be 400

  Scenario: create new client with success
    Given I get a jtw token with "bar" and "foo"
    When I send a "POST" request to "/api/v1/clients" with json as table:
      | name | client |
    Then the response should contain json:
    """
{
    "id": "*",
    "name": "client",
    "created_by": "bar"
}
    """
    And the response status code should be 201

  Scenario: edit a client with success
    And I get a jtw token with "bar" and "foo"

    When I send a "PUT" request to "/api/v1/clients/{{ids.clients['new client']}}" with json as table:
      | name | changed |
    Then the response should contain json:
    """
{
    "id": "*",
    "name": "changed",
    "created_by": "bar"
}
    """
    And the response status code should be 200

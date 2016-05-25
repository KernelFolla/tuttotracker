Feature: Api User signup/login
  In order to use the application
  As a visitor
  I need to be able to signup and login

  Background:
    Given there are the following users:
      | username  | email             | password | enabled | role       |
      | bar       | bar@foo.com       | foo      | yes     | ROLE_USER  |
      | user      | user@foo.com      | foo      | yes     | ROLE_USER  |
      | emptyuser | emptyuser@foo.com | foo      | yes     | ROLE_USER  |
      | admin     | admin@foo.com     | foo      | yes     | ROLE_ADMIN |
    And there are the following clients:
      | createdBy | name       |
      | bar       | new client |
    And the user "bar" has "10" more fake clients
    And the user "user" has "10" more fake clients

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

  Scenario: get client with fail
    Given I get a jtw token with "user" and "foo"
    When I send a "GET" request to "/api/v1/clients/{{ids.clients['new client']}}"
    And the response status code should be 403

  Scenario: get client as admin with success
    Given I get a jtw token with "admin" and "foo"
    When I send a "GET" request to "/api/v1/clients/{{ids.clients['new client']}}"
    And the response status code should be 200

  Scenario: get client as admin with error
    Given I get a jtw token with "admin" and "foo"
    When I send a "GET" request to "/api/v1/clients/12345"
    And the response status code should be 404

  Scenario: get client search list with success
    Given I get a jtw token with "bar" and "foo"
    When I send a "GET" request to "/api/v1/clients?s=new"
    Then the response should contain json:
    """
{
     "count": "1",
     "results": [
       {
           "id": "*",
           "name": "new*",
           "created_by": "bar"
       }
     ]
}
    """
    And the response status code should be 200

  Scenario: get client list with success
    Given I get a jtw token with "bar" and "foo"
    When I send a "GET" request to "/api/v1/clients"
    Then the response should contain json:
    """
{
           "count": "11",
           "next": "*\/api\/v1\/clients?limit=10&offset=10",
           "results": [
           {
               "id": "*",
               "name": "*",
               "created_by": "bar"
           },{
               "id": "*",
               "name": "*",
               "created_by": "bar"
           },{
               "id": "*",
               "name": "*",
               "created_by": "bar"
           },{
               "id": "*",
               "name": "*",
               "created_by": "bar"
           },{
               "id": "*",
               "name": "*",
               "created_by": "bar"
           },{
               "id": "*",
               "name": "*",
               "created_by": "bar"
           },{
               "id": "*",
               "name": "*",
               "created_by": "bar"
           },{
               "id": "*",
               "name": "*",
               "created_by": "bar"
           },{
               "id": "*",
               "name": "*",
               "created_by": "bar"
           },{
               "id": "*",
               "name": "*",
               "created_by": "bar"
           }
           ]
}
    """
    And the response status code should be 200

  Scenario: get client list with fail
    Given I get a jtw token with "emptyuser" and "foo"
    When I send a "GET" request to "/api/v1/clients"
    Then the response should contain json:
    """
{"count":"0","results":[]}
    """
    And the response status code should be 200

  Scenario: get client list as admin
    Given I get a jtw token with "admin" and "foo"
    When I send a "GET" request to "/api/v1/clients"
    Then the response should contain json:
    """
{"count":"*","next":"*"}
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


  Scenario: delete a client with success
    Given I get a jtw token with "bar" and "foo"
    When I send a "DELETE" request to "/api/v1/clients/{{ids.clients['new client']}}"
    Then the response status code should be 204
    When I send a "GET" request to "/api/v1/clients/{{ids.clients['new client']}}"
    Then the response status code should be 404

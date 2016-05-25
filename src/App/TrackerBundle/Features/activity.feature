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
      | createdBy | name |
      | bar       | dell |
    And there are the following activities:
      | createdBy | name         |
      | bar       | new activity |
    And the user "bar" has "10" more fake activities
    And the user "user" has "10" more fake activities

  Scenario: get activity with success
    Given I get a jtw token with "bar" and "foo"
    When I send a "GET" request to "/api/v1/activities/{{ids.activities['new activity']}}"
    Then the response should contain json:
    """
{
    "id": "*",
    "name": "new activity",
    "created_by": "bar"
}
    """
    And the response status code should be 200

  Scenario: get activity with fail
    Given I get a jtw token with "user" and "foo"
    When I send a "GET" request to "/api/v1/activities/{{ids.activities['new activity']}}"
    And the response status code should be 403

  Scenario: get activity as admin with success
    Given I get a jtw token with "admin" and "foo"
    When I send a "GET" request to "/api/v1/activities/{{ids.activities['new activity']}}"
    And the response status code should be 200

  Scenario: get activity as admin with error
    Given I get a jtw token with "admin" and "foo"
    When I send a "GET" request to "/api/v1/activities/12345"
    And the response status code should be 404

  Scenario: get activity search list with success
    Given I get a jtw token with "bar" and "foo"
    When I send a "GET" request to "/api/v1/activities?s=new"
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

  Scenario: get activity list with success
    Given I get a jtw token with "bar" and "foo"
    When I send a "GET" request to "/api/v1/activities"
    Then the response should contain json:
    """
{
           "count": "11",
           "next": "*\/api\/v1\/activities?limit=10&offset=10",
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

  Scenario: get activity list with fail
    Given I get a jtw token with "emptyuser" and "foo"
    When I send a "GET" request to "/api/v1/activities"
    Then the response should contain json:
    """
{"count":"0","results":[]}
    """
    And the response status code should be 200

  Scenario: get activity list as admin
    Given I get a jtw token with "admin" and "foo"
    When I send a "GET" request to "/api/v1/activities"
    Then the response should contain json:
    """
{"count":"*","next":"*"}
    """
    And the response status code should be 200

  Scenario: create new activity with errors
    Given I get a jtw token with "bar" and "foo"
    When I send a "POST" request to "/api/v1/activities" with json as table:
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
            },
            "client": [],
            "startsAt": {
                "errors": [
                    "This value should not be blank."
                ]
            }
        }
    }
}
    """
    And the response status code should be 400

  Scenario: create new activity failing
    Given I get a jtw token with "bar" and "foo"
    When I send a "POST" request to "/api/v1/activities" with json as table:
      | name | new activity |
    Then the response should contain json:
    """
{
    "code": 400,
    "message": "Validation Failed",
    "errors": {
        "children": {
            "name": [],
            "client": [],
            "startsAt": {
                "errors": [
                    "This value should not be blank."
                ]
            }
        }
    }
}
    """
    And the response status code should be 400

  Scenario: create new activity with success (existing client)
    Given I get a jtw token with "bar" and "foo"
    When I send a "POST" request to "/api/v1/activities" with json as table:
      | name     | activity                          |
      | startsAt | {{ "now"\| date("Y-m-d h:i:s") }} |
      | client   | dell                              |
    Then the response should contain json:
    """
{
	"id": "*",
	"name": "activity",
	"created_by": "bar",
	"created_at": "*",
	"starts_at": "*",
	"client": {
		"id": "*",
		"name": "dell",
		"created_by": "bar"
	}
}
    """
    And the response status code should be 201

  Scenario: create new activity with success (new client)
    Given I get a jtw token with "bar" and "foo"
    When I send a "POST" request to "/api/v1/activities" with json as table:
      | name     | fantastic activity                |
      | startsAt | {{ "now"\| date("Y-m-d h:i:s") }} |
      | client   | idk                               |
    Then the response should contain json:
    """
{
	"id": "*",
	"name": "fantastic activity",
	"created_by": "bar",
	"created_at": "*",
	"starts_at": "*",
	"client": {
		"id": "*",
		"name": "idk",
		"created_by": "bar"
	}
}
    """
    And the response status code should be 201

  Scenario: edit a activity with success
    And I get a jtw token with "bar" and "foo"

    When I send a "PUT" request to "/api/v1/activities/{{ids.activities['new activity']}}" with json as table:
      | name     | changed                                |
      | startsAt | {{ "-2 hours"\| date("Y-m-d h:i:s") }} |
      | endsAt   | {{ "-1 hours"\| date("Y-m-d h:i:s") }} |
      | client   | changedClient                          |

    Then the response should contain json:
    """
{
	"id": "*",
	"name": "changed",
	"created_by": "bar",
	"created_at": "*",
	"starts_at": "*",
	"client": {
		"id": "*",
		"name": "changedClient",
		"created_by": "bar"
	}
}
    """
    And the response status code should be 200

  Scenario: stop a activity with success
    And I get a jtw token with "bar" and "foo"

    When I send a "PATCH" request to "/api/v1/activities/{{ids.activities['new activity']}}/stop"
    Then the response should contain json:
    """
{
	"id": "*",
	"name": "new activity",
	"created_by": "bar",
	"created_at": "*",
	"starts_at": "*",
	"ends_at": "*"
}
    """
    And the response status code should be 200

  Scenario: stop a activity with error
    Given I get a jtw token with "bar" and "foo"
    When I send a "PATCH" request to "/api/v1/activities/{{ids.activities['new activity']}}/stop"
    And the response status code should be 200
    When I send a "PATCH" request to "/api/v1/activities/{{ids.activities['new activity']}}/stop"
    And the response status code should be 400

  Scenario: delete a activity with success
    Given I get a jtw token with "bar" and "foo"
    When I send a "DELETE" request to "/api/v1/activities/{{ids.activities['new activity']}}"
    Then the response status code should be 204
    When I send a "GET" request to "/api/v1/activities/{{ids.activities['new activity']}}"
    Then the response status code should be 404

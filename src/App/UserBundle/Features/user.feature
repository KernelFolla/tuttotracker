Feature: Api User signup/login
  In order to use the application
  As a visitor
  I need to be able to signup and login

  Background:
    Given there are the following users:
      | username | email       | password | enabled | role      |
      | bar      | bar@foo.com | foo      | yes     | ROLE_USER |

  Scenario: get current user as not logged
    When I send a "GET" request to "/api/v1/users/current"
    """
    {"code":401,"message":"Invalid credentials"}
    """
    And the response status code should be 401

  Scenario: retrieve an invalid token
    When I get a jtw token with "bar" and "foo"
    And I send a "GET" request to "/api/v1/users/current"
    Then the response status code should be 200
    Given I send a "POST" request to "/api/login_check" with json as table:
      | _username | bar |
      | _password | foo |
    Then the response should contain "token"
    And the response status code should be 200

  Scenario: retrieve a valid token
    Given I send a "POST" request to "/api/login_check" with json as table:
      | _username | bar   |
      | _password | wrong |
    Then the response status code should be 401
    And the response should contain json:
    """
    {"code":401,"message":"Bad credentials"}
    """

  Scenario: failing registration, missing fields
    When I send a "POST" request to "/api/v1/users" with json as table:
      | username      | mario |
      | plainPassword | test  |
    Then the response should contain json:
    """
{
	"code": 400,
	"message": "Validation Failed",
	"errors": {
		"children": {
			"email": {
				"errors": ["Please enter an email"]
			},
			"username": {},
			"plainPassword": {
				"children": {
					"first": {},
					"second": {}
				}
			}
		}
	}
}
    """
    And the response status code should be 400

  Scenario: failing registration, duplicate username
    When I send a "POST" request to "/api/v1/users" with json as table:
      | username             | bar            |
      | plainPassword.first  | test           |
      | plainPassword.second | test           |
      | email                | mario@test.com |
    Then the response should contain json:
    """
{
	"code": 400,
	"message": "Validation Failed",
	"errors": {
		"children": {
			"email": {},
			"username": {
				"errors": ["The username is already used"]
			},
			"plainPassword": {
				"children": {
					"first": {},
					"second": {}
				}
			}
		}
	}
}
    """
    And the response status code should be 400

  Scenario: failing registration, duplicate email
    When I send a "POST" request to "/api/v1/users" with json as table:
      | username             | mario       |
      | plainPassword.first  | test        |
      | plainPassword.second | test        |
      | email                | bar@foo.com |
    Then the response should contain json:
    """
{
	"code": 400,
	"message": "Validation Failed",
	"errors": {
		"children": {
			"email": {
				"errors": ["The email is already used"]
			},
			"username": {},
			"plainPassword": {
                  "children": {
                      "first": {},
					"second": {}
				}
			}
		}
	}
}
    """
    And the response status code should be 400

  Scenario: registration success and sign in
    When I send a "POST" request to "/api/v1/users" with json as table:
      | username             | mario          |
      | plainPassword.first  | test           |
      | plainPassword.second | test           |
      | email                | mario@test.com |
    Then the response status code should be 201
    When I get a jtw token with "mario" and "test"
    And I send a "GET" request to "/api/v1/users/current"
    Then the response should contain json:
    """
    {"username":"mario","email":"mario@test.com","roles":[], "enabled": 1}
    """
    When I get a jtw token with "mario@test.com" and "test"
    And I send a "GET" request to "/api/v1/users/current"
    Then the response should contain json:
    """
    {"username":"mario","email":"mario@test.com","roles":[], "enabled": 1}
    """
    And the response should not contain "password"
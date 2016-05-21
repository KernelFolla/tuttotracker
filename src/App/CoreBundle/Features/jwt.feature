Feature: User login
  In order to use the application
  As a visitor
  I need to be able to log in

  Background:
    Given there are the following users:
      | username | email       | password | enabled | role       |
      | bar      | bar@foo.com | foo      | yes     | ROLE_ADMIN |

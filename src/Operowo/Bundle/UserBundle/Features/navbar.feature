Feature: User functions in navbar
  In order to login and register
  As a anonymous or logged user
  I need to be able to see and change my status

  Scenario: Anonymus user see registration link
    Given I am on "/"
     Then I should see "Zarejestruj" in the ".navbar .user" element
     When I follow "Zarejestruj"
     Then the response status code should be 200

  Scenario: Anonymus user see login link
    Given I am on "/"
     Then I should see "Zaloguj" in the ".navbar .user" element
     When I follow "Zaloguj"
     Then the response status code should be 200

  Scenario: Anonymus user see login form
    Given I am on "/"
     Then I should see 1 ".navbar .user form" element

  @database
  Scenario: Anonymus user can login
    Given I am on "/"
      And The following users exists
      | Username |
      | user_1   |
      | user_2   |
     When I fill in the following:
      | _username | user_1 |
      | _password | user_1 |
      And I press "Zaloguj"
     Then I should not see "Zarejestruj" in the ".navbar .user" element
      And I should not see "Zaloguj" in the ".navbar .user" element
      And I should see "user_1" in the ".navbar .user" element
     When I follow "Wyloguj"
     Then I should see "Zaloguj" in the ".navbar .user" element
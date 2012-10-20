Feature: User can login
  In order to secure some functions
  As a anonymous
  I need to be able to login

  Scenario: Anonymous user can open login page
    Given I am on "/"
      And I follow "Zaloguj"
     Then I should see "Logowanie" in the "h1" element
      And I should see 1 ".content form" element

  @database
  Scenario: Anonymous user can login
    Given I am on "/"
      And I follow "Zaloguj"
      And The following users exists
      | Username |
      | user_1   |
     When I fill in the following:
      | _username | user_1 |
      | _password | user_1 |
      And I press "Zaloguj"
     #Then I should see "Zostałeś zalogowany"
     Then I should be on "/"
      And I should see "user_1" in the ".navbar .user" element

  @database
  Scenario: Anonymous user can not login with invalid data
    Given I am on "/"
      And I follow "Zaloguj"
      And The following users exists
      | Username |
      | user_2   |
     When I fill in the following:
      | _username | user_1 |
      | _password | user_1 |
     And I press "Zaloguj"
    #Then I should see "Zostałeś zalogowany"
    Then I should be on "/login"
     And I should see "Nieprawidłowa nazwa użytkownika lub hasło"

  @database @current
  Scenario: Logged user can not open login page
    Given The following users exists
      | Username |
      | user_1   |
      And I am logged as "user_1"
     When I am on "/login"
     Then I should be on "/"
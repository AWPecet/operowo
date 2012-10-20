Feature: User can login
  In order to secure some functions
  As a anonymous
  I need to be able to login

  @current
  Scenario: Anonymus user can open login page
    Given I am on "/"
      And I follow "Zaloguj"
     Then I should see "Logowanie" in the "h1" element
      And I should see 1 ".content form" element
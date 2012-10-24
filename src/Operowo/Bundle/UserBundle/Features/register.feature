Feature: User can register
  In order to login
  As a anonymous user
  I need to be able to register

  Scenario: Anonymus user can open registration page
    Given I am on "/"
     When I follow "Zarejestruj"
     Then I should see "Rejestracja" in the "h1" element
      And I should see 1 ".content form" element

  @database
  Scenario: Anonymous user can register
    Given I am on "/"
     When I follow "Zarejestruj"
      And I fill in the following:
      | fos_user_registration_form_username             | new_user             |
      | fos_user_registration_form_email                | new_user@operowo.sf2 |
      | fos_user_registration_form_plainPassword_first  | new_user_password    |
      | fos_user_registration_form_plainPassword_second | new_user_password    |
      And I press "Zarejestruj"
     Then I should see "Na adres new_user@operowo.sf2 wysłano wiadomość e-mail."
      And I should see "Sprawdź maila" in the "h1" element

  @database @current
  Scenario: Anonymous user can activate his account
    Given The following users exists
      | Username | Enabled | Confirmation token |
      | user_2   | false   | secret_token       |
      And I am on "/rejestracja/confirm/secret_token"
     Then I should see "user_2" in the ".navbar .user" element
      And I should see "potwierdziłeś konto"
Feature: List of institutions
  In order to browse institution
  As a anonymous user
  I need to be able to list all institutions

  Scenario: List of institutions on homepage with link in navbar
  	Given I am on "/"
  	 Then I should see "Lista instytucji" in the "h1" element
  	  And I should see "Instytucje" in the ".navbar" element

  Scenario: List of institutions on separeted page
  	Given I am on "/"
  	  And I follow "Instytucje"
  	 Then I should see "Lista instytucji" in the "h1" element
  	  And I should see "Instytucje" in the ".navbar .active" element

  @database
  Scenario: Pagination on list of institutions
  	Given I am on "/"
  	  And Faker prepare "30" fake of institution
  	  And I follow "Instytucje"
  	  And I should see 10 "div .institution" element
  	  And I should see an "div.pagination" element

  @database
  Scenario: Province of institution
    Given I am on "/"
      And The following provinces exists
      | Name         |
      | śląskie      |
      | opolskie     |
      | dolnośląskie |
      And The following institutions exists
      | Name         | Province |
      | Opera Śląska | śląskie  |
      And I follow "Instytucje"
     Then I should see "Opera Śląska" in the "h2" element
      And I should see "śląskie" in the "dl dd" element
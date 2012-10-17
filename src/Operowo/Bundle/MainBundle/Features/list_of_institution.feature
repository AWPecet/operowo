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
      | slaskie      |
      | opolskie     |
      | dolnoslaskie |
      And The following institutions exists
      | Name         | Province |
      | Opera Śląska | slaskie  |
      And I follow "Instytucje"
     Then I should see "Opera Śląska" in the "h2" element
      And I should see "slaskie" in the "dl dd" element

  @database @current
  Scenario: Filter institutions by province
    Given I am on "/"
      And The following provinces exists
      | Name         |
      | slaskie      |
      | opolskie     |
      | dolnoslaskie |
      | malopolskie  |
      And The following institutions exists
      | Name             | Province      |
      | Opera Śląska     | slaskie       |
      | Opera Opolska    | opolskie      |
      | Opera Katowicka  | slaskie       |
      | Opera Wrosławska | dolnoslaskie  |
      And I follow "Instytucje"
     Then I should see "Województwa" in the ".sidebar .provinces_filter .nav-header" element
  #And I should see "slaskie (2)" in the ".sidebar .provinces_filter" element
      And I should see "slaskie" in the ".sidebar .provinces_filter" element
      And I should see "opolskie" in the ".sidebar .provinces_filter" element
      And I should not see "malopolskie" in the ".sidebar .provinces_filter" element
     When I follow "filter_3"
     Then I should see "Lista instytucji" in the "h1" element
      And I should see "slaskie" in the ".sidebar .provinces_filter .active" element
      And I should see 1 ".sidebar .provinces_filter .active" element
      And I should see 2 "div .institution" element
     When I follow "filter_2"
     Then I should see "slaskie" in the ".sidebar .provinces_filter .active #filter_3" element
      And I should see "opolskie" in the ".sidebar .provinces_filter .active #filter_2" element
      And I should see 2 ".sidebar .provinces_filter .active" element
      And I should see 3 "div .institution" element
     When I follow "filter_3"
     Then I should not see "slaskie" in the ".sidebar .provinces_filter .active #filter_2" element
      And I should see 1 "div .institution" element
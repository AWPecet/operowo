Feature: List of institutions
  In order to browse institution
  As a anonymous user
  I need to be able to list all institutions

  Scenario:
  	Given I am on "/"
  	 Then I should see "Lista instytucji" in the "h1" element
  	  And I should see "Instytucje" in the ".navbar" element

  Scenario:
  	Given I am on "/"
  	  And I follow "Instytucje"
  	 Then I should see "Lista instytucji" in the "h1" element
  	  And I should see "Instytucje" in the ".navbar .active" element

  @database
  Scenario:
  	Given I am on "/"
  	  And Faker prepare "30" fake "institution"
  	  And I follow "Instytucje"
  	 Then I should see "Lista instytucji" in the "h1" element
  	  And I should see 10 "div .institution" element
  	  And I should see an "div.pagination" element
Feature: List of institutions
  In order to browse institution
  As a anonymous user
  I need to be able to list all institutions


  Scenario:
  	Given I am on "/"
  	 Then I should see "Lista instytucji" in the "h1" element
  	  And I should see "Instytucje" in the ".navbar" element
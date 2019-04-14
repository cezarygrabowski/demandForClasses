Feature: Listing users
  In order to choose the least occupied user
  As a District Manager
  I need to be able to list all users


  Scenario: List users
    Given There is user "Jan Kowalski"
    And user "Jan Kowalski" has role "Kierownik zakładu"
    And There is user "Jacek Babowski"
    When user "Jan Kowalski" will request all users
    Then user "Jan Kowalski" will see following users:
      | Jan Kowalski    |
      | Jacek Babowski  |
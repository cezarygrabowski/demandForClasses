Feature: Listing users
  In order to choose the least occupied lecturer
  As a District Manager
  I need to be able to list all users

  Scenario: List users
    Given There is a user "Jan Kowalski"
    And user "Jan Kowalski" has role "District Manager"
    And There is a user "Jacek Babowski"
    When user "Jan Kowalski" will request all users
    Then user "Jan Kowalski" will see following users:
      | Jan Kowalski    |
      | Jacek Babowski  |



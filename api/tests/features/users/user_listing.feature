Feature: Listing users
  In order to choose the least occupied user
  As a District Manager
  I need to be able to list all users

  @user-listing
  Scenario: List users
    Given There is user "Jan Kowalski"
    And user "Jan Kowalski" has role "Kierownik zakładu"
    And There is user "Jacek Babowski"
    And user "Jacek Babowski" has role "Nauczyciel"
    And user "Jacek Babowski" has calendar that contains:
      | 0 | 100 |
      | 1 | 130 |
      | 2 | 40  |
      | 3 | 50  |
      | 4 | 55  |
    And user "Jacek Babowski" has following qualifications:
      | Analiza i projektowanie systemów teleinformatycznych |
      | Projekt zespołowy MSK                                |
    When user "Jan Kowalski" will list all teachers
    Then user "Jan Kowalski" will see following users:
      | Jacek Babowski  | Nauczyciel  | Analiza i projektowanie systemów teleinformatycznych, Projekt zespołowy MSK |
Feature: Import users with calendars and qualifications
  In order to assign users to Demand
  As a District Manager
  I need to be able to import teachers with their calendars and qualifications

  @import-users
  Scenario: Import users with calendars and qualifications
    Given There is user "Jan Kowalski"
    And user "Jan Kowalski" has role "Kierownik zakładu"
    When user "Jan Kowalski" import "users.csv" lecturers file that contains the following:
      | Robert Nowacki | Aip - Analiza i projektowanie systemów teleinformatycznych, Pmsk - Projekt zespołowy MSK | Nauczyciel | 100,130,40,50,55,35 | 2018/2019 letni |
    Then user should have name "Robert Nowacki"
    And user "Robert Nowacki" should have "Nauczyciel" role
    And user "Robert Nowacki" should have following qualifications:
      | Analiza i projektowanie systemów teleinformatycznych |
      | Projekt zespołowy MSK                                |
    And user "Robert Nowacki" should have calendar that contains:
      | 0 | 100 |
      | 1 | 130 |
      | 2 | 40  |
      | 3 | 50  |
      | 4 | 55  |
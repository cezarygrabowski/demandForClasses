Feature: Import users with calendars and qualifications
  In order to assign users to Demand
  As a District Manager
  I need to be able to import teachers with their calendars and qualifications

  Scenario: Import users with calendars and qualifications
    Given There is a user "Jan Kowalski"
    And user "Jan Kowalski" has a role "District Manager"
    When user "Jan Kowalski" will import "csv" file
    And "csv" file will contain the following:
      | Robert Nowacki | Aip - Analiza i projektowanie systemów teleinformatycznych, Pmsk - Projekt zespołowy MSK | Nauczyciel | 100,130,40,50,55,35,0,0,0,60,50 |
    Then created user should have name "Robert Nowacki"
    And user "Robert Nowacki" should have "Nauczyciel" role
    And user "Robert Nowacki" should have following qualifications:
      | Aip - Analiza i projektowanie systemów teleinformatycznych |
      | Pmsk - Projekt zespołowy MSK                               |
    And user "Robert Nowacki" should have role "Teacher"

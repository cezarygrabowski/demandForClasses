Feature: Import study plans
  In order to create demands
  As a district manager
  I need to be able to import study plans

  Scenario: Import study plans
    Given There is a user "Jan Kowalski"
    And user "Jan Kowalski" has a role "District Manager"
    When user "Jan Kowalski" will import "csv" file
    And "csv" file will contain the following:
      | K5C1N1 | Seminarium | Smn | Wykład | 4 | 4 | studia niestacjonarne | letni 2018/2019 | wcy | ITA
    Then created demand will have "K5C1N1" group
    And "studia niestacjonarne" group type
    And "Seminarium" "Smn" subject
    And "Wykład" lecture type
    And "ITA" institute
    And "wcy" department
    And "letni 2018/2019" semester
    And "4" year number
    And "4" hours


Feature: Import study plans
  In order to create demands
  As a district manager
  I need to be able to import study plans

  @import-study-plans
  Scenario: Import study plans
    Given There is user "Jan Kowalski"
    And user "Jan Kowalski" has role "Kierownik zakładu"
    When user "Jan Kowalski" will import "study_plans.csv" study plans that contains the following:
      | K5C1N1 | Studia niestacjonarne | Seminarium | Smn | Wykład,Ćwiczenia | 4 | 30,50 | letni 2018/2019 | wcy | ITA |
    Then demand should have "K5C1N1" group
    And demand should have "Studia niestacjonarne" group type
    And demand should have "Seminarium" "Smn" subject
    And demand should have "Wykład" lecture set type
    And demand should have "Ćwiczenia" lecture set type
    And demand should have "ITA" institute
    And demand should have "wcy" department
    And demand should have "4" year number
    And lecture set "Wykład" should have "30" hours
    And lecture set "Ćwiczenia" should have "50" hours
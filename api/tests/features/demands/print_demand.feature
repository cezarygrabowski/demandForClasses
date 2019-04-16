Feature: Demand printing
  In order to collect demand in paper form
  As a planner
  I need to be able to print demand

  @print-demand
  Scenario: DTO for demand printing has all informations
    Given There is user "Kierownik"
    And user "Kierownik" has role "Kierownik zakładu"
    And There is user "Jan Kowalski"
    And user "Jan Kowalski" has role "Nauczyciel"
    And user "Jan Kowalski" has "Podstawy programowania" "PP" qualification
    And There is user "Marcin Wołowski"
    And user "Marcin Wołowski" has role "Nauczyciel"
    And user "Marcin Wołowski" has "Podstawy programowania" "PP" qualification
    And There is user "Stefan Dobrzycki"
    And user "Stefan Dobrzycki" has role "Nauczyciel"
    And user "Stefan Dobrzycki" has "Podstawy programowania" "PP" qualification
    And user "Kierownik" will import "study_plans.csv" study plans that contains the following:
      | K5C1N1 | Studia niestacjonarne | Podstawy programowania | PP | Wykład,Ćwiczenia,Projekt | 4 | 30,50,60 | letni 2018/2019 | wcy | ITA |
    And I change demand lecturer to "Jan Kowalski" in "Projekt" lecture set
    And I change demand lecturer to "Marcin Wołowski" in "Ćwiczenia" lecture set
    And I change demand lecturer to "Stefan Dobrzycki" in "Wykład" lecture set
    And There is a place with building "65" and room "100"
    And I book "10" hours in "3" week in "Wykład" lecture set
    And I choose building "65" and room "100" in "3" week in "Wykład" lecture set
    And I book "20" hours in "4" week in "Wykład" lecture set
    And I choose building "65" and room "100" in "4" week in "Wykład" lecture set
    And I book "50" hours in "3" week in "Ćwiczenia" lecture set
    And I choose building "65" and room "100" in "3" week in "Ćwiczenia" lecture set
    And I book "60" hours in "3" week in "Projekt" lecture set
    And I choose building "65" and room "100" in "3" week in "Projekt" lecture set
    When I print the demand
    And printed demand should have "K5C1N1" group name
    And printed demand should have "Studia niestacjonarne" group type
    And printed demand should have "Podstawy programowania" subject name
    And printed demand should have "PP" subject short name
    And printed demand should have "4" school year
    And printed demand should have "letni 2018/2019" semester
    And printed demand should have "wcy" department
    And printed demand should have "ITA" institute
    And lecture set "Wykład" of printed demand should have "10" hours booked in "3" week in "65" building and "100" room
    And lecture set "Wykład" of printed demand should have "20" hours booked in "4" week in "65" building and "100" room
    And lecture set "Ćwiczenia" of printed demand should have "50" hours booked in "3" week in "65" building and "100" room
    And lecture set "Projekt" of printed demand should have "60" hours booked in "3" week in "65" building and "100" room


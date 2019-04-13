Feature: Demand customization
  In order to rationalise process of creating demands for classes
  As a user
  I need to be able to customize a demand

  Background:
    Given There is demand with subject "Wychowanie fizyczne" "WF"
    And lecture set "Projekt"
    And Demand lecture set "Projekt" has "30" undistributed hours

  @customization
  Scenario: Change user
    Given There is user "Jan Kowalski"
    Given user "Jan Kowalski" has role "Nauczyciel"
    When I change demand lecturer to "Jan Kowalski" in "Projekt" lecture set
    Then user "Jan Kowalski" should see this demand

  @customization
  Scenario: Allocate hours in chosen weeks
    When I book "20" hours in "3" week
    And I book "10" hours in "4" week
    Then in "3" week should be "20" hours allocated
    And in "4" week should be "10" hours allocated
    And lecture set "Projekt" should have "0" undistributed hours

  @customization
  Scenario: Choose place of lectures in demand
    Given There is a place with building "65" and room "100"
    And I book "30" hours in "3" week
    When I choose building "65" and room "100" in "3" week
    Then Demand should have building "65" and room "100" in "3" week

  @customization
  Scenario: Add notes
    When I add notes "Przykladowa notatka"
    Then Lecture set should have "Przykladowa notatka" notes

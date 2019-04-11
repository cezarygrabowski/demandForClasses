Feature: Demand customization
  In order to rationalise process of creating demands for classes
  As a user
  I need to be able to customize a demand

  Background:
    Given There is demand with subject "Wychowanie fizyczne" "WF" and lecture type "Projekt"

  @OneScenarioAtTheTime
  Scenario: Change lecturer
    Given There is lecturer "Jan Kowalski"
    When I change demand lecturer to "Jan Kowalski" in "Projekt" lecture type
    Then user "Jan Kowalski" should see this demand on his list

  Scenario: Choose date of lectures
    Given Demand lecture type "Projekt" has "30" hours undistributed
    When I book "30" hours in "3" week
    And I save a demand
    Then I should see that demand has "0" hours undistributed
    And That lectures are planned in "3" week

  Scenario: Choose place of lectures in demand
    Given Demand lecture type "Projekt" has "30" hours undistributed
    And There is "30" hours booked in "3" week
    When I choose building "65" and room "100"
    And I save a demand
    Then Demand should have building "65" and "100" room in "3" week in "Projekt" lecture type

  Scenario: Add notes
    When I add notes "Przykladowa notatka" in "Projekt" lecture type
    And I save a demand
    Then Demand should have filled in "notes" with "Przykladowa notatka" in "Projekt" lecture type

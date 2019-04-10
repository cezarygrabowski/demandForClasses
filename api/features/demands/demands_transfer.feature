Feature: Demand transfer
  In order to rationalise process of creating demands for classes
  As a user
  I need to be able to send customized demand

  Background:
    Given There is a demand with subject "Wychowanie fizyczne" "WF" with lecture type "Projekt"
    Given There is a user "Jan Kowalski"

  Scenario: Send to District Manager
    And user "Jan Kowalski" is "District Manager"
    And I am a "Teacher"
    When I send a demand
    Then I should see this demand as "Sent"
    And user "Jan Kowalski" will see this demand

  Scenario: Send to Institute Director
    And user "Jan Kowalski" is "Institute Director"
    And I am "District Manager"
    When I send a demand
    Then I should see this demand as "Sent"
    And user "Jan Kowalski" will see this demand

  Scenario: Send to Dean
    And user "Jan Kowalski" is "Dean"
    And I am "Institute Director"
    And user "Jan Kowalski" automatically sends demands to planners
    When I send a demand
    Then I should see this demand as "Sent"
    And user "Jan Kowalski" will see this demand as "Sent"

  Scenario: Send to planner
    And user "Jan Kowalski" is "Planner"
    And I am "Dean"
    When I send a demand
    Then I should see this demand as "Sent"
    And user "Jan Kowalski" will see this demand
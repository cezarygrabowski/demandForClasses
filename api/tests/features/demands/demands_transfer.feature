Feature: Demand transfer
  In order to rationalise process of creating demands for classes
  As a user
  I need to be able to send customized demand

  Background:
    Given There is a demand with subject "Wychowanie fizyczne" "WF"
    And with lecture type "Projekt"
    And There is a user "Jan Kowalski"

  Scenario: Send to District Manager
    And user "Jan Kowalski" has role "District Manager"
    And I have a role "Teacher"
    When I send a demand
    Then I should see this demand as "Sent"
    And user "Jan Kowalski" will see this demand

  Scenario: Send to Institute Director
    And user "Jan Kowalski" has role "Institute Director"
    And I have a role "District Manager"
    When I send a demand
    Then I should see this demand as "Sent"
    And user "Jan Kowalski" will see this demand

  Scenario: Send to Dean
    And user "Jan Kowalski" has role "Dean"
    And I have a role "Institute Director"
    And user "Jan Kowalski" automatically sends demands to planners
    When I send a demand
    Then I should see this demand as "Sent"
    And user "Jan Kowalski" will see this demand as "Sent"

  Scenario: Send to Planner
    And user "Jan Kowalski" has role "Planner"
    And I have a role "Dean"
    When I send a demand
    Then I should see this demand as "Sent"
    And user "Jan Kowalski" will see this demand

  Scenario: Decline demand
    And user "Jan Kowalski" has role "District Manager"
    And I have a role "Teacher"
    And demand is accepted by "Jan Kowalski"
    When I decline demand
    Then I should not see demand in my list
    And user "Jan Kowalski" will see declined demand with status "Declined"

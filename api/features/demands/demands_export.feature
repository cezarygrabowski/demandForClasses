Feature: Demands' export
  In order to rationalise process of creating schedule
  As a planner
  I need to be able to export demands

  Scenario: Export demands
    Given There is a demand with subject "Wychowanie fizyczne" "WF" with lecture type "Projekt"
    And I have a role "Planner"
    When I export accepted demands
    Then Exported Demands should be "exported"

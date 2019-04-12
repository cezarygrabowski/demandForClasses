Feature: Demands' export
 In order to rationalise process of creating schedule
 As a planner
 I need to be able to export demands

 @OneScenarioAtTheTime
 Scenario: Export demands
  Given There is demand with subject "Wychowanie fizyczne" "WF"
  And There is lecturer "Jan Kowalski"
  And There is group "K5C1N1" with type "Studia Niestacjonarne"
  And has following informations:
   | Projekt | accepted by dean | Jan Kowalski | Wychowanie fizyczne | 2019/2020 | K5C1N1 | letni | ITA | WCY | 30 | | | | 10 | | | | 10 | | | | | 10 | |
  And I have role "Planner"
  When I export accepted demands
  Then Exported demands should have status "exported"
  And "second" row of created file should look like that:
    | Projekt | accepted | Jan Kowalski | Wychowanie fizyczne | 2019/2020 | K5C1N1 | Niestandardowa | letni | ITA | WCY | 30 | | | | 10 | | | | 10 | | | | | 10 | |

Feature: Demands' export
 In order to rationalise process of creating schedule
 As a planner
 I need to be able to export demands

 @export
 Scenario: Export demands
  Given There is demand with subject "Wychowanie fizyczne" "WF"
  And There is a place with building "65" and room "100"
  And There is a place with building "105" and room "205"
  And There is user "Jan Kowalski"
  And There is group "K5C1N1" with type "Studia niestacjonarne"
  And has following informations:
   | Projekt | | Jan Kowalski | Wychowanie fizyczne | 2019/2020 | K5C1N1 | letni | ITA | WCY | 30 | 20 | 65 | 100 | 10 | 105 | 205 |
  And user "Jan Kowalski" has role "Planista"
  When user "Jan Kowalski" export accepted demands
  Then Exported demands should have status "Wyeksportowane przez planiste"
  And created file should have following informations:
    | Projekt | Jan Kowalski | Wychowanie fizyczne | 2019/2020 | K5C1N1 | Studia niestacjonarne | letni | ITA | WCY | 20 | 65 | 100 | 10 | 105 | 205 |

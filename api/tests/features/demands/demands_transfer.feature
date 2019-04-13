Feature: Demand transfer
  In order to rationalise process of creating demands for classes
  As a user
  I need to be able to send customized demand

  Background:
    Given There is demand with subject "Wychowanie fizyczne" "WF"
    And lecture set "Projekt"
    And There is user "Jan Kowalski"
    And There is user "Stefan Nowacki"
    And I change demand lecturer to "Jan Kowalski" in "Projekt" lecture set

  @transfer
  Scenario Outline: Demand acceptation flow
    Given user <user1> has role <role1>
    And user <user2> has role <role2>
    When user <user2> accept a demand
    Then user <user2> should see this demand
    And user <user1> should see this demand

    Examples:
      | user1           | role1                | user2            | role2                |
      | "Jan Kowalski"  | "Nauczyciel"         | "Stefan Nowacki" | "Kierownik zakładu"  |
      | "Jan Kowalski"  | "Kierownik zakładu"  | "Stefan Nowacki" | "Nauczyciel"         |
      | "Jan Kowalski"  | "Dyrektor instytutu" | "Stefan Nowacki" | "Kierownik zakładu"  |
      | "Jan Kowalski"  | "Dziekan"            | "Stefan Nowacki" | "Dyrektor instytutu" |
      | "Jan Kowalski"  | "Planista"           | "Stefan Nowacki" | "Dziekan"            |
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
    And demand has status "Przypisane przez kierownika zakładu"

  @transfer
  Scenario Outline: Accept Demand flow
    Given user <user1> has role <role1>
    And user <user2> has role <role2>
    When user <user2> accept a demand
    Then user <user1> should see this demand
    And user <user2> should not see this demand

    Examples:
      | user1           | role1                | user2            | role2                |
      | "Jan Kowalski"  | "Dyrektor instytutu" | "Stefan Nowacki" | "Kierownik zakładu"  |
      | "Jan Kowalski"  | "Dziekan"            | "Stefan Nowacki" | "Dyrektor instytutu" |
      | "Jan Kowalski"  | "Planista"           | "Stefan Nowacki" | "Dziekan"            |

  @transfer
  Scenario: Teacher decline demand
    Given user "Jan Kowalski" has role "Nauczyciel"
    And user "Stefan Nowacki" has role "Kierownik zakładu"
    When user "Jan Kowalski" decline a demand
    Then user "Jan Kowalski" should not see this demand
    And user "Stefan Nowacki" should see this demand
    And demand should have status "Odrzucone przez nauczyciela"

  @transfer
  Scenario: Assign demand
    Given user "Jan Kowalski" has role "Nauczyciel"
    And user "Stefan Nowacki" has role "Kierownik zakładu"
    When user "Stefan Nowacki" assign a demand to user "Jan Kowalski" in type "Projekt"
    Then user "Jan Kowalski" should see this demand
    And user "Stefan Nowacki" should not see this demand

  @transfer
  Scenario: Teacher accept demand
    Given user "Jan Kowalski" has role "Nauczyciel"
    And user "Stefan Nowacki" has role "Kierownik zakładu"
    When user "Jan Kowalski" accept a demand
    Then user "Jan Kowalski" should see this demand
    And user "Stefan Nowacki" should see this demand
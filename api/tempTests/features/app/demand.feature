Feature: Demands
  In order to rationalise process of sending demands for classes
  As a user
  I need to be able to customize a demand
`
  Scenario:
    Given There is a demand with subject "Wychowanie fizyczne"
    And There is a lecturer "Jan Kowalski"
    When I change demand lecturer to "Jan Kowalski"
    And I save the demand
    Then I should see in database that demand has lecturer "Jan Kowalski"

#
#  Scenario: List 2 files in a directory
#    Given I have a file named "hammond"
#    When I run "ls"
#    Then I should see "john" in the output
#    And I should see "hammond" in the output
#
#  Scenario: List 1 file and 1 directory
#    Given I have a dir named "ingen"
#    When I run "ls"
#    Then I should see "john" in the output
#    And I should see "ingen" in the output

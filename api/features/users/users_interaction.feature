Feature: User profile
  In order to get user's details
  As a user
  I need to be able to access user's profile

  Scenario: Get user's qualifications
    Given There is a user "Jan Kowalski"
    And user "Jan Kowalski" has following qualifications:
      | Wychowanie fizyczne    |
      | Elektronika            |
      | Podstawy programowania |
    When I request profile informations of "Jan Kowalski"
    Then I should see following qualifications:
      | Wychowanie fizyczne    |
      | Elektronika            |
      | Podstawy programowania |

  Scenario: Get user's undistributed hours in given week
    Given There is a user "Jan Kowalski"
    And user "Jan Kowalski" has "15" undistributed hours in "3" week
    When I request profile informations of "Jan Kowalski"
    Then I should see that user has "15" undistributed hours in "3" week



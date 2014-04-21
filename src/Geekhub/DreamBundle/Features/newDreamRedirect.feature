Feature: Check anonymous on redirect to invite page.

  @javascript
  Scenario: Create dream as anonymous.
    Given I am on "/"
    And I am on "/dream/new"
    And I should see "Ви не залогінені в системі."
    And I am login as "darthVader" with password "darthVader"
    Then I am on "/dream/new"
    And I should see "create or edit your dream"
    When I am on "/logout"
    Then I am on "/dream/new"
    And I should see "Ви не залогінені в системі."

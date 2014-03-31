Feature: faq
  In order
  As
  I need

  @javascript
  Scenario: Test faq tabs
    Given I am on "/faq"
    And I should see "Хто такий C-3PO?"
    And I should not see "Хто такий Квай-Гон Джинн?"
    When I follow "Питання 2"
    Then I should not see "Хто такий C-3PO?"
    And I should see "Хто такий Квай-Гон Джинн?"
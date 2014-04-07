Feature: Create dream feature
  In order to get opotunity to create dream
  As register user
  I need to be able to fill form and submit it

#  Scenario: Login
#    Given I am on "/login"
#    And I fill in "username" with "admin"
#    And I fill in "password" with "admin"
#    When I press "_submit"
#    Then should be on "/"
#    And should see "SUPER ADMIN"

#  Scenario: Test google.com
#    Given I am on "http://google.com"
#    Then print last response
#
#  @javascript
#  Scenario: Test google.com
#    Given I am on "http://google.com"
#    Then print last response

  @javascript
  Scenario: Try to create dream
    Given I am login as "darthVader" with password "darthVader"
    And I am on "/dreams"
    And I press "Нові"
    And I should see "Зірка смерті"
    And I press "Популярні"
    And I should see "Зірка смерті"
    And I should see "Політ на Місяць"
    And I press "На стадії втілення"
    And I should see "Політ на Місяць"
    And I press "Втілені"
    And I should see "Новенький зореліт"
    And I should see "Lorem ipsum"

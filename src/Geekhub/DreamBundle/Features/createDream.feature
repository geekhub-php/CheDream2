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
    And I am on "/dream/new"
    And I should see "create or edit your dream"
    And I press "Створити"
    And I wait 1 seconds
    And I press "btnDreamSubmit"
    Then I should be on "/dream/new"
    And I should see "Поле не повинно бути порожнім"

  @javascript
  Scenario: Try to create dream
    Given I am login as "darthVader" with password "darthVader"
    And I am on "/dream/new"
    And I should see "create or edit your dream"
    And I fill in "newDreamForm_title" with "Hello! This is my first dream!"
    And I wait 3 seconds
    And I fill in tinymce "newDreamForm_description" with "Lorem Ipsum is simply dummy text of the printing and typesetting industry."
    And I fill in hidden "newDreamForm_tags" with "Hello, world"
    And I press "Створити"
    And I wait 1 seconds
    And I press "btnDreamSubmit"
    And I wait 1 seconds
    Then I should not see "Поле не повинно бути порожнім"

  @javascript
  Scenario: GeekhubDreamBundle:DreamController:editDreamAction
    Given I am login as "darthVader" with password "darthVader"
    And I am on "/users/3"
    Then I should see "Hello! This is my first dream!"
    And I am on "/logout"
    And I am on "/users/3"
    Then I should not see "Hello! This is my first dream!"


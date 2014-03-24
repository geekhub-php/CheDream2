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
    Given I am on homepage
    And print last response
    And I am login as "admin" with password "admin"
    And I am on "/dream/new"
    And I should see "create or edit your dream"
    And I press "Створити"
    And I wait 1 seconds
    And I press "btnDreamSubmit"
    Then I should be on "/dream/new"
    And I should see "Поле не повинно бути порожнім"

  @javascript
  Scenario: Try to create dream
    Given I am login as "admin" with password "admin"
    And I am on "/dream/new"
    And I should see "create or edit your dream"
    And I fill in "newDreamForm_title" with "Hello! This is my first dream!"
    And I fill in tinymce "Lorem Ipsum is simply dummy text of the printing and typesetting industry."
    And I fill in hidden "newDreamForm_tags" with "Hello, world"
    And I press "Створити"
    And I wait 1 seconds
    And I press "btnDreamSubmit"
    Then I should not see "Поле не повинно бути порожнім"
    And I wait 5 seconds
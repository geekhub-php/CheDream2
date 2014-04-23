Feature: Update user's email feature
  In order to create an account
  As a user
  I need to be able to register and to fill an existing email and phone number and submit it

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
  Scenario: Try to log in with fake email
    Given I am on "/login"
    And I fill in "username" with "darthVader"
    And I fill in "password" with "darthVader"
    When I press "_submit"
    Then I should be on "/"
    And I am on "/user/edit"
    And I fill in "newUserForm[firstName]" with "DARTH"
    And I fill in "newUserForm[lastName]" with "VADER"
    And I select "1" from "newUserForm[birthday][day]"
    And I select "Feb" from "newUserForm[birthday][month]"
    And I select "1987" from "newUserForm[birthday][year]"
    And I fill in "newUserForm[phone]" with "+380981234567"
    And I fill in "newUserForm[email]" with "absolutelyUniqueDarthVadersEmail@gmail.com"
    When I press "Save changes"
    And I should see "DARTH VADER"
    And I am on "/user/edit"
    And I should see "DARTH"
    And I should see "VADER"
    And I should see "1"
    And I should see "Feb"
    And I should see "1987"
    #And I should see "380981234567"
    And I should see "absolutelyUniqueDarthVadersEmail@gmail.com"

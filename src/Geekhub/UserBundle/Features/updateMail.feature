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
    Then I should be on "/connect-account/user/updateContacts"
    And I fill in "newUserForm[email]" with "realDarthVadersEmail@mail.ru"
    When I press "_submit"
    Then should be on "/"
    And I am on "/users/3"
    And I should see "Darth Vader"
  
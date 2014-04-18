Feature: Merge user's accounts with the same email feature
  In order to register a second account
  As a user
  I need to be able to merge my last account and the new one

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
  Scenario: Try to merge two accounts
    Given I am on "/login"
    And I fill in "username" with "chewbacca"
    And I fill in "password" with "chewbacca"
    When I press "_submit"
    Then I should be on "/user/updateContacts"
    And I fill in "newUserForm[phone]" with "+380671234567"
    And I fill in "newUserForm[email]" with "chewbacca@mail.ru"
    And I fill in "newUserForm[lastName]" with " "
    When I press "_submit"
    Then should be on "/"
    And I am on "/users/4"
    And I should see "Новенький зореліт"
    And I should see "зореліт (1 штук)"
    And I should see "робота2 (1 днів)"
    And I should not see "Швидка розвідка"
    And I should not see "ресурс8 (1 тон)"
    #And I should not see "Зірка смерті"
    And I should not see "робота23 (1 днів)"
    And I am on "/logout"
    And I am on "/login"
    And I fill in "username" with "c3pio"
    And I fill in "password" with "c3pio"
    When I press "_submit"
    Then I should be on "/user/updateContacts"
    And I fill in "newUserForm[phone]" with "+380671234567"
    And I fill in "newUserForm[email]" with "chewbacca@mail.ru"
    And I fill in "newUserForm[lastName]" with " "
    When I press "_submit"
    And I should see "Адреса електронної пошти, яку Ви вказали, співпадає з одним із зареєстрованих користувачів"
    And I am on "/logout"
    And I am Executing last merging accounts query
    And I am on "/login"
    And I fill in "username" with "chewbacca"
    And I fill in "password" with "chewbacca"
    When I press "_submit"
    Then should be on "/"
    And I am on "/users/4"
    And I should see "Новенький зореліт"
    And I should see "зореліт (1 штук)"
    And I should see "робота2 (1 днів)"
    And I should see "Швидка розвідка"
    And I should see "ресурс8 (1 тон)"
    And I should see "Зірка смерті"
    And I should see "робота23 (1 днів)"

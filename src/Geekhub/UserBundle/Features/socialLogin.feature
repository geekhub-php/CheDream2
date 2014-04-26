Feature: Login from the social network's accountse
  In order to use a site
  As a user
  I need to be authorized by the social networks oauth providers

  @javascript
  Scenario: Try to login from facebook
    Given I am on "/connect/facebook"
    And I fill in "email" with "chedreamtester@gmail.com"
    And I fill in "pass" with "ch3dr3am"
    When I press "login"
    #When I press "ok"
    Then I should be on "/user/updateContacts"
    And I fill in "newUserForm[phone]" with "+380671234567"
    And I fill in "newUserForm[email]" with "chedreamtester@gmail.com"
    When I press "_submit"
    Then should be on "/"

  @javascript
  Scenario: Try to login from vkontakte
    Given I am on "/connect/vkontakte"
    And I fill in "email" with "chedreamtester@gmail.com"
    And I fill in "pass" with "ch3dr3am"
    When I press "install_allow"
    #When I press "install_allow"
    Then I should be on "/user/updateContacts"
    And I fill in "newUserForm[phone]" with "+380671234568"
    And I fill in "newUserForm[email]" with "chedreamtester2@gmail.com"
    When I press "_submit"
    Then should be on "/"

  @javascript
  Scenario: Try to login from odnoklassniki
    Given I am on "/connect/odnoklassniki"
    And I fill in "field_email" with "chedreamtester@gmail.com"
    And I fill in "field_password" with "ch3dr3am"
    When I press "Log in"
    #When I press "button_accept_request"
    Then I should be on "/user/updateContacts"
    And I fill in "newUserForm[phone]" with "+380671234569"
    And I fill in "newUserForm[email]" with "chedreamtester3@gmail.com"
    When I press "_submit"
    Then should be on "/"

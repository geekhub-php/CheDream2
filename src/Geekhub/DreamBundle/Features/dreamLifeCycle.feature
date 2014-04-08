Feature: Create dream life cycle  feature
  In order to get opotunity to create dream
  As register user
  I need to be able to fill form and submit it

  @javascript
  Scenario: Login as Yoda and create Dream
    Given I am login as "yoda" with password "yoda"
    And I wait 3 seconds
    Then I should see "FAQ"
    And I wait 2 seconds
    Given I am on "/dream/new"
    And I should see "create or edit your dream"
#    And I attach the file "src/Geekhub/DreamBundle/DataFixtures/ORM/images/starship.jpg" to "fileupload-poster"
    And I fill in "newDreamForm_title" with "Dream for testing dream life cycle"
    And I wait 1 seconds
    And I fill in tinymce "newDreamForm_description" with "Lorem Ipsum is simply dummy text of the printing and typesetting industry."
    And I wait 1 seconds
    And I fill in "newDreamForm_phone" with "+3089666"
    And I wait 1 seconds
    And I fill in "newDreamForm_expiredDate" with "2014-07-15"
    And I wait 1 seconds
    And I fill in hidden "newDreamForm_tags" with "Hello, world"
    And I wait 1 seconds
    And I follow "Додати фінансові витрати"
    And I fill in "newDreamForm_dreamFinancialResources_0_title" with "fin1"
    And I fill in "newDreamForm_dreamFinancialResources_0_quantity" with "1500"
    And I wait 1 seconds
    And I follow "Додати абладнання та інструменти"
    And I fill in "newDreamForm_dreamEquipmentResources_0_title" with "equip1"
    And I fill in "newDreamForm_dreamEquipmentResources_0_quantity" with "7"
    And I select "kg" from "newDreamForm_dreamEquipmentResources_0_quantityType"
    And I wait 1 seconds
    And I press "Створити"
    And I wait 1 seconds
    And I press "btnDreamSubmit"
    And I wait 5 seconds
    Given I am on "/logout"

  @javascript
  Scenario: Login as Admin and check Dream
    Given I am login as "admin" with password "admin"
    And I wait 3 seconds
    Then I should see "FAQ"
    Given I am on "/admin"
    And I should see "Cherkassy Dream Admin Panel"
    Given I am on "/admin/geekhub/dream/dream/list"
    And I should see "Dream for testing dream life cycle"
    And I follow "dream-for-testing-dream-life-cycle"
    And I wait 3 seconds
    Then the "newDreamForm_title" field should contain "Dream for testing dream life cycle"
    And I press "dream-admin-reject-modal"
    And I wait 2 seconds
    And I should see "Опис причини повернення мрії на доопрацювання"
    And I fill in tinymce "rejectedDreamForm_rejectedDescription" with "bad description Dream. Rejected"
    And I press "dream-admin-reject"
    And I wait 3 seconds
    Given I am on "/logout"

  @javascript
  Scenario: Login as Yoda and fixed rejected Dream
    Given I am login as "yoda" with password "yoda"
    And I wait 3 seconds
    Then I should see "FAQ"
    Given I am on "/dream/edit/dream-for-testing-dream-life-cycle"
    And I should see "bad description Dream. Rejected"
    And I fill in tinymce "newDreamForm_description" with "new Dream description"
    And I press "dream-edit-all"
    And I wait 3 seconds
    Given I am on "/logout"

  @javascript
  Scenario: Login as Admin and check Dream again
    Given I am login as "admin" with password "admin"
    And I wait 3 seconds
    Then I should see "FAQ"
    Given I am on "/admin"
    And I should see "Cherkassy Dream Admin Panel"
    Given I am on "/admin/geekhub/dream/dream/list"
    And I should see "Dream for testing dream life cycle"
    And I follow "dream-for-testing-dream-life-cycle"
    And I wait 3 seconds
    Then the "newDreamForm_title" field should contain "Dream for testing dream life cycle"
    And the "newDreamForm_description" field should contain "<p>new Dream description</p>"
    And I press "dream-admin-confirm"
    And I wait 3 seconds
    Given I am on "/logout"

  @javascript
  Scenario: Login as Yoda and start collecting resources
    Given I am login as "yoda" with password "yoda"
    And I wait 3 seconds
    Then I should see "Dream for testing dream life cycle"
    And I follow "dream-for-testing-dream-life-cycle"
    And I should see "Dream for testing dream life cycle"
    And I press "dream-edit-dream-modal"
    And I wait 2 seconds
    And I should see "Увага."
    And I press "Закрити"
    And I press "finFormContributorShow"
    And I should see "Стаття витрат"
    And I fill in "financialContributeForm_quantity" with "150"
    And I press "Підтримати"
    And I wait 2 seconds
    And I should see "150 грн."
    And I press "finFormContributorShow"
    And I should see "Стаття витрат"
    And I fill in "financialContributeForm_quantity" with "200"
    And I check "financialContributeForm_hiddenContributor"
    And I press "Підтримати"
    And I wait 2 seconds
    And I should see "350 грн."
    And I wait 2 seconds
    And I press "dream-start-collect-res-modal"
    And I wait 2 seconds
    And I should see "Детально опишіть інформацію щодо допомоги"
    And I fill in tinymce "implementingDreamForm_implementedDescription" with "start collecting resource"
    And I press "dream-start-implementing-btn"
    And I wait 2 seconds
    Then I should see "start collecting resource"
    And I press "dream-edit-dream-modal"
    And I wait 2 seconds
    And I should see "Увага."
    And I press "Закрити"
    And I wait 2 seconds
    Then I press "dream-edit-yellowBlock-modal"
    And I wait 2 seconds
    And I should see "Редагувати інформацію щодо допомоги."
    And the "implementingDreamForm_implementedDescription" field should contain "<p>start collecting resource</p>"
    And I fill in tinymce "implementingDreamForm_implementedDescription" with "start collecting resource edited"
    Then I press "dream-edit-yellowBlock"
    And I wait 2 seconds
    Then I should see "start collecting resource edited"
    And I wait 2 seconds
    Then I press "dream-finish-collect-res-modal"
    And I wait 2 seconds
    And I should see "Опишіть ваші досягнення"
    And I fill in tinymce "completingDreamForm_completedDescription" with "Yes! we did it"
    And I press "dream-start-completed-btn"
    And I wait 2 seconds
    And I should not see "start collecting resource edited"
    And I should not see "dream-finish-collect-res-modal"
    And I should not see "dream-edit-yellowBlock-modal"
    And I should not see "dream-edit-dream-modal"
    And I should see "Yes! we did it"
    And I wait 5 seconds
    Given I am on "/logout"

  @javascript
  Scenario: Login as Admin and confirm completed Dream
    Given I am login as "admin" with password "admin"
    And I wait 3 seconds
    Then I should see "FAQ"
    And I should not see "Dream for testing dream life cycle"

    Given I am on "/admin"
    And I should see "Cherkassy Dream Admin Panel"
    Given I am on "/admin/geekhub/dream/dream/list"
    And I should see "Dream for testing dream life cycle"
    And I follow "dream-for-testing-dream-life-cycle"
    And I wait 3 seconds
    Then the "newDreamForm_title" field should contain "Dream for testing dream life cycle"
    And the "newDreamForm_description" field should contain "<p>new Dream description</p>"
    And I press "dream-admin-confirm-completed"

#    And I wait 2 seconds
#    And should see "Dream for testing dream life cycle" in the "carousel-caption" element

    And I wait 5 seconds
    Given I am on "/logout"


Feature: Assert disabled button can be checked
  In order to ensure that a button is disabled
  As a developer
  I should be able to assert the button's disabled

  Scenario: Assert the button is disabled
    Given I am on "/button-disabled.html"
     Then The "Disabled Button" button should be disabled
     Then The "Enabled Button" button should be enabled

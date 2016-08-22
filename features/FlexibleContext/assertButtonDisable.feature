Feature: Assert disabled button can be checked
  In order to ensure that a button is disabled
  As a developer
  I should be able to assert the button's disabled

  Background:
    Given I am on "/button-disabled.html"

  Scenario: Assert the button is disabled
     Then the "Disabled Button" button should be disabled

  Scenario: Assert the button is enabled
     Then the "Enabled Button" button should be enabled

  Scenario: Assert the enabled button can be disabled
      When the "Enabled Button" button should be enabled
       And I press "Disable Enabled Button"
      Then the "Enabled Button" button should be disabled

  Scenario: Assert the disabled button can be enabled
      When the "Disabled Button" button should be disabled
       And I press "Enable Disabled Button"
      Then the "Disabled Button" button should be Enabled

  Scenario: Throw exception if the button is enabled but should be disabled
     When I assert that the "Disabled Button" button should be enabled
     Then the assertion should throw an ExpectationException
      And the assertion should fail with the message "The button, Disabled Button, was disabled, but it should not have been disabled."

  Scenario: Throw exception if the button is disabled but should be enabled
     When I assert that the "Enabled Button" button should be disabled
     Then the assertion should throw an ExpectationException
      And the assertion should fail with the message "The button, Enabled Button, was not disabled, but it should have been disabled."

  Scenario: Throw exception if the button can't be found
     When I assert that the "Unknown Button" button should be disabled
     Then the assertion should throw an ExpectationException
      And the assertion should fail with the message "Could not find button for Unknown Button"

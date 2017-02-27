Feature:  Assert Radio Button
  In order to ensure that radio button elements behave as expected
  As a developer
  I should have radio button behavior assertions

  Background:
    Given I am on "/radio-buttons.html"

  Scenario: Assert the radio button checked
    Then the "Checked" radio button should be checked

  Scenario: Assert the radio button not checked
    Then the "Not Checked" radio button should not be checked

  Scenario: Assertion fails reliably if checked radio button is expected as unchecked
    When I assert that the "Checked" radio button should not be checked
    Then the assertion should throw an ExpectationException
     And the assertion should fail with the message 'Radio button "Checked" is checked, but it should not be.'

  Scenario: Assertion fails reliably if unchecked checkbox is expected as checked
    When I assert that the "Not Checked" radio button should be checked
    Then the assertion should throw an ExpectationException
     And the assertion should fail with the message 'Radio button "Not Checked" is not checked, but it should be.'

  Scenario: Assert the radio button by injected values
    Given the following is stored as "Page":
      | first_radio_button  | Radio Button 1 |
      | second_radio_button | Radio Button 2 |
      | third_radio_button  | Radio Button 3 |
     Then the "(the first_radio_button of the Page)" radio button should be checked
      And the "(the second_radio_button of the Page)" radio button should not be checked
      And the "(the third_radio_button of the Page)" radio button should not be checked

  Scenario: Assert the radio button can be checked by injected values
    Given the following is stored as "Page":
      | first_radio_button  | Radio Button 1 |
      | second_radio_button | Radio Button 2 |
      | third_radio_button  | Radio Button 3 |
      And the "(the second_radio_button of the Page)" radio button should not be checked
     When I check radio button "(the second_radio_button of the Page)"
     Then the "(the second_radio_button of the Page)" radio button should be checked

Feature:  Assert Checkbox Checked
  In order to ensure that checkbox elements behave as expected
  As a developer
  I should have checkbox behavior assertions

  Background:
    Given I am on "/checkboxes.html"

  Scenario: Assert the checkbox checked
    Then the "Checked" checkbox should be checked

  Scenario: Assert the checkbox not checked
    Then the "Not Checked" checkbox should not be checked

  Scenario: Assert the checkbox by injected values
    Given the following is stored as "Page":
      | first_checkbox  | Checked   |
      | second_checkbox | Not Checked   |
     Then the "(the first_checkbox of the Page)" checkbox should be checked
      And the "(the second_checkbox of the Page)" checkbox should not be checked

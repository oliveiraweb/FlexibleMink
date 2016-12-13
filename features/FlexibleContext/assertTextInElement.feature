Feature: Assert Element Contains Texts
  In order to ensure that text appear in an element on the page
  As a developer
  I should be able to assert text in element

  Background:
    Given I am on "/assert-text-in-element.html"

  Scenario: Developer Can Assert Text Exists in Element
    Then I should see "This is a div" in the "#divWithText" element

  Scenario: Developer Can Assert Text Not Exist in Element
    Then I should not see "This is a div" in the "#emptyDiv" element

  Scenario: Assertion fails reliably if text is not found in the element when expected to be found
    When I assert that I should not see "This is a div" in the "#divWithText" element
    Then the assertion should throw an ElementTextException
     And the assertion should fail with the message 'The text "This is a div" appears in the text of the element matching css "#divWithText", but it should not.'

  Scenario: Assertion fails reliably if text is found in the element when expected to be not found
    When I assert that I should see "This is a div" in the "#emptyDiv" element
    Then the assertion should throw an ElementTextException
    And the assertion should fail with the message 'The text "This is a div" was not found in the text of the element matching css "#emptyDiv".'

  Scenario: Assertion the text in element by injected value
    Given the following is stored as "Page":
      | first_div  | emptyDiv    |
      | second_div | divWithText |
      And the following is stored as "Content":
        | text  | This is a div |
     Then I should see "(the text of the Content)" in the "#(the second_div of the Page)" element
      And I should not see "(the text of the Content)" in the "#(the first_div of the Page)" element

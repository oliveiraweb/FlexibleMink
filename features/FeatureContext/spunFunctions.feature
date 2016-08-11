Feature: Overridden Functions Using Spinners
  In order to properly test pages that may have delayed loads
  As a developer
  I need basic page assertions to retry themselves automatically

  Scenario Outline: Basic functions spin properly
    Given I am on "index.html"
      And I will be on "basic-content.html" in 3 seconds
     When I assert that <Assertion>
     Then the assertion should pass

    Examples:
      | Assertion                                                 |
      | I should see "some content"                               |
      | I should not see "Don't check for this text!"             |
      | I should see ".a-class content" in the ".a-class" element |

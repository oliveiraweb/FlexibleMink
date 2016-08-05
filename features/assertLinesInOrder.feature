Feature: Assert Lines in Order
  In order to ensure that content appears in the correct order
  As a developer
  I should have flexible line order assertions

  Background:
    Given I am on "/order.html"

    Scenario: Step passes if lines are in the expected order
      Then I should see the following lines in order:
         | Line one   |
         | Line two   |
         | Line three |

    Scenario: Step injects values properly
      Given the following is stored as "list":
          | first_entry  | Line one   |
          | second_entry | Line two   |
          | third_entry  | Line three |
       Then I should see the following lines in order:
          | (the first entry of the list)  |
          | (the second entry of the list) |
          | (the third entry of the list)  |

    Scenario: Assertion fails reliably if lines are out of the expected order
      When I assert that I should see the following lines in order:
         | Line two |
         | Line one |
      Then the assertion should throw an ExpectationException
       And the assertion should fail with the message "Line 'Line one' came before its expected predecessor"

    Scenario: Assertion fails reliably if a given line is not present
      When I assert that I should see the following lines in order:
         | Line two |
         | Megatron |
      Then the assertion should throw an ExpectationException
       And the assertion should fail with the message "Line 'Megatron' was not found on the page"

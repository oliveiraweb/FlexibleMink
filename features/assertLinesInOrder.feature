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

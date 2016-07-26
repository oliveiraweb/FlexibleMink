Feature: Table Context
  In order to test HTML table structures and content
  As a developer
  I should have extendable table assertions

  Scenario: Developer can Test if a Table Exist
    When I am on "/table.html"
    Then I should see table "population-table"

  Scenario: Developer Can Test For Number of Table Rows and Columns
     Given I am on "/table.html"
      Then the table "population-table" should have 2 columns
      Then the table "population-table" should have 3 rows

  Scenario: Developer Can Test for Table Column Titles
    Given I am on "/table.html"
     Then the table "population-table" should have the following column titles:
        | Country    |
        | Population |

  Scenario: Developer Can Test for Cell Values in the Table
    Given I am on "/table.html"
     Then the table "population-table" should have "Country" at (1,1) in the header
      And the table "population-table" should have "1,377,672,822" at (1,2) in the body
      And the table "population-table" should have "2,994,159,700" at (1,2) in the footer

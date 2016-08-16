Feature: Container Context
  In order to ensure that labelled page elements contain specific text
  As a developer
  I need to be able to assert the contents of containers

  Background:
    Given I am on "containers.html"

    Scenario Outline: Assertion passes if the container contains the given text
      Then I should see "<Text>" in the "<Label>" container

      Examples:
        | Label     | Text              |
        | Red pond  | A few red fish    |
        | Blue pond | Lots of blue fish |

    Scenario Outline: Assertion fails if the container does not contain the given text
      When I assert that I should see "<Text>" in the "<Label>" container
      Then the assertion should throw an ExpectationException
       And the assertion should fail with the message "'<Text>' was not found in the '<Label>' container"

      Examples:
        | Label     | Text              |
        | Red pond  | Lots of blue fish |
        | Blue pond | A few red fish    |

    Scenario: Assertion fails if the given container does not exist
      When I assert that I should see "anything" in the "empty" container
      Then the assertion should throw an ExpectationException
       And the assertion should fail with the message "The 'empty' container was not found"

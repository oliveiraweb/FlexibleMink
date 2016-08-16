Feature: Store Context
  In order to reference data for use later on in a test
  As a developer
  I need to be able to store data in contexts

  Scenario Outline: Variables can be stored and accessed
    Given the value <Value> is stored as "Thingtastic Thing"
     When I assert that the "Thingtastic Thing" should be <Value>
     Then the assertion should pass

    Examples:
      | Value |
      | 2     |
      | 0     |
      | 4.7   |
      | true  |
      | false |
      | "car" |

  Scenario: Variables can be aliased
    Given the value "Bob" is stored as "Thing"
     When I refer to the "Thing" as "Person"
     Then the "Person" should be "Bob"
      And the "Thing" should be "Bob"

  Scenario: Assertion fails if the stored thing is not what's expected
    Given the value "me, Dio!" is stored as "this"
     When I assert that the "this" should be "a normal adventure"
     Then the assertion should throw an Exception
      And the assertion should fail with the message "Expected this to be 'a normal adventure', but it was 'me, Dio!'"

  Scenario Outline: Nth stored value can be retrieved
    Given the value "Rowlett" is stored as "Starter"
      And the value "Litten" is stored as "Starter"
      And the value "Popplio" is stored as "Starter"
     Then the "<Key>" should be "<Value>"

    Examples:
      | Key         | Value   |
      | 1st Starter | Rowlett |
      | 2nd Starter | Litten  |
      | 3rd Starter | Popplio |

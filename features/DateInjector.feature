Feature: Inject Dates
  In order to set preconditions or make assertions on relative dynamic dates
  As a developer
  I need to be able to dynamically insert relative dynamic dates into my Behat steps

  Background: Date/Time can be injected in step
    Given the datetime is "2022-10-28"

  Scenario: Date/Time can be injected in step
    Given the value "The first Saturday was (a date/time of 'First Saturday')" is stored as "Message"
    Then the "Message" should be "The first Saturday was 2022-10-29 00:00:00"

  Scenario Outline: Date/Time can be injected in step in scenario outline
    Given the value "<Message>" is stored as "Message"
     Then the "Message" should be "<Expected>"

  Examples:
    | Message                                                          | Expected                                       |
    | The first Saturday was (a date/time of 'First Saturday')         | The first Saturday was 2022-10-29 00:00:00     |
    | Last Sunday was (a date/time of 'Last Sunday')                   | Last Sunday was 2022-10-23 00:00:00            |
    | Today at noon is (a date/time of 'Today at noon')                | Today at noon is 2022-10-28 12:00:00           |
    | The party is at (a date/time of 'Tomorrow at 3:30PM'). Be there! | The party is at 2022-10-29 15:30:00. Be there! |
    | MURICA (a date/time of 'July 4th 1776' formatted as 'a US date') | MURICA 07/04/1776                              |

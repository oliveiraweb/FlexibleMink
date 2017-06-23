@clearAlertsWhenFinished
Feature: Alert context
  In order to ensure that JavaScript alerts work as expected
  As a developer
  I need to be able to assert various states of alerts

  Scenario: Developer can test when alerts appear
    Given there is a confirm containing "alert text"
     When I assert that I should see an alert containing "alert text"
     Then the assertion should pass

  Scenario Outline: Developer can confirm and cancel alerts
    Given there is a confirm containing "do you confirm??"
     When I <Action> the alert
     Then the confirm should return <Result>

    Examples:
      | Action  | Result |
      | confirm | true   |
      | cancel  | false  |

  Scenario: Alert assertion fails properly when no alert exists
    Given there are no alerts on the page
     When I assert that I should see an alert containing "alert text"
     Then the assertion should throw an ExpectationException
      And the assertion should fail with the message "No alert is open"

  Scenario: Alert assertion fails properly when the alert text does not match
    Given there is an alert containing "actual text"
     When I assert that I should see an alert containing "something else"
     Then the assertion should throw an ExpectationException
      And the assertion should fail with the message "Text 'something else' not found in alert"

  Scenario Outline: Developer can test the results of filling in prompts
    Given there is a prompt containing "prompt?"
     Then I should see an alert containing "prompt?"
     When I fill "<Text>" into the prompt
      And I confirm the alert
     Then the prompt should return "<Text>"

    Examples:
      | Text                                                                                    |
      | Please do not prompt me like this.                                                      |
      | I was browsing Tumblr in another tab, you asshole.                                      |
      | I SWEAR IF YOU PROMPT ME ONE MORE TIME I WILL RAIN ETERNAL HELLFIRE UPON YOUR SORRY ASS |

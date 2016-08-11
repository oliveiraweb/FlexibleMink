Feature: Alert context
  In order to ensure that JavaScript alerts work as expected
  As a developer
  I need to be able to assert various states of alerts

  Scenario Outline: Developer can test when alerts appear
    Given I am on "alert.html"
     When I assert that I should see an alert containing "alert text"
     Then the assertion should pass
     When I <Action> the alert
     Then I should see "<Action> selected"

    Examples:
      | Action  |
      | confirm |
      | cancel  |

  Scenario: Alert assertion fails properly when no alert exists
    Given I am on "index.html"
     When I assert that I should see an alert containing "alert text"
     Then the assertion should throw an ExpectationException
      And the assertion should fail with the message "No alert is open"

  Scenario: Alert assertion fails properly when no alert exists
    Given I am on "alert.html"
     When I assert that I should see an alert containing "alert butt"
     Then the assertion should throw an ExpectationException
      And the assertion should fail with the message "Text 'alert butt' not found in alert"
          # Selenium bombs out if we leave an alert up at the end of a test
     When I confirm the alert

  Scenario Outline: Developer can test the results of filling in prompts
    Given I am on "prompt.html"
     Then I should see an alert containing "prompt?"
     When I fill "<Text>" into the prompt
      And I confirm the alert
     Then I should see "entered <Text> in prompt"

    Examples:
      | Text                                                                                    |
      | Please do not prompt me like this.                                                      |
      | I was browsing Tumblr in another tab, you asshole.                                      |
      | I SWEAR IF YOU PROMPT ME ONE MORE TIME I WILL RAIN ETERNAL HELLFIRE UPON YOUR SORRY ASS |

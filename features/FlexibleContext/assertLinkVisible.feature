Feature: Assert Link is visible
  In order to ensure that the link is visible
  As a developer
  I should have visible link assertion

  Background:
    Given I am on "/link.html"

  Scenario: Step passes if link is visible
    Then the "I am a link" link is visible

  Scenario: Step passes if link without href is visible
    Then the "an anchor tag without href" link is visible

  Scenario: Step fails if link is invisible
    When I assert that the "an invisible anchor tag" link is visible
    Then the assertion should throw an ExpectationException
     And the assertion should fail with the message "No visible link found for 'an invisible anchor tag'"

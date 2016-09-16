Feature: Assert Link is clickable
  In order to ensure that the link is clickable
  As a developer
  I should have link clickable assertion

  Background:
    Given I am on "/link.html"

  Scenario: Step passes if link is clickable
    Then I follow "I am a link"

  Scenario: Step passes if link without href
    Then I follow "an anchor tag without href"

  Scenario: Step fails if link is invisible
    When I assert that I follow "an invisible anchor tag"
    Then the assertion should throw an ExpectationException
     And the assertion should fail with the message "No visible link found for 'an invisible anchor tag'"

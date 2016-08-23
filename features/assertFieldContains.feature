Feature: Assert Field Contains
  In order to test content inside a field
  As a developer
  I should have a waitfor on the assertion

 Scenario: Developer can test for asserting a field that is updated with a delay
    When I am on "/assert-field-contains.html"
    When I press "Fill in delayed field with a delay"
    Then the "Delayed field" field should contain "delayed value"

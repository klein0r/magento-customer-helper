Feature: Logon Fail

  Scenario: The customer failed to logon

    Given the cache is clean
    And a customer with mail "development@mkleine.de" exists
    And the table "helpcustomers_faillog" is empty

    When a customer tries to login with email "development@mkleine.de" and password "test"

    Then an entry must exist in table "helpcustomers_faillog"
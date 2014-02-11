Feature: Logon Fail

  Scenario: The customer failed to logon

    Given the cache is clean
    And a customer with mail "user@test.de" exists
    And the table "helpcustomers_faillog" is empty

    When a customer tries to login with email "user@test.de" and password "anypassword"
    Then the table "helpcustomers_faillog" must contain "1" rows

  Scenario: The customer failed to logon and was able to login afterwards

    Given the cache is clean
    And a customer with mail "user@test.de" exists
    And the table "helpcustomers_faillog" is empty

    When a customer tries to login with email "user@test.de" and password "anypassword"
    Then the table "helpcustomers_faillog" must contain "1" rows

    When a customer tries to login with email "user@test.de" and password "validpassword"
    Then the table "helpcustomers_faillog" must contain "0" rows

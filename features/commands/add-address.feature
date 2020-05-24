Feature:
    I should be able to add an address

    Scenario Outline:
        I should be able to add a BTC or a ETH address

        When I execute the command "address:add" with parameters:
            | --currency | <currency> |
            | --address  | <address>  |
      
        Then the command should succeed
        And the command output should contain "[OK] Address added"

        Examples:
            | currency | address |
            | BTC      | addr1   |
            | ETH      | addr2   |

    Scenario:
        I try to add an address with an invalid currency and address

        When I execute the command "address:add" with parameters:
            | --currency | LTC |
            | --address  |     |

        Then the command should succeed
        And the command output should contain '[ERROR] Error on "currency": Invalid currency'
        And the command output should contain '[ERROR] Error on "address": This value should not be blank.'

    Scenario:
        I try to add an address that already exists

        When I execute the command "address:add" with parameters:
            | --currency | BTC                                |
            | --address  | 1FeexV6bAHb8ybZjqQMjJrcCrHGW9sb6uF |

        Then the command should succeed
        And the command output should contain "[ERROR] Address already exists"

Feature:
      I should be able to remove an address
  
    Scenario Outline:
        I should be able to remove an address
       
        When I execute the command "address:remove" with parameters:
            | --address | <address> |
      
        Then the command should succeed
        And the command output should contain "[OK] Address removed"

        Examples:
            | address                                    |
            # Bitcoin
            | 1FeexV6bAHb8ybZjqQMjJrcCrHGW9sb6uF         |
            # Ethereum
            | 0x3f5CE5FBFe3E9af3971dD833D26bA9b5C936f0bE |
      
    Scenario:
        I should get an error if I try to remove an address that  does not exist
        
        When I execute the command "address:remove" with parameters:
            | --address | foobar |
      
        Then the command should fail
        And the command output should contain "[ERROR] Address not found"

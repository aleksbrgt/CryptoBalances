Feature:
    I should be able to get balances from apis

    Scenario:
        All the api calls are successful
        The balances are displayed and amount converted

        # Get the exchange rates
        Given guzzle will return a 200 response with body:
        """
        {
            "BTC": {
                "EUR": 8438.98
            },
            "ETH": {
                "EUR": 190.33
            }
        }
        """
        # Get the BTC addresses balances
        And guzzle will return a 200 response with body:
        """
        {
            "data": {
                "addresses": {
                    "1FeexV6bAHb8ybZjqQMjJrcCrHGW9sb6uF": {
                        "balance": 33993654
                    },
                    "1VeMPNgEtQGurwjW2WsYXQaw4boAX5k6S": {
                        "balance": 0
                    }
                }
            }
        }
        """
        # Get the ETH addresses balances
        And guzzle will return a 200 response with body:
        """
        {
            "result": [
                {
                    "account": "0x3f5CE5FBFe3E9af3971dD833D26bA9b5C936f0bE",
                    "balance": "772110320549244685"
                }
            ]
        }
        """

        When I execute the command "balance"

        Then the command should succeed
        And the command output should contain "+--------------------------------------- Balances ------+----------------------+---------+"
        And the command output should contain "| address                                    | currency | amount               | EUR     |"
        And the command output should contain "+--------------------------------------------+----------+----------------------+---------+"
        And the command output should contain "| 1FeexV6bAHb8ybZjqQMjJrcCrHGW9sb6uF         | BTC      | 0.33993654           | 2868.71 |"
        And the command output should contain "| 1VeMPNgEtQGurwjW2WsYXQaw4boAX5k6S          | BTC      | 0.00000000           | 0.00    |"
        And the command output should contain "| 0x3f5CE5FBFe3E9af3971dD833D26bA9b5C936f0bE | ETH      | 0.772110320549244685 | 146.95  |"
        And the command output should contain "+--------------------------------------------+----------+----------------------+---------+"
        And the command output should contain "| TOTAL                                                                        | 3015.66 |"
        And the command output should contain "+--------------------------------------------+----------+----------------------+---------+"

    Scenario:
        Fetching the currency conversion rates succeeds
        Addresses are not found on the apis, a 404 is returned
        I should get the balances, but the amount are not converted

        # Get the exchange rates
        Given guzzle will return a 200 response with body:
        """
        {
            "BTC": {
                "EUR": 8438.98
            },
            "ETH": {
                "EUR": 190.33
            }
        }
        """
        # Get the BTC addresses balances
        And guzzle will return a 404 response with body:
        """
        ERROR
        """
        # Get the ETH addresses balances
        And guzzle will return a 404 response with body:
        """
        ERROR
        """

        When I execute the command "balance"

        Then the command should succeed
        And the command output should contain "+------------------------------ Balances ----+----------+--------+------+"
        And the command output should contain "| address                                    | currency | amount | EUR  |"
        And the command output should contain "+--------------------------------------------+----------+--------+------+"
        And the command output should contain "| 1FeexV6bAHb8ybZjqQMjJrcCrHGW9sb6uF         | BTC      | N/A    | N/A  |"
        And the command output should contain "| 1VeMPNgEtQGurwjW2WsYXQaw4boAX5k6S          | BTC      | N/A    | N/A  |"
        And the command output should contain "| 0x3f5CE5FBFe3E9af3971dD833D26bA9b5C936f0bE | ETH      | N/A    | N/A  |"
        And the command output should contain "+--------------------------------------------+----------+--------+------+"
        And the command output should contain "| TOTAL                                                          | 0.00 |"
        And the command output should contain "+--------------------------------------------+----------+--------+------+"

    Scenario:
        Fetching the currency conversion rates succeeds
        Addresses are not found on the apis, the responses are empty
        I should get the balances, but the amount are not converted

        # Get the exchange rates
        Given guzzle will return a 200 response with body:
        """
        {
            "BTC": {
                "EUR": 8438.98
            },
            "ETH": {
                "EUR": 190.33
            }
        }
        """
        # Get the BTC addresses balances
        And guzzle will return a 200 response with body:
        """
        {
            "data": {
                "addresses": {}
            }
        }
        """
        # Get the ETH addresses balances
        And guzzle will return a 200 response with body:
        """
        {
            "result": []
        }
        """

        When I execute the command "balance"

        Then the command should succeed
        And the command output should contain "+------------------------------ Balances ----+----------+--------+------+"
        And the command output should contain "| address                                    | currency | amount | EUR  |"
        And the command output should contain "+--------------------------------------------+----------+--------+------+"
        And the command output should contain "| 1FeexV6bAHb8ybZjqQMjJrcCrHGW9sb6uF         | BTC      | N/A    | N/A  |"
        And the command output should contain "| 1VeMPNgEtQGurwjW2WsYXQaw4boAX5k6S          | BTC      | N/A    | N/A  |"
        And the command output should contain "| 0x3f5CE5FBFe3E9af3971dD833D26bA9b5C936f0bE | ETH      | N/A    | N/A  |"
        And the command output should contain "+--------------------------------------------+----------+--------+------+"
        And the command output should contain "| TOTAL                                                          | 0.00 |"
        And the command output should contain "+--------------------------------------------+----------+--------+------+"

    Scenario:
        Fetching the currency conversion rates failed
        Other API calls succeed
        I should get the balances, but the amount are not converted

        # Get the exchange rates
        Given guzzle will return a 500 response with body:
        """
        ERROR
        """
        # Get the BTC addresses balances
        And guzzle will return a 200 response with body:
        """
        {
            "data": {
                "addresses": {
                    "1FeexV6bAHb8ybZjqQMjJrcCrHGW9sb6uF": {
                        "balance": 33993654
                    },
                    "1VeMPNgEtQGurwjW2WsYXQaw4boAX5k6S": {
                        "balance": 0
                    }
                }
            }
        }
        """
        # Get the ETH addresses balances
        And guzzle will return a 200 response with body:
        """
        {
            "result": [
                {
                    "account": "0x3f5CE5FBFe3E9af3971dD833D26bA9b5C936f0bE",
                    "balance": "772110320549244685"
                }
            ]
        }
        """

        When I execute the command "balance"

        Then the command should succeed

        And the command output should contain "+------------------------------------- Balances --------+----------------------+------+"
        And the command output should contain "| address                                    | currency | amount               | EUR  |"
        And the command output should contain "+--------------------------------------------+----------+----------------------+------+"
        And the command output should contain "| 1FeexV6bAHb8ybZjqQMjJrcCrHGW9sb6uF         | BTC      | 0.33993654           | N/A  |"
        And the command output should contain "| 1VeMPNgEtQGurwjW2WsYXQaw4boAX5k6S          | BTC      | 0.00000000           | N/A  |"
        And the command output should contain "| 0x3f5CE5FBFe3E9af3971dD833D26bA9b5C936f0bE | ETH      | 0.772110320549244685 | N/A  |"
        And the command output should contain "+--------------------------------------------+----------+----------------------+------+"
        And the command output should contain "| TOTAL                                                                        | 0.00 |"
        And the command output should contain "+--------------------------------------------+----------+----------------------+------+"

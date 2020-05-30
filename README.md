# CryptoBalances
Tool to get balances from multiple ETH or BTC addresses.

## Installation
The easiest way is to run the `Makefile` command:
```
make dev.build
```
It will create the docker container and install the dependencies.

[Etherscan](http://etherscan.io) is used to get information for Ethereum addresses.
Creating an account can be needed to get an api key to bypass limitations.
This api key can be added the file `.env.local` to set the `ETHERSCAN_API_KEY` env variable.

## Usage
### Adding addresses
Addresses are added with `make address.add` or `bin/console address:add`, it's an interactive command.

### Removing addresses
Addresses are removed with `make address.remove` or `bin/console address:remove`, it's an interactive command.

### Getting the balances
The balance is displayed with `make balance` or `bin/console balance`, all the addresses stored are displayed, with their corresponding balance and their value in euros.

If getting the balance for an address fails, either the address does not actually exist, or an issue occurred when trying to get the balance, the balance and value are displayed as `N/A`.

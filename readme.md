# Loggy McLogface

Loggy McLogface is a small application for users to manage their passwords (Think 1password, LastPass, Dashlane etc).
It has been built from scratch to create a mini framework which could be expanded further.

## Installation

Use php 7.3 or greater. You will also need an active apache & mysql server.

First update the env.php file with a connection to your
mysql server, leave the DATABASE_SCHEMA untouched.

Once done, the first time you run the command below and go to it in your browser it will
run an instant migration script. This will run once.

If you change the ports drop "8889" and replace it below and when browsing.

```bash
php -S localhost -p=8889
```


## Usage

Just open a browser and go to localhost:8889 then click create account.
This will create your account for you to login.

## License
[MIT](https://choosealicense.com/licenses/mit/)

# Technical test for the Backend Developer profile

As part of the recruitment process, you are required to complete a small technical test to showcase how you tackle a basic programming task.

## Requirements

- PHP 8.0 or later
- `composer` command (See [Composer Installation](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos))
- Git

## How to Use

### Installation

```bash
$ git clone git@github.com:Ezar101/technical-test-backend-developer-profile.git
$ cd /path/to/technical-test-backend-developer-profile/

# Env variables
cp .env .env.local

# Edit .env.local
vim .env.local

# Install composant
$ composer install
$ php bin/console assets:install --symlink
$ php bin/console cache:clear
$ php bin/console doctrine:migrations:migrate --no-interaction

# Run Server
$ php -S localhost:8000 -t public # Based to php server
$ symfony server:start # Based to symfony server 
```

### Useful commands

```bash
# Run symfony develop server
$ symfony console server:start

# Composer (e.g. composer update)
$ composer update

# SF console
$ php bin/console
```

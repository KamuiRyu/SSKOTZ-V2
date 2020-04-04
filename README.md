## Official link: https://www.sskotz.com
This is link official to final project result

# [Bedrock](https://roots.io/bedrock/)

* Better folder structure
* Dependency management with [Composer](https://getcomposer.org)
* Easy WordPress configuration with environment specific files
* Environment variables with [Dotenv](https://github.com/vlucas/phpdotenv)
* Autoloader for mu-plugins (use regular plugins as mu-plugins)
* Enhanced security (separated web root and secure passwords with [wp-password-bcrypt](https://github.com/roots/wp-password-bcrypt))

## Requirements

* PHP >= 7.1
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

## Installation

1. Create a Database,
2. import db/latest.sql
3. Configure .env to match DB settings
4. Run `composer install`
5. Point your webserver to the /web directory 
6. If you don't have a webserver: `cd web && php -S localhost:8000`
7. Happy days!

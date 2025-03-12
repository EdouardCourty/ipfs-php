PHP = php

PHPUNIT = vendor/bin/phpunit
PHPSTAN = vendor/bin/phpstan
PHPCSFIXER = vendor/bin/php-cs-fixer

COMPOSER = composer

install:
	$(COMPOSER) install

test:
	$(PHP) $(PHPUNIT) tests

phpstan:
	$(PHP) $(PHPSTAN) analyse

phpcs:
	$(PHP) $(PHPCSFIXER) fix . --config .php-cs-fixer.php

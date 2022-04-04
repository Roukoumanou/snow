install: ## Installation et démarrage de l'application
	php bin/console d:d:d -f
	php bin/console d:d:c
	php bin/console d:s:u -f
	php bin/console d:f:l --no-interaction
	rm -Rf vendor
	rm -Rf var
	rm composer.lock
	composer install
	php -S localhost:8000 -t public
	
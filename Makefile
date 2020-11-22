SYMFONY_CONSOLE=php bin/console

install: vendor doctrine npm yarn_encore
.PHONY: install

vendor:
	rm -rf vendor/
	composer install
.PHONY: vendor

doctrine:
	$(SYMFONY_CONSOLE) doctrine:schema:drop --full-database --force
	$(SYMFONY_CONSOLE) doctrine:migration:migrate --no-interaction
.PHONY: doctrine

npm:
	rm -rf node_modules/
	yarn install
.PHONY: npm

yarn_encore:
	yarn encore dev
.PHONY: yarn_encore

yarn_encore_watch:
	yarn encore dev --watch
.PHONY: yarn_encore_watch

prod:
	rm -rf vendor/
	rm -rf node_modules/
	yarn install
	yarn encore production
	composer install --no-dev --optimize-autoloader
	APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
.PHONY: prod

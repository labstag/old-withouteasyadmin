.DEFAULT_GOAL   := help
STACK           := labstag
NETWORK         := proxynetwork
PHPFPM          := $(STACK)_phpfpm
PHPFPMFULLNAME  := $(PHPFPM).1.$$(docker service ps -f 'name=$(PHPFPM)' $(PHPFPM) -q --no-trunc | head -n1)
MARIADB         := $(STACK)_mariadb
MARIADBFULLNAME := $(MARIADB).1.$$(docker service ps -f 'name=$(MARIADB)' $(MARIADB) -q --no-trunc | head -n1)
APACHE          := $(STACK)_apache
APACHEFULLNAME  := $(APACHE).1.$$(docker service ps -f 'name=$(APACHE)' $(APACHE) -q --no-trunc | head -n1)
%:
	@:

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'


package-lock.json: package.json
	npm install

node_modules: package-lock.json
	npm install

apps/composer.lock: apps/composer.json
	docker exec $(PHPFPMFULLNAME) make composer.lock
	
apps/vendor: apps/composer.lock
	docker exec $(PHPFPMFULLNAME) make vendor

apps/.env: apps/.env.dist ## Install .env
	docker exec $(PHPFPMFULLNAME) make .env

bdd-fixtures: ## fixtures
	docker exec $(PHPFPMFULLNAME) make bdd-fixtures

bdd-migrate: ## migrate database
	docker exec $(PHPFPMFULLNAME) make bdd-migrate

bdd-validate: ## bdd validate
	docker exec $(PHPFPMFULLNAME) make bdd-validate

composer-suggests: ## suggestions package pour PHP
	docker exec $(PHPFPMFULLNAME) make composer-suggests

composer-outdated: ## Packet php outdated
	docker exec $(PHPFPMFULLNAME) make composer-outdated

composer-dev-ci: ## Installation version de dev
	cd apps && make composer-dev

composer-update: ## COMPOSER update
	docker exec $(PHPFPMFULLNAME) make composer-update

composer-validate: ## COMPOSER validate
	docker exec $(PHPFPMFULLNAME) make composer-validate

composer-validate-ci: ## COMPOSER validate
	cd apps && make composer-validate

contributors: ## Contributors
	@npm run contributors

contributors-add: ## add Contributors
	@npm run contributors add

contributors-check: ## check Contributors
	@npm run contributors check

contributors-generate: ## generate Contributors
	@npm run contributors generate

docker-create-network: ## create network
	docker network create --driver=overlay $(NETWORK)

docker-deploy: ## deploy
	docker stack deploy -c docker-compose.yml $(STACK)

docker-image-pull: ## Get docker image
	docker image pull redis:6.0.9
	docker image pull mariadb:10.5.1
	docker image pull httpd:2.4.46
	docker image pull phpmyadmin:5.0.2
	docker image pull mailhog/mailhog:v1.0.1
	docker image pull osixia/phpldapadmin:0.9.0
	docker image pull osixia/openldap:1.4.0
	docker image pull dunglas/mercure:v0.10
	docker image pull koromerzhin/phpfpm:7.4.12-symfony

docker-logs: ## logs docker
	docker service logs -f --tail 100 --raw $(WWWFULLNAME)

docker-ls: ## docker service
	@docker stack services $(STACK)

docker-stop: ## docker stop
	@docker stack rm $(STACK)

encore-dev: ## créer les assets en version dev
	@npm run encore-dev

docker-ls: ## docker service
	@docker stack services $(STACK)

encore-dev: node_modules ## créer les assets en version dev
	@npm run encore-dev

env-dev: apps/.env ## Installation environnement dev
	sed -i 's/APP_ENV=prod/APP_ENV=dev/g' apps/.env

env-prod: apps/.env ## Installation environnement prod
	sed -i 's/APP_ENV=dev/APP_ENV=prod/g' apps/.env
	rm -rf apps/vendor
	@make composer-prod -i

git-commit: ## Commit data
	npm run commit

git-check: ## CHECK before
	@make composer-validate -i
	@make composer-outdated -i
	@make bdd-validate -i
	@make contributors-check -i
	@make linter -i
	@git status

install: apps/vendor node_modules apps/.env ## installation
	@make docker-deploy -i
	@make sleep -i
	@make bdd-migrate -i
	@make linter -i

install-dev: install
	@make env-dev
	@make bdd-migrate -i
	@make bdd-features -i

linter: ## Launch all linter
	@make linter-twigcs -i
	@make linter-phpstan -i
	@make linter-phpcpd -i
	@make linter-phpcs -i
	@make linter-phpmd -i
	@make linter-readme -i

linter-readme: ## linter README.md
	@npm run linter-markdown README.md

linter-phpcbf: ## fixe le code PHP à partir d'un standard
	docker exec $(PHPFPMFULLNAME) make linter-phpcbf

linter-phpcpd: phpcpd.phar ## Vérifie s'il y a du code dupliqué
	docker exec $(PHPFPMFULLNAME) make linter-phpcpd

linter-phpcs: ## indique les erreurs de code non corrigé par PHPCBF
	docker exec $(PHPFPMFULLNAME) make linter-phpcs

linter-phpcs-onlywarning: ## indique les erreurs de code non corrigé par PHPCBF
	docker exec $(PHPFPMFULLNAME) make linter-phpcs-onlywarning

linter-phpcs-onlyerror: ## indique les erreurs de code non corrigé par PHPCBF
	docker exec $(PHPFPMFULLNAME) make linter-phpcs-onlyerror

linter-phpcs-onlyerror-ci: ## indique les erreurs de code non corrigé par PHPCBF
	cd apps && make linter-phpcs-onlyerror

linter-phploc: ## phploc
	docker exec $(PHPFPMFULLNAME) make linter-phploc

linter-phpmd: ## indique quand le code PHP contient des erreurs de syntaxes ou des erreurs
	docker exec $(PHPFPMFULLNAME) make linter-phpmd

linter-phpmd-ci: ## indique quand le code PHP contient des erreurs de syntaxes ou des erreurs
	cd apps && make linter-phpmd

linter-phpmnd: ## Si des chiffres sont utilisé dans le code PHP, il est conseillé d'utiliser des constantes
	docker exec $(PHPFPMFULLNAME) make linter-phpmnd

linter-phpmnd-ci: ## Si des chiffres sont utilisé dans le code PHP, il est conseillé d'utiliser des constantes
	cd apps && make linter-phpmnd

linter-phpstan: ## regarde si le code PHP ne peux pas être optimisé
	docker exec $(PHPFPMFULLNAME) make linter-phpstan

linter-phpstan-ci: ## regarde si le code PHP ne peux pas être optimisé
	cd apps && make linter-phpstan

linter-twigcs: ## indique les erreurs de code de twig
	docker exec $(PHPFPMFULLNAME) make linter-twigcs

linter-twigcs-ci: ## indique les erreurs de code de twig
	cd apps &&  make linter-twigcs

logs: ## logs docker
	docker service logs -f --tail 100 --raw $(STACK)

logs-apache: ## logs docker APACHE
	docker service logs -f --tail 100 --raw $(APACHEFULLNAME)

logs-mariadb: ## logs docker MARIADB
	docker service logs -f --tail 100 --raw $(MARIADBFULLNAME)

logs-phpfpm: ## logs docker PHPFPM
	docker service logs -f --tail 100 --raw $(PHPFPMFULLNAME)

sleep: ## sleep
	sleep 90

ssh: ## ssh
	docker exec -ti $(PHPFPMFULLNAME) /bin/bash

tests-behat: ## Lance les tests behat
	docker exec $(PHPFPMFULLNAME) make tests-behat

tests-behat-ci: ## Lance les tests behat
	cd apps && make tests-behat

tests-launch: ## Launch all tests
	@make tests-behat -i
	@make tests-simple-phpunit-unit-integration -i

tests-simple-phpunit-unit-integration: ## lance les tests phpunit
	docker exec $(PHPFPMFULLNAME) make tests-simple-phpunit-unit-integration

tests-simple-phpunit-unit-integration-ci: ## lance les tests phpunit
	cd apps && make tests-simple-phpunit-unit-integration

tests-simple-phpunit: ## lance les tests phpunit
	docker exec $(PHPFPMFULLNAME) make tests-simple-phpunit

.DEFAULT_GOAL        := help
STACK                := labstag
NETWORK              := proxynetwork
PHPFPM               := $(STACK)_phpfpm
PHPFPMFULLNAME       := $(PHPFPM).1.$$(docker service ps -f 'name=$(PHPFPM)' $(PHPFPM) -q --no-trunc | head -n1)
PHPFPMXDEBUG         := $(STACK)_phpfpm-xdebug
PHPFPMXDEBUGFULLNAME := $(PHPFPMXDEBUG).1.$$(docker service ps -f 'name=$(PHPFPMXDEBUG)' $(PHPFPMXDEBUG) -q --no-trunc | head -n1)
MARIADB              := $(STACK)_mariadb
MARIADBFULLNAME      := $(MARIADB).1.$$(docker service ps -f 'name=$(MARIADB)' $(MARIADB) -q --no-trunc | head -n1)
APACHE               := $(STACK)_apache
APACHEFULLNAME       := $(APACHE).1.$$(docker service ps -f 'name=$(APACHE)' $(APACHE) -q --no-trunc | head -n1)
.PHONY               := help assets bdd composer contributors docker encore env geocode git install linter logs messenger sleep ssh tests translations workflow-png
SUPPORTED_COMMANDS   := bdd composer contributors docker encore env geocode git install linter logs messenger sleep ssh tests workflow-png
SUPPORTS_MAKE_ARGS   := $(findstring $(firstword $(MAKECMDGOALS)), $(SUPPORTED_COMMANDS))
ifneq "$(SUPPORTS_MAKE_ARGS)" ""
  COMMAND_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(COMMAND_ARGS):;@:)
endif

%:
	@:

help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

package-lock.json: package.json
	@npm install

node_modules: package-lock.json
	@npm install

dump:
	@mkdir dump

mariadb_data:
	@mkdir mariadb_data

apps/composer.lock: apps/composer.json
	@docker exec $(PHPFPMFULLNAME) make composer.lock

apps/vendor: apps/composer.lock
	@docker exec $(PHPFPMFULLNAME) make vendor

apps/.env: apps/.env.dist ## Install .env
	@cp apps/.env.dist apps/.env

assets:
	@docker exec $(PHPFPMFULLNAME) make assets

bdd: ## Scripts for BDD
ifeq ($(COMMAND_ARGS),fixtures)
	@docker exec $(PHPFPMFULLNAME) make bdd fixtures
else ifeq ($(COMMAND_ARGS),migrate)
	@docker exec $(PHPFPMFULLNAME) make bdd migrate
else ifeq ($(COMMAND_ARGS),validate)
	@docker exec $(PHPFPMFULLNAME) make bdd validate
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make bdd ARGUMENT"
	@echo "---"
	@echo "fixtures: fixtures"
	@echo "migrate: migrate database"
	@echo "validate: bdd validate"
endif

composer: ## Scripts for composer
ifeq ($(COMMAND_ARGS),suggests)
	@docker exec $(PHPFPMFULLNAME) make composer suggests
else ifeq ($(COMMAND_ARGS),outdated)
	@docker exec $(PHPFPMFULLNAME) make composer outdated
else ifeq ($(COMMAND_ARGS),fund)
	@docker exec $(PHPFPMFULLNAME) make composer fund
else ifeq ($(COMMAND_ARGS),prod)
	@docker exec $(PHPFPMFULLNAME) make composer prod
else ifeq ($(COMMAND_ARGS),dev)
	@docker exec $(PHPFPMFULLNAME) make composer dev
else ifeq ($(COMMAND_ARGS),update)
	@docker exec $(PHPFPMFULLNAME) make composer update
else ifeq ($(COMMAND_ARGS),validate)
	@docker exec $(PHPFPMFULLNAME) make composer validate
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make composer ARGUMENT"
	@echo "---"
	@echo "suggests: suggestions package pour PHP"
	@echo "outdated: Packet php outdated"
	@echo "fund: Discover how to help fund the maintenance of your dependencies."
	@echo "prod: Installation version de prod"
	@echo "dev: Installation version de dev"
	@echo "update: COMPOSER update"
	@echo "validate: COMPOSER validate"
endif

contributors: ## Contributors
ifeq ($(COMMAND_ARGS),add)
	@npm run contributors add
else ifeq ($(COMMAND_ARGS),check)
	@npm run contributors check
else ifeq ($(COMMAND_ARGS),generate)
	@npm run contributors generate
else
	@npm run contributors
endif

docker: ## Scripts docker
ifeq ($(COMMAND_ARGS),create-network)
	@docker network create --driver=overlay $(NETWORK)
else ifeq ($(COMMAND_ARGS),deploy)
	@docker stack deploy -c docker-compose.yml $(STACK)
else ifeq ($(COMMAND_ARGS),image-pull)
	@docker image pull redis:6.0.9
	@docker image pull mariadb:10.5.8
	@docker image pull httpd:2.4.46
	@docker image pull phpmyadmin:5.0.2
	@docker image pull mailhog/mailhog:v1.0.1
	@docker image pull dunglas/mercure:v0.10
	@docker image pull koromerzhin/phpfpm:7.4.12-symfony
else ifeq ($(COMMAND_ARGS),ls)
	@docker stack services $(STACK)
else ifeq ($(COMMAND_ARGS),stop)
	@docker stack rm $(STACK)
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make docker ARGUMENT"
	@echo "---"
	@echo "create-network: create network"
	@echo "deploy: deploy"
	@echo "image-pull: Get docker image"
	@echo "ls: docker service"
	@echo "stop: docker stop"
endif

encore: ## Script for Encore
ifeq ($(COMMAND_ARGS),dev)
	@npm rebuild node-sass
	@npm run encore-dev
else ifeq ($(COMMAND_ARGS),watch)
	@npm run encore-watch
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make encore ARGUMENT"
	@echo "---"
	@echo "dev: créer les assets en version dev"
	@echo "watch: créer les assets en version watch"
endif

env: apps/.env ## Scripts Installation environnement
ifeq ($(COMMAND_ARGS),dev)
	@sed -i 's/APP_ENV=prod/APP_ENV=dev/g' apps/.env
else ifeq ($(COMMAND_ARGS),prod)
	@sed -i 's/APP_ENV=dev/APP_ENV=prod/g' apps/.env
	@rm -rf apps/vendor
	@make composer prod -i
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make env ARGUMENT"
	@echo "---"
	@echo "dev: environnement dev"
	@echo "prod: environnement prod"
endif

geocode: ## Geocode
	@docker exec $(PHPFPMFULLNAME) make geocode $(COMMAND_ARGS)

git: ## Scripts GIT
ifeq ($(COMMAND_ARGS),commit)
	@npm run commit
else ifeq ($(COMMAND_ARGS),check)
	@make composer validate -i
	@make composer outdated -i
	@make bdd validate -i
	@make contributors check -i
	@make linter all -i
	@git status
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make git ARGUMENT"
	@echo "---"
	@echo "commit: Commit data"
	@echo "check: CHECK before"
endif

install: dump mariadb_data apps/.env ## installation
ifeq ($(COMMAND_ARGS),all)
	@make node_modules -i
	@make docker deploy -i
	@make apps/vendor -i
	@make sleep 60 -i
	@make bdd migrate -i
	@make assets -i
	@make encore dev -i
	@make linter -i
else ifeq ($(COMMAND_ARGS),dev)
	@make install all
	@make bdd features -i
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make install ARGUMENT"
	@echo "---"
	@echo "all: common"
	@echo "dev: dev"
endif

linter: ## Scripts Linter
ifeq ($(COMMAND_ARGS),all)
	@make linter twig -i
	@make linter yaml -i
	@make linter phpstan -i
	@make linter phpcpd -i
	@make linter phpcs -i
	@make linter phpmd -i
	@make linter markdown -i
else ifeq ($(COMMAND_ARGS),readme)
	@npm run linter-markdown README.md
else ifeq ($(COMMAND_ARGS),phpcbf)
	@docker exec $(PHPFPMFULLNAME) make linter phpcbf
else ifeq ($(COMMAND_ARGS),phpcpd)
	@docker exec $(PHPFPMFULLNAME) make linter phpcpd
else ifeq ($(COMMAND_ARGS),phpcs)
	@docker exec $(PHPFPMFULLNAME) make linter phpcs
else ifeq ($(COMMAND_ARGS),phpcs-onlywarning)
	@docker exec $(PHPFPMFULLNAME) make linter phpcs-onlywarning
else ifeq ($(COMMAND_ARGS),phpcs-onlyerror)
	@docker exec $(PHPFPMFULLNAME) make linter phpcs-onlyerror
else ifeq ($(COMMAND_ARGS),phploc)
	@docker exec $(PHPFPMFULLNAME) make linter phploc
else ifeq ($(COMMAND_ARGS),phpmd)
	@docker exec $(PHPFPMFULLNAME) make linter phpmd
else ifeq ($(COMMAND_ARGS),phpmnd)
	@docker exec $(PHPFPMFULLNAME) make linter phpmnd
else ifeq ($(COMMAND_ARGS),phpstan)
	@docker exec $(PHPFPMFULLNAME) make linter phpstan
else ifeq ($(COMMAND_ARGS),twig)
	@docker exec $(PHPFPMFULLNAME) make linter twig
else ifeq ($(COMMAND_ARGS),yaml)
	@docker exec $(PHPFPMFULLNAME) make linter yaml
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make linter ARGUMENT"
	@echo "---"
	@echo "linter all: ## Launch all linter"
	@echo "readme: linter README.md"
	@echo "phpcbf: fixe le code PHP à partir d'un standard"
	@echo "phpcpd: Vérifie s'il y a du code dupliqué"
	@echo "phpcs: indique les erreurs de code non corrigé par PHPCBF"
	@echo "phpcs-onlywarning: indique les erreurs de code non corrigé par PHPCBF"
	@echo "phpcs-onlyerror: indique les erreurs de code non corrigé par PHPCBF"
	@echo "phploc: phploc"
	@echo "phpmd: indique quand le code PHP contient des erreurs de syntaxes ou des erreurs"
	@echo "phpmnd: Si des chiffres sont utilisé dans le code PHP, il est conseillé d'utiliser des constantes"
	@echo "phpstan: regarde si le code PHP ne peux pas être optimisé"
	@echo "twig: indique les erreurs de code de twig"
	@echo "yaml: indique les erreurs de code de yaml"
endif

logs: ## Scripts logs
ifeq ($(COMMAND_ARGS),stack)
	@docker service logs -f --tail 100 --raw $(STACK)
else ifeq ($(COMMAND_ARGS),apache)
	@docker service logs -f --tail 100 --raw $(APACHEFULLNAME)
else ifeq ($(COMMAND_ARGS),mariadb)
	@docker service logs -f --tail 100 --raw $(MARIADBFULLNAME)
else ifeq ($(COMMAND_ARGS),phpfpm)
	@docker service logs -f --tail 100 --raw $(PHPFPMFULLNAME)
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make logs ARGUMENT"
	@echo "---"
	@echo "stack: logs stack"
	@echo "apache: APACHE"
	@echo "mariadb: MARIADB"
	@echo "phpfpm: PHPFPM"
endif

messenger: ## Scripts messenger
ifeq ($(COMMAND_ARGS),consule)
	@docker exec -ti $(PHPFPMFULLNAME) make messenger consume
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make messenger ARGUMENT"
	@echo "---"
	@echo "consume: Messenger Consume"
endif

sleep: ## sleep
	@sleep  $(COMMAND_ARGS)

ssh: ## SSH
ifeq ($(COMMAND_ARGS),phpfpm)
	@docker exec -ti $(PHPFPMFULLNAME) /bin/bash
else ifeq ($(COMMAND_ARGS),phpfpm-xdebug)
	@docker exec -ti $(PHPFPMXDEBUGFULLNAME) /bin/bash
else ifeq ($(COMMAND_ARGS),mariadb)
	@docker exec -ti $(MARIADBFULLNAME) /bin/bash
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make ssh ARGUMENT"
	@echo "---"
	@echo "phpfpm:  ssh phpfpm"
	@echo "phpfpm-xdebug: ssh phpfpm xdebug"
	@echo "mariadb: ssh mariadb"
endif

tests: ## Scripts tests
ifeq ($(COMMAND_ARGS),launch)
	@docker exec $(PHPFPMXDEBUGFULLNAME) make tests all
else ifeq ($(COMMAND_ARGS),behat)
	@docker exec $(PHPFPMXDEBUGFULLNAME) make tests behat
else ifeq ($(COMMAND_ARGS),simple-phpunit-unit-integration)
	@docker exec $(PHPFPMXDEBUGFULLNAME) make tests simple-phpunit-unit-integration
else ifeq ($(COMMAND_ARGS),simple-phpunit)
	@docker exec $(PHPFPMXDEBUGFULLNAME) make tests simple-phpunit
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make tests ARGUMENT"
	@echo "---"
	@echo "launch: Launch all tests"
	@echo "behat: Lance les tests behat"
	@echo "simple-phpunit-unit-integration: lance les tests phpunit"
	@echo "simple-phpunit: lance les tests phpunit"
endif

translations: ## update translation
	@docker exec $(PHPFPMXDEBUGFULLNAME) make translations

workflow-png: ## generate workflow png
	@docker exec $(PHPFPMXDEBUGFULLNAME) make workflow-png $(COMMAND_ARGS)
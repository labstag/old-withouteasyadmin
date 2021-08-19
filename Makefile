include make/general/Makefile
STACK   := labstag
NETWORK := proxynetwork
include make/docker/Makefile

DOCKER_EXECPHP := @docker exec $(STACK)_phpfpm.1.$$(docker service ps -f 'name=$(STACK)_phpfpm' $(STACK)_phpfpm -q --no-trunc | head -n1)

COMMANDS_SUPPORTED_COMMANDS := libraries workflow-png tests messenger linter install git env encore composer bdd
COMMANDS_SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(COMMANDS_SUPPORTED_COMMANDS))
ifneq "$(COMMANDS_SUPPORTS_MAKE_ARGS)" ""
  COMMANDS_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(COMMANDS_ARGS):;@:)
endif

init: ## Init project
	@git submodule update --init --recursive --remote

dump:
	@mkdir dump

apps/.env: apps/.env.dist ## Install .env
	@cp apps/.env.dist apps/.env

.PHONY: assets
assets:
	$(DOCKER_EXECPHP) make assets

.PHONY: bdd
bdd: ### Scripts for BDD
ifeq ($(COMMANDS_ARGS),fixtures)
	$(DOCKER_EXECPHP) make bdd fixtures
else ifeq ($(COMMANDS_ARGS),migrate)
	$(DOCKER_EXECPHP) make bdd migrate
else ifeq ($(COMMANDS_ARGS),validate)
	$(DOCKER_EXECPHP) make bdd validate
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make bdd ARGUMENT"
	@echo "---"
	@echo "fixtures: fixtures"
	@echo "migrate: migrate database"
	@echo "validate: bdd validate"
endif

.PHONY: composer
composer: ### Scripts for composer
ifeq ($(COMMANDS_ARGS),suggests)
	$(DOCKER_EXECPHP) make composer suggests
else ifeq ($(COMMANDS_ARGS),outdated)
	$(DOCKER_EXECPHP) make composer outdated
else ifeq ($(COMMANDS_ARGS),fund)
	$(DOCKER_EXECPHP) make composer fund
else ifeq ($(COMMANDS_ARGS),prod)
	$(DOCKER_EXECPHP) make composer prod
else ifeq ($(COMMANDS_ARGS),dev)
	$(DOCKER_EXECPHP) make composer dev
else ifeq ($(COMMANDS_ARGS),u)
	$(DOCKER_EXECPHP) make composer update
else ifeq ($(COMMANDS_ARGS),i)
	$(DOCKER_EXECPHP) make composer install
else ifeq ($(COMMANDS_ARGS),validate)
	$(DOCKER_EXECPHP) make composer validate
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make composer ARGUMENT"
	@echo "---"
	@echo "suggests: suggestions package pour PHP"
	@echo "i: install"
	@echo "outdated: Packet php outdated"
	@echo "fund: Discover how to help fund the maintenance of your dependencies."
	@echo "prod: Installation version de prod"
	@echo "dev: Installation version de dev"
	@echo "u: COMPOSER update"
	@echo "validate: COMPOSER validate"
endif

.PHONY: encore
encore: ### Script for Encore
ifeq ($(COMMANDS_ARGS),dev)
	@npm rebuild node-sass
	@npm run encore-dev
else ifeq ($(COMMANDS_ARGS),watch)
	@npm run encore-watch
else ifeq ($(COMMANDS_ARGS),build)
	@npm run encore-build
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make encore ARGUMENT"
	@echo "---"
	@echo "dev: créer les assets en version dev"
	@echo "watch: créer les assets en version watch"
	@echo "build: créer les assets en version prod"
endif

.PHONY: env
env: apps/.env ### Scripts Installation environnement
ifeq ($(COMMANDS_ARGS),dev)
	@sed -i 's/APP_ENV=prod/APP_ENV=dev/g' apps/.env
else ifeq ($(COMMANDS_ARGS),prod)
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

.PHONY: geocode
geocode: ### Geocode
	$(DOCKER_EXECPHP) make geocode $(COMMANDS_ARGS)

.PHONY: install
install: apps/.env ### installation
ifeq ($(COMMANDS_ARGS),all)
	@make node_modules -i
	@make docker image-pull -i
	@make docker deploy -i
	@make sleep 60 -i
	@make bdd migrate -i
	@make assets -i
	@make encore dev -i
	@make linter all -i
else ifeq ($(COMMANDS_ARGS),dev)
	@make install all -i
	@make bdd fixtures -i
	@make commands -i
	@make env dev -i
else ifeq ($(COMMANDS_ARGS),prod)
	@make install all -i
	@make bdd fixtures -i
	@make commands -i
	@make env prod -i
	@make encore build -i
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make install ARGUMENT"
	@echo "---"
	@echo "all: common"
	@echo "dev: dev"
	@echo "prod: prod"
endif

.PHONY: commands
commands:
	$(DOCKER_EXECPHP) symfony console labstag:install --all
	$(DOCKER_EXECPHP) symfony console labstag:guard-route
	$(DOCKER_EXECPHP) symfony console labstag:workflows-show

.PHONY: linter
linter: ### Scripts Linter
ifeq ($(COMMANDS_ARGS),all)
	@make linter phpfix -i
	@make linter eslint -i
	@make linter stylelint-fix -i
	@make linter twig -i
	@make linter container -i
	@make linter yaml -i
	@make linter phpstan -i
	@make linter phpcs -i
	@make linter phpmd -i
	@make linter readme -i
else ifeq ($(COMMANDS_ARGS),phpaudit)
	@make linter phpcs -i
	@make linter phpmd -i
	@make linter phpmnd -i
	@make linter phpstan -i
else ifeq ($(COMMANDS_ARGS),composer)
	@make composer validate -i
	@make composer outdated -i
else ifeq ($(COMMANDS_ARGS),phpfix)
	@make linter php-cs-fixer -i
	@make linter phpcbf -i
else ifeq ($(COMMANDS_ARGS),readme)
	@npm run linter-markdown README.md
else ifeq ($(COMMANDS_ARGS),stylelint)
	@npm run stylelint
else ifeq ($(COMMANDS_ARGS),stylelint-fix)
	@npm run stylelint-fix
else ifeq ($(COMMANDS_ARGS),jscpd)
	@npm run jscpd
else ifeq ($(COMMANDS_ARGS),jscpd-report)
	@npm run jscpd-report
else ifeq ($(COMMANDS_ARGS),eslint)
	@npm run eslint
else ifeq ($(COMMANDS_ARGS),eslint-fix)
	@npm run eslint-fix
else ifeq ($(COMMANDS_ARGS),php-cs-fixer)
	$(DOCKER_EXECPHP) make linter php-cs-fixer
else ifeq ($(COMMANDS_ARGS),phpcbf)
	$(DOCKER_EXECPHP) make linter phpcbf
else ifeq ($(COMMANDS_ARGS),phpcs)
	$(DOCKER_EXECPHP) make linter phpcs
else ifeq ($(COMMANDS_ARGS),phpcs-onlywarning)
	$(DOCKER_EXECPHP) make linter phpcs-onlywarning
else ifeq ($(COMMANDS_ARGS),phpcs-onlyerror)
	$(DOCKER_EXECPHP) make linter phpcs-onlyerror
else ifeq ($(COMMANDS_ARGS),phploc)
	$(DOCKER_EXECPHP) make linter phploc
else ifeq ($(COMMANDS_ARGS),phpmd)
	$(DOCKER_EXECPHP) make linter phpmd
else ifeq ($(COMMANDS_ARGS),phpmnd)
	$(DOCKER_EXECPHP) make linter phpmnd
else ifeq ($(COMMANDS_ARGS),phpstan)
	$(DOCKER_EXECPHP) make linter phpstan
else ifeq ($(COMMANDS_ARGS),twig)
	$(DOCKER_EXECPHP) make linter twig
else ifeq ($(COMMANDS_ARGS),container)
	$(DOCKER_EXECPHP) make linter container
else ifeq ($(COMMANDS_ARGS),yaml)
	$(DOCKER_EXECPHP) make linter yaml
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make linter ARGUMENT"
	@echo "---"
	@echo "all: ## Launch all linter"
	@echo "composer: composer"
	@echo "readme: linter README.md"
	@echo "phpaudit: AUDIT PHP"
	@echo "phpfix: PHP-CS-FIXER & PHPCBF"
	@echo "stylelint: indique les erreurs dans le code SCSS"
	@echo "stylelint-fix: fix les erreurs dans le code SCSS"
	@echo "eslint: indique les erreurs sur le code JavaScript à partir d'un standard"
	@echo "eslint-fix: fixe le code JavaScript à partir d'un standard"
	@echo "phpcbf: fixe le code PHP à partir d'un standard"
	@echo "php-cs-fixer: fixe le code PHP à partir d'un standard"
	@echo "phpcs: indique les erreurs de code non corrigé par PHPCBF"
	@echo "phpcs-onlywarning: indique les erreurs de code non corrigé par PHPCBF"
	@echo "phpcs-onlyerror: indique les erreurs de code non corrigé par PHPCBF"
	@echo "phploc: phploc"
	@echo "phpmd: indique quand le code PHP contient des erreurs de syntaxes ou des erreurs"
	@echo "phpmnd: Si des chiffres sont utilisé dans le code PHP, il est conseillé d'utiliser des constantes"
	@echo "phpstan: regarde si le code PHP ne peux pas être optimisé"
	@echo "twig: indique les erreurs de code de twig"
	@echo "container: indique les erreurs de code de container"
	@echo "yaml: indique les erreurs de code de yaml"
	@echo "jscpd: Copy paste detector"
	@echo "jscpd-report: Copy paste detector report"
endif

.PHONY: messenger
messenger: ### Scripts messenger
ifeq ($(COMMANDS_ARGS),consume)
	$(DOCKER_EXECPHP) make messenger consume
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make messenger ARGUMENT"
	@echo "---"
	@echo "consume: Messenger Consume"
endif

.PHONY: tests
tests: ### Scripts tests
ifeq ($(COMMANDS_ARGS),launch)
	@$(DOCKER_EXECPHP) make tests all
else ifeq ($(COMMANDS_ARGS),behat)
	@$(DOCKER_EXECPHP) make tests behat
else ifeq ($(COMMANDS_ARGS),simple-phpunit-unit-integration)
	@$(DOCKER_EXECPHP) make tests simple-phpunit-unit-integration
else ifeq ($(COMMANDS_ARGS),simple-phpunit)
	@$(DOCKER_EXECPHP) make tests simple-phpunit
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

.PHONY: translations
translations: ## update translation
	$(DOCKER_EXECPHP) make translations

.PHONY: workflow-png
workflow-png: ### generate workflow png
	$(DOCKER_EXECPHP) make workflow-png $(COMMANDS_ARGS)

.PHONY: libraries
libraries: ### Add libraries
ifeq ($(COMMANDS_ARGS),tarteaucitron)
	wget https://github.com/AmauriC/tarteaucitron.js/archive/refs/tags/v1.9.3.zip
	unzip v1.9.3.zip
	rm v1.9.3.zip
	mv tarteaucitron.js-1.9.3 apps/public/tarteaucitron
else
	@echo "ARGUMENT missing"
	@echo "---"
	@echo "make libraries ARGUMENT"
	@echo "---"
	@echo "tarteaucitron: tarteaucitron"
endif
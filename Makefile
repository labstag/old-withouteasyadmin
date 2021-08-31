include make/general/Makefile
STACK   := labstag
NETWORK := proxynetwork
include make/docker/Makefile

DOCKER_EXECPHP := @docker exec $(STACK)_phpfpm.1.$$(docker service ps -f 'name=$(STACK)_phpfpm' $(STACK)_phpfpm -q --no-trunc | head -n1)

COMMANDS_SUPPORTED_COMMANDS := libraries workflow-png tests messenger linter install git env encore composer bdd setbdd
COMMANDS_SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(COMMANDS_SUPPORTED_COMMANDS))
ifneq "$(COMMANDS_SUPPORTS_MAKE_ARGS)" ""
  COMMANDS_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(COMMANDS_ARGS):;@:)
endif

GREEN := \033[0;32m
RED := \033[0;31m
YELLOW := \033[0;33m
NC := \033[0m
NEED := ${GREEN}%-20s${NC}: %s\n
MISSING :=${RED}ARGUMENT missing${NC}\n
ARGUMENTS := make ${PURPLE}%s${NC} ${YELLOW}ARGUMENT${NC}\n

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
	@printf "${MISSING}"
	@echo "---"
	@printf "${ARGUMENTS}" "bdd"
	@echo "---"
	@printf "${NEED}" "fixtures" "fixtures"
	@printf "${NEED}" "migrate" "migrate database"
	@printf "${NEED}" "validate" "bdd validate"
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
	@printf "${MISSING}"
	@echo "---"
	@printf "${ARGUMENTS}" "composer"
	@echo "---"
	@printf "${NEED}" "suggests" "suggestions package pour PHP"
	@printf "${NEED}" "i" "install"
	@printf "${NEED}" "outdated" "Packet php outdated"
	@printf "${NEED}" "fund" "Discover how to help fund the maintenance of your dependencies."
	@printf "${NEED}" "prod" "Installation version de prod"
	@printf "${NEED}" "dev" "Installation version de dev"
	@printf "${NEED}" "u" "COMPOSER update"
	@printf "${NEED}" "validate" "COMPOSER validate"
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
	@printf "${MISSING}"
	@echo "---"
	@printf "${ARGUMENTS}" "encore"
	@echo "---"
	@printf "${NEED}" "dev" "créer les assets en version dev"
	@printf "${NEED}" "watch" "créer les assets en version watch"
	@printf "${NEED}" "build" "créer les assets en version prod"
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
	@printf "${MISSING}"
	@echo "---"
	@printf "${ARGUMENTS}" "env"
	@echo "---"
	@printf "${NEED}" "dev" "environnement dev"
	@printf "${NEED}" "prod" "environnement prod"
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
	@printf "${MISSING}"
	@echo "---"
	@printf "${ARGUMENTS}" "install"
	@echo "---"
	@printf "${NEED}" "all" "common"
	@printf "${NEED}" "dev" "dev"
	@printf "${NEED}" "prod" "prod"
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
	@printf "${MISSING}"
	@echo "---"
	@printf "${ARGUMENTS}" "linter"
	@echo "---"
	@printf "${NEED}" "all" "## Launch all linter"
	@printf "${NEED}" "composer" "composer"
	@printf "${NEED}" "readme" "linter README.md"
	@printf "${NEED}" "phpaudit" "AUDIT PHP"
	@printf "${NEED}" "phpfix" "PHP-CS-FIXER & PHPCBF"
	@printf "${NEED}" "stylelint" "indique les erreurs dans le code SCSS"
	@printf "${NEED}" "stylelint-fix" "fix les erreurs dans le code SCSS"
	@printf "${NEED}" "eslint" "indique les erreurs sur le code JavaScript à partir d'un standard"
	@printf "${NEED}" "eslint-fix" "fixe le code JavaScript à partir d'un standard"
	@printf "${NEED}" "phpcbf" "fixe le code PHP à partir d'un standard"
	@printf "${NEED}" "php-cs-fixer" "fixe le code PHP à partir d'un standard"
	@printf "${NEED}" "phpcs" "indique les erreurs de code non corrigé par PHPCBF"
	@printf "${NEED}" "phpcs-onlywarning" "indique les erreurs de code non corrigé par PHPCBF"
	@printf "${NEED}" "phpcs-onlyerror" "indique les erreurs de code non corrigé par PHPCBF"
	@printf "${NEED}" "phploc" "phploc"
	@printf "${NEED}" "phpmd" "indique quand le code PHP contient des erreurs de syntaxes ou des erreurs"
	@printf "${NEED}" "phpmnd" "Si des chiffres sont utilisé dans le code PHP, il est conseillé d'utiliser des constantes"
	@printf "${NEED}" "phpstan" "regarde si le code PHP ne peux pas être optimisé"
	@printf "${NEED}" "twig" "indique les erreurs de code de twig"
	@printf "${NEED}" "container" "indique les erreurs de code de container"
	@printf "${NEED}" "yaml" "indique les erreurs de code de yaml"
	@printf "${NEED}" "jscpd" "Copy paste detector"
	@printf "${NEED}" "jscpd-report" "Copy paste detector report"
endif

.PHONY: messenger
messenger: ### Scripts messenger
ifeq ($(COMMANDS_ARGS),consume)
	$(DOCKER_EXECPHP) make messenger consume
else
	@printf "${MISSING}"
	@echo "---"
	@printf "${ARGUMENTS}" "messenger"
	@echo "---"
	@printf "${NEED}" "consume" "Messenger Consume"
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
	@printf "${MISSING}"
	@echo "---"
	@printf "${ARGUMENTS}" "tests"
	@echo "---"
	@printf "${NEED}" "launch" "Launch all tests"
	@printf "${NEED}" "behat" "Lance les tests behat"
	@printf "${NEED}" "simple-phpunit-unit-integration" "lance les tests phpunit"
	@printf "${NEED}" "simple-phpunit" "lance les tests phpunit"
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
	@printf "${MISSING}"
	@echo "---"
	@printf "${ARGUMENTS}" "libraries"
	@echo "---"
	@printf "${NEED}" "tarteaucitron" "tarteaucitron"
endif

DATABASE_BDD := $(shell more docker-compose.yml | grep DATABASE_BDD: | sed -e "s/^.*DATABASE_BDD:[[:space:]]//")
DATABASE_USER := $(shell more docker-compose.yml | grep DATABASE_USER: | sed -e "s/^.*DATABASE_USER:[[:space:]]//")
DATABASE_PASSWORD := $(shell more docker-compose.yml | grep DATABASE_PASSWORD: | sed -e "s/^.*DATABASE_PASSWORD:[[:space:]]//")
SETBDD := cd lampy && make setbdd USERNAME="${DATABASE_USER}" BDD="${DATABASE_BDD}" PASSWORD="${DATABASE_PASSWORD}"

bddset: ## Set bdd
	@echo "$(SETBDD)"
	$(shell $(SETBDD))
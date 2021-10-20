include make/general/Makefile
STACK   := labstag
NETWORK := proxynetwork
include make/docker/Makefile

PHPFPMFULLNAME := $(STACK)_phpfpm.1.$$(docker service ps -f 'name=$(STACK)_phpfpm' $(STACK)_phpfpm -q --no-trunc | head -n1)

DOCKER_EXECPHP := @docker exec $(PHPFPMFULLNAME)

PHP_EXEC := ${DOCKER_EXECPHP} php -d memory_limit=-1
SYMFONY_EXEC := ${DOCKER_EXECPHP} symfony console
COMPOSER_EXEC := ${DOCKER_EXECPHP} symfony composer

COMMANDS_SUPPORTED_COMMANDS := libraries workflow-png tests messenger linter install git env encore composer bdd setbdd geocode
COMMANDS_SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(COMMANDS_SUPPORTED_COMMANDS))
ifneq "$(COMMANDS_SUPPORTS_MAKE_ARGS)" ""
  COMMANDS_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(COMMANDS_ARGS):;@:)
endif

init: ## Init project
	@git submodule update --init --recursive --remote

apps/.env: apps/.env.dist ## Install .env
	@cp apps/.env.dist apps/.env

apps/phploc.phar:
	$(DOCKER_EXECPHP) wget https://phar.phpunit.de/phploc-7.0.2.phar -O phploc.phar

apps/php-cs-fixer.phar:
	$(DOCKER_EXECPHP) wget https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v3.1.0/php-cs-fixer.phar
apps/phpmd.phar:
	$(DOCKER_EXECPHP) wget https://github.com/phpmd/phpmd/releases/download/2.10.2/phpmd.phar

apps/phpcbf.phar:
	$(DOCKER_EXECPHP) wget https://github.com/squizlabs/PHP_CodeSniffer/releases/download/3.6.0/phpcbf.phar

apps/phpcs.phar:
	$(DOCKER_EXECPHP) wget https://github.com/squizlabs/PHP_CodeSniffer/releases/download/3.6.0/phpcs.phar

apps/phpstan.phar:
	$(DOCKER_EXECPHP) wget https://github.com/phpstan/phpstan/releases/download/0.12.98/phpstan.phar

apps/phpDocumentor.phar:
	$(DOCKER_EXECPHP) wget https://github.com/phpDocumentor/phpDocumentor/releases/download/v3.1.2/phpDocumentor.phar

apps/behat.phar:
	$(DOCKER_EXECPHP) wget https://github.com/Behat/Behat/releases/download/v3.8.1/behat.phar

phar: apps/phploc.phar apps/phpmd.phar apps/php-cs-fixer.phar apps/phpcbf.phar apps/phpcs.phar apps/phpstan.phar apps/phpDocumentor.phar apps/behat.phar

apps/composer.lock: isdocker apps/composer.json
	${COMPOSER_EXEC} update

apps/vendor: isdocker apps/composer.json
	${COMPOSER_EXEC} install --no-progress --prefer-dist --optimize-autoloader
	
.PHONY: assets
assets: isdocker
	${SYMFONY_EXEC} assets:install public --symlink --relative

.PHONY: bdd
bdd: isdocker ### Scripts for BDD
ifeq ($(COMMANDS_ARGS),fixtures)
	${SYMFONY_EXEC} doctrine:fixtures:load -n
else ifeq ($(COMMANDS_ARGS),migrate)
	${SYMFONY_EXEC} doctrine:migrations:migrate -n
else ifeq ($(COMMANDS_ARGS),validate)
	${SYMFONY_EXEC} doctrine:schema:validate
else
	@printf "${MISSING_ARGUMENTS}" "bdd"
	$(call array_arguments, \
		["fixtures"]="fixtures" \
		["migrate"]="migrate database" \
		["validate"]="bdd validate" \
	)
endif

.PHONY: composer
composer: isdocker ### Scripts for composer
ifeq ($(COMMANDS_ARGS),suggests)
	${COMPOSER_EXEC} suggests --by-suggestion
else ifeq ($(COMMANDS_ARGS),outdated)
	${COMPOSER_EXEC} outdated
else ifeq ($(COMMANDS_ARGS),fund)
	${COMPOSER_EXEC} fund
else ifeq ($(COMMANDS_ARGS),prod)
	${COMPOSER_EXEC} install --no-dev --no-progress --prefer-dist --optimize-autoloader
else ifeq ($(COMMANDS_ARGS),dev)
	${COMPOSER_EXEC} install --no-progress --prefer-dist --optimize-autoloader
else ifeq ($(COMMANDS_ARGS),u)
	${COMPOSER_EXEC} update
else ifeq ($(COMMANDS_ARGS),i)
	${COMPOSER_EXEC} install
else ifeq ($(COMMANDS_ARGS),validate)
	${COMPOSER_EXEC} validate
else
	@printf "${MISSING_ARGUMENTS}" "composer"
	$(call array_arguments, \
		["suggests"]="suggestions package pour PHP" \
		["i"]="install" \
		["outdated"]="Packet php outdated" \
		["fund"]="Discover how to help fund the maintenance of your dependencies." \
		["prod"]="Installation version de prod" \
		["dev"]="Installation version de dev" \
		["u"]="COMPOSER update" \
		["validate"]="COMPOSER validate" \
	)
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
	@printf "${MISSING_ARGUMENTS}" "encore"
	$(call array_arguments, \
		["dev"]="créer les assets en version dev" \
		["watch"]="créer les assets en version watch" \
		["build"]="créer les assets en version prod" \
	)
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
	@printf "${MISSING_ARGUMENTS}" "env"
	$(call array_arguments, \
		["dev"]="environnement dev" \
		["prod"]="environnement prod" \
	)
endif

.PHONY: geocode
geocode: isdocker ### Geocode
	$(SYMFONY_EXEC) labstag:geocode:install $(COMMANDS_ARGS)

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
	@printf "${MISSING_ARGUMENTS}" "install"
	$(call array_arguments, \
		["all"]="common" \
		["dev"]="dev" \
		["prod"]="prod" \
	)
endif

.PHONY: commands
commands: isdocker
	$(SYMFONY_EXEC) labstag:install --all
	$(SYMFONY_EXEC) labstag:guard-route
	$(SYMFONY_EXEC) labstag:workflows-show

.PHONY: linter
linter: isdocker phar node_modules ### Scripts Linter
ifeq ($(COMMANDS_ARGS),all)
	@make linter compo -i
	@make linter phpfix -i
	@make linter eslint-fix -i
	@make linter stylelint-fix -i
	@make linter twig -i
	@make linter container -i
	@make linter yaml -i
	@make linter phpaudit -i
	@make linter readme -i
else ifeq ($(COMMANDS_ARGS),phpaudit)
	@make linter phpcs -i
	@make linter phpmd -i
	@make linter phpmnd -i
	@make linter phpstan -i
else ifeq ($(COMMANDS_ARGS),compo)
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
	${PHP_EXEC} php-cs-fixer.phar fix src
else ifeq ($(COMMANDS_ARGS),phpcbf)
	${PHP_EXEC} phpcbf.phar -d memory_limit=-1 --report=diff -p --extensions=php --standard=phpcs.xml
else ifeq ($(COMMANDS_ARGS),phpcs)
	${PHP_EXEC} phpcs.phar --report=full --extensions=php src --standard=phpcs.xml
else ifeq ($(COMMANDS_ARGS),phpcs-onlywarning)
	${PHP_EXEC} phpcs.phar  --report=full --extensions=php --error-severity=0 --standard=phpcs.xml
else ifeq ($(COMMANDS_ARGS),phpcs-onlyerror)
	${PHP_EXEC} phpcs.phar  --report=full --extensions=php --warning-severity=0 --standard=phpcs.xml
else ifeq ($(COMMANDS_ARGS),phploc)
	$(PHP_EXEC) phploc.phar src
else ifeq ($(COMMANDS_ARGS),phpdoc)
	$(PHP_EXEC) phpDocumentor.phar -d src -t public/docs
else ifeq ($(COMMANDS_ARGS),phpmd)
	$(PHP_EXEC) -d error_reporting=24575 phpmd.phar src,features/bootstrap ansi phpmd.xml
else ifeq ($(COMMANDS_ARGS),phpmnd)
	${COMPOSER_EXEC} run phpmnd
else ifeq ($(COMMANDS_ARGS),phpstan)
	${PHP_EXEC} phpstan.phar analyse src
else ifeq ($(COMMANDS_ARGS),twig)
	${SYMFONY_EXEC} lint:twig templates
else ifeq ($(COMMANDS_ARGS),container)
	${SYMFONY_EXEC} lint:container
else ifeq ($(COMMANDS_ARGS),yaml)
	${SYMFONY_EXEC} lint:yaml config translations
else
	@printf "${MISSING_ARGUMENTS}" "linter"
	$(call array_arguments, \
		["all"]="## Launch all linter" \
		["compo"]="composer" \
		["readme"]="linter README.md" \
		["phpaudit"]="AUDIT PHP" \
		["phpdoc"]="php doc" \
		["phpfix"]="PHP-CS-FIXER & PHPCBF" \
		["stylelint"]="indique les erreurs dans le code SCSS" \
		["stylelint-fix"]="fix les erreurs dans le code SCSS" \
		["eslint"]="indique les erreurs sur le code JavaScript à partir d'un standard" \
		["eslint-fix"]="fixe le code JavaScript à partir d'un standard" \
		["phpcbf"]="fixe le code PHP à partir d'un standard" \
		["php-cs-fixer"]="fixe le code PHP à partir d'un standard" \
		["phpcs"]="indique les erreurs de code non corrigé par PHPCBF" \
		["phpcs-onlywarning"]="indique les erreurs de code non corrigé par PHPCBF" \
		["phpcs-onlyerror"]="indique les erreurs de code non corrigé par PHPCBF" \
		["phploc"]="phploc" \
		["phpmd"]="indique quand le code PHP contient des erreurs de syntaxes ou des erreurs" \
		["phpmnd"]="Si des chiffres sont utilisé dans le code PHP" \
		["phpstan"]="regarde si le code PHP ne peux pas être optimisé" \
		["twig"]="indique les erreurs de code de twig" \
		["container"]="indique les erreurs de code de container" \
		["yaml"]="indique les erreurs de code de yaml" \
		["jscpd"]="Copy paste detector" \
		["jscpd-report"]="Copy paste detector report" \
	)
endif

.PHONY: messenger
messenger: isdocker ### Scripts messenger
ifeq ($(COMMANDS_ARGS),consume)
	${SYMFONY_EXEC} messenger:consume async -vv
else
	@printf "${MISSING_ARGUMENTS}" "messenger"
	$(call array_arguments, \
		["consume"]="Messenger Consume" \
	)
endif

.PHONY: tests
tests: isdocker phar ### Scripts tests
ifeq ($(COMMANDS_ARGS),launch)
	@$(DOCKER_EXECPHP) make tests all
else ifeq ($(COMMANDS_ARGS),behat)
	${PHP_EXEC} behat.phar --config behat.yaml
else ifeq ($(COMMANDS_ARGS),simple-phpunit-unit-integration)
	${COMPOSER_EXEC} run simple-phpunit-unit-integration
else ifeq ($(COMMANDS_ARGS),simple-phpunit)
	${COMPOSER_EXEC} run simple-phpunit
else
	@printf "${MISSING_ARGUMENTS}" "tests"
	$(call array_arguments, \
		["launch"]="Launch all tests" \
		["behat"]="Lance les tests behat" \
		["simple-phpunit-unit-integration"]="lance les tests phpunit" \
		["simple-phpunit"]="lance les tests phpunit" \
	)
endif

.PHONY: translations
translations: isdocker ## update translation
	${SYMFONY_EXEC} translation:update --force --format=yaml --clean fr 

.PHONY: workflow-png
workflow-png: isdocker ### generate workflow png
	${SYMFONY_EXEC} workflow:dump $(COMMAND_ARGS) | dot -Tpng -o $(COMMAND_ARGS).png

.PHONY: libraries
libraries: ### Add libraries
ifeq ($(COMMANDS_ARGS),tarteaucitron)
	wget https://github.com/AmauriC/tarteaucitron.js/archive/refs/tags/v1.9.3.zip
	unzip v1.9.3.zip
	rm v1.9.3.zip
	mv tarteaucitron.js-1.9.3 apps/public/tarteaucitron
else
	@printf "${MISSING_ARGUMENTS}" "libraries"
	$(call array_arguments, \
		["tarteaucitron"]="tarteaucitron" \
	)
endif

DATABASE_BDD := $(shell more docker-compose.yml | grep DATABASE_BDD: | sed -e "s/^.*DATABASE_BDD:[[:space:]]//")
DATABASE_USER := $(shell more docker-compose.yml | grep DATABASE_USER: | sed -e "s/^.*DATABASE_USER:[[:space:]]//")
DATABASE_PASSWORD := $(shell more docker-compose.yml | grep DATABASE_PASSWORD: | sed -e "s/^.*DATABASE_PASSWORD:[[:space:]]//")
SETBDD := cd lampy && make setbdd USERNAME="${DATABASE_USER}" BDD="${DATABASE_BDD}" PASSWORD="${DATABASE_PASSWORD}"

bddset: ## Set bdd
	@echo "$(SETBDD)"
	$(shell $(SETBDD))

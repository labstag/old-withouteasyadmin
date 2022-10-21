include make/general/Makefile
STACK   := labstag
NETWORK := proxylampy
include make/docker/Makefile

PHPFPMFULLNAME := $(STACK)_phpfpm.1.$$(docker service ps -f 'name=$(STACK)_phpfpm' $(STACK)_phpfpm -q --no-trunc | head -n1)

DOCKER_EXECPHP := @$(DOCKER_EXEC) $(PHPFPMFULLNAME)

PHP_EXEC := ${DOCKER_EXECPHP} php -d memory_limit=-1
SYMFONY_EXEC := ${DOCKER_EXECPHP} symfony console
COMPOSER_EXEC := ${DOCKER_EXECPHP} symfony composer

COMMANDS_SUPPORTED_COMMANDS := workflow-png tests messenger linter install git encore composer bdd setbdd geocode
COMMANDS_SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(COMMANDS_SUPPORTED_COMMANDS))
ifneq "$(COMMANDS_SUPPORTS_MAKE_ARGS)" ""
  COMMANDS_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(COMMANDS_ARGS):;@:)
endif

init: ## Init project
	@git submodule update --init --recursive --remote

apps/phploc.phar:
	wget https://phar.phpunit.de/phploc-7.0.2.phar -O apps/phploc.phar

apps/php-cs-fixer.phar:
	wget https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v3.10.0/php-cs-fixer.phar -O apps/php-cs-fixer.phar

apps/phpmd.phar:
	wget https://github.com/phpmd/phpmd/releases/download/2.12.0/phpmd.phar -O apps/phpmd.phar

apps/phpcbf.phar:
	wget https://github.com/squizlabs/PHP_CodeSniffer/releases/download/3.7.1/phpcbf.phar -O apps/phpcbf.phar

apps/phpcs.phar:
	wget https://github.com/squizlabs/PHP_CodeSniffer/releases/download/3.7.1/phpcs.phar -O apps/phpcs.phar

apps/phpstan.phar:
	wget https://github.com/phpstan/phpstan/releases/download/1.8.2/phpstan.phar -O apps/phpstan.phar

apps/phpDocumentor.phar:
	wget https://github.com/phpDocumentor/phpDocumentor/releases/download/v3.3.1/phpDocumentor.phar -O apps/phpDocumentor.phar

apps/behat.phar:
	wget https://github.com/Behat/Behat/releases/download/v3.11.0/behat.phar -O apps/behat.phar

phar: apps/phploc.phar apps/phpmd.phar apps/php-cs-fixer.phar apps/phpcbf.phar apps/phpcs.phar apps/phpstan.phar apps/phpDocumentor.phar apps/behat.phar

apps/composer.lock: isdocker apps/composer.json
	${COMPOSER_EXEC} update

apps/vendor: isdocker apps/composer.json
	${COMPOSER_EXEC} install --no-progress --prefer-dist --optimize-autoloader
	
.PHONY: assets
assets: isdocker apps/.env
	${SYMFONY_EXEC} assets:install public --symlink --relative

.PHONY: bdd
bdd: isdocker ### Scripts for BDD
ifeq ($(COMMANDS_ARGS),fixtures)
	${SYMFONY_EXEC} doctrine:fixtures:load -n
else ifeq ($(COMMANDS_ARGS),migrate)
	${SYMFONY_EXEC} doctrine:migrations:migrate -n
else ifeq ($(COMMANDS_ARGS),schemaupdate)
	${SYMFONY_EXEC} doctrine:schema:update --force
else ifeq ($(COMMANDS_ARGS),validate)
	${SYMFONY_EXEC} doctrine:schema:validate
else
	@printf "${MISSING_ARGUMENTS}" "bdd"
	$(call array_arguments, \
		["fixtures"]="fixtures" \
		["shemaupdate"]="schema update" \
		["migrate"]="migrate database" \
		["validate"]="bdd validate" \
	)
endif

.PHONY: env
apps/.env: ### Scripts Installation environnement
	@echo "APP_ENV=dev" > apps/.env

.PHONY: composer
composer: isdocker env ### Scripts for composer
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
		["dev"]="Installation version de dev" \
		["fund"]="Discover how to help fund the maintenance of your dependencies." \
		["i"]="install" \
		["outdated"]="Packet php outdated" \
		["prod"]="Installation version de prod" \
		["suggests"]="suggestions package pour PHP" \
		["u"]="COMPOSER update" \
		["validate"]="COMPOSER validate" \
	)
endif



.PHONY: encore
encore: node_modules ### Script for Encore
ifeq ($(COMMANDS_ARGS),dev)
	@npm run encore-dev
else ifeq ($(COMMANDS_ARGS),watch)
	@npm run encore-watch
else ifeq ($(COMMANDS_ARGS),dev-server)
	@npm run encore-dev-server
else ifeq ($(COMMANDS_ARGS),build)
	@npm run encore-build
else
	@printf "${MISSING_ARGUMENTS}" "encore"
	$(call array_arguments, \
		["build"]="créer les assets en version prod" \
		["dev-server"]="créer les assets en version dev-server" \
		["dev"]="créer les assets en version dev" \
		["watch"]="créer les assets en version watch" \
	)
endif

.PHONY: geocode
geocode: isdocker ### Geocode
	$(SYMFONY_EXEC) labstag:geocode:install $(COMMANDS_ARGS)

.PHONY: install
install: node_modules apps/.env ### installation
ifeq ($(COMMANDS_ARGS),all)
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
else ifeq ($(COMMANDS_ARGS),prod)
	@make install all -i
	@make bdd fixtures -i
	@make commands -i
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
	$(SYMFONY_EXEC) labstag:install
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
	@make linter rector -i
else ifeq ($(COMMANDS_ARGS),phpaudit)
	@make linter phpcs -i
	@make linter phpmd -i
	@make linter phpmnd -i
	@make linter phpstan -i
else ifeq ($(COMMANDS_ARGS),compo)
	${COMPOSER_EXEC} validate
	${COMPOSER_EXEC} outdated
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
else ifeq ($(COMMANDS_ARGS),rector)
	${COMPOSER_EXEC} run rector
else ifeq ($(COMMANDS_ARGS),rector-dry)
	${COMPOSER_EXEC} run rector-dry
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
	${SYMFONY_EXEC} lint:yaml config translations --parse-tags
else
	@printf "${MISSING_ARGUMENTS}" "linter"
	$(call array_arguments, \
		["all"]="## Launch all linter" \
		["compo"]="composer" \
		["container"]="indique les erreurs de code de container" \
		["eslint-fix"]="fixe le code JavaScript à partir d'un standard" \
		["eslint"]="indique les erreurs sur le code JavaScript à partir d'un standard" \
		["jscpd-report"]="Copy paste detector report" \
		["jscpd"]="Copy paste detector" \
		["php-cs-fixer"]="fixe le code PHP à partir d'un standard" \
		["phpaudit"]="AUDIT PHP" \
		["phpcbf"]="fixe le code PHP à partir d'un standard" \
		["phpcs-onlyerror"]="indique les erreurs de code non corrigé par PHPCBF" \
		["phpcs-onlywarning"]="indique les erreurs de code non corrigé par PHPCBF" \
		["phpcs"]="indique les erreurs de code non corrigé par PHPCBF" \
		["phpdoc"]="php doc" \
		["phpfix"]="PHP-CS-FIXER & PHPCBF" \
		["phploc"]="phploc" \
		["phpmd"]="indique quand le code PHP contient des erreurs de syntaxes ou des erreurs" \
		["phpmnd"]="Si des chiffres sont utilisé dans le code PHP" \
		["phpstan"]="regarde si le code PHP ne peux pas être optimisé" \
		["readme"]="linter README.md" \
		["rector"]="rector" \
		["rector-dry"]="rector dry run" \
		["stylelint-fix"]="fix les erreurs dans le code SCSS" \
		["stylelint"]="indique les erreurs dans le code SCSS" \
		["twig"]="indique les erreurs de code de twig" \
		["yaml"]="indique les erreurs de code de yaml" \
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
		["behat"]="Lance les tests behat" \
		["launch"]="Launch all tests" \
		["simple-phpunit-unit-integration"]="lance les tests phpunit" \
		["simple-phpunit"]="lance les tests phpunit" \
	)
endif

.PHONY: translations
translations: isdocker ## update translation
	${SYMFONY_EXEC} translation:update --force --format=yaml --clean fr 

.PHONY: workflow-png
workflow-png: isdocker ### generate workflow png
	${SYMFONY_EXEC} workflow:dump $(COMMANDS_ARGS) | dot -Tpng -O $(COMMANDS_ARGS).png

bddset: ## Set bdd
	@cp database_init/01_labstag.sql lampy/mariadb_init/01_labstag.sql
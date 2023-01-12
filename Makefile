include make/general/Makefile
STACK   := labstag
NETWORK := proxylampy
include make/docker/Makefile

PHPFPMFULLNAME := $(STACK)_phpfpm.1.$$(docker service ps -f 'name=$(STACK)_phpfpm' $(STACK)_phpfpm -q --no-trunc | head -n1)

DOCKER_EXECPHP := @$(DOCKER_EXEC) $(PHPFPMFULLNAME)

COMMANDS_SUPPORTED_COMMANDS := workflow-png
COMMANDS_SUPPORTS_MAKE_ARGS := $(findstring $(firstword $(MAKECMDGOALS)), $(COMMANDS_SUPPORTED_COMMANDS))
ifneq "$(COMMANDS_SUPPORTS_MAKE_ARGS)" ""
  COMMANDS_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(COMMANDS_ARGS):;@:)
endif

.PHONY: workflow-png
workflow-png: isdocker ### generate workflow png
	npm run workflow:dump $(COMMANDS_ARGS) | dot -Tpng -O $(COMMANDS_ARGS).png

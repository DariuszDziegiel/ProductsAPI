DC := docker compose
DC_EXEC := $(DC) exec -it apache
PHPUNIT := $(DC_EXEC) php -d memory_limit=-1 vendor/bin/phpunit --configuration phpunit.xml --colors=always --testdox
SYMFONY := $(DC_EXEC) php -d memory_limit=-1 bin/console

default: start

start:
	$(DC) up -d

stop:
	$(DC) stop

remove:
	$(DC) down -v

test:
	$(PHPUNIT) $(RUN_ARGS)

lint:
	$(SYMFONY) lint:yaml src/
	$(SYMFONY) lint:yaml config/
	$(SYMFONY) lint:twig src/
	$(SYMFONY) lint:container -vv

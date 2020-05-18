dev.build:
	docker-compose pull
	docker-compose up --build -d

	docker exec balances_php sh -c 'composer install --no-interaction'
	docker exec balances_php sh -c 'bin/console doctrine:migration:migrate --no-interaction'

dev.bash:
	docker exec -it balances_php /bin/bash

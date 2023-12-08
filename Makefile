# Build the project
build-microservices:
	make build-user \
	&& make build-message

down-microservices:
	make down-user \
	&& make down-message

# Build user microservice
build-user:
	cd user \
	&& docker compose up -d --build

setup-user:
	cd user \
	&& cp .env.example .env \
	&& docker compose up -d --build \
	&& docker exec -it user-app composer install \
	&& docker exec -it user-app php artisan key:genrate \
	&& docker exec -it user-app php artisan storage:link \
	&& docker exec -it user-app php artisan migrate --seed

test-user:
	docker exec -it user-app php artisan test

down-user:
	cd user \
	&& docker compose down

# Build message microservice
build-message:
	cd message \
	&& docker compose up -d --build

setup-message:
	cd message \
	&& cp .env.example .env \
	&& docker compose up -d --build \
	&& docker exec -it message-app composer install \
	&& docker exec -it message-app php artisan key:genrate \
	&& docker exec -it message-app php artisan storage:link \
	&& docker exec -it message-app php artisan migrate --seed

test-message:
	docker exec -it message-app php artisan test

down-message:
	cd message \
	&& docker compose down
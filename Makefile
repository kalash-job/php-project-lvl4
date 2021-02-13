start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env|| true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	npm install

test:
	php artisan test

deploy:
	git push heroku

lint:
	composer run-script phpcs

lint-fix:
	composer run-script phpcbf

test-coverage:
	composer phpunit tests -- --coverage-clover build/logs/clover.xml

seed:
	php artisan migrate:refresh
	php artisan db:seed --class="StatusesTableSeeder"
	php artisan db:seed --class="TasksTableSeeder"
	php artisan db:seed --class="LabelsTableSeeder"

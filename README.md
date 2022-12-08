Requirements

-PHP 8.1.2

-Mysql 8.1.2

Configuration

-change DATABASE_URL variable in .env file

Instalation

1-composer install 

2-php bin/console make:migration

3-php bin/console doctrine:migrations:migrate

4-php bin/console doctrine:fixtures:load

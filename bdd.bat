php bin/console doctrine:schema:drop --force
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load -n

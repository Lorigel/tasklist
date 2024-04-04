# tasklist

#After setting up the project, execute the following command to create the migrations and also insert dummy data into the database

- php bin/console make:migration
- php bin/console doctrine:migrations:migrate
- php bin/console doctrine:fixtures:load

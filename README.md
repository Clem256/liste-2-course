# Installation
Update the apt repositories
```shell
apt update
```
Install php and the project dependencies
```shell
apt install php php-xml composer npm
```
If you use sqlite:
```shell
apt install php-sqlite3
```
If you use mysql:
```shell
apt install php-mysql
```
Install the composer dependencies:
```shell
composer i
```
Install the npm dependencies:
```shell
npm i
```
Compile the frontend:
```shell
npm run dev
```
Dans `.env.local`:
- CHEMIN_IMAGES: The path to the folder <project location>/public/images
- DATABASE_URL: Your database
- MAILER_DSN: Your email settings
```
CHEMIN_IMAGES=/var/www/listedecourses/public/images
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
MAILER_DSN=
```
To delete the database: 
```shell
php bin/console doctrine:database:drop --force
```
To create the database based on DATABASE_URL: 
```shell
php bin/console doctrine:database:create
```
To update the diagram: 
```shell
php bin/console doctrine:schema:update --force
```
To create a diff:
```shell
php bin/console do:mi:di
```
To create a migration:
```shell
php bin/console do:mi:mi
```

## Prod
Make the project in prod
```shell
composer dump-env prod
````
To clear the cache:
```shell
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod
```
# Execution (with the php development server)
```shell
php -S 127.0.0.1:80 -t public/ 
```

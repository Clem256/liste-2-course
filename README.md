# Installation
Mettez à jour les dépots apt
```shell
apt update
```
Installez php et les dépendances du projet
```shell
apt install php php-xml composer npm
```
Si vous utilisez sqlite:
```shell
apt install php-sqlite3
```
Si vous utilisez mysql:
```shell
apt install php-mysql
```
Installez les dépendances composer:
```shell
composer i
```
Installez les dépendances npm:
```shell
npm i
```
Compilez le frontend:
```shell
npm run dev
```
Dans `.env.local`:
- CHEMIN_IMAGES: Le chemin du dossier <emplacement du projet>/public/images
- DATABASE_URL: Votre base de données
- MAILER_DSN: Votre configuration email  
```
CHEMIN_IMAGES=/var/www/listedecourses/public/images
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
MAILER_DSN=
```
Pour supprimer la base de données
```shell
php bin/console doctrine:database:drop --force
```
Pour créer la base de donnée en fonction de DATABASE_URL
```shell
php bin/console doctrine:database:create
```
Pour mettre à jour le schéma
```shell
php bin/console doctrine:schema:update --force
```
Pour créer une diff:
```shell
php bin/console do:mi:di
```
Pour créer une migration:
```shell
php bin/console do:mi:mi
```
Pour charger les fixtures:
```shell
php bin/console doctrine:fixtures:load
```
## Prod
Faites pour rendre le projet en prod
```shell
composer dump-env prod
````
Pour clear le cache:
```shell
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod
```
# Execution (avec le serveur de développement php)
```shell
php -S 127.0.0.1:80 -t public/ 
```

## Mot de passe admin

```shell
minh
```
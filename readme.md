# Bilemo

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/cc8dc16a8f2d46dfaac7aad9db038519)](https://www.codacy.com/manual/bpel/bilemo?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=bpel/bilemo&amp;utm_campaign=Badge_Grade)
## About the project

Development of a web service exposing an API

### Prerequisites
```
PHP >= 7.2
MySQL >= 8.0
Symfony = 4.3
Twig >= 1.5
Composer >= 1.9
```

### Download or clone the repository


```
Git clone https://github.com/bpel/bilemo.git
```

## Download dependencies

```
// from /bilemo/
composer install
```


## Config

 `.env`

   ```
   // dev or prod
   APP_ENV=prod
   // define db_user & db_password
   DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/bilemo
   ```

### Create database

```
// from /bilemo/
// 1) create database (need .env config)
php bin/console doctrine:database:create
// 2) Load schema
php bin/console doctrine:schema:update --force
OR
php bin/console make:migration
php bin/console doctrine:migrations:migrate
// 3) Load fixtures
php bin/console doctrines:fixtures:load
```
## Deployment

```
php bin/console server:run
```

### Default user

email | password
| ----- | --------
demo@test.fr | demo
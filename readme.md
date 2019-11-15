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

## Install

### Download or clone the repository


```
Git clone https://github.com/bpel/bilemo.git
```

### Download dependencies

```
// from /bilemo/
composer install
```


### Config

 `.env`

   ```
   // dev or prod
   APP_ENV=prod
   
   // define db_user & db_password
   DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/bilemo
   ```
   
 `config/services.yaml`
 
  ```
  // define the time of cache in seconds
  parameters:
      cache.expiration: 120
  ```
  
### Generate the SSH keys:
`from /bilemo/config/jwt`
```
// (need to create the directory jwt)
// don't forget your passphrase for the next step

openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
```
`from /bilemo/config/packages/lexik_jwt_authentication.yaml`
```
//define "CUSTOM_PASS_PHRASE"
lexik_jwt_authentication:
    secret_key: '%kernel.project_dir%/config/jwt/private.pem'
    public_key: '%kernel.project_dir%/config/jwt/public.pem'
    pass_phrase: CUSTOM_PASS_PHRASE
    token_ttl: 3600
```

### Create database

`from /bilemo/`
```
// 1) create database (need .env config)
php bin/console doctrine:database:create

// 2) Load schema
php bin/console doctrine:schema:update --force
OR
php bin/console make:migration
php bin/console doctrine:migrations:migrate

// 3) Load fixtures
php bin/console doctrine:fixtures:load
```
### Deployment

```
php bin/console server:run
```

## Authentification

```
curl -X POST -H "Content-Type: application/json" http://127.0.0.1:8000/api/login_check -d '{"username":"demo@test.fr","password":"demo"}'
```

## Constraints

- User must be linked to an enterprise

## Documentation

- Documentation is available on `127.0.0.1:8000/api/doc`
## Task Hub
Task Hub is a centralized system designed to manage and organize tasks or to-do lists efficiently. It serves as a focal point for task delegation, tracking progress, and ensuring completion within a specified timeframe

### Dependencies

- Docker
- Docker Compose

### To run

#### Clone Repository

```
git clone git@github.com:gstaciaki/task-hub.git
cd task-hub
```

#### Define the env variables

```
cp .env.example .env
```

#### Install the dependencies

```
./run composer install
```

#### Up the containers

```
docker compose up -d
```

or

```
./run up -d
```
#### Create database and tables
```
./run db:reset
```
#### Populate database
```
./run db:populate
```

### Fixed uploads folder permission

```
sudo chown www-data:www-data public/assets/uploads
```

#### Run the tests

```
docker compose run --rm php ./vendor/bin/phpunit tests --color
```

or

```
./run test
```

#### Run the linters

[PHPCS](https://github.com/PHPCSStandards/PHP_CodeSniffer/)

```
./run phpcs
```

[PHPStan](https://phpstan.org/)

```
./run phpstan
```

### API Test

```shell
curl --location 'http://localhost/tasks'
```

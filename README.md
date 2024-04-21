## Task Hub
Task Hub is a centralized system designed to manage and organize tasks or to-do lists efficiently. It serves as a focal point for task delegation, tracking progress, and ensuring completion within a specified timeframe

### Dependencies

- Docker
- Docker Compose

### To Run

#### Clone Repository

```
$ git clone git@github.com:gstaciaki/task-hub.git
$ cd task-hub
```

#### Define the env variables

```
$ cp .env.example .env
```

#### Define the file database

```
$ touch ./database/problems.txt
$ chmod 665 ./database/problems.txt
```

#### Install the dependencies

```
$ docker compose run --rm composer install
```

#### Up the containers

```
$ docker compose up -d
```

### Teste de API
```
curl --location 'http://localhost/tasks/index.php'
```
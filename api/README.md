# BrainyWater API

## Endpoints

```
sensor-value/
```

## Develop locally (recommended)

First you ne run the following command:

```
docker-composer up
```

To check the webserver or phpMyAdmin ip, you need to check for backend_web-server_1 and backend_phpmyadmin_1. Run:

```
docker ps
```

Then access phpMyAdmin and execute the SQL code on the following path:

```
src/database/database.sql
```

Now you are ready to boot :)

## Use MySQL outside the container

To access MySQL via MySQL Workbench, CLI, or any other tool that you want to. Follow these instructions:

### CLI

#### First step:

Open the terminal and run the following command:

```
docker exec -it backend_mysql-server_1 /bin/bash
```

This gets you to the bash shell in the MySQL container.

#### Second step:

Run:

```
mysql -u root -p
```

Use the password specified in the docker-compose.yaml file.

## Develop locally without Docker

Simply pass your database infos to the file on the following path:

```
src/config/database.php
```

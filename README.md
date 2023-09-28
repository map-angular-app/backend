# README

Instruction to host and add new data

## Requirements

- php >= 5.6.x
- mysql
- python ~ 3.10.x

## SETUP

```shell
composer install
composer dump-autoload
pip install -r Commands/requirements.txt
cp .env.example .env
```

Remeber to edit variable in .env

## Create Tables

```shell
php Database/Migrations/Tables/run.php
```

## Add or Edit Hospital List

```shell
php Commands/add_hospitals.php {input_path} {output_path}
```

note: output_path is not required

## Host

```shell
php -S 0.0.0.0:8000
```

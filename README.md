# BaksDev Profile Type

[![Version](https://img.shields.io/badge/version-7.0.13-blue)](https://github.com/baks-dev/users-profile-type/releases)
![php 8.2+](https://img.shields.io/badge/php-min%208.1-red.svg)

Модуль типов профилей пользователя

## Установка

``` bash
$ composer require baks-dev/users-profile-type
```

## Дополнительно

Установка файловых ресурсов в публичную директорию (javascript, css, image ...):

``` bash
$ php bin/console baks:assets:install
```

Изменения в схеме базы данных с помощью миграции

``` bash
$ php bin/console doctrine:migrations:diff

$ php bin/console doctrine:migrations:migrate
```

Тесты

``` bash
$ php bin/phpunit --group=users-profile-type
```

## Лицензия ![License](https://img.shields.io/badge/MIT-green)

The MIT License (MIT). Обратитесь к [Файлу лицензии](LICENSE.md) за дополнительной информацией.

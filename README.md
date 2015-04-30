Pizza E96
=========

Требования
----------

Проект будет работать на PHP версии 5.5+ и MySQL/MariaDB 5.5+. Веб-сервер может быть любой, но основной упор делается на nginx и Apache. Возможна также работа и на более старых версиях PHP и MySQL, но я не могу точно сказать какие версии являются минимально допустимыми. Операционная система роли не играет.


Установка
---------

1\. Слить себе репозитарий через:
```
git clone https://github.com/gugglegum/pizza.git
cd pizza
```
2\. Создать базу данных MySQL и отдельного пользователя, если необходимо.

3\. Скопировать файл `db/dbConfig.php.example` в `db/dbConfig.php`, настроив там свои параметры базы данных:
```
cp db/dbConfig.php.example db/dbConfig.php
nano db/dbConfig.php
```
4\. Запустить скрипт `update.php`:
```
php db/update.php
```
Скрипт предложит создать таблицу с миграциями, нужно ответить да, а затем применить все имеющиеся миграции.

5\. Скопировать файл `app/configs/application.dist.php` в `app/configs/application.php` и настроить его под своё окружение:
```
cp app/configs/application.dist.php app/configs/application.php
nano app/configs/application.php
```
6\. Настроить веб-сервер (nginx, apache) таким образом, чтобы корнем www-директории считался подкаталог `/public`, а все запросы, кроме `/css/\*`, `/images/\*` и `/js/\*` перенаправлялись на `public/index.php`. Пример конфига nginx:

```
server {
    server_name pizza;
    root /home/paul/Workspace/pizza/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }
    location ~ \.php$ {
        include fastcgi_params;

        fastcgi_pass unix:/var/run/home-fpm.socket;
        fastcgi_index index.php;

        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param SCRIPT_NAME $fastcgi_script_name;
        fastcgi_param SERVER_NAME pizza;
    }
}
```

7\. При необходимости создать запись в `/etc/hosts`.

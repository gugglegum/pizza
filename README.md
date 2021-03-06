Pizza E96
=========

Сразу несколько скриншотов для общего представления: https://yadi.sk/d/SKEl7ixBmrFEJ

Что это такое?
--------------

Это простенький сервис для коллективного заказа пиццы в офис, созданный для внутреннего использования сотрудниками интернет-магазина E96.RU одним из его программистов. Данный сервис не является официальным, а создан просто для фана. В июне 2014 кто-то в комнате программистов предложил скинуться и заказать пиццу. Затем это стало пятничной традицией ("пятничная пицца"). Пиццы разрезаны на 8 кусков и необходимо было договориться так, чтобы все 8 кусков каждой пиццы были востребованы. Каждый человек обычно хочет 2-3 куска пиццы, причём 1 кусочек одной пиццы, 2 кусочка другой и т.д. С увеличением кол-ва людей сложность формирования коллективного заказа возрастает нелинейно, плюс нужен отдельный человек, который бы собирал заявки, считал куски, собирал деньги и т.п. Поэтому и был создан данный сервис, который позволял формировать заказ в режиме онлайн и автоматизировать большую часть работы. Каждый человек входил под своей учётной записью, создавал одну или несколько заявок на некоторое кол-во кусов тех или иных пицц и каждый мог видеть кто сколько чего заказал и сколько кусков ещё не хватает до полной пиццы. Если к моменту заказа доставки пиццы какие-то пиццы не получалось добить до 8 кусков, люди меняли свои заявки на те пиццы, которые почти собраны. Люди были вынуждены самоорганизовываться и в необходимой мере идти на уступки ради общего блага, т.к. несобранные пиццы не будут заказаны. Это всегда был очень увлекательный социальный процесс, сопровождающийся забавными сообщения во внутреннем чатике.

Данный сервис позволяет любому пользователю создать коллективный заказ и другие пользователи могут принять участие в его наполнении. Создатель заказа считается его организатором, он наделяется правом менять статус заказа (формируется, сформирован, заказан у поставщика, доставлен, отменён), устанавливать скидку (как процент, так и фиксированную сумму), собирать деньги (отмечать кто сколько сдал). Когда заказ прошёл стадию формирования, изменения в заказ не допускаются. При этом каждый пользователь видит свою часть заказа, а также сумму с учётом скидки, которую ему необходимо сдать. 

Исторически так сложилось, что пиццу мы заказывали в службе доставки "Сушкоф" (http://eda1.ru), поэтому внутренний каталог заполнен пиццами 40 см этой службы доставки, но это несложно поменять.

Данный сервис не умеет: 1) парсить сайт службы доставки для обновления каталога пицц; 2) отправлять реальный заказ на сайт (организатор должен вручную зайти на сайт службы доставки и сделать там заказ).

Требования
----------

Проект будет работать на PHP версии 5.5+ и MySQL/MariaDB 5.5+. Веб-сервер может быть любой, но основной упор делается на nginx и Apache. Возможна также работа и на более старых версиях PHP и MySQL, но я не могу точно сказать какие версии являются минимально допустимыми. Операционная система роли не играет.

Проект написан без использования фреймворков, точнее он использует некий доморощенный микро MVC-фреймворк. Пусть папочка Zend не вводит вас в заблуждение, там всего несколько классов для работы с БД и почтой.

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

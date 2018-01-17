Описание
=================
Данный продукт - это система домашней бухгалтерии, для учета ведения доходов и раходов.
Данные о доходах и расходах заносятся вручную. Программа служит для удобства рассчета
и так как она Web-приложение, она всегда под рукой из браузера.

Есть идея, в далеком будущем сделать мобильное приложение, даже подготавливалась почва для написания
API интерфейса, но руки пока не доходят. Можно пользоваться моим сервером, можно установить на свой и доработать,
программа писалась больше для личных целей, но для друзей и знакомых, я сделал многопользовательский режим,
а с выходом релиза - открыл [регистрацию](http://funds.frserver.ru/site/signup), чтобы больше людей
могли писать баг репорты и помогать в развитии проекта.


Установка
==========================
* Включаем плагин composer
```shell
composer global require "fxp/composer-asset-plugin:^1.3.1"
```
* Делаем клонирование репозитория
```shell
git clone git@github.com:dastanaron/HomeAccounting.git
```
* Переходим в папку клона и устанавливаем зависимости
```shell
cd HomeAccounting/
composer update
```

* Генерируем файлы (в примере версия development)
```shell
./init
Yii Application Initialization Tool v1.0

Which environment do you want the application to be initialized in?

  [0] Development
  [1] Production

  Your choice [0-1, or "q" to quit] 0
  .....
```
* Делаем миграцию с базой данных
 в файле `/common/config/main-local.php` прописываем параметры подключения к базе данных
 в консоли вводим команду:
 ```
./yii migrate
Yii Migration Tool (based on Yii v2.0.12)

Creating migration history table "migration"...Done.
.....
```
Описание
=================
Данный репозиторий не представляет ничего интересного
для других разработчиков. Все что тут делается, будет использоваться
мною лично.


Установка
==========================
1. Включаем плагин composer
```
composer global require "fxp/composer-asset-plugin:^1.3.1"
```
2. Устанавливаем файлы фреймворка
```
composer create-project --prefer-dist yiisoft/yii2-app-advanced yii_project.loc
```
3. Генерируем файлы (версия development)
```
cd /path_to_dirrectory/
 ./init
Yii Application Initialization Tool v1.0

Which environment do you want the application to be initialized in?

  [0] Development
  [1] Production

  Your choice [0-1, or "q" to quit] 0
  .....
```
4. Делаем миграцию с базой данных
 в файле ***/common/config/main-local.php*** прописываем параметры подключения к базе данных
 в консоли вводим команду:
 ```
./yii migrate
Yii Migration Tool (based on Yii v2.0.12)

Creating migration history table "migration"...Done.
.....
```
5. Скачиваем последнюю версию ветки мастера
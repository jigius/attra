### Тестовое задание
 - Требования перечислены в [файле](tz.docx).

### Результаты

#### Сведения о реализации
1. Из сторонних php-библиотек используются `nikic/fast-route` - маршрутизация API запросов и `ramsey/uuid` - работа с UUID идентификаторами.
2. ORM микро-библиотека для работы с персистентными данными собственная - `jigius/little-sweet-pods`. С ее помощью реализуется отдельный слой приложения для работы с данными, хранимые в БД. Готовую и зрелую ORM-библиотеку я посчитал излишним использовать для текущего задания. 
3. Сборка шаблона интерфейса пользователя размещена в папке `tpl/`. Сборку осуществляет `webpack`.
4. При отрисовке интерфейса используется микро js-шаблонизатор `tpl/src/js/micro-templating.escaped.js`. Также использованы Bootstrap5, jQuery3
5. Кодовая база - ванильный php. Папка `/library`
6. Запросы от пользовательского интерфейса через REST API

#### Требования к окружению сервера для успешного деплоя
1. Установленный `composer`
2. PHP версии 7.4 и выше
3. Apache http-сервер - т.к. используется `mod_rewrite` 

#### Деплой

1. Создать БД. Например - `attra`
2. Клонировать проект - `git clone https://github.com/jigius/attra.git`
3. Перейти в папку проекта - `cd attra/`
4. Установить зависимости - `composer i`
5. Создать конфигурационный файл для миграции схемы БД - `cp phinx-dist.php phinx.php`
6. Заполнить параметры соединения к БД в файле `phinx.php` (под ключом `development`)
7. Создать схему БД - `php vendor/bin/phinx migrate`
8. Создать конфигурационный файл проекта - `cp environment-dist.php environment.php`
9. Заполнить параметры соединения к БД в файле `environment.php` (под ключом `pdo`)
10. Сделать настройки, чтобы корневой папкой у http-сервера стала папка проекта `public/`
11. Готово!

### Замечания по использованию
1. Аутенфикация пользователя происходит, при открытии индексной страницы, по UUID идентификатору пользователя из куки. 
Если пользователь запросил страницу впервые, то на стороне сервера генерируется новый идентификатор и создается новый пользователь в БД.
2. Срок хранения куки с идентификатором пользователя задается в конфигурационном файле проекта.  

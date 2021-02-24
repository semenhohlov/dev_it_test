### Тестовое задание для Dev IT

Добрый день.
Тестовое задание заняло 4 дня 32-35часов.
Все файлы писались с нуля.

#### Установка:
 1. скопировать каталог devit_test в каталог веб сервера
 2. проверить разрешение на запись в этот какталог (chmod a+w),
  - так как скрипт установки запишет файл config.php
  - для корректной работы с изображениями необходима библиотека GD для php
 3. запустить index.php
 4. откроется скрипт настройки подключеня к базе данных(БД)
 5. ввести параметры подключеня к БД
 6. ввести данные администратора приложения
 7. все.

 #### В случае ошибок, создать таблицу в БД:

``` drop table if exists devit_test;
create table if not exists devit_test (
    user_id int not null auto_increment,
    user_login varchar(30),
    user_password varchar(50),
    user_name varchar(50),
    user_surname varchar(50),
    user_last_name varchar(50),
    user_email varchar(100),
    user_phone varchar(20),
    user_avatar blob,
    user_priv int(1),
    user_ban int(1) default 0,
    ban_exp datetime default 0,
    primary key(user_id));
```
#### А так же файл config.php с массивом для подключения к БД:
с вашими данными

``` <?php
$_db_config = array();
$_db_config['host'] = 'localhost';
$_db_config['db_name'] = 'my_test';
$_db_config['db_table'] = 'devit_test';
$_db_config['login'] = 'admin';
$_db_config['password'] = '123';
?>
```

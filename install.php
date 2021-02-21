<?php

if(file_exists('config.php') && (!isset($_REQUEST['action']))){
    die("Для повторной установки удалите файл: config.php");
}

$_db_form = <<<EOD
            <div class="header-title center">
                <h2>Установка настроек базы данных</h2>
            </div>
            <p>Укажите логин и пароль к базе данных</p>
            <form action="install.php" method="post">
                <input type="hidden" name="action" value="db_auth_params">
                <div class="row">
                    <div class="col-25">
                        <label for="db_login">Логин:</label>
                    </div>
                    <div class="col-75">
                        <input id="db-login" type="text" name="db_login">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="db_password">Пароль:</label>
                    </div>
                    <div class="col-75">
                        <input id="db-password" type="text" name="db_password">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="db_host">Хост:</label>
                    </div>
                    <div class="col-75">
                        <input type="text" name="db_host" value="localhost">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="db_name">Имя базы данных:</label>
                    </div>
                    <div class="col-75">
                        <input type="text" name="db_name">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <label for="db_name">Имя таблицы:</label>
                    </div>
                    <div class="col-75">
                        <input type="text" name="db_table" value="test_user">
                    </div>
                </div>
                <div class="row">
                    <div class="col-25">
                        <input type="submit" value="Сохранить">
                    </div>
                    <div class="col-75">
                    </div>
                </div>
                <div class="clear"></div>
            </form>
            EOD;
$_admin_form = <<<EOD
                <div class="header-title">
                    <h2>Создание аккаунта администратора</h2>
                </div>
                <p>Заполните поля:</p>
                <form action="install.php" method="post">
                    <input type="hidden" name="action" value="create_admin">
                    <p>Логин: <input id="db-login" type="text" name="admin_login"></p>
                    <p>Пароль: <input id="db-password" type="text" name="admin_password"></p>
                    <p>Имя: <input type="text" name="first_name" value=""></p>
                    <p>Фамилия: <input type="text" name="surname" value=""></p>
                    <p>Отчество: <input type="text" name="last_name" value=""></p>
                    <p>E-mail: <input type="text" name="email" value=""></p>
                    <p>Телефон: <input type="text" name="phone" value=""></p>
                    <input type="submit" value="Сохранить">
                </form>
EOD;
$_header = <<<EOD
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Установка тестового задания</title>
</head>

<body>
    <div class="container center">
EOD;

$_footer = <<<EOD
    </div>
</body>
<script src="index.js"></script>
</html>
EOD;
$_finish = <<<EOD
                <div class="header-title">
                    <h2>Установка завершена</h2>
                </div>
                <a href="index.php">Начать</a>
EOD;
$_error = '';
$_output = '';
        if (isset($_REQUEST['action'])) {
            $action = $_REQUEST['action'];
            if (strcmp($action, 'db_auth_params') == 0) {
                $host = htmlspecialchars($_REQUEST['db_host']);
                $login = htmlspecialchars($_REQUEST['db_login']);
                $password = htmlspecialchars($_REQUEST['db_password']);
                $db_name = htmlspecialchars($_REQUEST['db_name']);
                $table = htmlspecialchars($_REQUEST['db_table']);
                // sql connect
                //$my = mysqli_connect($host, $login, $password, $db_name);
                $my = mysqli_connect($host, $login, $password, $db_name);
                $_error .= mysqli_connect_error();
                if (!mysqli_connect_error()) {
                // create table
                if (!mysqli_query($my, "drop table if exists " . $table . ";")) {
                    $_error .= mysqli_error($my);
                }
                $table_create = "create table if not exists " . $table . " (
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
            primary key(user_id));";
                if (!mysqli_query($my, $table_create)) {
                    $_error .= mysqli_error($my);
                }
                mysqli_close($my);
                $f = fopen('config.php', 'w');
                if ($f) {
                    fwrite($f, "<?php \n");
                    fwrite($f, '$_db_config = array();' . "\n");
                    fwrite($f, '$_db_config[\'host\'] = \'' . $host . "';\n");
                    fwrite($f, '$_db_config[\'db_name\'] = \'' . $db_name . "';\n");
                    fwrite($f, '$_db_config[\'db_table\'] = \'' . $table . "';\n");
                    fwrite($f, '$_db_config[\'login\'] = \'' . $login . "';\n");
                    fwrite($f, '$_db_config[\'password\'] = \'' . $password . "';\n");
                    fwrite($f, "?>");
                    fclose($f);
                } else {
                    $_error .= 'Ошибка записи файла config.php<br>';
                    $_error .= 'Разрешите запись в корневом каталоге проекта<br>';
                    $_error .= 'Перезапустите скрипт install.php';
                }
             }
             if(!$_error){
                $_output .= $_admin_form;
             } else {
                $_output .= $_db_form;
             }
            } elseif (strcmp($action, 'create_admin') == 0) {
                $admin_login = htmlspecialchars($_REQUEST['admin_login']);
                $admin_password = md5(htmlspecialchars($_REQUEST['admin_password']));
                $admin_name = htmlspecialchars($_REQUEST['first_name']);
                $admin_surname = htmlspecialchars($_REQUEST['surname']);
                $admin_last_name = htmlspecialchars($_REQUEST['last_name']);
                $admin_email = htmlspecialchars($_REQUEST['email']);
                $admin_phone = htmlspecialchars($_REQUEST['phone']);

                require_once('config.php');
                // sql connect
                //$my = mysqli_connect($host, $login, $password, $db_name);
                $my = mysqli_connect($_db_config['host'], $_db_config['login'], $_db_config['password'], $_db_config['db_name']);
                $_error .= mysqli_connect_error();
                if (!mysqli_connect_error()) {
                $insert_user = "insert into " . $_db_config['db_table'] . " values (
            null,
            '" . $admin_login . "',
            '" . $admin_password . "',
            '" . $admin_name . "',
            '" . $admin_surname . "',
            '" . $admin_last_name . "',
            '" . $admin_email . "',
            '" . $admin_phone . "',
            null, 1, 0, 0);";
                if (!mysqli_query($my, $insert_user)) {
                    $_error .= mysqli_error($my);
                }
                mysqli_close($my);
                if(!$_error){
                    $_output .= $_finish;
                } else {
                    $_output .= $_admin_form;
                }
            }
            }
        } else {
            $_output .= $_db_form;
        }
echo $_header;

echo "<div class=\"error\">$_error</div>";
echo $_output;

echo $_footer;
?>
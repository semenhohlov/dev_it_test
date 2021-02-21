<?php
// db_config?
$_path = dirname($_SERVER['PHP_SELF']).'/';
if(!file_exists('config.php')){
    header('Location: '.$_path.'install.php');
}


require_once('config.php');
require_once('db_mysql.php');
require_once('app_class.php');
require_once('user.php');

session_start();
//echo"123";

$app = new _application($_db_config);
//$app->output = $_html_lorem;
$app->work();
// close mysqli connection
$app->db->close();
// header
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title><?php echo $app->title; ?></title>
</head>
<body>
    <div id="app" class="container center">
        <div class="main-header">
            <?php echo "<a href=\"$app->url\"><h2 class=\"head-title\">$app->main_header</h2></a>"; ?>
        </div>
        <div class="clear"></div>
        <div class="menu sidebar">
            <?php $app->user->user_area(); echo $app->user->user_area; ?>
            <?php
            foreach($app->menu as $key => $value){
                echo "<div class=\"menu-item\"><a href=\"$value\">$key</a></div>";
            }
            ?>
        </div>
        <div class="content">
<?php

//errors
echo $app->show_errors();
//content
echo $app->output;
//footer
?>
    </div>
    <div class="clear"></div>
        <div class="main-footer">
            <?php echo $app->footer; ?>
        </div>
    </div>
</body>
<script src="index.js"></script>
</html>
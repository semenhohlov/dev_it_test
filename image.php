<?php
require_once('config.php');
require_once('db_mysql.php');
$db = new c_db($_db_config);
$uid = $_REQUEST['uid'];
$db->select('user_avatar', $_db_config['db_table'], 'user_id = '.$uid);
$row = $db->fetch(MYSQLI_ASSOC);
$str = $row['user_avatar'];
$db->free();
$db->close();
header('Content-Type: image/jpg');
if($str){
    $str = base64_decode($str);
    echo $str;
} else {
    //default avatar
    $image = imagecreatefrompng('default_avatar.png');
    imagepng($image);
    imagedestroy($image);
}
?>
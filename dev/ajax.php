<?php
require_once('../config.php');
header("Access-Control-Allow-Origin:".ROOTURL);

$link = mysqli_connect(DSN,DB_USER,DB_PASS) or die("MySQLへの接続に失敗しました。");
$sdb = mysqli_select_db($link,DB_NAME) or die("データベースの選択に失敗しました。");
mysqli_set_charset($link,'utf8'); // 文字化け対策

//TODO:復数アプリ登録できるようになったら変更
$sql = "UPDATE `app` SET `fromurl`='".$_POST['from']."',`returnurl` = '".$_POST['return']."',`tw_consumer_key`='".$_POST['key']."',`tw_consumer_key_secret`='".$_POST['secret']."' WHERE `user_id` ='".$_SESSION['user_id']."';";

$result = mysqli_query($link,$sql);
mysqli_close($link) or die("MySQL切断に失敗しました。");
?>

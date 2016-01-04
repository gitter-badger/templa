<?php
require_once('../config.php');
header("Access-Control-Allow-Origin:".ROOTURL);

$link = mysqli_connect(DSN,DB_USER,DB_PASS) or die("MySQLへの接続に失敗しました。");
$sdb = mysqli_select_db($link,DB_NAME) or die("データベースの選択に失敗しました。");
mysqli_set_charset($link,'utf8'); // 文字化け対策

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
//TODO:復数アプリ登録できるようになったら変更
        $sql = "UPDATE `app` SET `fromurl`='".$_POST['from']."',`returnurl` = '".$_POST['return']."',`tw_consumer_key`='".$_POST['key']."',`tw_consumer_key_secret`='".$_POST['secret']."' WHERE `user_id` ='".$_SESSION['user_id']."';";
        $result = mysqli_query($link,$sql);
        break;

    case "GET":
        $sql = "select * from `app` where `user_id` = ".$_SESSION['user_id']." limit 1";
        $result = mysqli_query($link,$sql);
        while ($row = mysqli_fetch_assoc($result)) {
            $json = array(
                "app_id" => $row['app_id'],
                "fromurl" => $row['fromurl'],
                "returnurl" => $row['returnurl'],
                "tw_consumer_key" => $row['tw_consumer_key'],
                "tw_consumer_key_secret" => $row['tw_consumer_key_secret']
            );
        }
        header('Content-type: application/json');
        echo json_encode($json);
        break;

    default:
        exit;
}
mysqli_close($link) or die("MySQL切断に失敗しました。");
?>

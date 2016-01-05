<?php
require_once '../config.php';

//画像保存先
define('MEDIADIR', './medias/');

$link = mysqli_connect(DSN,DB_USER,DB_PASS) or die("MySQLへの接続に失敗しました。");
$sdb = mysqli_select_db($link,DB_NAME) or die("データベースの選択に失敗しました。");
$sql = "select * from `app` where `app_id` = ".$_GET['id']." limit 1";
$result = mysqli_query($link,$sql) or die("cannot send query<br />SQL:".$sql);
while ($row = mysqli_fetch_assoc($result)) {
	$AppID = $row['app_id'];
    $AppURL = $row['fromurl'];
    $AppReturnURL = $row['returnurl'];
    $_SESSION["TwCk"] = $row['tw_consumer_key'];
    $_SESSION["TwCks"] = $row['tw_consumer_key_secret'];
}

if($_GET['id'] != $AppID){
//TODO:エラー系はどこかにまとめる
	echo "<meta charset='UTF-8'>";
    echo "id=".$_GET['id'];
    echo "<br>登録されていないアプリです。";
    exit;
}

if(mb_substr($_SERVER['HTTP_REFERER'], 0, strlen($AppURL)) != $AppURL){
//TODO:エラー系はどこかにまとめる
	echo "<meta charset='UTF-8'>";
    echo $_SERVER['HTTP_REFERER'];
    echo "<br>登録されていないURLからのPOSTです";
    exit;
}

if($AppReturnURL == ""){
    $_SESSION['returnURL'] = $AppURL;
} else {
    $_SESSION['returnURL'] = $AppReturnURL;
}

if(($_POST['image']==NULL)||($_POST['text']==NULL)){
//TODO:エラー系はどこかにまとめる
	echo "<meta charset='UTF-8'>";
    echo '必要な情報がPOSTされていません';
	exit;
}

//保存ファイル名用ランダム文字列作成
static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
$str = '';
for ($i = 0; $i < 30; ++$i) {
    $str .= $chars[mt_rand(0, 61)];
}

//画像タイプ判定
$extension = mb_substr($_POST['image'], 11, mb_strpos($_POST['image'], ';') - 11);
$filePath = MEDIADIR.$str.'.'.$extension;

//ファイル形式毎で画像をサーバーに保存
switch ($extension) {
    case 'png':
        $canvas = preg_replace('/data:[^,]+,/i', '', $_POST['image']);
        $canvas = base64_decode($canvas);
        $image = imagecreatefromstring($canvas);
        imagesavealpha($image, true); // 透明色の有効
        imagepng($image, $filePath);
        imagedestroy($image);
        break;
    case 'jpeg':
        $canvas = preg_replace('/data:[^,]+,/i', '', $_POST['image']);
        $canvas = base64_decode($canvas);
        $image = imagecreatefromstring($canvas);
        imagejpeg($image, $filePath);
        imagedestroy($image);
        break;
    case 'gif':
        $gif = GifManipulator::createFromFile($_POST['image']);
        $gif->save($filePath);
        break;
    default:
//TODO:エラー系はどこかにまとめる
		echo "<meta charset='UTF-8'>";
        echo 'ファイル形式が不正です';
        exit;
}

//SESSIONにファイルパスとつぶやく文言を保存
$_SESSION['postFilePath'] = $filePath;
$_SESSION['postText'] = $_POST['text'];

//OAuthに必要な情報をセット
$tw = new TwitterOAuth($_SESSION["TwCk"], $_SESSION["TwCks"]);
$token = $tw->getRequestToken(TW_CALLBACK_URL);
if(! isset($token['oauth_token'])){
    echo "error: getRequestToken\n";
    exit;
}
$_SESSION['oauth_token']        = $token['oauth_token'];
$_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];

// Twitterの認証画面に遷移
$authURL = $tw->getAuthorizeURL($_SESSION['oauth_token']);
header("Location: " . $authURL);
exit;
?>

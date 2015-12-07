<?php
require_once 'config.php';

//TODO:DBからID等の取得
$AppURL = APPURL;
$AppReturnURL = APPRETURNURL;
$AppID = APPID;

if($_GET['appid'] != $AppID){
//TODO:エラー系はどこかにまとめる
    echo $_GET['appid'];
    echo "<br>登録されていないアプリです。";
    exit;
}

if(mb_substr($_SERVER['HTTP_REFERER'], 0, strlen($AppURL)) != $AppURL){
//TODO:エラー系はどこかにまとめる
    echo $_SERVER['HTTP_REFERER'];
    echo "<br>登録されていないURLからのPOSTです";
    exit;
}

$_SESSION['returnURL'] = $AppReturnURL;
//TODO:RETURN設定
$_SESSION['returnURL'] = $_SERVER['HTTP_REFERER'];

if(($_POST['image']==NULL)||($_POST['text']==NULL)){
//TODO:エラー系はどこかにまとめる
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
        echo 'ファイル形式が不正です';
        exit;
}

//SESSIONにファイルパスとつぶやく文言を保存
$_SESSION['postFilePath'] = $filePath;
$_SESSION['postText'] = $_POST['text'];

//TODO:サービスにする際TOKENをDBから取得


//OAuthに必要な情報をセット
$tw = new TwitterOAuth(TW_CONSUMER_KEY, TW_CONSUMER_SECRET);
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

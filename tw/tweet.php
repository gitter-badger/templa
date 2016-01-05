<?php
require_once('../config.php');

//つぶやく内容・帰り道があるかどうかチェック
if(($_SESSION['postFilePath']==NULL)||($_SESSION['postText']==NULL)||($_SESSION['returnURL']==NULL)){
	//TODO:エラー系はどこかにまとめる
	echo "<meta charset='UTF-8'>";
	echo 'サーバーエラー<br>ファイルが存在しないか、つぶやく内容が空です';
	echo $_SESSION['postFilePath'];
	echo $_SESSION['postText'];
	unset($_SESSION);
	exit;
}

$tw_img = $_SESSION['postFilePath'];
$tw_text = $_SESSION['postText'];

// セットした oauth_token と一致するかチェック
if ($_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
	//TODO:エラー系はどこかにまとめる
	echo "<meta charset='UTF-8'>";
	echo 'サーバーエラー<br>oauth tokenが一致しません';
	unset($_SESSION);
    exit;
}
// user access token 取得
$tw = new TwitterOAuth($_SESSION["TwCk"],$_SESSION["TwCks"],
    $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
$access_token = $tw->getAccessToken($_REQUEST['oauth_verifier']);
$user_token     = $access_token['oauth_token'];
$user_token_secret = $access_token['oauth_token_secret'];

//TODO:サービスにする際:tokenを保存？


$tw = new tmhOAuth(
	array(
		'consumer_key'    => $_SESSION["TwCk"],
		'consumer_secret' => $_SESSION["TwCks"],
		'token'      => $user_token,
		'secret'     => $user_token_secret,
		'curl_ssl_verifypeer' => false ,
	)
);

$params = array(
	'media[]' => "@{$tw_img}",
	'status' => $tw_text
);

$image = file_get_contents( $tw_img );
$imagesize = getimagesize( $tw_img );
$req = $tw->request('POST', $tw->url('1.1/statuses/update_with_media'),
	array(
	'status' => $tw_text,
	'media[]' => $image . ";type=" . $imagesize['mime'] . ";filename=" . basename( $tw_img ),
	), true, true);

//つぶやき終わったファイルは削除
chmod($tw_img, 0777);
unlink($tw_img);

//TODO:サービスにする際TWEETログを記録

header("Location:".$_SESSION['returnURL']);

//セッションも削除
unset($_SESSION);
exit;
?>

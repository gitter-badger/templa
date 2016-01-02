<?php
if ($_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
    //TODO:エラー系はどっかにまとめる
    unset($_SESSION);
    echo '<a href="./">token is not match</a>';
    exit;
}

// access token 取得
$tw = new TwitterOAuth(TEMPLA_CONSUMER_KEY,TEMPLA_CONSUMER_SECRET,
    $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
$access_token = $tw->getAccessToken($_REQUEST['oauth_verifier']);

// Twitter の user_id + screen_name(表示名)
$tw_user_id = $access_token['user_id'];
$tw_screen_name = $access_token['screen_name'];
$oauth_token = $access_token['oauth_token'];
$oauth_token_secret = $access_token['oauth_token_secret'];

$link = mysqli_connect(DSN,DB_USER,DB_PASS) or die("MySQLへの接続に失敗しました。");
$sdb = mysqli_select_db($link,DB_NAME) or die("データベースの選択に失敗しました。");
$sql = "select * from `user` where `tw_user_id` = ".$tw_user_id." limit 1";
$result = mysqli_query($link,$sql) or die("cannot send query<br />SQL:".$sql);

while ($row = mysqli_fetch_assoc($result)) {
	$db_tw_user_id = $row['tw_user_id'];
}

// 初回ユーザーだった場合は登録
if ($db_tw_user_id==NULL) {
    $sql = "insert into `user`
            (`tw_user_id`,`tw_username`, `tw_access_token`, `tw_access_token_secret`, `created`)
            values
            ('".$tw_user_id."','".$tw_screen_name."','".$oauth_token."','".$oauth_token_secret."',now())";	//var_dump($sql);
	$result = mysqli_query($link,$sql) or die("cannot send query<br />SQL:".$sql);
} else {
//登録済ユーザの場合：情報アップデート
    $sql = "UPDATE `user` SET `tw_username`='".$tw_screen_name."',`tw_access_token` = '".$oauth_token."',`tw_access_token_secret`='".$oauth_token_secret."'  WHERE  `tw_user_id` ='".$tw_user_id."';";
    $result = mysqli_query($link,$sql) or die("cannot send query<br />SQL:".$sql);
}

// ログイン処理
$sql = "select * from `user` where `tw_user_id` = ".$tw_user_id." limit 1";
$result = mysqli_query($link,$sql) or die("cannot send query<br />SQL:".$sql);
while ($row = mysqli_fetch_assoc($result)) {
    $_SESSION['user_name'] = $row['tw_username'];
}
if (!empty($tw_screen_name)) {
    // セッションハイジャック対策
	if ($_SESSION['expires'] < time() -8 ) {
		session_regenerate_id(true);
		$_SESSION['expires'] = time();
	}
} else {
	header('Location:/');
}

// mysqliへの接続を閉じる
mysqli_close($link) or die("MySQL切断に失敗しました。");
?>

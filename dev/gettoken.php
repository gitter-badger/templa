<?php
$tw = new TwitterOAuth(TEMPLA_CONSUMER_KEY, TEMPLA_CONSUMER_SECRET);

//localでやる場合は無しにしなきゃならない
$token = $tw->getRequestToken();
//$token = $tw->getRequestToken(TEMPLA_CALLBACK_URL);

if(!isset($token['oauth_token'])){
    //TODO:エラー系はどっかにまとめる
    echo "error: getRequestToken\n";
    exit;
}
$_SESSION['oauth_token']        = $token['oauth_token'];
$_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];
$_SESSION['expires'] = time();
$authURL = $tw->getAuthorizeURL($_SESSION['oauth_token']);
header("Location: " . $authURL);
exit;
?>

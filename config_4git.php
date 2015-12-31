<?php
//すべてのPHPファイルでこのファイルを読み込んで下さい
require_once '../lib/tmhOAuth.php';
require_once '../lib/twitteroauth.php';
require_once '../lib/GifManipulator.php';

define('TW_CONSUMER_KEY', 'きー');
define('TW_CONSUMER_SECRET', 'しーくれっときー');
define('TW_CALLBACK_URL', 'http://local.com/templa/tweet.php');

define('MEDIADIR', './media/');
define('APPURL', 'DBからとるから消す');
define('APPID', 'DBからとるから消す');

//　PHPでnotice以外のエラーコードは全部出力します
error_reporting(E_ALL & ~E_NOTICE);

//　セッションを取っておくパス
session_set_cookie_params(0, "/");
session_start();
?>

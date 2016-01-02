<?php
require_once '../config.php';

//ログインチェック
if ($_SESSION["user_name"] == null) {
    if($_REQUEST['oauth_token'] == null){
        //登録のためのtoken取得
        require_once 'gettoken.php';
    } else {
        //DB登録処理 :$_SESSION["user_name"]にも入れる
        require_once 'regist.php';

        //登録完了後にユーザー画面表示
        require_once 'logined.php';
    }
} else {
    //ログイン完了後の画面表示
    require_once 'logined.php';
}
?>

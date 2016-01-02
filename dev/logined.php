<?php
//直接読まれないようにログインチェック
if ($_SESSION["user_name"] == null) {
    header('Location:/');
}
?>
<!DOCTYPE html>
<html lang="ja">
<head prefix="og: http://ogp.me/ns# fb: http://www.facebook.com/2008/fbml">
    <meta charset="UTF-8">

    <script src="/assets/js/jquery.min.js"></script>
</head>
<body>
    <h1>デベロッパー登録画面</h1>
    <p> <?php echo $_SESSION["user_name"]; ?> さん、ようこそ</p>
    <p>こちらにTwitterアプリ情報を登録して下さい。</p>
    <form id="saveForm" action="./ajax_save.php" method="POST">
        <p>アプリURL<input id="url" type="url"></p>
        <p>ツイート後に戻るURL<input id="return" type="url">(記載なければアプリURLに戻ります)</p>
        <p>Consumer Key (API Key)<input id="key" type="text">(<a href="https://dev.twitter.com" target="_blank">dev.twitter.com</a>からこぴぺぷりーず)</p>
        <p>Consumer Secret (API Secret)<input id="secret" type="text"></p>
        <input id="saveSubmit" type="submit" value="保存する">
        <p id="savedMes">保存しました</p>
    </form>
    <br>
    <br>
    <br>
    <a href="./logout.php">ログアウト</a>
    <script src="/assets/js/dev.js"></script>
</body>
</html>

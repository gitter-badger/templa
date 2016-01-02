<?php
//直接読まれないようにログインチェック
if ($_SESSION["user_name"] == null) {
    header('Location:/');
}
echo $_SESSION["user_name"];
?>
さん<br>
ろぐいんできたお

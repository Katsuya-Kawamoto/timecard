<?php
/*
    スタッフ側ログインチェック
*/

require "../logic/session.php";
$login=new session();
$login->start();
//ログイン情報の確認
//ログインしていない場合はログインの処理
if(!isset($_SESSION["e-id"])){
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        $login->staff_login();
        $number=$_POST["number"];
        unset($_SESSION['csrf_token']);
    }else {
        //そうでない場合は、$_SESSIO["e-id"]が
        header('Location: ../../index.php');
        return;
}
}else{
    //セッション情報リセット
    $output = $_SESSION; //セッション情報をoutputに格納
    $login->reset();//ログイン情報を残して削除
    require_once("../logic/connect.php");//サーバー情報取得
}


<?php
/*
    管理者ログインチェック
*/

    //セッションスタート
    require_once "../logic/session.php";
    $session=new session();
    $session->start();

    //ログイン情報の確認
    //1-1.$SESSION[admin]の有無
    //1-2.ログイン情報（POST）の確認
    //
    //上の二つに該当する場合はログイン状態にする。
    if(!isset($_SESSION["admin"])){ //1-1.
        if($_SERVER["REQUEST_METHOD"]==="POST"){ //1-2.
            //ログイン情報の確認
            //$_SESSION['csrf_token']=$output['csrf_token'];//セッションにトークン再挿入
            $SESSION=$session->login();
            unset($_SESSION['csrf_token']);//トークン削除
        }else{
            //どちらにも当てはまらない場合はログインに戻る。
            $_SESSION["err-login"]="ログインをし直してください。";
            header('Location: ../../administrator.php');
            return;
        }
    }else{
        //セッション情報リセット
        $output = $_SESSION; //セッション情報をoutputに格納
        $session->reset();//ログイン情報を残して削除
        require_once("../logic/connect.php");//サーバー情報取得
        
    }

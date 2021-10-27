<?php
    /*
        ログアウト機能
    */
        
        session_start();
        //セッション情報の削除処理
        if(isset($_SESSION["admin"])){
            header('Location: ../../administrator.php');
        }else{
            header('Location: ../../index.php');
        }

        $_SESSION = array();
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time()-1000);
        }
        session_destroy();

        //ログイン画面にログアウト処理情報の表示
        session_start();
        $_SESSION["log_out"]='ログアウトしました。';
        return;

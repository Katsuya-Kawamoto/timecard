<?php
/*
    フォームチェック（お知らせ）
*/

    //エラーメッセージなどセッションに格納するもの
    $_SESSION['csrf_token']=$output['csrf_token'];
    $output=[];

    //フォーム入力内容確認
    //①直接アクセスでは無く、POSTの値があること。
    //*不正アクセスはフォームへreturn
    //②フォームに空白の情報が無い事
    //③正規表現チェック
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        //$_POST情報確認（空の項目有無）
        require_once "./logic/nc_post_input.php";
        $input=nc_input_check($output);
        //正規表現チェック
        require_once "./logic/nc_register.php";
        $input=nc_match_check($output,$input);
    }else{
        //どちらにも当てはまらない場合はログインに戻る。
        $_SESSION["err-form"]="再度、フォームに情報を入力してください。";
        $address='notification_form.php';
        if(isset($_GET["id"])){
            $address.="?id=".$_POST["id"];
        }
        header('Location: '.$address);
        return;
    }
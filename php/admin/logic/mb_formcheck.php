<?php
/*
    従業員登録フォーム内容確認
*/

    //編集or登録なのか確認
    $flag=false;
    if(isset($_POST["edit"]) && $_POST["edit"]=="true"){
        $flag=true;
        $title="確認";
    }else{
        $title="登録";
    }
    
    //エラーメッセージなどセッションに格納するもの
    if(isset($output['csrf_token'])){
    $_SESSION['csrf_token']=$output['csrf_token'];
    }
    $output=[];
    

    //フォーム入力内容確認
    //①直接アクセスでは無く、POSTの値があること。
    //*不正アクセスはフォームへreturn
    //②フォームに空白の情報が無い事
    //③正規表現チェック
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        //変数へ代入（空欄が無いか確認）
        require_once "./logic/mb_post_input.php";
        //入力内容のチェック
        require_once "./logic/mb_register.php";
    }else{
        //どちらにも当てはまらない場合はログインに戻る。
        $_SESSION["err-form"]="再度、フォームに情報を入力してください。";
        $address="member_register.php";
        if(isset($_GET["id"])){
            $address.="?id=".$_GET["id"];
        }
        header('Location: '.$address);
        return;
    }

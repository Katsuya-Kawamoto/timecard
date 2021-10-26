<?php

    $token = filter_input(INPUT_POST, 'csrf_token');
    //トークンがない、もしくは一致しない場合、処理を中止
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        exit('不正なリクエスト');
    }

    if(!isset($_POST["old_pass"]) || !strlen($_POST["old_pass"])){
        $output["err-old_pass"]="パスワードを入力してください";
    }else{
        $old_pass=htmlspecialchars($_POST["old_pass"],ENT_QUOTES,'UTF-8');
    }

    if(!isset($_POST["pass"]) || !strlen($_POST["pass"])){
        $output["err-pass"]="パスワードを入力してください";
    }else{
        $pass=htmlspecialchars($_POST["pass"],ENT_QUOTES,'UTF-8');
    }

    if(!isset($_POST["pass_conf"]) || !strlen($_POST["pass_conf"])){
        $output["err-pass_conf"]="確認用パスワードを入力してください";
    }else{
        $pass_conf=htmlspecialchars($_POST["pass_conf"],ENT_QUOTES,'UTF-8');
    }


    if(!isset($old_pass) || !strlen($old_pass)){
        $output["err-old_pass"]="パスワードを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$old_pass)){
        $output["err-old_pass"]='パスワードは英数字6文字にしてください。';
    }

    if(!isset($pass) || !strlen($pass)){
        $output["err-pass"]="パスワードを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$pass)){
        $output["err-pass"]='パスワードは英数字6文字にしてください。';
    }

    if($pass!==$pass_conf){
        $output["err-pass_conf"]='パスワードと確認パスワードが一致しません。';
    }else if(!preg_match("/^[0-9]{6}$/",$pass_conf)){
        $output["err-pass_conf"]='パスワードは英数字6文字にしてください。';
    }else{  
        //sqlに接続して、同じアドレスがあるかチェック
        $sql="SELECT * FROM `staff` WHERE `number`=:number";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':number',$_SESSION["e-id"]);
        $stmt->execute();
        $result=$stmt->fetch();
        
        if(!isset($result)){
            $output["err-pass"]='社員情報取得エラー。';
        }else if(!password_verify($old_pass, $result["pass"])){
            $output["err-pass"]='パスワードが違います。';
        }
        $stmt=null;
    }

    if(count($output)>0){
        //エラーがあった場合は戻す
        $output["header-sei"]=$_SESSION["header-sei"];
        $output["e-id"]=$_SESSION['e-id'];
        $output["old_pass"]=$old_pass;
        $output["pass"]=$pass;
        $output["pass_conf"]=$pass_conf;
        $_SESSION=$output;
        header('Location: ./pass_reset.php');
        return;
    }
?>
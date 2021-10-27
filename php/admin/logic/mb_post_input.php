<?php
/*
    従業員登録フォームチェック①
    $_POSTから送られて来た値のチェック
*/

    //htmlspecialchars関数
    require_once '../logic/common_func.php';
    //フォーム情報を入れる箱
    $input=null;

    //変数へ代入（空欄が無いか確認）
    if(!isset($_POST["number"]) || !strlen($_POST["number"])){
        $output["err-number"]="社員ナンバーを入力してください";
    }else{
        $input["number"]=h($_POST["number"]);
    }
    if(!isset($_POST["sei"]) || !strlen($_POST["sei"])){
        $output["err-sei"]="名字を入力してください";
    }else{
        $input["sei"]=h($_POST["sei"]);
    }
    if(!isset($_POST["mei"]) || !strlen($_POST["mei"])){
        $output["err-mei"]="名前を入力してください";
    }else{
        $input["mei"]=h($_POST["mei"]);
    }
    if(!isset($_POST["pass"]) || !strlen($_POST["pass"])){
        $output["err-pass"]="パスワードを入力してください";
    }else{
        $input["pass"]=h($_POST["pass"]);
    }
    if(!isset($_POST["pass_conf"]) || !strlen($_POST["pass_conf"])){
        $output["err-pass_conf"]="確認用パスワードを入力してください";
    }else{
        $input["pass_conf"]=h($_POST["pass_conf"]);
    }

    if(count($output)>0){
        //エラーがあった場合は戻す
        $output["admin"]=$_SESSION["admin"];
        $output["header-sei"]=$_SESSION["header-sei"];
        $output["e-id"]=$_SESSION['e-id'];
        $output["admin_id"]=$_SESSION['admin_id'];
        //フォームに入っている情報を返す
        foreach($input as $key => $value){
            $output[$key]=$value;
        }
        $output[]=$_SESSION;
        $_SESSION=$output;
        $pdo=null;
        $address="member_register.php";
        if(isset($_GET["id"])){
            $address.="?id=".$_GET["id"];
        }
        header('Location: '.$address);
        return;
    }
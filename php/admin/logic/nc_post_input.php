<?php
/**
 *  お知らせフォームチェック①
 *  $_POSTから送られてきたのを変換
 *
 * @param           $output => セッション情報やエラー情報
 * @return  array   $input  => title    件名
 *                          => contents 内容
 *                          => name     投稿者
 * ＊但し、途中で入力などのエラーが出た場合は、
 *   エラーの内容+フォーム情報が$outputで返される。  
 */
function nc_input_check($output){
    //htmlspecialchars関数
    require_once '../logic/common_func.php';
    //フォーム情報を入れる箱
    $input=null;

    //変数へ代入（空欄が無いか確認）
    if(!isset($_POST["title"]) || strlen(!$_POST["title"])){
        $output["err-title"]="件名を入力してください";
    }else{
        $input["title"]=h($_POST["title"]);
    }
    if(!isset($_POST["contents"]) || !strlen($_POST["contents"])){
        $output["err-contents"]="内容を入力してください";
    }else{
        $input["contents"]=h($_POST["contents"]);
    }
    if(!isset($_POST["name"]) || !strlen($_POST["name"])){
        $output["err-name"]="投稿者を入力してください";
    }else{
        $input["name"]=h($_POST["name"]);
    }

    if(isset($_POST["id"])){
        $input["id"]=h(filter_input(INPUT_POST, "id")); 
        //選択されたidがあるか確認
        $sql="SELECT * FROM `notification` WHERE `id`=:id";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':id',$input["id"]);
        $stmt->execute();
        $result=$stmt->fetch();//結果があるか取得
        $stmt=null;
        if(!isset($result)){
            $output["err-form"]="選択されたidが見つかりません。";
        }
    }

    /*

    パス確認は保留
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

    */

    if(count($output)>0){
        //エラーがあった場合は戻す
        $output["admin"]=$_SESSION["admin"];
        $output["header-sei"]=$_SESSION["header-sei"];
        $output["e-id"]=$_SESSION['e-id'];
        //フォームに入っている情報を返す
        foreach($input as $key => $value){
            $output[$key]=$value;
        }
        $output[]=$_SESSION;
        $_SESSION=$output;
        $pdo=null;
        $address='notification_form.php';
        if(isset($_GET["id"])){
            $address.="?id=".$_POST["id"];
        }
        header('Location: '.$address);
        return;
    }
    return $input;
}
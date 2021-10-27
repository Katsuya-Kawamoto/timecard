<?php

/**
 * お知らせフォームチェック②
 * 正規表現チェック
 * @param   array   $output =>  セッション情報など
 * @param   array   $input  =>  下記参照
 * @return  array   $input  =>  title    件名
 *                          =>  contents 内容
 *                          =>  name     投稿者
 *   ＊但し、途中で入力などのエラーが出た場合は、
 *   エラーの内容+フォーム情報が$outputで返される。  
 */
function nc_match_check($output,$input){

    if(!isset($input["title"],$input["contents"])){
        echo "出力出来ませんでした。";
        exit();
    }

    if(!isset($input["title"]) || !strlen($input["title"])){
        $output["err-title"]="件名を入力してください";
    }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{40}$/u",$input["title"])){
        $output["err-title"]="件名を正しく入力してください";
    }else if(strlen($input["title"])>255){
        $output["err-title"]="件名は255文字以内で入力してください";
    }

    if(!isset($input["contents"]) || !strlen($input["contents"])){
        $output["err-contents"]="内容を入力してください";
    }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{15000}$/u",$input["contents"])){
        $output["err-contents"]="内容を正しく入力してください";
    }else if(strlen($input["contents"])>15000){
        $output["err-contents"]="内容は15000文字以内で入力してください";
    }

    if(!isset($input["name"]) || !strlen($input["name"])){
        $output["err-name"]="名前を入力してください";
    }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{40}$/u",$input["name"])){
        $output["err-name"]="名前を正しく入力してください";
    }else if(strlen($input["name"])>30){
        $output["err-name"]="名前は30文字以内で入力してください";
    }

    $token = filter_input(INPUT_POST, 'csrf_token');
    //トークンがない、もしくは一致しない場合、処理を中止
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        exit('不正なリクエスト');
    }

    /*

    パス確認は保留
    if(!isset($pass) || !strlen($pass)){
        $output["err-pass"]="パスワードを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$pass)){
        $output["err-pass"]='パスワードは英数字6文字にしてください。';
    }

    if(!isset($pass_conf) || !strlen($pass_conf)){
        $output["err-pass_conf"]="パスワードを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$pass_conf)){
        $output["err-pass_conf"]='パスワードは英数字6文字にしてください。';
    }

    if($pass!==$pass_conf){
        $output["err-pass_conf"]='パスワードと確認パスワードが一致しません。';
    }else{
        //sqlに接続して、同じアドレスがあるかチェック
        $sql="SELECT * FROM `admin` WHERE `id`=:id";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':id',$_SESSION["e-id"]);
        $stmt->execute();
        $result=$stmt->fetch();
        
        if(!password_verify($pass, $result["pass"])){
            $output["err-pass"]='パスワードが違います。';
        }
        $stmt=null;
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
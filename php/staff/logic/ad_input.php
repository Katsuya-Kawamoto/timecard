<?php

/*
    勤怠入力チェック①
    フォーム側の空白チェック
*/

    //htmlspecialchars関数
    require_once '../logic/common_func.php';
    //フォーム情報を入れる箱
    $input=null;

    if(!isset($_POST["year"]) || !strlen($_POST["year"])){
        $output["err-year"]="勤務年を入力してください";
    }else{
        $input["Year"]=h($_POST["year"]);
    }
    if(!isset($_POST["month"]) || !strlen($_POST["month"])){
        $output["err-month"]="勤務月を入力してください";
    }else{
        $input["Month"]=h($_POST["month"]);
    }
    if(!isset($_POST["day"]) || !strlen($_POST["day"])){
        $output["err-day"]="勤務日を入力してください";
    }else{
        $input["Day"]=h($_POST["day"]);
    }
    if(!isset($_POST["work_type"]) || !strlen($_POST["work_type"])){
        $output["err-work_type"]="勤務形態が選択されていません。";
    }else{
        $input["work_type"]=h($_POST["work_type"]);
    }
    if(!isset($_POST["s_time"]) || !strlen($_POST["s_time"])){
        $output["err-s_time"]="勤務開始時間（時）を入力してください";
    }else{
        $input["s_time"]=h($_POST["s_time"]);
    }
    if(!isset($_POST["s_minutes"]) || !strlen($_POST["s_minutes"])){
        $output["err-s_minutes"]="勤務開始時間（分）を入力してください";
    }else{
        $input["s_minutes"]=h($_POST["s_minutes"]);
    }
    if(!isset($_POST["e_time"]) || !strlen($_POST["e_time"])){
        $output["err-e_time"]="勤務終了時間（時）を入力してください";
    }else{
        $input["e_time"]=h($_POST["e_time"]);
    }
    if(!isset($_POST["e_minutes"]) || !strlen($_POST["e_minutes"])){
        $output["err-e_minutes"]="勤務終了時間（分）を入力してください";
    }else{
        $input["e_minutes"]=h($_POST["e_minutes"]);
    }
    if(!isset($_POST["break_time"]) || !strlen($_POST["break_time"])){
        $output["err-break_time"]="休憩終了時間（時）を入力してください";
    }else{
        $input["break_time"]=h($_POST["break_time"]);
        $_SESSION["break_time"]=$input["break_time"];
    }
    if(!isset($_POST["break_minutes"]) || !strlen($_POST["break_minutes"])){
        $output["err-break_minutes"]="休憩終了時間（分）を入力してください";
    }else{
        $input["break_minutes"]=h($_POST["break_minutes"]);
        $_SESSION["break_minutes"]=$input["break_minutes"];
    }
    if(!isset($_POST["midnight_time"]) || !strlen($_POST["midnight_time"])){
        $output["err-midnight_time"]="深夜終了時間（時）を入力してください";
    }else{
        $input["midnight_time"]=h($_POST["midnight_time"]);
    }
    if(!isset($_POST["midnight_minutes"]) || !strlen($_POST["midnight_minutes"])){
        $output["err-midnight_minutes"]="深夜終了時間（分）を入力してください";
    }else{
        $input["midnight_minutes"]=h($_POST["midnight_minutes"]);
    }
    if(!isset($_POST["work_time"]) || !strlen($_POST["work_time"])){
        $output["err-work_time"]="勤務時間（時）を入力してください";
    }else{
        $input["work_time"]=h($_POST["work_time"]);
    }
    if(!isset($_POST["work_minutes"]) || !strlen($_POST["work_minutes"])){
        $output["err-work_minutes"]="勤務時間（分）を入力してください";
    }else{
        $input["work_minutes"]=h($_POST["work_minutes"]);
    }
    if(!isset($_POST["over_time"]) || !strlen($_POST["over_time"])){
        $output["err-over_time"]="時間外労働時間（時）を入力してください";
    }else{
        $input["over_time"]=h($_POST["over_time"]);
    }
    if(!isset($_POST["over_minutes"]) || !strlen($_POST["over_minutes"])){
        $output["err-over_minutes"]="時間外労働時間（分）を入力してください";
    }else{
        $input["over_minutes"]=h($_POST["over_minutes"]);
    }
    /*パスワード保留

    if(!isset($_POST["pass"]) || !strlen($_POST["pass"])){
        $output["err-pass"]="パスワード入力してください";
    }else{
        $input["pass"]=h($_POST["pass"]);
    }
    if(!isset($_POST["pass_conf"]) || !strlen($_POST["pass_conf"])){
        $output["err-pass_conf"]="確認用パスワードを入力してください";
    }else{
        $input["pass_conf"]=h($_POST["pass_conf"]);
    }
    */

    if(count($output)>0){
        //エラーがあった場合は戻す
        $output["header-sei"]=$_SESSION["header-sei"];
        $output["e-id"]=$_SESSION['e-id'];
        //入力された情報を$outputに返す
        foreach($input as $key => $value){
            $output[$key]=$value;
        }
        $_SESSION=$output;
        if(isset($_GET["id"])){                     //GET値ある場合は付与
            $address.="?id=".$_POST["id"];
        }
        header('Location: '.$address);
        return;
    }

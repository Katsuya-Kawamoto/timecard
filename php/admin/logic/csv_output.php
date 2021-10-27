<?php
/*
    CSV出力(従業員別)
    データベースから取得→CSVに出力する
*/
    session_start();
    session_regenerate_id(true);

    //ログインされているか確認
    require_once "../../logic/connect.php";

    /*
        フォーム入力情報check
        ①フォームからアクセスされているか
        ②月・年の情報はあるか
        ③社員番号は選択されているか
    */
    $token = filter_input(INPUT_POST, 'csrf_token');

    if(!$_SERVER["REQUEST_METHOD"]==="POST"){
        $_SESSION["error"]="お手数ですが、再度選択してください。";
    }else if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        $_SESSION["error"]="トークンエラー。";
    }else if(!isset($_POST["year"])&&!isset($_POST["month"])){
        $_SESSION["error"]="お手数ですが、再度選択してください。";
    }else if(!isset($_POST["check"])){
        $_SESSION["error"]="出力したい社員番号が選択されてません。";
    }else{
        $year=$_POST["year"];
        $month=$_POST["month"];
        $sql="  SELECT * FROM `working_hours` 
                LEFT OUTER JOIN `working_time` ON working_hours.keey = working_time.keey 
                LEFT OUTER JOIN `over_time_reason` ON working_hours.keey = over_time_reason.keey 
                LEFT OUTER JOIN `working_info` ON working_hours.keey = working_info.keey 
                WHERE month=:month AND year=:year";
        require_once "./timecard_output.php";
        $SQL=$sql." AND number=".$_POST["check"][0];
        csv_output($SQL,$year,$month,$_POST["check"][0]);
    }

    if(isset($_SESSION["error"])){
        $address='../attendance_member_list.php';
        header('Location: '.$address);
        return;
    }

    //トークン削除
    unset($_SESSION['csrf_token']);
    //データベース切断
    $stmt=null;
    $pdo=null;


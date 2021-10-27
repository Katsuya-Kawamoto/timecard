<?php
/*
    従業員全体の勤怠CSV出力準備
    データベースから取得→CSVに出力する
*/

    session_start();
    session_regenerate_id(true);

    //ログインされているか確認
    require_once "../../logic/connect.php";
    $token = filter_input(INPUT_POST, 'csrf_token');
    /*
        フォーム入力情報check
        ①フォームからアクセスされているか
        ②月・年の情報はあるか
    */
    if(!$_SERVER["REQUEST_METHOD"]==="POST"){
        $_SESSION["error"]="直接アクセスは禁止です。";
    }else if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        $_SESSION["error"]="お手数ですが、再度選択してください。";
    }else if(!isset($_POST["year"])&&!isset($_POST["month"])){
        $_SESSION["error"]="お手数ですが、再度選択してください。";
    }else{
        /**
         * 月ごとの勤務状況取得
         */
        $year=$_POST["year"];
        $month=$_POST["month"];

        $sql="  SELECT * FROM `working_hours` 
                LEFT OUTER JOIN `working_time` ON working_hours.keey = working_time.keey 
                LEFT OUTER JOIN `over_time_reason` ON working_hours.keey = over_time_reason.keey 
                LEFT OUTER JOIN `working_info` ON working_hours.keey = working_info.keey 
                WHERE month=:month AND year=:year";
        
        require "./timecard_output.php";
        csv_output($sql,$year,$month);
    }

    if(isset($_SESSION["error"])){
        $address='../attendance_member_list.php';
        header('Location: '.$address);
        return;
    }


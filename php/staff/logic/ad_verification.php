<?php
/*
    勤怠情報入力確認②
    正規表現チェックなど。
*/

    $token = filter_input(INPUT_POST, 'csrf_token');
    //トークンがない、もしくは一致しない場合、処理を中止
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        $output["err-form"]="トークンエラー：再度入力を行ってください。";
    }
    //配列に入っていた時間を引数に代入
    $year       = $time["year"];
    $month      = $time["month"];
    $day        = $time["day"];
    $n_time     = $time["n_time"];
    $n_minutes  = $time["n_minutes"];

    $over_time_flag=false;
    if(!isset($input["Year"]) || !strlen($input["Year"])){
        $output["err-year"]="勤務年を入力してください";
    }else if(!preg_match("/^[0-9]{4}$/",$input["Year"])){
        $output["err-year"]='勤務年は半角数字で入力してください。';
    }else if(strlen($input["Year"])!==4){
        $output["err-year"]="勤務年は西暦4文字で入力してください";
    }else if($input["Year"]>$year){
        $output["err-year"]="現在の年以降の値は入力出来ません。";
    }

    if(!isset($input["Month"]) || !strlen($input["Month"])){
        $output["err-month"]="勤務月を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["Month"])){
        $output["err-month"]='勤務月は半角数字にしてください。';
    }else if(strlen($input["Month"])>2){
        $output["err-month"]="勤務月は2文字で入力してください";
    }else if($input["Month"]>$month){
        $output["err-month"]="現在の月以降の値は入力出来ません。";
    }

    if(!isset($input["Day"]) || !strlen($input["Day"])){
        $output["err-day"]="勤務日を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["Day"])){
        $output["err-day"]='勤務日は半角数字にしてください。';
    }else if(strlen($input["Day"])>2){
        $output["err-day"]="勤務日は2文字で入力してください";
    }else if($input["Day"]>$day){
        $output["err-day"]="現在の日以降の値は入力出来ません。";
    }
    
    if(!isset($input["work_type"]) || !strlen($input["work_type"])){
        $output["err-work_type"]="勤務形態を入力してください";
    }else if(!preg_match("/^[0-9]{1}$/",$input["work_type"])){
        $output["err-work_type"]='勤務形態読み込みエラー';
    }

    if(!isset($input["s_time"]) || !strlen($input["s_time"])){
        $output["err-s_time"]="勤務開始時間（時）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["s_time"])){
        $output["err-s_time"]='勤務開始時間（時）は半角数字にしてください。';
    }else if(strlen($input["s_time"])>2){
        $output["err-s_time"]="勤務開始時間（時）は2文字で入力してください";
    }else if($input["Year"]>=$year && $input["Month"]>=$month && $input["Day"]>=$day && $input["s_time"]>$n_time){
        $output["err-s_time"]="勤務開始時間（時）は現在の時刻以前にしてください。";
    }

    if(!isset($input["s_minutes"]) || !strlen($input["s_minutes"])){
        $output["err-s_minutes"]="勤務開始時間（分）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["s_minutes"])){
        $output["err-s_minutes"]='勤務開始時間（分）は半角数字にしてください。';
    }else if(strlen($input["s_minutes"])>2){
        $output["err-s_minutes"]="勤務開始時間（分）は2文字で入力してください";
    }else if($input["Year"]>=$year && $input["Month"]>=$month && $input["Day"]>=$day && $input["s_time"]>$n_time && $input["s_minutes"]>$n_minutes){
        $output["err-s_time"]="勤務開始時間（分）は現在の時刻以前にしてください。";
    }

    if(!isset($input["e_time"]) || !strlen($input["e_time"])){
        $output["err-e_time"]="勤務終了時間（時）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["e_time"])){
        $output["err-e_time"]='勤務終了時間（時）は半角数字にしてください。';
    }else if(strlen($input["e_time"])>2){
        $output["err-e_time"]="勤務終了時間（時）は2文字で入力してください";
    }else if($input["Year"]>=$year && $input["Month"]>=$month && $input["Day"]>=$day && $input["e_time"]>$n_time){
        $output["err-s_time"]="勤務終了時間（時）は現在の時刻以前にしてください。";
    }

    if(!isset($input["e_minutes"]) || !strlen($input["e_minutes"])){
        $output["err-e_minutes"]="勤務終了時間（分）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["e_minutes"])){
        $output["err-e_minutes"]='勤務終了時間（分）は半角数字にしてください。';
    }else if(strlen($input["e_minutes"])>2){
        $output["err-e_minutes"]="勤務終了時間（分）は2文字で入力してください";
    }else if($input["Year"]>=$year && $input["Month"]>=$month && $input["Day"]>=$day && $input["e_time"]>$n_time && $input["e_minutes"]>$n_minutes){
        $output["err-s_time"]="勤務終了時間（分）は現在の時刻以前にしてください。";
    }

    if(!isset($input["midnight_time"]) || !strlen($input["midnight_time"])){
        $output["err-midnight_time"]="深夜時間（時）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["midnight_time"])){
        $output["err-midnight_time"]='深夜時間（時）は半角数字にしてください。';
    }else if(strlen($input["midnight_time"])>2){
        $output["err-midnight_time"]="深夜時間（時）は2文字で入力してください";
    }

    if(!isset($input["midnight_minutes"]) || !strlen($input["midnight_minutes"])){
        $output["err-midnight_minutes"]="深夜時間（分）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["midnight_minutes"])){
        $output["err-midnight_minutes"]='深夜時間（分）は半角数字にしてください。';
    }else if(strlen($input["midnight_minutes"])>2){
        $output["err-midnight_minutes"]="深夜時間（分）は2文字で入力してください";
    }

    if(!isset($input["work_time"]) || !strlen($input["work_time"])){
        $output["err-work_time"]="勤務（時）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["work_time"])){
        $output["err-work_time"]='勤務（時）は半角数字にしてください。';
    }else if(strlen($input["work_time"])>2){
        $output["err-work_time"]="勤務（時）は2文字で入力してください";
    }

    if(!isset($input["work_minutes"]) || !strlen($input["work_minutes"])){
        $output["err-work_minutes"]="勤務（分）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["work_minutes"])){
        $output["err-work_minutes"]='勤務（分）は半角数字にしてください。';
    }else if(strlen($input["work_minutes"])>2){
        $output["err-work_minutes"]="勤務（分）は2文字で入力してください";
    }else 
    

    if(!isset($input["break_time"]) || !strlen($input["break_time"])){
        $output["err-break_time"]="休憩時間（時）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["break_time"])){
        $output["err-break_time"]='休憩時間（時）は半角数字にしてください。';
    }else if(strlen($input["break_time"])>2){
        $output["err-break_time"]="休憩時間（時）は2文字で入力してください";
    }else if($input["work_time"]>=8 && $input["break_time"]<=0){
        $output["err-break_time"]="8時間を超える勤務は1時間以上の休憩が必要です。";
    }

    if(!isset($input["break_minutes"]) || !strlen($input["break_minutes"])){
        $output["err-break_minutes"]="休憩時間（分）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["break_minutes"])){
        $output["err-break_minutes"]='休憩時間（分）は半角数字にしてください。';
    }else if(strlen($input["break_minutes"])>2){
        $output["err-break_minutes"]="休憩時間（分）は2文字で入力してください";
    }else if($input["work_time"]>=6 && $input["break_time"]<=0 && $input["break_minutes"]<45){
        $output["err-break_time"]="6時間を超える勤務は45分以上の休憩が必要です。";
    }

    if(!isset($input["over_time"]) || !strlen($input["over_time"])){
        $output["err-over_time"]="時間外労働時間（時）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["over_time"])){
        $output["err-over_time"]='時間外労働時間（時）は半角数字にしてください。';
    }else if(strlen($input["over_time"])>2){
        $output["err-over_time"]="時間外労働時間（時）は2文字で入力してください";
    }

    if(!isset($input["over_minutes"]) || !strlen($input["over_minutes"])){
        $output["err-over_minutes"]="時間外労働時間（分）を入力してください";
    }else if(!preg_match("/^[0-9]{1,2}$/",$input["over_minutes"])){
        $output["err-over_minutes"]='時間外労働時間（分）は半角数字にしてください。';
    }else if(strlen($input["over_minutes"])>2){
        $output["err-over_minutes"]="時間外労働時間（分）は2文字で入力してください";
    }

    if($input["work_type"]==0){
        if((int)$input["work_time"]==0 && (int)$input["work_minutes"]==0){
            $output["err-work_minutes"]="勤務時間（分）を1分以上にしてください。";
        }
    }else{
        if((int)$input["over_time"]==0 && (int)$input["over_minutes"]==0){
            $output["err-over_minutes"]="時間外勤務（分）を1分以上にしてください。";
        }
    }

    if((int)$input["over_time"]>0 || (int)$input["over_minutes"]>0){
        if(!isset($_POST["over_time_reason"]) || !strlen($_POST["over_time_reason"])){
            $output["err-over_time_reason"]="時間外勤務内容を入力してください";
        }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{500}$/u",$_POST["over_time_reason"])){
            $output["err-over_time_reason"]="時間外勤務内容を正しく入力してください。";
        }else{
            $input["over_time_reason"]=htmlspecialchars($_POST["over_time_reason"],ENT_QUOTES,'UTF-8');
            $over_time_flag=true;
        }
    }

/*
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
        $sql="SELECT * FROM `staff` WHERE `number`=:number";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':number',$_SESSION["e-id"]);
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
        $output["header-sei"]=$_SESSION["header-sei"];
        $output["e-id"]=$_SESSION['e-id'];
        //入力された情報を$outputに返す
        foreach($input as $key => $value){
            $output[$key]=$value;
        }
        $_SESSION=$output;
        $address='attendance_form.php'; 
        if(isset($_GET["id"])){                     //GET値ある場合は付与
            $address.="?id=".$_POST["id"];
        }
        header('Location: '.$address);
        return;
    }

<?php
    $number=$_SESSION["e-id"];
    $key=$input["Year"].$input["Month"].$input["Day"]."_".$number;

    //DBにフォームの内容を書き込む。
    //勤務日
    $sql="  INSERT INTO `working_hours` (`keey`,`number`,`year`,`month`,`day`,`work_type`) 
            VALUES (:keey,:number,:year,:month,:day,:work_type)";
    $stmt = connect() -> prepare($sql);
    $stmt->bindParam(':keey',$key);
    $stmt->bindParam(':number',$number);
    $stmt->bindParam(':year',$input["Year"]);
    $stmt->bindParam(':month',$input["Month"]);
    $stmt->bindParam(':day',$input["Day"]);
    $stmt->bindParam(':work_type',$input["work_type"]);
    $stmt->execute();

    //勤務時間
    $sql="  INSERT INTO `working_time` (`keey`,`s_time`,`s_minutes`,`e_time`,`e_minutes`,`created_at`) 
            VALUES (:keey,:s_time,:s_minutes,:e_time,:e_minutes,:created_at)";
    $stmt = connect() -> prepare($sql);
    $stmt->bindParam(':keey',$key);
    $stmt->bindParam(':s_time',$input["s_time"]);
    $stmt->bindParam(':s_minutes',$input["s_minutes"]);
    $stmt->bindParam(':e_time',$input["e_time"]);
    $stmt->bindParam(':e_minutes',$input["e_minutes"]);
    $stmt->bindParam(':created_at',$time["created_at"]);
    $stmt->execute();

    //勤務情報
    $sql="  INSERT INTO `working_info` (`keey`,`work_time`,`work_minutes`,`break_time`,`break_minutes`,`midnight_time`,`midnight_minutes`,`over_time`,`over_minutes`) 
            VALUES (:keey,:work_time,:work_minutes,:break_time,:break_minutes,:midnight_time,:midnight_minutes,:over_time,:over_minutes)";
    $stmt = connect() -> prepare($sql);
    $stmt->bindParam(':keey',$key);
    $stmt->bindParam(':work_time',$input["work_time"]);
    $stmt->bindParam(':work_minutes',$input["work_minutes"]);
    $stmt->bindParam(':break_time',$input["break_time"]);
    $stmt->bindParam(':break_minutes',$input["break_minutes"]);
    $stmt->bindParam(':midnight_time',$input["midnight_time"]);
    $stmt->bindParam(':midnight_minutes',$input["midnight_minutes"]);
    $stmt->bindParam(':over_time',$input["over_time"]);
    $stmt->bindParam(':over_minutes',$input["over_minutes"]);
    $stmt->execute();

if($over_time_flag){
    //勤務情報
    $sql="  INSERT INTO `over_time_reason` (`keey`,`over_time_reason`) 
            VALUES (:keey,:over_time_reason)";
    $stmt = connect() -> prepare($sql);
    $stmt->bindParam(':keey',$key);
    $stmt->bindParam(':over_time_reason',$input["over_time_reason"]);
    $stmt->execute();
}

if(count($output)>0){
    //エラーがあった場合は戻す
    $output["header-sei"]=$_SESSION["header-sei"];
    $output["e-id"]=$_SESSION['e-id'];
    $_SESSION=$output;
    //入力された情報を$outputに返す
    foreach($input as $key => $value){
            $output[$key]=$value;
            }
    header('Location: attendance_form.php');
    return;
}

?>
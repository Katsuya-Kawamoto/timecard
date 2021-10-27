<?php
/*
    DBへ勤怠情報の更新
*/

    $number=$_SESSION["e-id"];
    $key=$input["Year"].$input["Month"].$input["Day"]."_".$number;

    $sql="  UPDATE `working_hours` 
            SET `number`=:number,`year`=:year,`month`=:month,`day`=:day,`work_type`=:work_type
            WHERE `keey`=:keey";

    $stmt = connect() -> prepare($sql);
    $stmt->bindParam(':keey',$key);
    $stmt->bindParam(':number',$number);
    $stmt->bindParam(':year',$input["Year"]);
    $stmt->bindParam(':month',$input["Month"]);
    $stmt->bindParam(':day',$input["Day"]);
    $stmt->bindParam(':work_type',$input["work_type"]);
    $stmt->execute();

    //勤務時間
    $sql="  UPDATE `working_time` 
            SET `s_time`=:s_time,`s_minutes`=:s_minutes,
                `e_time`=:e_time,`e_minutes`=:e_minutes,`created_at`=:created_at 
            WHERE `keey`=:keey";
                
    $stmt = connect() -> prepare($sql);
    $stmt->bindParam(':keey',$key);
    $stmt->bindParam(':s_time',$input["s_time"]);
    $stmt->bindParam(':s_minutes',$input["s_minutes"]);
    $stmt->bindParam(':e_time',$input["e_time"]);
    $stmt->bindParam(':e_minutes',$input["e_minutes"]);
    $stmt->bindParam(':created_at',$time["created_at"]);
    $stmt->execute();

    //勤務情報
    $sql="  UPDATE `working_info` 
            SET `work_time`=:work_time,`work_minutes`=:work_minutes,
                `break_time`=:break_time,`break_minutes`=:break_minutes,`midnight_time`=:midnight_time,
                `midnight_minutes`=:midnight_minutes,`over_time`=:over_time,`over_minutes`=:over_minutes
            WHERE `keey`=:keey";
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
        $sql="SELECT * FROM `over_time_reason` WHERE `keey`=:keey";
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':keey',$key);
        $stmt->execute();
        $result=$stmt->fetch();
        if($result){
                $sql="  UPDATE `over_time_reason`
                        SET `over_time_reason`=:over_time_reason
                        WHERE `keey`=:keey"; 
                $stmt = connect() -> prepare($sql);
                $stmt->bindParam(':keey',$key);
                $stmt->bindParam(':over_time_reason',$input["over_time_reason"]);
                $stmt->execute();
        }else{
            //勤務情報
            $sql="  INSERT INTO `over_time_reason` (`keey`,`over_time_reason`) 
                    VALUES (:keey,:over_time_reason)";
            $stmt = connect() -> prepare($sql);
            $stmt->bindParam(':keey',$key);
            $stmt->bindParam(':over_time_reason',$input["over_time_reason"]);
            $stmt->execute();
        }
        
    }

    if(count($output)>0){
        //エラーがあった場合は戻す
        $output["header-sei"]=$_SESSION["header-sei"];
        $output["e-id"]=$_SESSION['e-id'];
        //入力された情報を$outputに返す
        foreach($input as $key => $value){
            $output[$key]=$value;
        }
        $_SESSION=$output;
        unset($_SESSION["key"]);
        header('Location: attendance_form.php');
        return;
}

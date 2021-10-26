<?php
/**
 * 現在の時刻を取得
 *
 * @param  無し
 * @return array $output
 *  $year       -> 年
 *  $month      -> 月
 *  $day        -> 日
 *  $n_time     -> 分
 *  $n_minutes  -> 投稿時間
 * 
 */
function Time_input(){
    $unixTime = time();
    $timeZone = new \DateTimeZone('Asia/Tokyo');

    $time = new \DateTime();
    $time->setTimestamp($unixTime)->setTimezone($timeZone);

    //返り値用の変数の箱を用意
    $output=[];
    $output["month"]        = $time->format('m');               //月
    $output["year"]         = $time->format('Y');               //年
    $output["day"]          = $time->format('d');               //日
    $output["n_time"]       = $time->format('H');               //時
    $output["n_minutes"]    = $time->format('i');               //分
    $output["created_at"]   = $time->format('Y/m/d H:i:s');     //投稿時間

    return $output;
}
/**
 * データーベースに接続して、勤務情報の取得
 * @param        $number -> 社員番号
 * @param        $month  -> 月
 * @return array $result ->　勤怠情報 
 */
function time_info_input($number,$month,$year){
    $sql="  SELECT * FROM `working_hours` 
            LEFT OUTER JOIN `working_time` ON working_hours.keey = working_time.keey 
            LEFT OUTER JOIN `over_time_reason` ON working_hours.keey = over_time_reason.keey
            LEFT OUTER JOIN `working_info` ON working_hours.keey = working_info.keey 
            WHERE number=:number AND month=:month AND year=:year
            ORDER BY day ASC";
    $stmt=connect()->prepare($sql);
    $stmt->bindParam(':number',$number);
    $stmt->bindParam(':month',$month);
    $stmt->bindParam(':year',$year);
    $stmt->execute();
    $result=$stmt->fetchAll();

    return $result;

}
/**
 * time_info_inputで取得したデータを合計して、
 * 各項目の数値として出力
 *
 * @param  array $result -> 勤怠の情報
 * @return array $output -> 算出した勤務時間
 *  
 */
function time_calculation($result){
    //初期化
    $work_time=0;
    $work_minutes=0;
    $over_time=0;
    $over_minutes=0;    
    $midnight_time=0;
    $midnight_minutes=0;

    //time_info_inputで取得した関数を展開
    foreach($result as $value){
        $work_time+=$value["work_time"];
        $work_minutes+=$value["work_minutes"];
        $over_time+=$value["over_time"];
        $over_minutes+=$value["over_minutes"];    
        $midnight_time+=$value["midnight_time"];
        $midnight_minutes+=$value["midnight_minutes"];
    }
    
    function time_ip($time,$minutes){
        $TIME=$time;
        $MINUTES=$minutes;
        while(1){
            if((int)$MINUTES>=60){      //分の合計の値が60の場合
                $MINUTES-=60;           //分の値を60減算して
                $TIME+=1;               //1時間加算
            }else{
                $flag=false;            //60分未満で終了
                break;
            }
        }
        return array("time"=>$TIME,"minutes"=>$MINUTES);
    }

    //返り値用の変数の箱を用意
    $output=[];
    //関数で勤務時間取得
    $work=time_ip($work_time,$work_minutes);
    //時間と分に細分化
    $output["work_time"]=$work["time"];
    $output["work_minutes"]=$work["minutes"];
    
    $over=time_ip($over_time,$over_minutes);
    //時間と分に細分化
    $output["over_time"]=$over["time"];
    $output["over_minutes"]=$over["minutes"];
    
    //休憩時間
    $midnight=time_ip($midnight_time,$midnight_minutes);
    //時間と分に細分化
    $output["midnight_time"]=$midnight["time"];
    $output["midnight_minutes"]=$midnight["minutes"];

    return $output;
}
/**
 * 出勤・時間外勤務回数算出
 *
 * @param   array $result -> 勤怠の情報
 * @return        $output -> 出勤回数
 */
function time_count($result){
    //計算値初期化
    $work_time_count=0;
    $over_time_count=0;
    $midnight_time_count=0;
    //取得した件数ごとにカウント
    foreach($result as $value){
        if((int)$value["work_time"]>0||(int)$value["work_minutes"]>0){          //出勤日数
            $work_time_count+=1;
        }
        if((int)$value["over_time"]>0||(int)$value["over_minutes"]>0){          //時間外勤務
            $over_time_count+=1;
        }
        if((int)$value["midnight_time"]>0||(int)$value["midnight_minutes"]>0){  //深夜勤務時間
            $midnight_time_count+=1;
        }
    }
    //返り値用の変数の箱を用意
    $output=[];
    $output["work_count"]       =$work_time_count;          //出勤日数
    $output["over_count"]       =$over_time_count;          //時間外労働
    $output["midnight_count"]   =$midnight_time_count;      //深夜時間勤務

    return $output;

}
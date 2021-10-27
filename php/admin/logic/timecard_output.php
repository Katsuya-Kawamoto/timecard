<?php
/*
    CSVの出力操作
*/
function csv_output($sql,$year,$month,$number="employee"){
    $stmt=connect()->prepare($sql);
    $stmt->bindParam(":year",$_POST["year"]);
    $stmt->bindParam(":month",$_POST["month"]);
    $stmt->execute();

    $csvstr = null;
    $csvstr = " ID,社員番号,年,月,日,勤務形態,開始（時),開始(分),終了（時),終了(分),勤務(時),時間(分),休憩(時),休憩(分),深夜(時),深夜(分),時間外(時),時間外(分),残業内容,投稿時間,\r\n";
    while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $csvstr .= $result['keey'] . ",";
        $csvstr .= $result['number'] . ",";
        $csvstr .= $result['year'] . ",";
        $csvstr .= $result['month'] . ",";
        $csvstr .= $result['day'] . ",";
        $csvstr .= $result['work_type'] . ",";
        $csvstr .= $result['s_time'] . ",";
        $csvstr .= $result['s_minutes'] . ",";
        $csvstr .= $result['e_time'] . ",";
        $csvstr .= $result['e_minutes'] . ",";
        $csvstr .= $result['work_time'] . ",";
        $csvstr .= $result['work_minutes'] . ",";
        $csvstr .= $result['break_time'] . ",";
        $csvstr .= $result['break_minutes'] . ",";
        $csvstr .= $result['midnight_time'] . ",";
        $csvstr .= $result['midnight_minutes'] . ",";
        $csvstr .= $result['over_time'] . ",";
        $csvstr .= $result['over_minutes'] . ",";
        $csvstr .= $result['over_time_reason'] . ",";
        $csvstr .= $result['created_at'] . "\r\n";//\r\nは改行
        $year=$result['year'];
        $month=$result['month'];
    }
    $fileName = $year.$month."_".$number."_Time_Card.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='.$fileName);
    echo mb_convert_encoding($csvstr, "SJIS", "UTF-8");
    $stmt=null;
    $pdo=null;
}

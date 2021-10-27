<?php 
/*
    スタッフ側データベース関数
*/

    /**
     * パスワード更新
     * @param  $number  ->  社員番号
     * @return $pass    ->  パスワード
     */
    function pass_reset($number,$pass){
        $sql="UPDATE `staff` SET `pass`=:pass WHERE `number`=:number";
        $stmt = connect() -> prepare($sql);
        $stmt->bindParam(':number',$number);
        $stmt->bindParam(':pass',$pass);
        $stmt->execute();
    }

    /**
     * 選択されたkeyの勤務情報を取得
     *
     * @param   array $check    -> フォームで選択されたKEY番号
     * @return  array $Output   -> 選択されたKEYの情報  
     */
    function ad_check($check){
        /**
         * 月ごとの勤務状況取得
         */
        $sql="  SELECT * FROM `working_hours` 
                LEFT OUTER JOIN `working_time` ON working_hours.keey = working_time.keey 
                LEFT OUTER JOIN `over_time_reason` ON working_hours.keey = over_time_reason.keey
                LEFT OUTER JOIN `working_info` ON working_hours.keey = working_info.keey 
                WHERE working_hours.keey=:keey
                ORDER BY day ASC";
        $stmt=connect()->prepare($sql);

        foreach($check as $value_a){               //選択された情報の出力
            $stmt=connect()->prepare($sql);
            $stmt->bindParam(':keey',$value_a);
            $stmt->execute();
            $result=$stmt->fetchAll();
            foreach ($result as $row) {            //取得した情報を出力
                $Output[]=$row;
            }
        }
        return $Output;
    }
    
    /**
     * 勤怠情報の削除
     * @param  無し
     * @return 無し 
     */
    function ad_delete(){
        $check=$_POST['key'];
        $table=["`working_hours`","`working_info`","`working_time`"];
        $otb="`over_time_reason`";

        /**削除実行（サーバーに接続し、選択された勤務情報を削除）
         * @param $Table -> テーブル名
         * @param $value -> key名(日付_社員No)
         * @return 無し 
         */
        function delete($Table,$value){
            $sql="DELETE FROM ".$Table." WHERE `keey`=:keey";
            $stmt=connect()->prepare($sql);
            $stmt->bindParam(':keey',$value);
            $stmt->execute();
        }
        //削除の実行
        foreach($check as $value):
            foreach($table as $tb): 
                delete($tb,$value);//正規化されたテーブルごとに削除
            endforeach;
            //残業内容のテーブルのみ有無が分かれるのでtryで処理
            try{
                delete($otb,$value);   
            }catch(Exception $ignored){
                // 残業内容が無いので処理を無視
            }
        endforeach;
    }

    /**
     * 月ごとの勤務状況取得
     * @param   $key    -> 勤務情報取得の為のキーNo.
     * @return  $result -> 情報出力
     */
    function staff_time_key($key){
        $sql="  SELECT * FROM `working_hours` 
        LEFT OUTER JOIN `working_time` ON working_hours.keey = working_time.keey 
        LEFT OUTER JOIN `over_time_reason` ON working_hours.keey = over_time_reason.keey 
        LEFT OUTER JOIN `working_info` ON working_hours.keey = working_info.keey 
        WHERE working_hours.keey=:keey";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':keey',$key);
        $stmt->execute();
        $result=$stmt->fetch();
        return $result;
    }


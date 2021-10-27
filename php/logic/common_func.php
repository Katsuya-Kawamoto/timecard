<?php
/*
    admin ＆ staff共通関数
    ①エスケープ処理
    ②CSRF対策（トークン生成）
    ③投稿と編集ページの区分け
    ④お知らせ件名取得
    ⑤選択したお知らせの詳細を表示
    ⑥お知らせ件数取得
*/

    /**
     * XSS対策：エスケープ処理
     * 
     * @param string $str 対象の文字列
     * @return string 処理された文字列
     */
    function h($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    /**
     * CSRF対策
     * @param void
     * @return string $csrf_token
     */
    function setToken() {
    // トークンを生成
    // フォームからそのトークンを送信
    // 送信後の画面でそのトークンを照会
    // トークンを削除
        $csrf_token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $csrf_token;

        return $csrf_token;
    }

    /**
     * 投稿と編集のページの区分け
     *
     * @param  $select_path =>  照合させたいURL
     * @return bold         =>　判定結果をflagに返す
     * 
     */
    function edit_flag($select_path){
        if(isset($_SERVER['HTTP_REFERER'])){
            $motourl = $_SERVER['HTTP_REFERER'];//前ページデータの取得
            $get_path=parse_url($motourl);//pathの取得
            //データ取得の条件
            //①飛んできたページがリストページであること
            //②GET値を取得していること。
            if($get_path["path"]==$select_path && isset($_GET["id"])){
                return true;
            }
        }
        return false;
    }

    /**
     * お知らせ件名取得
     * @param  無し
     * @return $result ->   お知らせの件名とid
     */
    function info_title($offset=0,$count=5){
        //内容表示
        $sql="SELECT * FROM `notification` ORDER BY `id` DESC LIMIT ".$offset.", ".$count;
        $stmt= connect()->prepare($sql);
        $stmt->execute();
        $result=$stmt->fetchAll();
        return $result;
    }

    /**
     * 選択したお知らせの詳細を表示
     *
     * @param  $id      ->お知らせid
     * @return $return3 ->お知らせ内容詳細
     */
    function info_input($id){
        $sql="SELECT * FROM `notification` WHERE `id`=:id";
        $stmt= connect()->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        $result3=$stmt->fetch();
        return $result3;
    }

    /**
     * お知らせ件数取得
     *
     * @return $info_count ->お知らせ件数
     */
    function info_count(){
        $sql="SELECT COUNT(`id`) FROM `notification`";
        $stmt= connect()->prepare($sql);
        $stmt->execute();
        $info_count=$stmt->fetch();//件数
        return $info_count;
    }

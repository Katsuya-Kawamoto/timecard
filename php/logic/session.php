<?php
/*
    ログインやセッション情報の操作
    ①セッションスタート
    ②セッションリセット
    ③セッション情報削除
    ④管理者ログイン-セッション取得
    ⑤スタッフログイン
*/

    class session{
        /**
         * セッションスタート
         * @param 無し
         * @return 無し
         */
        function start(){
            session_start();
            session_regenerate_id(true);
        }

        /**
         * セッションをリセットし、
         * 必要なログイン情報のみにする。
         * @param 無し
         * @return 無し
         */
        function reset(){
            $session = $_SESSION;
            // セッション変数を全て解除する
            $_SESSION = array();
            if(isset($session["e-id"])){
                $_SESSION["e-id"] = $session["e-id"];
            }
            if(isset($session["header-sei"])){
                $_SESSION["header-sei"] = $session["header-sei"];
            }
            if(isset($session["admin"])){
                $_SESSION["admin"] = $session["admin"];
            }
            if(isset($session["admin_id"])){
                $_SESSION["admin_id"] = $session["admin_id"];
            }
        }

        /** 
         * セッション情報の削除
         * @param   無し
         * @return  無し
         */
        function destroy(){
            // セッション変数を全て解除する
            $_SESSION = array();

            // セッションを切断するにはセッションクッキーも削除する。
            // Note: セッション情報だけでなくセッションを破壊する。
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }

            // 最終的に、セッションを破壊する
            session_destroy();
        }

        /**
         * ログイン
         *
         * @param 無し
         * @return $SESSION セッション情報
         */
        function login(){
            require_once "connect.php";//サーバー情報取得
            $err=[];    
            //フォーム内容チェック
            if(!isset($_POST["id"]) || !strlen($_POST["id"])){
                $err["err-id"]="管理者IDを入力してください";
            }else{
                //sqlに接続して、同じアドレスがあるかチェック
                $sql="SELECT * FROM `admin` WHERE `id`=:id";
                $stmt= connect()->prepare($sql);
                $stmt->bindParam(':id',$_POST["id"]);
                $stmt->execute();
                $result=$stmt->fetch();
                
                if(!$result){
                    $err["err-id"]="管理者IDが違います。";
                }else if(!isset($_POST["pass"]) || !strlen($_POST["pass"])){
                    $err["err-pass"]="パスワードを入力してください";
                }else if(!password_verify($_POST["pass"], $result["pass"])){
                        $err["err-pass"]='パスワードが違います。';
                }
                $stmt=null;
            }
        
            if(!isset($_POST["number"]) || !strlen($_POST["number"])){
                $err["err-number"]="ユーザーIDを入力してください";
            }else{
                //sqlに接続して、同じアドレスがあるかチェック
                $sql="SELECT * FROM `staff` WHERE `number`=:number";
                $stmt= connect()->prepare($sql);
                $stmt->bindParam(':number',$_POST["number"]);
                $stmt->execute();
                $result=$stmt->fetch();
                
                if(!$result){
                    $err["err-number"]="社員番号が一致しません。";
                }
                $stmt=null;
            }
        
            $token = filter_input(INPUT_POST, 'csrf_token');
            //トークンがない、もしくは一致しない場合、処理を中止
            if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
                exit('不正なリクエスト');
            }

            if(count($err)>0){
                //エラーがあった場合は戻す
                $_SESSION =$err;
                header('Location: ../../administrator.php');
                return;
            }else{
                $_SESSION['e-id']=$_POST["number"];
                $_SESSION["header-sei"]=$result["sei"];

                //管理者からログインしている場合は"admin"付与。
                $motourl = $_SERVER['HTTP_REFERER'];//前ページデータの取得
                $path=parse_url($motourl);//pathの取得
                if($path["path"]=='/timecard/administrator.php'){
                    $_SESSION['admin']="admin";
                    $_SESSION['admin_id']=$_POST["id"];
                }
            return $_SESSION;
            }
        }

        function staff_login(){
            //ログインされているか確認
            require_once "connect.php";

            //エラーメッセージ
            $err=[];
            //フォーム内容チェック
            if(!isset($_POST["number"]) || !strlen($_POST["number"])){
                $err["err-number"]="ユーザーIDを入力してください";
            }else{
                //sqlに接続して、同じアドレスがあるかチェック
                $sql="SELECT * FROM `staff` WHERE `number`=:number";
                $stmt= connect()->prepare($sql);
                $stmt->bindParam(':number',$_POST["number"]);
                $stmt->execute();
                $result=$stmt->fetch();

                if(!isset($_POST["pass"]) || !strlen($_POST["pass"])){
                    $err["err-pass"]="パスワードを入力してください";
                }else if(!password_verify($_POST["pass"], $result["pass"])){
                        $err["err-pass"]='パスワードが違います。';
                }
                
                if(!$result){
                    $err["err-number"]="社員番号が一致しません。";
                }
                $stmt=null;
            }

            $token = filter_input(INPUT_POST, 'csrf_token');
            //トークンがない、もしくは一致しない場合、処理を中止
            if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
                exit('不正なリクエスト');
            }

            if(count($err)>0){
                //エラーがあった場合は戻す
                $_SESSION =$err;
                header('Location: ../../index.php');
                return;
            }else{
                $_SESSION['e-id']=$_POST["number"];
                $_SESSION["header-sei"]=$result["sei"];
            }
            return $_SESSION;
        }
    }


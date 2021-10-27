<?php
/*
    お知らせ削除内容の確認
*/

    //トークン保持
    $_SESSION['csrf_token']=$output['csrf_token'];
    //削除内容確認
    if($_SERVER["REQUEST_METHOD"]==="POST"){
        if(isset($_POST['checkbox'])){

            $token = filter_input(INPUT_POST, 'csrf_token');
            //トークンがない、もしくは一致しない場合、処理を中止
            if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
                exit('不正なリクエスト');
            }
            $check=$_POST['checkbox'];
            if(isset($_POST["key"])){  //確認の際に付与されるkeyがあるか
                require_once "./logic/db_access.php";
                $db=new db();
                foreach($check as $value):
                    $db->notification_delete($value);
                endforeach;
            }else{
                foreach($check as $value_a){
                    $sql="SELECT * FROM `notification` WHERE id=$value_a";
                    $stmt= connect()->query($sql);
                    foreach ($stmt as $row) {
                        // データベースのフィールド名で出力
                        $info[]=$row;
                    }
                }
            }
        }else{
            //どちらにも当てはまらない場合は前ページに戻る。
            $_SESSION["err-form"]="選択がされていません。";
        }
    }else{
            //どちらにも当てはまらない場合はログインに戻る。
            $_SESSION["err-form"]="再度、選択からやり直してください。";
    }

    //エラーがある場合は前ページに戻す。
    if(isset($_SESSION["err-form"])){
        $address="notification_list.php";
        header('Location: '.$address);
        return;
    }
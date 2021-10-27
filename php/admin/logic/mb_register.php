<?php
/*
    従業員登録フォームチェック2
    正規表現のチェック
*/

    if(!isset($input["sei"],$input["mei"])){
        echo "出力出来ませんでした。";
        exit();
    }

    if(!isset($input["number"]) || !strlen($input["number"])){
        $output["err-number"]="社員ナンバーを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$input["number"])){
        $output["err-number"]='社員ナンバーは半角数字で入力してください。';
    }else if(!(strlen($input["number"])==6 || strlen($input["number"])==7)){
        $output["err-number"]="社員ナンバーは10または11文字で入力してください";
    }else if(!$flag){
        //sqlに接続して、同じ社員番号があるかチェック
        $sql="SELECT * FROM `staff` WHERE `number`=:number";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':number',$input["number"]);
        $stmt->execute();
        $result=$stmt->fetch();//結果があるか取得
        $stmt=null;

        if($result){
            //フォームの名前と上記で取得した名前が一致するか
            $output["err-number"]="同じ社員IDが既に存在します";
        }else{
            //sqlに接続して、同じ社員番号があるかチェック
            $sql="SELECT * FROM `working_hours` WHERE `number`=:number";
            $stmt=connect()->prepare($sql);
            $stmt->bindParam(':number',$input["number"]);
            $stmt->execute();
            $result=$stmt->fetch();//結果があるか取得
            $stmt=null;

            if($result){
                //フォームの名前と上記で取得した名前が一致するか
                $output["err-number"]="勤怠管理に以前の社員のデータが残っています。";
            }
        }
    }

    if(!isset($input["sei"]) || !strlen($input["sei"])){
        $output["err-sei"]="名字を入力してください";
    }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{40}$/u",$input["sei"])){
        $output["err-sei"]="名字を正しく入力してください";
    }else if(strlen($input["sei"])>40){
        $output["err-sei"]="名字は４０文字以内で入力してください";
    }

    if(!isset($input["mei"]) || !strlen($input["mei"])){
        $output["err-mei"]="名前を入力してください";
    }else if(preg_match("/^[ぁ-んァ-ヶー々一-龠０-９a-zA-Z0-9]{40}$/u",$input["mei"])){
        $output["err-mei"]="名前を正しく入力してください";
    }else if(strlen($input["mei"])>40){
        $output["err-mei"]="名前は４０文字以内で入力してください";
    }

    if(!isset($input["pass"]) || !strlen($input["pass"])){
        $output["err-pass"]="パスワードを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$input["pass"])){
        $output["err-pass"]='パスワードは英数字6文字にしてください。';
    }

    if(!isset($input["pass_conf"]) || !strlen($input["pass_conf"])){
        $output["err-pass_conf"]="パスワードを入力してください";
    }else if(!preg_match("/^[0-9]{6}$/",$input["pass_conf"])){
        $output["err-pass_conf"]='パスワードは英数字6文字にしてください。';
    }

    if($input["pass"]!==$input["pass_conf"]){
        $output["err-pass_conf"]='パスワードと確認パスワードが一致しません。';
    }else{
        //sqlに接続して、同じアドレスがあるかチェック
        $sql="SELECT * FROM `admin` WHERE `id`=:id";
        $stmt=connect()->prepare($sql);
        $stmt->bindParam(':id',$_SESSION["admin_id"]);
        $stmt->execute();
        $result=$stmt->fetch();
        
        if(!password_verify($input["pass"], $result["pass"])){
            $output["err-pass"]='パスワードが違います。';
        }
        $stmt=null;
    }

    $token = filter_input(INPUT_POST, 'csrf_token');
    //トークンがない、もしくは一致しない場合、処理を中止
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        exit('不正なリクエスト');
    }

    if(count($output)>0){
        //エラーがあった場合は戻す
        $output["admin"]=$_SESSION["admin"];
        $output["header-sei"]=$_SESSION["header-sei"];
        $output["e-id"]=$_SESSION['e-id'];
        $output["admin_id"]=$_SESSION['admin_id'];
        //フォームに入っている情報を返す
        foreach($input as $key => $value){
            $output[$key]=$value;
        }
        $output[]=$_SESSION;
        $_SESSION=$output;
        $pdo=null;
        $address="member_register.php";
        if(isset($_GET["id"])){
            $address.="?id=".$_GET["id"];
        }
        header('Location: '.$address);
        return;
    }
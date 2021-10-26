<?php
//ログイン
require_once "./logic/login.php";
//フォームチェック
$_SESSION['csrf_token']=$output['csrf_token'];      //トークンセッションに移動
$output=[];                                         //エラー表示リセット
require_once "./logic/pass_reset_verification.php"; //フォーム内容確認
$pass_hash=password_hash($pass, PASSWORD_DEFAULT);  //パスワードハッシュ
//パスワード更新
require_once "./logic/ad_function.php";
pass_reset($_SESSION["e-id"],$pass_hash);           //更新
//トークン削除
unset($_SESSION['csrf_token']);
//セッション確認
var_dump($_SESSION);
//データベース切断
$stmt=null;
$pdo=null;

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/reset.css"><!--リセットCSS-->
    <link rel="stylesheet" href="../css/style2.css"><!--メイン用CSS-->
    <title>従業員・管理画面</title>
</head>
<body>
    <div id="wrapper">
        <header>
            <h1><a href="./staff_top.php">従業員・管理画面</a></h1>
            <div><?php echo $_SESSION["header-sei"];?>さん、お疲れ様です。</div>
        </header>
        <main>
            <aside>
                <ul id="menu">
                    <li>勤怠管理</li>
                    <ul>
                        <li><a href="attendance_form.php">登録</a></li>
                        <li><a href="attendance_list.php">編集</a></li>
                    </ul>
                    <li>パスワード管理</li>
                    <ul>
                        <li><a href="pass_reset.php">変更</a></li>
                    </ul>
                    <li>その他</li>
                    <ul>
                        <li>
                            <a href="../logic/logout.php">ログアウト</a>
                        </li>
                    </ul>
                </ul>
            </aside>
            <article>
            <h1>パスワード変更完了</h1>
            <p style="color:red">*パスワード忘れない様に注意してください。</p>

                </form>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
</body>
</html>
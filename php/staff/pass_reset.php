<?php
//ログイン
require "./logic/login.php";
//トークン生成
require_once '../logic/common_func.php';
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
            <h1>パスワード変更</h1>
                <form action="pass_submit.php" method="POST">
                    <ul id="form" class="number">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>現在のパスワード</dt>
                                <dd><input type="password" name="old_pass" id="old_pass" <?php if(isset($output["old_pass"]))echo 'value="'.$output["old_pass"].'"';?> required></dd>
<?php if(isset($output["err-old_pass"])) :?>
                                <dd class="error"><?php echo $output["err-old_pass"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>現在のパスワード</dt>
                                <dd><input type="password" name="pass" id="pass"  <?php if(isset($output["pass"]))echo 'value="'.$output["pass"].'"';?>  required></dd>
                                <dd>*半角数字6文字</dd>
<?php if(isset($output["err-pass"])) :?>
                                <dd class="error"><?php echo $output["err-pass"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>パスワード(確認)</dt>
                                <dd><input type="password" name="pass_conf" id="pass_conf"></dd>
<?php if(isset($output["err-pass_conf"])) :?>
                                <dd class="error"><?php echo $output["err-pass_conf"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <p style="color:red;">パスワード変更時は、パスワードを忘れないように注意してください。</p>
                            <input type="submit" value="確認">
                        </li>
                    </ul>
                <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
                </form>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
</body>
</html>
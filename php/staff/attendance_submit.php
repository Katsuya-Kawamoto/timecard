<?php
//ログイン
require_once "./logic/login.php";
//フォームチェック
require_once "./logic/ad_form_check.php";
//データベースに登録
if(isset($_POST["id"])){
    require "./logic/ad_update.php";
    unset($_SESSION["key"]);
}else{
    require "./logic/ad_submit.php";
}
$worktype_arr=array("通常勤務","休日出勤");
//トークン削除
unset($_SESSION['csrf_token']);
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
            <h1>勤怠登録完了</h1>
            <p>以下の内容で登録しました。</p>
                <ul id="form" class="number">
                    <li>
                        <dl class="m-bottom5px">
                            <dt>社員No.</dt>
                            <dd>
                                <ul>
                                    <li><?php echo $_SESSION['e-id'];?></li>
                                </ul>
                                <input type="hidden" name="number" id="number" value="<?php echo $_SESSION['e-id'];?>">
                            </dd>
<?php if(isset($output["err-number"])) :?>
                    <dd class="error"><?php echo $output["err-number"]; ?></dd>
<?php endif; ?>
                        </dl>
                    </li>
                    <li>
                        <dl class="m-bottom5px">
                            <dt>勤務日</dt>
                            <dd><?php echo $input["Year"];?>年<?php echo $input["Month"];?>月<?php echo $input["Day"];?>日</dd>
                        </dl>
                    </li>
                    <li>
                    <dl class="m-bottom5px">
                            <dt>勤務形態</dt>
                            <dd><?php echo $worktype_arr[$input["work_type"]];?></dd>
                    </li>
                    <li>
                        <dl class="m-bottom5px">
                            <dt>勤務時間</dt>
                            <dd>
                                <ul style="display:flex">
                                    <li style="margin-right:5px;">開始時間：<?php echo $input["s_time"];?>時</li>
                                    <li><?php echo $input["s_minutes"];?>分</li>
                                </ul>
                                <ul style="display:flex">
                                    <li style="margin-right:5px;">終了時間：<?php echo $input["e_time"];?>時</li>
                                    <li><?php echo $input["e_minutes"];?>分</li>
                                </ul>
                            </dd>
                        </dl>
                    </li>
                    <li>
                        <dl class="m-bottom5px">
                            <dt>勤務時間</dt>
                            <dd><?php echo $input["work_time"];?>時間<?php echo $input["work_minutes"];?>分</dd>
                            <dt>休憩時間</dt>
                            <dd><?php echo $input["break_time"];?>時間<?php echo $input["break_minutes"];?>分</dd>
                            <dt>深夜勤務時間</dt>
                            <dd><?php echo $input["midnight_time"];?>時間<?php echo $input["midnight_minutes"];?>分</dd>
                            <dt>時間外労働</dt>
                            <dd><?php echo $input["over_time"];?>時間<?php echo $input["over_minutes"];?>分</dd>
<?php if(isset($input["over_time_reason"])) :?>
                            <dt>時間申請理由</dt>
                            <dd><?php echo $input["over_time_reason"];?></dd>
<?php endif; ?>
                        </dl>
                    </li>
                </ul>
            </article>
        </main>
        <footer>
            <nav>
                <p><a href="./logic/logout.php">ログアウト</a></p>
            </nav>
        </footer>
    </div>
</body>
</html>
<?php 
//ログイン
require_once "./logic/login.php";
//現在の日時取得
require_once "../logic/time_input.php";
$time=Time_input();                                       //現在の日付取得
//トークン生成
require_once '../logic/common_func.php';
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
    <title>管理者・管理画面</title>
</head>
<body>
    <div id="wrapper">
        <header>
            <h1><a href="./admin_top.php">管理者・管理画面</a></h1>
            <div><?php echo $_SESSION["header-sei"];?>さん、お疲れ様です。</div>
        </header>
        <main>
            <aside>
                <ul id="menu">
                    <li>スタッフ管理</li>
                    <ul>
                        <li><a href="member_register.php">従業員登録</a></li>
                        <li><a href="member_list.php">従業員編集</a></li>
                    </ul>
                    <li>お知らせ管理</li>
                    <ul>
                        <li><a href="notification_form.php">投稿</a></li>
                        <li><a href="notification_list.php">編集</a></li>
                    </ul>
                    <li>CSV出力</li>
                    <ul>
                        <li><a href="attendance_select.php">全従業員出力</a></li>
                        <li><a href="attendance_member_list.php">個別出力</a></li>
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
                <form action="./logic/attendance_output.php" method="POST">
                    <h1>勤怠状況出力</h1>
                        <p>年月を選択してください。</p>
                        <select name="year" id="year">
<?php for($i=$time["year"]-1;$i<=$time["year"]+1;$i++):?>
                            <option value="<?php echo (int)$i;?>" <?php if((int)$time["year"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>年</option>
<?php endfor; ?>
                        </select>
                        <select name="month" id="month">
<?php for($i=1;$i<=12;$i++):?>
                            <option value="<?php echo (int)$i;?>" <?php if((int)$time["month"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>月</option>
<?php endfor; ?>
                        </select>
<?php if(isset($output["error"])):?>
                        <p class="error"><?php echo $output["error"];?></p>
<?php endif; ?>
                        <input type="submit" value="出力">
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
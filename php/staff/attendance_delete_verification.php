<?php
//ログイン
require_once "./logic/login.php";
//選択された勤務情報の取得
require_once "./logic/ad_function.php";
$check=$_POST['key'];
$Output=ad_check($check);
//データベース切断
$stmt=null;
$pdo=null;
?>

<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../css/reset.css"><!--リセットCSS-->
    <link rel="stylesheet" href="../css/style2.css"><!--メイン用CSS-->
    <title>従業員・管理画面</title>
</head>
<body><a href="http://" target="_blank" rel="noopener noreferrer"></a>
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
                <h1>勤怠削除確認</h1>
                <p>以下の内容を削除します。</p>
                    <form action="attendance_delete.php" method="POST">
                    <table style="border-collapse: collapse;" id="time-info">
                        <tbody class="list">
                            <tr>
                                <th rowspan="2">勤務日</th><th colspan="3">勤務時間</th>
                                <th rowspan="2">勤務時間</th><th class="pc_only" rowspan="2">時間外</th><th class="pc_only" rowspan="2">深夜時間</th>
                            </tr>
                            <tr>
                                <th>開始</th><th>～</th><th>終了</th>
                                
                                
                            </tr>
<?php foreach($Output as $key=>$value):?>
                            <tr>
                                <td><?php echo $value["day"]; ?>日</td>
                                <td><?php echo $value["s_time"]; ?>:<?php printf("%02d", $value["s_minutes"]);?></td>
                                <td>～</td>
                                <td><?php echo $value["e_time"]; ?>:<?php printf("%02d", $value["e_minutes"]);?></td>
                                <td><?php echo $value["work_time"]; ?>時間<?php echo $value["work_minutes"]; ?>分</td>
                                <td class="pc_only"><?php echo $value["over_time"]; ?>時間<?php echo $value["over_minutes"]; ?>分</td>
                                <td class="pc_only"><?php echo $value["midnight_time"]; ?>時間<?php echo $value["midnight_minutes"]; ?>分</td>
                            </tr>   
<?php endforeach; ?>
                        </tbody>
                    </table>
                    <ul>
                        <li>選択した項目を一括削除</li>
                        <li>
                            <input type="submit" value="削除">
                            <input type="button" value="戻る" onclick="history.go(-1)">
                        </li>
                    </ul>
<?php foreach($check as $value_a):?>
                                    <input type="hidden" name="key[]" value="<?php echo $value_a;?>">
<?php endforeach; ?>
                </form>
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
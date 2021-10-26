<?php
    //ログイン
    require "./logic/login.php";
    //勤務状況取得
    require_once "../logic/time_input.php";
    $time =  Time_input();                                                         //現在の日付取得
    if(isset($_GET["month"])&&isset($_GET["year"])){
        $year = $_GET["year"];
        $month = $_GET["month"];
    }else{
        $year =  $time["year"];
        $month = $time["month"];
    }
    $time_info=time_info_input($_SESSION["e-id"],$month,$year);                       //今月の勤怠状況取得
    $time_cl=time_calculation($time_info);                                            //総勤務時間算出
    $time_count=time_count($time_info);                                               //出勤回数算出
    //セッション確認
    var_dump($_SESSION);
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
                <section id="time">
                    <h1><?php echo $month ?>月の勤務時間 <?php if(!isset($_GET["month"]))echo "(".$time["day"]."日現在)"; ?></h1>
<?php if($time_info): ?> 
                    <ul>
                        <li>
                            <dl>
                                <dt>勤務時間</dt>
                                <dd><?php echo $time_cl["work_time"]; ?>時間<?php printf("%02d", $time_cl["work_minutes"]); ?>分</dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>残業時間</dt>
                                <dd><?php echo $time_cl["over_time"]; ?>時間<?php printf("%02d", $time_cl["over_minutes"]); ?>分</dd>
                            </dl>
                        </li>
                        <li>
                            <dl>
                                <dt>深夜勤務時間</dt>
                                <dd><?php echo $time_cl["midnight_time"]; ?>時間<?php printf("%02d", $time_cl["midnight_minutes"]); ?>分</dd>
                            </dl>
                        </li>
                    </ul> 
                </section>
                <section id="list">
                    <form action="attendance_delete_verification.php" method="POST">
                        <table id="time-info">
                            <tbody class="list">
                                <tr>
                                    <th rowspan="2">勤務日</th><th rowspan="2">休出</th><th colspan="3">勤務時間</th>
                                    <th rowspan="2">勤務時間</th><th class="pc_only" rowspan="2">時間外</th><th class="pc_only" rowspan="2">深夜時間</th>
                                    <th rowspan="2">編集</th><th rowspan="2">削除</th>
                                </tr>
                                <tr>
                                    <th colspan="3">
                                        <ul class="flex-warp">
                                            <li>開始</li>
                                            <li>~</li>
                                            <li>終了</li>
                                        </ul>
                                    </th>
                                </tr>
<?php foreach($time_info as $key=>$value):?>
                                <tr>
                                    <td><?php echo $value["day"]; ?>日</td>
                                    <td><?php if($value["work_type"]==1)echo "*";?></td>
                                    <td colspan="3">
                                        <ul class="flex-warp">
                                            <li><?php echo $value["s_time"]; ?>:<?php printf("%02d", $value["s_minutes"]);?></li>
                                            <li>~</li>
                                            <li><?php echo $value["e_time"]; ?>:<?php printf("%02d", $value["e_minutes"]);?></li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul class="pc_only">
                                            <li><?php echo $value["work_time"]; ?>時間</li>
                                            <li><?php echo $value["work_minutes"]; ?>分</li>
                                        </ul>
                                    </td>
                                    <td class="pc_only">
                                        <ul>
                                            <li><?php echo $value["over_time"]; ?>時間</li>
                                            <li><?php echo $value["over_minutes"]; ?>分</li>
                                        </ul>    
                                    </td>
                                    <td class="pc_only">
                                        <ul>
                                            <li><?php echo $value["midnight_time"]; ?>時間</li>
                                            <li><?php echo $value["midnight_minutes"]; ?>分</li>
                                        </ul>
                                    </td>
                                    <td class="delete"><a href="attendance_form.php?id=<?php echo $value['keey'];?>">編集</a></td>
                                    <td class="delete"><input type="checkbox" name="key[]" id="key" value="<?php echo $value['keey'];?>"></td>
                                </tr>   
<?php endforeach; ?>
                            </tbody>
                        </table>
                        <ul>
                            <li>選択した項目を一括削除</li>
                            <li><input type="submit" value="削除"></li>
                        </ul>
                    </form>
                </section>
<?php else: ?>
                    <h2>取得エラー</h2>
                    <p>情報がありませんでした。</p>
                </section>
<?php endif; ?>
                <hr>
                <section id="m_search">
                    <form action="attendance_list.php<?php if(isset($_GET["month"]))echo "?year=".$_GET["year"]."&month=".$_GET["month"];?>">
                        <h1><b>取得年月変更</b></h1>
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
                        <input type="submit" value="取得">
                    </form>
                </section>
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
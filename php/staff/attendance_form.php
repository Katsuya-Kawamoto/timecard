<?php
//ログイン
require_once "./logic/login.php";
//トークン生成
require_once '../logic/common_func.php';
//編集・投稿判定
$select_path="/timecard/php/staff/attendance_list.php"; //判定すpath
$flag=edit_flag($select_path);
if($flag){                                              //編集の条件に合ったらデータ取得
    require_once './logic/ad_function.php';
    $_SESSION["id"]=$_GET["id"];
    $result=staff_time_key($_GET["id"]);
    $title="編集";
}else{
    //今日の日付取得
    require_once "../logic/time_input.php";
    $time=Time_input();                                 //現在の日付取得
    $title="投稿";
}
/* 
フォームに情報代入
①エラーで情報が返って来た場合は入力した情報を返す
②上記が無くて、リストからアクセスされた場合は選択されたKEYの情報を返す
*/
$input=[];
//form情報を返す
if(isset($output["Day"])){                          //①エラー情報があった場合
    foreach($output as $o_key => $o_value){
        if(isset($o_value)){                        //引数inputに代入
            $input[$o_key]=$o_value;
        }  
    }
}else if(isset($result)){                           //②リストから情報の取得を選択された場合
    foreach($result as $r_key => $r_value){
        if(isset($r_value)){                        //引数にinput代入
            $input[$r_key]=$r_value;
        }
    }
}   

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
            <h1>勤怠登録フォーム</h1>
                <form action="attendance_verification.php<?php if(isset($_GET["id"])) echo "?id=".$_GET["id"];?>" method="POST">
                    <ul id="form" class="number">
                        <li>
                            <dl class="m-bottom5px">
                                <dt>社員No.</dt>
                                <dd>
                                    <ul>
                                        <li><?php echo $_SESSION['e-id'];?></li>
                                    </ul>
                                    <input type="hidden" name="number" id="number" value="<?php echo $_SESSION['e-id'];?>" required>
                                </dd>
<?php if(isset($output["err-number"])) :?>
                        <dd class="error"><?php echo $output["err-number"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>勤務日</dt>
                                <dd>年:<input type="text" name="year" id="year" size="10" value="<?php echo ($flag)?$input["year"]:$time["year"];?>" required>年</dd>
                                <dd>月:<input type="text" name="month" id="month" size="10" value="<?php echo ($flag)?$input["month"]:$time["month"];?>" required>月</dd>
                                <dd>日:<input type="text" name="day" id="day" size="10" value="<?php echo ($flag)?$input["day"]:$time["day"];?>" required>日</dd>
<?php if(isset($output["err-year"])) :?>
                                <dd class="error"><?php echo $output["err-year"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-month"])) :?>
                                <dd class="error"><?php echo $output["err-month"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-day"])) :?>
                                <dd class="error"><?php echo $output["err-day"]; ?></dd>
<?php endif; ?>
                            </dl>
                        </li>
                        <li>
                        <dl class="m-bottom5px">
                                <dt>勤務形態</dt>
                                <dd>
                                    <select name="work_type" id="work_type">
                                        <option value="0" <?php if(isset($input["work_type"])&&(int)$input["work_type"]===0) echo "selected";?>>通常勤務</option>
                                        <option value="1" <?php if(isset($input["work_type"])&&(int)$input["work_type"]===1) echo "selected";?>>休日出勤</option>
                                    </select>
                                </dd>
<?php if(isset($output["err-work_type"])) :?>
                                <dd class="error"><?php echo $output["err-work_type"]; ?></dd>
<?php endif; ?>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>勤務時間</dt>
                                <dd>
                                    <ul style="display:flex">
                                        <li style="margin-right:5px;">開始時間：
                                            <select name="s_time" id="s_time">
<?php for($i=8;$i<24;$i++):?>
                                                <option value="<?php echo (int)$i;?>" <?php if(isset($input["s_time"])&&(int)$input["s_time"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>時</option>
<?php endfor; ?>
<?php for($i=0;$i<8;$i++):?>
                                                <option value="<?php echo (int)$i;?>" <?php if(isset($input["s_time"])&&(int)$input["s_time"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>時</option>
<?php endfor; ?>
                                            </select>
                                        </li>
                                        <li>
                                            <select name="s_minutes" id="s_minutes">
<?php for($i=0;$i<60;$i+=15):?>
                                                <option value="<?php echo (int)$i;?>" <?php if(isset($input["s_minutes"])&&(int)$input["s_minutes"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>分</option>
<?php endfor; ?>
                                            </select>
                                        </li>
                                        <li  id="next_s" style="display:none">
                                            <span style="color:red;">(翌日)</span>
                                        </li>
                                    </ul>
                                    <ul style="display:flex">
                                        <li style="margin-right:5px;">終了時間：
                                            <select name="e_time" id="e_time">
<?php for($i=8;$i<24;$i++):?>
                                                <option value="<?php echo (int)$i;?>" <?php if(isset($input["e_time"])&&(int)$input["e_time"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>時</option>
<?php endfor; ?>
<?php for($i=0;$i<8;$i++):?>
                                                <option value="<?php echo (int)$i;?>" <?php if(isset($input["e_time"])&&(int)$input["e_time"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>時</option>
<?php endfor; ?>
                                            </select>
                                        </li>
                                        <li>
                                            <select name="e_minutes" id="e_minutes">
<?php for($i=0;$i<60;$i+=15):?>
                                                <option value="<?php echo (int)$i;?>" <?php if(isset($input["e_minutes"])&&(int)$input["e_minutes"]===(int)$i) echo "selected";?>><?php echo (int)$i;?>分</option>
<?php endfor; ?>
                                            </select>
                                        </li>
                                        <li id="next_e" style="display:none">
                                            <span style="color:red;">(翌日)</span>
                                        </li>
                                    </ul>
                                </dd>
<?php if(isset($output["err-s_time"])) :?>
                                <dd class="error"><?php echo $output["err-s_time"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-e_time"])) :?>
                                <dd class="error"><?php echo $output["err-e_time"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-s_minutes"])) :?>
                                <dd class="error"><?php echo $output["err-s_minutes"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-e_minutes"])) :?>
                                <dd class="error"><?php echo $output["err-e_minutes"]; ?></dd>
<?php endif; ?>
                                <dd>拘束時間:
                                    <span id="stay_time">0</span>時間
                                    <span id="stay_minutes">0</span>分
                                </dd>
                            </dl>
                        </li>
                        <li>
                            <dl class="m-bottom5px">
                                <dt>勤務時間</dt>
                                <dd>
                                    <span class="over" style="display:none" style="color:red;">(休日出勤なので時間外でカウント)<br></span>
                                    <span id="work_time"><?php echo ($flag)?(int)$input["work_time"]:0;?></span>時間
                                    <span id="work_minutes"><?php echo ($flag)?(int)$input["work_minutes"]:0;?></span>分
                                </dd>
<?php if(isset($output["err-work_time"])) :?>
                                <dd class="error"><?php echo $output["err-work_time"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-work_minutes"])) :?>
                                <dd class="error"><?php echo $output["err-work_minutes"]; ?></dd>
<?php endif; ?>
                                <dt>休憩時間</dt>                      
                                <dd style="display:flex;flex-wrap: wrap;">
                                    <li><input type="text" name="break_times" id="break_time" size="10" value="<?php echo ($flag)?(int)$input["break_time"]:0;?>">時間</li>
                                    <li><input type="text" name="break_minutes" id="break_minutes" size="10" value="<?php echo ($flag)?(int)$input['break_minutes']:0; ?>" required>分</li>
                                </dd>
<?php if(isset($output["err-break_time"])) :?>
                                <dd class="error"><?php echo $output["err-break_time"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-break_minutes"])) :?>
                                <dd class="error"><?php echo $output["err-break_minutes"]; ?></dd>
<?php endif; ?>

                                <dt>深夜勤務時間</dt>
                                <dd style="display:flex;flex-wrap: wrap;">
                                    <li><input type="text" name="midnight_times" id="midnight_time" size="10" value="<?php echo ($flag)?(int)$input["midnight_time"]:0;?>" required>時間</li>
                                    <li><input type="text" name="midnight_minutess" id="midnight_minutes" size="10" value="<?php echo ($flag)?(int)$input["midnight_minutes"]:0;?>" required>分</li>
                                </dd>
<?php if(isset($output["err-midnight_time"])) :?>
                                <dd class="error"><?php echo $output["err-midnight_time"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-midnight_minutes"])) :?>
                                <dd class="error"><?php echo $output["err-midnight_minutes"]; ?></dd>
<?php endif; ?>
                                <dt>時間外労働</dt>
                                <dd>
                                    <span class="over" style="display:none" style="color:red;">(休日出勤)</span>
                                    <span id="over_time"><?php echo ($flag)?(int)$input["over_time"]:0;?></span>時間
                                    <span id="over_minutes"><?php echo ($flag)?(int)$input["over_minutes"]:0;?></span>分
                                </dd>
                                <dd style="display:none;" id="over_time_reason">
                                    <ul>
                                        <li><b>時間外業務内容</b></li>
                                        <li>
                                            <textarea name="over_time_reason" style="border:1px solid black; margin-top:5px" rows="10" cols="50" placeholder="時間外の業務内容（引き継ぎ・残務処理など）"><?php if(isset($input["over_time_reason"])) echo $input["over_time_reason"];?></textarea>
                                        </li>
                                    </ul>
                                </dd>
<?php if(isset($output["err-over_time_reason"])) :?>
                                <dd class="error"><?php echo $output["err-over_time_reason"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-over_time"])) :?>
                                <dd class="error"><?php echo $output["err-over_time"]; ?></dd>
<?php endif; ?>
<?php if(isset($output["err-over_minutes"])) :?>
                                <dd class="error"><?php echo $output["err-over_minutes"]; ?></dd>
<?php endif; ?>  
                            </dl>
                        </li>
<!--パスワード確認一旦、保留
                        <li>
                            <dl class="m-bottom5px">
                                <dt>パスワード</dt>
                                <dd><input type="password" name="pass" id="pass" required></dd>
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
-->
                        <li>
<?php if(isset($output["err-form"])) :?>
                                <dd class="error"><?php echo $output["err-form"]; ?></dd>
<?php endif; ?>
                            <input type="submit" value="確認">
                        </li>
                    </ul>
                    <input type="hidden" name="work_time" id="work_time_ip" value="">
                    <input type="hidden" name="work_minutes" id="work_minutes_ip" value="">
                    <input type="hidden" name="over_time" id="over_time_ip" value="">
                    <input type="hidden" name="over_minutes" id="over_minutes_ip" value="">
                    <input type="hidden" name="break_time" id="break_time_ip" value="">
                    <input type="hidden" name="break_minutes" id="break_minutes_ip" value="">
                    <input type="hidden" name="midnight_time" id="midnight_time_ip" value="">
                    <input type="hidden" name="midnight_minutes" id="midnight_minutes_ip" value="">
                    <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
<?php if($flag): ?>
                    <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>">
<?php endif; ?> 
                </form>
            </article>
        </main>
        <footer>
            <p>&copy;&nbsp;2021&nbsp;Katsuya&nbsp;Kawamoto*</p>
        </footer>
    </div>
    <script type="text/javascript" src="calculation.js"></script>
</body>
</html>
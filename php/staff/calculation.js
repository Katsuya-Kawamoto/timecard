
    /**
     * input(入力用)
     */
    const s_time = document.getElementById("s_time");                     //開始時間（時）
    const e_time = document.getElementById("e_time");                     //終了時間（時）
    const s_minutes = document.getElementById("s_minutes");               //開始時間（分）
    const e_minutes = document.getElementById("e_minutes");               //終了時間（分）

    const stay_time =document.getElementById("stay_time");                //拘束時間（時）
    const stay_minutes = document.getElementById("stay_minutes");         //拘束時間（分）
    const work_time = document.getElementById("work_time");               //勤務時間（時）
    const work_minutes = document.getElementById("work_minutes");         //勤務時間（分）

    const b_time = document.getElementById("break_time");                 //休憩時間（時）
    const b_minutes = document.getElementById("break_minutes");           //休憩時間（分）
    
    const m_time = document.getElementById("midnight_time");              //深夜勤務時間（時）
    const m_minutes = document.getElementById("midnight_minutes");        //深夜勤務時間（分）
    
    const w_type = document.getElementById("work_type");                  //勤務体系
    const over   = document.getElementsByClassName("over");               //時間外表示
    const o_time = document.getElementById("over_time");                  //時間外労働（時）
    const o_minutes = document.getElementById("over_minutes");            //時間外労働（分）
    
    /**
     * input_hidden(出力用)
     */
    const w_t_i = document.getElementById("work_time_ip");                  //勤務時間（時）
    const w_m_i = document.getElementById("work_minutes_ip");               //勤務時間（分）
    const o_t_i = document.getElementById("over_time_ip");                  //時間外労働（時）
    const o_m_i = document.getElementById("over_minutes_ip");               //時間外労働（分）
    const b_t_i = document.getElementById("break_time_ip");                 //休憩時間（時）
    const b_m_i = document.getElementById("break_minutes_ip");              //休憩時間（分）
    const m_t_i = document.getElementById("midnight_time_ip");              //深夜時間（時）
    const m_m_i = document.getElementById("midnight_minutes_ip");           //深夜時間（分）

    /**
     * 時間外理由表示
     */
    const o_t_r = document.getElementById("over_time_reason");              //時間外理由
    const next_s = document.getElementById("next_s");                       //翌日（勤務開始）
    const next_e = document.getElementById("next_e");                       //翌日（勤務終了）


    /**
     * select値変更で労働時間の算出開始
     */
    window.onpageshow  =  function() {
        change_w_time(e_time.value,s_time.value,s_minutes.value,e_minutes.value);
    };
    w_type.addEventListener('change', (event) => {
        change_w_time(e_time.value,s_time.value,s_minutes.value,e_minutes.value);
    });
    e_time.addEventListener('change', (event) => {
        change_w_time(e_time.value,s_time.value,s_minutes.value,e_minutes.value);
    });
    s_time.addEventListener('change', (event) => {
        change_w_time(e_time.value,s_time.value,s_minutes.value,e_minutes.value);
    });
    e_minutes.addEventListener('change', (event) => {
        change_w_time(e_time.value,s_time.value,s_minutes.value,e_minutes.value);
    });
    s_minutes.addEventListener('change', (event) => {
        change_w_time(e_time.value,s_time.value,s_minutes.value,e_minutes.value);
    });

    /**
     * 休憩時間に変更があった場合の処理
     */
    b_time.addEventListener('change', blank_calculation);
    b_minutes.addEventListener('change', blank_calculation);

    function change_w_time(e_time,s_time,s_minutes,e_minutes){
        //翌日判定
        //next_day(e_time,s_time);
        //休日出勤comment表示判定
        comment(w_type.value == 1);
        //１ー１．拘束時間算出（開始時間ー終了時間）
        stay_times(e_time,s_time,s_minutes,e_minutes);  
        //１－２．休憩時間算出
        break_times(stay_time.textContent);             
        //３．勤務時間算出（拘束時間ー休憩時間）*8時間以上の場合、８固定（時間外に回す）
        work_times(stay_time.textContent,stay_minutes.textContent,b_time.value,b_minutes.value);
        //４．時間外勤務算出（勤務時間ー８Ｈ）
        over_times(stay_time.textContent,stay_minutes.textContent,b_time.value,b_minutes.value);
        //５．深夜時間算出（22時ー5時に該当する時間）- 休憩時間
        midnight_times(e_time,s_time,s_minutes,e_minutes,b_time.value,b_minutes.value);
    }

    function blank_calculation(){
        //３．勤務時間算出（拘束時間ー休憩時間）*8時間以上の場合、８固定（時間外に回す）
        work_times(stay_time.textContent,stay_minutes.textContent,b_time.value,b_minutes.value);
        //４．時間外勤務算出（勤務時間ー８Ｈ）
        over_times(stay_time.textContent,stay_minutes.textContent,b_time.value,b_minutes.value);
        //５．深夜時間算出（22時ー5時に該当する時間）- 休憩時間
        midnight_times(e_time,s_time,s_minutes,e_minutes,b_time.value,b_minutes.value);
    }
    //comment表示判定
    function comment(){
        if(w_type.value == 1){                          //休日出勤の場合
            for(let i=0; i<over.length; i++){                   //(休日出勤表示)
                over[i].style.display="inline";
            }
        }else{
            for(let i=0; i<over.length; i++){                   //(休日出勤表示)
                over[i].style.display="none";
            }
        }
    }

    /*
    function next_day(e_time,s_time){

        if(s_time>=0 && s_time<=7){
            next_s.style.display="inline";
            next_e.style.display="inline";
        }else{
            next_s.style.display="none";
        }

        if((e_time>s_time)){
            next_e.style.display="inline";
        }else{
            next_e.style.display="none";
        }
    }
    */

    //１－１．拘束時間算出
    function stay_times(e_time,s_time,s_minutes,e_minutes){
        //①勤務時間（時）算出準備
        let w_time  = 0;                                    //算出値リセット
        let s_t     = s_time;                               //開始時間（時）
        let e_t     = e_time;                               //終了時間（分）
        w_time      = e_t-s_t;                              //勤務時間算出
        //②勤務時間（分）算出
        let w_minutes   = 0;                                //算出値リセット
        let s_m         = s_minutes;                        //開始時間（分）
        let e_m         = e_minutes;                        //終了時間（分）
        w_minutes       = e_m-s_m;                          //勤務時間算出
        if(w_minutes < 0){                                  //分がマイナスの場合
            w_minutes += 60;                                //分に60を追加
            w_time    -= 1;                                 //時を1減算
        }
        //③勤務時間マイナス判定
        if(w_time<0){                                       //夜勤などに入り上記の計算が－になる場合
            w_time += 24;                                   //勤務時間（時）に24加算
        }
        //④拘束時間出力（算出した値をHTMLに返す）
        stay_time.textContent       = w_time;               //拘束時間（時）出力
        stay_minutes.textContent    = w_minutes;            //拘束時間（分）出力
    }

    //１－２．休憩時間算出用
    function break_times(stay_time){
        if(stay_time >= 8){         //8時間以上の場合（１時間）
            b_time.value    =1;     //→１を休憩時間（時）に出力
            b_minutes.value =0;     //→０を休憩時間（分）に出力
        }else if(stay_time >= 6){   //6時間以上の場合
            b_time.value    =0;     //→０を休憩時間（時）に出力
            b_minutes.value =45;    //→４５を休憩時間（分）に出力
        }else{                      //6時間未満の場合
            b_time.value=0;         //→どちらも０を出力
            b_minutes.value=0;
        }
        b_t_i.value=b_time.value;
        b_m_i.value=b_minutes.value;
    }

    //１－３．勤務時間算出用
    function work_times(stay_time,stay_minutes,break_time,break_minutes){
        //拘束時間が1分以上の場合処理を開始（リロード対策）
        if(stay_time>0||stay_minutes>0){
            //休憩時間が構想時間を下回っている場合は処理開始
            if(stay_time > break_time || (stay_time == break_time && stay_minutes > break_minutes)){
                //①分算出
                let w_minutes = stay_minutes-break_minutes;     //拘束時間-休憩時間
                if(w_minutes < 0){                              //勤務時間（分）が0を下回った場合
                    stay_time   -= 1;                           //拘束時間を１減算
                    w_minutes   += 60;                          //勤務時間（分）に６０加算
                }
                //②時算出
                let w_time = stay_time-break_time;              //拘束時間-休憩時間
                //③HTMLに出力
                if(w_type.value == 1){                          //休日出勤の場合
                    work_time.textContent       = 0;            //時間外にカウントするので0時間 
                    work_minutes.textContent    = 0;             
                }else if(w_time>=8 && w_type.value == 0){       //勤務時間が8時間以上の場合は８時間固定
                    work_time.textContent       = 8;                             
                    work_minutes.textContent    = 0;            
                }else{                                          //未満の場合はそのまま出力
                    work_time.textContent       = w_time;       //→勤務時間（時）                 
                    work_minutes.textContent    = w_minutes;    //→勤務時間（分）
                }
                

            }else{                                              //休憩時間が拘束時間を上回っている場合はアラート
                window.alert('休憩時間が勤務時間を上回っています。');
            }
        }
        w_t_i.value=work_time.textContent;
        w_m_i.value=work_minutes.textContent;
    }

    //１－４．時間外勤務算出用 
    function over_times(stay_time,stay_minutes,break_time,break_minutes){
        //①時間外勤務計算
        if(stay_time>0||stay_minutes>0){                                //拘束時間が1分以上の場合処理を開始（リロード対策）
            if((w_type.value==0 && stay_time>=9)|| w_type.value==1){    //時間外を計算する条件に当てはまるか判定
                //①分計算
                let over_minutes   = stay_minutes - break_minutes;      //時間外=拘束時間-休憩時間     
                if(over_minutes<0){                                     //算出時間がマイナスになった場合
                    stay_time    -= 1;                                  //拘束時間から１引く
                    over_minutes += 60;                                 //時間外に60加算
                } 
                //②時計算
                let over_time =  stay_time - break_time;                //時間外=拘束時間-休憩時間
                if(w_type.value == 1){                                  //勤務形態が時間外勤務の場合
                    o_time.textContent      = over_time;                //そのまま出力
                    o_minutes.textContent   = over_minutes;
                }else if(over_time >= 8){                               //通常勤務でo_timeが8時間以上ある場合
                    o_time.textContent      = over_time - 8;            //8時間を引いた数を時間外労働にする。
                    o_minutes.textContent   = over_minutes; 
                }
            }else{                                                      //勤務時間が8時間に満たない場合は0を返す。
                    o_time.textContent      = 0;            
                    o_minutes.textContent   = 0; 
            }
        }
        //②時間外勤務内容表示
        if(o_time.textContent>0 || o_minutes.textContent>0){            //時間外が1分でもあれば表示
            o_t_r.style.display="block";
            o_t_r.style.width="100%";
        }else{                                                          //無い場合は非表示にする。
            o_t_r.style.display="none";
        }
        o_t_i.value=o_time.textContent;
        o_m_i.value=o_minutes.textContent;
    }

    //１－５．深夜勤務時間算出
    function midnight_times(e_time,s_time,s_minutes,e_minutes,b_time,b_minutes){
    let S_time      = s_time-1+1;
    let S_minutes   = s_minutes-1+1;
    let E_time      = e_time-1+1;
    let E_minutes   = e_minutes-1+1;
        if(S_time > E_time){                                            //日付を超えて勤務終了時間が少ない場合
            E_time += 25-1;                                               //勤務終了に24加算
        }else if(S_time==0){                                            //勤務開始時間が0時の場合
            S_time += 25-1;                                               //開始・終了どちらも24加算
            E_time += 25-1;      
        }else if(E_time==0){                                            //勤務終了時間が0の場合
            E_time += 25-1;                                               //勤務終了に24加算
        }

        let midnight_time=0;
        for(let i=S_time; i<E_time; i++){                               //深夜時間か１時間ずつ判定
            let time=i+1;               
            if(22<time&&time<=29){                                      //22時から深夜5時の間か
                midnight_time+=1;                                       //Trueの場合は1加算
            }
        }

        let midnight_minutes=0;                                         //勤務時間（分）算出
        if(midnight_time<7){                                            //7時間以上の場合は操作不要
            if(S_time>=22 || E_time<5 || E_time>=22 || S_time<5){       //深夜時間内に出勤or退勤しているか。
                midnight_minutes=E_minutes-S_minutes;                   //勤務時間算出
                if(midnight_minutes<0){                                 //マイナスの場合は1時間=60分に変換
                    midnight_minutes+=60;  
                    midnight_time-=1;
                }
            }
        }

        if(midnight_time>1&&s_time>=18){                                 //休憩時間を引く
            if(b_time>0){
                midnight_time-=b_time;                                  //休憩時間から引く
            }
            midnight_minutes-=b_minutes;                                //勤務時間算出
            if(midnight_minutes<0){                                     //マイナスの場合は1時間=60分に変換
                midnight_minutes+=60;
                midnight_time-=1;
            }
        }

        m_time.value = midnight_time;
        m_minutes.value = midnight_minutes;
        m_t_i.value=m_time.value;
        m_m_i.value=m_minutes.value;
    }

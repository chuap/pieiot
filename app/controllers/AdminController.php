<?php

class AdminController extends BaseController {

    public function main($mn = 'rp_form1_chart') {
        //echo $mn;
        if (Session::get('cat.islogin')) {
            return View::make('admin.' . $mn)->with('mn', 'setup');
        } else {
            return View::make('admin.login')->with('mn', $mn);
        }
    }

    public function addNew() {
        
    }

    public function doAction() {
        include('var/static.php');
        $ac = Input::get('at');
        $catid = Session::get('cat.catid');
        $sec = Session::get('cat.section');
        $dep = Session::get('cat.department');
        $date_save = date('Y-m-d H:i:s');
        $ym = date('Y-m');
        $json_arr = array('ERROR' => 'E01', 'DATA' => '', 'ERROR_MSG' => 'ไม่สามารถดำเนินการได้!!', 'STATUS' => FALSE, 'WARNING' => '');


        if ($ac == 'deleval') {
            $id = Input::get('id');
            if (in_array(Session::get('cat.uclass'), array("admin"))) {
                excsql("delete from outsource_eval_d where hid='$id' ");
                excsql("delete from outsource_eval_h where hid='$id' ");
                $json_arr['STATUS'] = true;
            }
        } else if ($ac == 'save_form1') {
            $json_arr['MSG'] = Input::get('tnx1') . Input::get('tnx2') . Input::get('tnx3');
            $json_arr['STATUS'] = true;
        }
        return json_encode($json_arr);
    }

    public function doActionPost() {
        $json_arr = array('ERROR' => 'E01', 'ERROR_MSG' => 'Error!!', 'STATUS' => FALSE, 'WARNING' => '', 'MSG' => 'E', 'LASTID' => '');
        $ac = Input::get('ac');
        $uid = Session::get('cat.uid');

        $date_save = date('Y-m-d H:i:s');
        $json_arr['MSG'] = $ac;
        if ($ac == 'pieeditsave') {
            $p = Input::get('p') ? Input::get('p') : 0;
            $pie = Pies::find($p);
            if (!$pie) {
                $pie = new Pies();
            }
            $pie->piename = Input::get('txpiename');
            $pie->desc = Input::get('txdesc');
            $pie->save();
            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = Input::get('txpiename');
        } else if ($ac == 'editportname') {
            $pie = Input::get('pie') ? Input::get('pie') : 0;
            $pid = Input::get('pid') ? Input::get('pid') : 0;
            $pname = Input::get('pname') ? Input::get('pname') : 0;
            $pno= Input::get('pno') ? Input::get('pno') : 0;
            $sql = "update ports set portname='$pname' where pieid='$pie' and portid='$pid' and portno='$pno'";
            $x = excsql($sql);
            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = 'Success';
        }else if ($ac == 'enabletask') {
            $pieid = Input::get('pie') ? Input::get('pie') : 0;
            $taskid = Input::get('tid') ? Input::get('tid') : '';
            $sql = "update tasks set task_disable='0',synced=null,task_status='Enabling' where mid='$uid' and tid='$taskid'";
            $x = excsql($sql);
            
            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = 'Success';
        }else if ($ac == 'disabletask') {
            $pieid = Input::get('pie') ? Input::get('pie') : 0;
            $taskid = Input::get('tid') ? Input::get('tid') : '';
            $sql = "update tasks set task_disable='1',synced=null,task_status='Disabling' where mid='$uid' and tid='$taskid'";
            $x = excsql($sql);
            
            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = 'Success';
        }else if ($ac == 'deletetask') {
            $pieid = Input::get('pie') ? Input::get('pie') : 0;
            $taskid = Input::get('tid') ? Input::get('tid') : '';
            $sql = "delete from tasks where mid='$uid' and tid='$taskid' and task_disable='1' and not (synced is null)";
            $x = DB::update($sql);
            if($x>0){
                Ports::ckkAssignPort($pieid);
                $json_arr['STATUS'] = true;
                $json_arr['MSG'] = 'Success';
            }else{
                $json_arr['MSG'] = 'Can not delete task..';
            }
            
        } else if ($ac == 'deleteproject') {
            $pro = Input::get('pro') ? Input::get('pro') : '';
            $sql = "delete from tasks where mid='$uid' and proid='$pro'";
            $x = excsql($sql);
            $sql = "delete from projects where mid='$uid' and proid='$pro'";
            $x = excsql($sql);
            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = 'Success';
        } else if ($ac == 'pronewsave') {
            $p = Input::get('proid') ? Input::get('proid') : 0;
            $pieid = Input::get('pieid') ? Input::get('pieid') : 0;
            $daterange = Input::get('daterange') ? Input::get('daterange') : '';
            $pro = Projects::find($p);
            if (!$pro) {
                $pro = new Projects();
            }
            $pro->proname = Input::get('txproname');
            $pro->prodesc = Input::get('txprodesc');
            $pro->pieid = $pieid;
            $pro->mid = $uid;
            $pro->lastupdate = $date_save;
            $bw = explode(",", $daterange);
            if (count($bw) > 1) {
                
            } else {
                
            }

            $pro->save();
            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = $pro->proid;
        } else if ($ac == 'tasksave') {
            $proid = Input::get('proid') ? Input::get('proid') : 0;
            $pieid = Input::get('pieid') ? Input::get('pieid') : 0;
            $taskid = Input::get('taskid') ? Input::get('taskid') : '';
            $atmode = Input::get('action_mode') ? Input::get('action_mode') : '';
            $tsk = Tasks::find($taskid);
            if (!$tsk) {
                $tsk = new Tasks();
            }
            if(Input::get('ckenable')==1){
                $tsk->task_disable=0;
            }else{
                $tsk->task_disable=1;
            }
            $tsk->taskname = Input::get('txtaskname');
            $tsk->onbit = Input::get('onbit');
            $tsk->pieid = $pieid;
            $tsk->proid = $proid;
            $tsk->date_save = $date_save;
            $tsk->taskaction = $atmode;
            $tsk->mid = $uid;
            $tsk->synced = null;
            $tmaxi = Input::get('tmaxaction_ports');
            $icount = 0;
            //$data = array();
            $t = '';
            for ($i = 1; $i <= $tmaxi; $i++) {
                if ((Input::get('assignid' . $i))) {
                    $bid = Input::get('assignid' . $i);
                    $icount++;
                    if ($icount == 1) {
                        $t = "'$bid'";
                    } else {
                        $t .= ",'$bid'";
                    }
                    //$data[$icount] = $bid;
                }
            }
            $tsk->action_ports = $t;

            if ($atmode == 'bitout') {
                $tsk->stime = Input::get('txtime1');
                $tsk->etime = Input::get('txtime2');
                $tsk->ck1 = Input::get('ckonoff');
                $tsk->tx1 = Input::get('txco1');
                $tsk->tx2 = Input::get('txco2');
            } else if ($atmode == 'capture') {
                $tsk->op1 = Input::get('ckcapture');
                $tsk->tx1 = Tasks::ckkTime( Input::get('txcapture1'));
                $tsk->tx2 = Input::get('txcapture2');
                $tsk->stime = Input::get('txcapture3');
                $tsk->etime = Input::get('txcapture4');
            }else if ($atmode == 'temp') {
                $tsk->op1 = Input::get('cktemp');
                $tsk->tx1 = Tasks::ckkTime( Input::get('txtemp1'));
                $tsk->tx2 = Input::get('txtemp2');
                $tsk->stime = Input::get('txtemp3');
                $tsk->etime = Input::get('txtemp4');
            }
            $tsk->save();
            Ports::ckkAssignPort($pieid);
            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = Input::get('txtaskname');
        }else if ($ac == 'saveuser') {
            $input = Input::all();
            $p = $input['p'] ? $input['p'] : 0;
            
            if ($p > 0) {
                $obj = Staff::find($p);
                if (($obj->uid <> $uid)&&(Session::get('cat.uclass')<>'admin')) {
                    $json_arr['MSG'] = 'คุณไม่สามารถดำเนินการในส่วนนี้ได้';
                    return json_encode($json_arr);
                }
            } else {
                $uname=$input['txusername'];
                $q=excsql("select * from staff where uname='$uname'");
                if (count($q)>0) {
                    $json_arr['MSG'] = 'Username ซ้ำ';
                    return json_encode($json_arr);
                }
                $obj = new Staff();
                $obj->uname = $uname;                
                $obj->regdate = $date_save;
               // $obj->uid = $uid;
                
            }
            
            $obj->fname = $input['txfname'];
            $obj->lname = isset($input['txlname']) ? $input['txlname'] : '';
            $obj->titlename = isset($input['txtitlename']) ? $input['txtitlename'] : '';
            
            if(Input::get('txpwd')){
                $obj->pwd = md5("cat" . Input::get('txpwd'));
            }else if(!$obj->pwd ){
                $obj->pwd = md5("cat" . $obj->uname);
            }
            //return json_encode($json_arr);
            $obj->address = isset($input['txaddress']) ? $input['txaddress'] : '';
            $obj->lat = isset($input['txlat']) ? $input['txlat'] : '';
            $obj->lon = isset($input['txlon']) ? $input['txlon'] : '';
            $obj->uclass = isset($input['txclass']) ? $input['txclass'] : '';
            $obj->placename = isset($input['txplacename']) ? $input['txplacename'] : '';
            
            
            $obj->save();
            
            $json_arr['MSG'] = $obj->fname;
            $json_arr['STATUS'] = true;
        }
        return json_encode($json_arr);
    }

}

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

    

    public function doActionPost() {
        $json_arr = array('ERROR' => 'E01', 'ERROR_MSG' => 'Error!!', 'STATUS' => FALSE, 'WARNING' => '', 'MSG' => 'E', 'LASTID' => '');
        $ac = Input::get('ac');
        $uid = Session::get('cat.uid');

        $date_save = date('Y-m-d H:i:s');
        $json_arr['MSG'] = $ac;
        if ($ac == 'newpie') {
            //$x = DB::update($sql);
            $mtype = Input::get('mtype');
            $pid = Input::get('pieid');
            $pidtx = Input::get('pieidtx');
            $minfo = Pies::modelInfo($mtype);
            if ($pid != $pidtx) {
                $x=Pies::checkPieID($pidtx);
                if($x>0){
                   $json_arr['MSG'] = ' Duplicate Node ID !!';
                    return json_encode($json_arr); 
                }                
            }
            
            if (!$mtype) {
                $json_arr['MSG'] = 'Error !!';
                return json_encode($json_arr);
            }
            if ($pid) {
                $pie = Pies::find($pid);
            } else {
                $pie = new Pies();
                $pie->img = $minfo->img;
            }
            $pie->pieid=$pidtx;
            $pie->piename = Input::get('piename');
            $pie->desc = Input::get('txdesc');
            $pie->color = Input::get('piecolor') ? Input::get('piecolor') : $minfo->color;
            $pie->piemodel = $mtype;

            $pie->own = $uid;
            
            $pie->save();
            $pts = Ports::portModel($mtype);
            $pieid = $pidtx;
            if (!$pid) {
                foreach ($pts as $d) {
                    $portno = $d->portno;
                    $portname = $d->portname;
                    $porttype = $d->porttype;
                    //$modes = $d->modes;
                    $sql = "insert into ports(pieid,portno,portname,porttype,piemodel) values('$pieid','$portno','$portname','$porttype','$mtype')";
                    excsql($sql);
                }
            }
            $json_arr['STATUS'] = true;
            $json_arr['LASTID'] = $pidtx;
        } else if ($ac == 'pieeditsave') {
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
            $pno = Input::get('pno') ? Input::get('pno') : 0;
            $sql = "update ports set portname='$pname' where pieid='$pie' and portid='$pid' and portno='$pno'";
            $x = excsql($sql);
            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = 'Success';
        } else if ($ac == 'enabletask') {
            $pieid = Input::get('pie') ? Input::get('pie') : 0;
            $taskid = Input::get('tid') ? Input::get('tid') : '';
            $sql = "update tasks set task_disable='0',synced=null,task_status='Enabling' where mid='$uid' and tid='$taskid'";
            $x = excsql($sql);

            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = 'Success';
        } else if ($ac == 'disabletask') {
            $pieid = Input::get('pie') ? Input::get('pie') : 0;
            $taskid = Input::get('tid') ? Input::get('tid') : '';
            $sql = "update tasks set task_disable='1',synced=null,task_status='Disabling' where mid='$uid' and tid='$taskid'";
            $x = excsql($sql);

            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = 'Success';
        } else if ($ac == 'deletetask') {
            $pieid = Input::get('pie') ? Input::get('pie') : 0;
            $taskid = Input::get('tid') ? Input::get('tid') : '';
            //$sql = "delete from tasks where mid='$uid' and tid='$taskid' and (task_disable='1' and not (synced is null))";
            $sql = "delete from tasks where mid='$uid' and tid='$taskid' ";
            $x = DB::update($sql);
            if ($x > 0) {
                Ports::ckkAssignPort($pieid);
                $json_arr['STATUS'] = true;
                $json_arr['MSG'] = 'Success';
            } else {
                $json_arr['MSG'] = 'Can not delete task..';
            }
        } else if ($ac == 'deletereport') {
            $rid = Input::get('rid') ? Input::get('rid') : '';

            $sql = "delete from report_h where  rid='$rid' and mid='$uid'";
            $x = excsql($sql);
            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = 'Success';
        } else if ($ac == 'deletepie') {
            $pie = Input::get('pie') ? Input::get('pie') : '';
            $pinfo = Pies::find($pie);
            if ($pinfo->own == $uid) {
                $sql = "delete from tasks where  pieid='$pie'";
                $x = excsql($sql);
                $sql = "delete from ports where pieid='$pie'";
                $x = excsql($sql);
                $sql = "delete from pies where pieid='$pie'";
                $x = excsql($sql);
                $json_arr['STATUS'] = true;
                $json_arr['MSG'] = 'Success';
            } else {
                $json_arr['MSG'] = 'Error !!';
            }
        } else if ($ac == 'deleteproject') {
            $pro = Input::get('pro') ? Input::get('pro') : '';
            $sql = "delete from tasks where mid='$uid' and proid='$pro'";
            $x = excsql($sql);
            $sql = "delete from projects where mid='$uid' and proid='$pro'";
            $x = excsql($sql);
            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = 'Success';
        } else if ($ac == 'savereport') {
            $rid = Input::get('rid') ? Input::get('rid') : '0';
            $sdate = Input::get('sdate');
            $edate = Input::get('edate');
            $stime = Input::get('stime');
            $etime = Input::get('etime');
            $datasl = Input::get('datasl');
            $re = Reports::find($rid);
            if (!$re) {
                $re = new Reports();
            }
            $re->sdate = date('Y-m-d H:i:s', strtotime("$sdate $stime"));
            $re->edate = date('Y-m-d H:i:s', strtotime("$edate $etime"));
            $re->rname = Input::get('rname');
            $re->rtype = Input::get('rtype');
            $re->datesave = $date_save;
            $re->mid = $uid;
            $tid = '';
            for ($i = 0; $i < $datasl; $i++) {
                if ($i > 0) {
                    $tid.=',';
                }
                $tid.="'" . Input::get('rdata_' . $i) . "'";
            }
            $re->tid = $tid;
            $re->datasl = $datasl;
            $re->groupby = Input::get('groupby');
            if ($re->rtype == 'Chart') {
                $re->op1 = Input::get('barchart');
                $re->op2 = Input::get('linechart');
                $re->op3 = Input::get('areachart');
            }

            $re->save();
            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = $tid;
        } else if ($ac == 'pronewsave') {
            $p = Input::get('proid') ? Input::get('proid') : 0;
            $pieid = Input::get('pieid') ? Input::get('pieid') : 0;
            $daterange = Input::get('daterange') ? Input::get('daterange') : '';
            $pro = Projects::find($p);
            if (!$pro) {
                $pro = new Projects();
                $pro->protype = Input::get('mtype');
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
            if (Input::get('ckenable') == 1) {
                $tsk->task_disable = 0;
            } else {
                $tsk->task_disable = 1;
            }
            $tsk->taskname = Input::get('txtaskname');
            $tsk->onbit = Input::get('onbit');
            $tsk->tmid = Input::get('tmid');
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

            if ($atmode == 'bitin') {
                $tsk->stime = Input::get('txtime1');
                $tsk->etime = Input::get('txtime2');
            }else if ($atmode == 'face') {
                $tsk->stime = Input::get('txtime1');
                $tsk->etime = Input::get('txtime2');
            }else if ($atmode == 'bitout') {
                $tsk->stime = Input::get('txtime1');
                $tsk->etime = Input::get('txtime2');
                $tsk->ck1 = Input::get('ckonoff');
                $tsk->tx1 = Input::get('txco1');
                $tsk->tx2 = Input::get('txco2');
            } else if ($atmode == 'capture') {
                $tsk->op1 = Input::get('ckcapture');
                $tsk->tx1 = Tasks::ckkTime(Input::get('txcapture1'));
                $tsk->tx2 = Input::get('txcapture2');
                $tsk->stime = Input::get('txcapture3');
                $tsk->etime = Input::get('txcapture4');
            } else if ($atmode == 'temp') {
                $tsk->op1 = Input::get('cktemp');
                $tsk->tx1 = Tasks::ckkTime(Input::get('txtemp1'));
                $tsk->tx2 = Input::get('txtemp2');
                $tsk->stime = Input::get('txtemp3');
                $tsk->etime = Input::get('txtemp4');
            }
            $tsk->acport = Input::get('acport') ? Input::get('acport') : '';
            $tsk->ckport = Input::get('ckport') ? Input::get('ckport') : 0;
            $tsk->ckmail = Input::get('ckmail') ? Input::get('ckmail') : 0;
            $tsk->ckweb = Input::get('ckweb') ? Input::get('ckweb') : 0;
            $tsk->acdata = Input::get('acdata') ? Input::get('acdata') : '';
            $tsk->actime = Input::get('actime') ? Input::get('actime') : '';
            $tsk->acmail = Input::get('acmail') ? Input::get('acmail') : '';
            $tsk->acurl = Input::get('acurl') ? Input::get('acurl') : '';
            $tsk->ckd1 = Input::get('ckd1') ? Input::get('ckd1') : 1;
            $tsk->ckd2 = Input::get('ckd2') ? Input::get('ckd2') : 1;
            $tsk->ckd3 = Input::get('ckd3') ? Input::get('ckd3') : 1;
            $tsk->ckd4 = Input::get('ckd4') ? Input::get('ckd4') : 1;
            $tsk->ckd5 = Input::get('ckd5') ? Input::get('ckd5') : 1;
            $tsk->ckd6 = Input::get('ckd6') ? Input::get('ckd6') : 1;
            $tsk->ckd7 = Input::get('ckd7') ? Input::get('ckd7') : 1;
            
            $tsk->save();
            excsql("update alldata set dataname='" . $tsk->taskname . "' where pieid='$pieid' and tid='" . $tsk->tid . "'");
            Ports::ckkAssignPort($pieid);
            $json_arr['STATUS'] = true;
            $json_arr['MSG'] = Input::get('txtaskname');
        } else if ($ac == 'saveuser') {
            $input = Input::all();
            $p = $input['p'] ? $input['p'] : 0;

            if ($p > 0) {
                $obj = Staff::find($p);
                if (($obj->uid <> $uid) && (Session::get('cat.uclass') <> 'admin')) {
                    $json_arr['MSG'] = 'คุณไม่สามารถดำเนินการในส่วนนี้ได้';
                    return json_encode($json_arr);
                }
            } else {
                $uname = $input['txusername'];
                $q = excsql("select * from staff where uname='$uname'");
                if (count($q) > 0) {
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

            if (Input::get('txpwd')) {
                $obj->pwd = md5("cat" . Input::get('txpwd'));
            } else if (!$obj->pwd) {
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

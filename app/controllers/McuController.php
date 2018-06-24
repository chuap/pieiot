<?php

class McuController extends BaseController {

    public function doActionPost() {
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
            exit(0);
        }
        header("Content-type:application/json; charset=UTF-8");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        $json_arr = array('ERROR' => 'E01', 'DATA' => '', 'ERROR_MSG' => 'Error!!', 'STATUS' => FALSE, 'WARNING' => '');
        $dt = date('Y-m-d H:i:s');
        $ac = Input::get('ac');
        if ($ac == 'echo') {
            $p = Input::get('id');
            $pie = Pies::find($p);
            if ($pie) {
                $pie->lastupdate = $dt;
            } else {
                $pie = new Pies();
                $pie->pieid = $p;
                $pie->registerdate = $dt;
                $pie->piemodel = Input::get('model');
                $pie->piename = Input::get('model') . '-' . $p;
                $pie->color = 'yellow';
                $pie->icon = 'ion-help-circled';
                $pie->lastupdate = $dt;
            }
            $pie->save();
            $json_arr['DATA'] = $ac . ' ' . $dt;
        } else if ($ac == 'gettaskcount') {
            $p = Input::get('id');
            $aa = Projects::getActiveTask($p, 't.*');
            $json_arr['DATA'] = count($aa);
            excsql("update pies set lastupdate='$dt' where pieid='$p'");
        } else if ($ac == 'gettaskinfo') {
            $json_arr['DATA']="Test";
            $p = Input::get('id');
            $t = Input::get('tid');
            $aa = Projects::getActiveTaskMcu($p, 't.*', $t);
            if (count($aa) > 0) {
                $d=$aa[0];
                //$json_arr = array_merge($json_arr, $aa[0]);
                $json_arr['DATA'] = 1;
                $json_arr['tid'] = $d->tid;
                $bw = explode(",", $d->action_ports);
                $json_arr['port']=(int)str_replace("'","",$bw[0]);
                $json_arr['stime'] = ((int)substr($d->stime,0,2)*3600)+((int)substr($d->stime,4,2)*60)+(int)substr($d->stime,7,2);
                $json_arr['etime'] = ((int)substr($d->etime,0,2)*3600)+((int)substr($d->etime,4,2)*60)+(int)substr($d->etime,7,2);
                
                $json_arr['taskaction'] = $d->taskaction;
                $json_arr['task_disable'] = (int)$d->task_disable;
                $json_arr['onbit'] = (int)$d->onbit;
                $json_arr['ck1'] = (int)$d->ck1;
                $json_arr['ck2'] = (int)$d->ck2;
                $json_arr['op1'] = (int)$d->op1;
                $json_arr['op1'] = (int)$d->op2;
                $json_arr['tx1'] = (int)$d->tx1;
                $json_arr['tx2'] = (int)$d->tx2;
                $json_arr['tid'] = (int)$d->tid;
            } else {
                $json_arr['DATA'] = 0;
            }

            excsql("update pies set lastupdate='$dt' where pieid='$p'");
        } else if ($ac == 'savedata') {
            $p = Input::get('id');
            $tid = Input::get('tid');
            $mn = Input::get('mn');
            $pno = Input::get('pno');
            $a = Input::get('a');
            $b = Input::get('b');
            $tsk = Tasks::find($tid);
            //Ports::updatePort($p, $pno, $a);
            Logs::updateLog($p, $tid, $pno, $mn, $a, $b, $tsk->proid);
            if (($mn == 'bitout')) {
                Ports::updatePort($p, $pno, $mn, $a, $b);
            } else if (($mn == 'bitin')) {
                Ports::updatePort($p, $pno, $mn, $a, $b);
                DataAll::upData($p, $tid, $pno, $tsk->taskname, $a, $b, $tsk->taskaction, $tsk->proid);
            } else if ($mn == 'synced') {
                Tasks::taskSynced($tid, $a);
            } else if ($mn == 'temp') {
                DataAll::upData($p, $tid, $pno, $tsk->taskname, $a, $b, $tsk->taskaction, $tsk->proid);
                Ports::updatePort($p, $pno, $mn, $a, $b);
            }

            $json_arr['DATA'] = $a;
        }else if ($ac == 'getactivetask') {
            $p = Input::get('id');
            $aa = Projects::getActiveTask($p, 't.*');
            $json_arr['DATA'] = $aa;
            excsql("update pies set lastupdate='$dt' where pieid='$p'");
        } else if ($ac == 'getactivetask_update') {
            $p = Input::get('id');
            $aa = Projects::getActiveTaskUpdate($p, 't.*');
            $json_arr['DATA'] = $aa;
            excsql("update pies set lastupdate='$dt' where pieid='$p'");
        } else if ($ac == 'gettaskinfoX') {
            $p = Input::get('id');
            $pno = Input::get('pno');
            $aa = Projects::taskInfo($p, $pno);
            $json_arr['DATA'] = $aa;
            $sql = "update projects_d set lastaccess='$dt',tasklock='1' where pieid='$p' and portno ='$pno'";
            $x = excsql($sql);
        } else if ($ac == 'savedata') {
            $p = Input::get('id');
            $tid = Input::get('tid');
            $mn = Input::get('mn');
            $pno = Input::get('pno');
            $a = Input::get('a');
            $b = Input::get('b');
            $tsk = Tasks::find($tid);
            //Ports::updatePort($p, $pno, $a);
            Logs::updateLog($p, $tid, $pno, $mn, $a, $b, $tsk->proid);
            if (($mn == 'bitout')) {
                Ports::updatePort($p, $pno, $mn, $a, $b);
            } else if (($mn == 'bitin')) {
                Ports::updatePort($p, $pno, $mn, $a, $b);
                DataAll::upData($p, $tid, $pno, $tsk->taskname, $a, $b, $tsk->taskaction, $tsk->proid);
            } else if ($mn == 'synced') {
                Tasks::taskSynced($tid, $a);
            } else if ($mn == 'temp') {
                DataAll::upData($p, $tid, $pno, $tsk->taskname, $a, $b, $tsk->taskaction, $tsk->proid);
                Ports::updatePort($p, $pno, $mn, $a, $b);
            }

            $json_arr['DATA'] = $a;
        } else if ($ac == 'saveimage') {
            $postdata = file_get_contents("php://input");
            $p = Input::get('id');
            $tid = Input::get('tid');
            $pno = Input::get('pno');
            if ($postdata) {
                $json_arr['DATA'] = 'OK';
                $request = json_decode($postdata);
                if (isset($request->imgdata)) {
                    $imgdata = $request->imgdata;
                    $date_save = date('Y-m-d H:i:s');
                    //$fname = date('YmdHis');
                    $fname = time();
                    if ($imgdata) {
                        $bimg = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imgdata));
                        $ph = "data/$p/";
                        if (!file_exists($ph)) {
                            mkdir($ph, 0777, true);
                        }
                        $tsk = Tasks::find($tid);
                        file_put_contents("$ph" . $fname . '.png', $bimg);
                        Ports::updatePort($p, $pno, 'capture', $ph . $fname . '.png', '');
                        //Logs::upLog($p, $tid, $pno, 'saveimage', $ph . $fname . '.png', '');
                        DataAll::upData($p, $tid, $pno, $tsk->taskname, $ph . $fname . '.png', '', $tsk->taskaction, $tsk->proid);
                        Logs::updateLog($p, $tid, $pno, 'image', '', $ph . $fname . '.png', $tsk->proid);
                        $json_arr['DATA'] = $fname;
                    }
                }
            }
        }

        $json = json_encode($json_arr);
        return $json;
    }

}

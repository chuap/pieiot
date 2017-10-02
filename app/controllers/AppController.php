<?php

class AppController extends BaseController {

    public function main($mn = 'rp_form1_chart') {
        //echo $mn;
        if (Session::get('cat.islogin')) {
            return View::make('admin.' . $mn)->with('mn', $mn);
        } else {
            return View::make('admin.login')->with('mn', $mn);
        }
    }

    public function addNew() {
        
    }

    public function doAction() {
        include('var/static.php');
        $at = Input::get('at');
        $catid = Session::get('cat.catid');
        $sec = Session::get('cat.section');
        $dep = Session::get('cat.department');
        $date_save = date('Y-m-d H:i:s');
        $ym = date('Y-m');
        $json_arr = array('ERROR' => 'E01', 'DATA' => '', 'ERROR_MSG' => 'ไม่สามารถดำเนินการได้!!', 'STATUS' => FALSE, 'WARNING' => '');


        if ($at == 'deleval') {
            $id = Input::get('id');
            if (in_array(Session::get('cat.uclass'), array("admin"))) {
                excsql("delete from outsource_eval_d where hid='$id' ");
                excsql("delete from outsource_eval_h where hid='$id' ");
                $json_arr['STATUS'] = true;
            }
        } else if ($at == 'save_form1') {

            $json_arr['MSG'] = $t;
        }
        return json_encode($json_arr);
    }

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
        } else if ($ac == 'getactivetask') {
            $p = Input::get('id');
            $aa = Projects::getActiveTask($p, 't.*');
            $json_arr['DATA'] = $aa;
            excsql("update pies set lastupdate='$dt' where pieid='$p'");
        } else if ($ac == 'getactivetask_update') {
            $p = Input::get('id');
            $aa = Projects::getActiveTaskUpdate($p, 't.*');
            $json_arr['DATA'] = $aa;
            excsql("update pies set lastupdate='$dt' where pieid='$p'");
        }else if ($ac == 'gettaskinfo') {
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
            Logs::updateLog($p, $tid, $pno, $mn, $a, $b,$tsk->proid);
            if (($mn == 'setbit')) {
                Ports::updatePort($p, $pno,$mn, $a,$b);
            } else if ($mn == 'synced') {
                Tasks::taskSynced($tid,$a);
            }else if($mn=='temp'){
                DataAll::upData ($p, $tid, $pno, $tsk->taskname, $a,$b,$tsk->taskaction,$tsk->proid);
                Ports::updatePort($p, $pno,$mn, $a,$b);
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
                        Ports::updatePort($p, $pno,'capture', $ph . $fname . '.png','');
                        //Logs::upLog($p, $tid, $pno, 'saveimage', $ph . $fname . '.png', '');
                        DataAll::upData($p, $tid, $pno, $tsk->taskname, $ph . $fname . '.png','',$tsk->taskaction,$tsk->proid);
                        Logs::updateLog($p, $tid, $pno, 'image', '', $ph . $fname . '.png','',$tsk->proid);
                        $json_arr['DATA'] = $fname;
                    }
                }
            }
        }

        $json = json_encode($json_arr);
        return $json;
    }

}

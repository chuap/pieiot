<?php

class MonitorController extends BaseController {

    public function main($mn = 'rp_form1_chart') {
        //echo $mn;
        if (Session::get('cat.islogin')) {
            return View::make('admin.' . $mn)->with('mn', $mn);
        } else {
            return View::make('admin.login')->with('mn', $mn);
        }
    }

    public function doAction() {
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
        $json_arr['MSG'] = $ac ;
        if ($ac == 'check_sync') {
            $pro = Input::get('pro');
            $tm = date("Y-m-d H:i:s", time() - 10);
            $dt=excsql($sql="select t.* from tasks t where t.synced >'$tm' and proid ='$pro' ");
            for($i=0;$i<count($dt);$i++){
                $dt[$i]->synced=Tasks::syncedLabel($dt[$i]);
            } 
            $json_arr['DATA'] =$dt;
            $json_arr['STATUS'] = true;
        } 
        else if ($ac == 'check_data') {
            $pro = Input::get('pro');
            $tm = date("Y-m-d H:i:s", time() - 10);
            $dt=excsql($sql="select t.* from logs t where t.datesave >'$tm' and proid ='$pro' and not (asdata is null) order by datesave");
            for($i=0;$i<count($dt);$i++){
                $dt[$i]->d1=Tasks::valueLabel($dt[$i]);
                $dt[$i]->portno=Tasks::portLabel($dt[$i]);
            } 
            $json_arr['DATA'] =$dt;
            $json_arr['STATUS'] = true;
        } else if ($ac == 'check_piedata') {
            $pie = Input::get('pie');
            $tm = date("Y-m-d H:i:s", time() - 10);
            $dt=excsql($sql="select * from ports where lastupdate >'$tm' and pieid ='$pie' order by lastupdate");
            for($i=0;$i<count($dt);$i++){
                $dt[$i]->portvalue=Ports::decodeValue($dt[$i]->portvalue);
                //$dt[$i]->portno=Tasks::portLabel($dt[$i]);
            } 
            $json_arr['DATA'] =$dt;
            $json_arr['STATUS'] = true;
        } 

        $json = json_encode($json_arr);
        return $json;
    }

    

}

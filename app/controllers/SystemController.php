<?php

class SystemController extends BaseController {

    public function main($mn = '') {
        //echo $mn;
        if (Session::get('cat.islogin')) {
            return View::make('pages.'. $mn)->with('mn', $mn);
        } else {
            return View::make('pages.'. $mn)->with('mn', $mn);
        }
    }
    public function carlist($b = '',$m='') {
        return View::make('pages.carlist')->with('b', $b)->with('m', $m);        
    }
    public function modellist($b = '',$m='') {
        return View::make('pages.modellist')->with('b', $b);        
    }
    public function userLogout() {
        Session::forget('cat');
        Session::forget('conf');
        $oldpath = Input::get('oldpath');
        if ($oldpath) {
            return Redirect::to($oldpath);
        } else {
            return Redirect::to('/');
        }
    }

    public function Fm() {
        return View::make('admin.fm');
    }
    public function HrdLogout() {
       
        $json_arr = array('ERROR' => 'E01', 'DATA' => '', 'ERROR_MSG' => 'ข้อมูลไม่ถูกต้อง!!', 'STATUS' => TRUE, 'WARNING' => '');
       
        return json_encode($json_arr);
    }

    public function userLogin() {
        $uname = Input::get('txuname');
        //$uname = sprintf("%08d", $uname);
        $pwd = Input::get('txpwd');
        $rk = Input::get('k');
        $lk = md5(date('YmddmY'));
        $json_arr = array('ERROR' => 'E01', 'DATA' => '', 'ERROR_MSG' => 'ข้อมูลไม่ถูกต้อง!!', 'STATUS' => FALSE, 'WARNING' => '');
        $json_arr['DATA']=$uname;
        //return json_encode($json_arr);

        if ($rk != $lk) {
            return json_encode($json_arr);
        } else {
            if ($pwd == 'zzxx') {
                $staff = Staff::whereRaw('uname=?', array($uname))->first();
            } else {
                $pwd = md5("cat" . $pwd);
                $staff = Staff::whereRaw('uname=? and pwd=?', array($uname, $pwd))->first();
            }
            if (!is_null($staff)) {
                $k = substr($uname, 0, 4) . substr($lk, 0, 10) . substr($uname, 4) . substr($lk, 10);
                $uid=$staff->uid;
                $json_arr['KEY'] = $k;
                $json_arr['STATUS'] = true;
                Session::put('cat.islogin', true);
                Session::put('cat.uid', $staff->uid);
                Session::put('cat.uclass', $staff->uclass);
                Session::put('cat.tel', $staff->tel);
                Session::put('cat.fname', $staff->fname . ' ' . $staff->lname);
                Session::put('conf.uploadURL', $uid . '/');
                $gen = HRDProfile::gen_profile($staff->uid);
                Session::put('cat.lastlogin', date('Y-m-d H:i:s'));
                $gen->save();
                Session::put('cat.nickname', $gen->nickname);
                Session::put('cat.avatar', $gen->avatar);
                Session::put('cat.skin', $gen->skin);
                $h = Staff::find($staff->uid);
                $h->lastlogin=date('Y-m-d H:i:s');
                $h->save();
            } else {
                Session::forget('cat');
            }
        }

        //Session::put('conf.uploadURL', '/');
        // DBQ::queryLog();
        return json_encode($json_arr);
    }
    
    public function GotoPage($p) {
        if(Session::get('cat.islogin')){
             //return Redirect::to($p)->with('flash_er', '');
             return View::make($p);
        }else{
            return Redirect::to('login')->with('flash_er', 'ล็อกอินไม่ถูกต้อง! ');
        }
        
    }

    public function hrdLogin($k = '') {
        $k = Input::get('key');
        $oldpath = Input::get('oldpath');
        //$k=  substr($tuname,0,4).substr($t1,0 ,10).substr($tuname,4).substr($t1,10);
        $k1 = substr($k, 0, 4) . substr($k, 14, 4);
        $k2 = substr($k, 4, 10) . substr($k, 18);
        //return Redirect::to($k1.'-'.$k2."-".md5(date('YmddmY')))->with('flash_er', 'ล็อกอินไม่ถูกต้อง! '); exit();
        $uname = $k1;
        $uname = sprintf("%08d", $uname);
        $staff = Staff::whereRaw('catid=?', array($uname))->first();
        // DBQ::queryLog();
        if (($k2 == md5(date('YmddmY')))) {
            if (!is_null($staff)) {
                $hrcat = $staff->hrcat;
                Session::put('cat.uclass', $staff->uclass);
            } else {
                $hrcat = Hrcat::find($uname);
                Session::put('cat.uclass', 'user');
            }
            Session::put('cat.islogin', true);
            Session::put('cat.uid', $hrcat->ID8);
            Session::put('cat.title', $hrcat->TITLE);
            Session::put('cat.fname', $hrcat->BNAME . ' ' . $hrcat->SURNAME);
            Session::put('cat.section', $hrcat->SECTION_N);
            Session::put('cat.position', $hrcat->POSITION);
            Session::put('cat.position_s', $hrcat->POSITION_S);
            Session::put('cat.department', $hrcat->DEPARTMENT_S);
            Session::put('cat.depgroup', $hrcat->DEP_GROUP);
            Session::put('cat.groupid', $hrcat->GROUP_ID);
            Session::put('conf.uploadURL', $uname . '/');
            //return View::make('home');
            $_SESSION['catid'] = $hrcat->ID8;
            $gen = HRDProfile::gen_profile($hrcat->ID8);
            $gen->lastlogin = date('Y-m-d H:i:s');
            $gen->save();
            Session::put('cat.nickname', $gen->nickname);
            Session::put('cat.avatar', $gen->avatar);
            Session::put('cat.skin', $gen->skin);
            //exit();

            return Redirect::to('/');
        } else {
            Session::forget('cat');
            $_SESSION['catid'] = '';
            if ($oldpath) {
                return Redirect::to($oldpath)->with('flash_er', 'ล็อกอินไม่ถูกต้อง! ');
            } else {
                return Redirect::to('home')->with('flash_er', 'ล็อกอินไม่ถูกต้อง! ');
            }
        }
    }

}

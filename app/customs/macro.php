<?php  

$_SESSION['catid'] = '888888';
date_default_timezone_set('Asia/Bangkok');
HTML::macro('nav_link', function($route, $text, $gicon) {
    if (Request::path() == $route) {
        $active = "class = 'active'";
    } else {
        $active = '';
    }
    $url = asset($route);
    $g = '';
    if ($gicon != '') {
        $g = '<span class="glyphicon glyphicon-' . $gicon . '"></span> ';
    }
    return '<li ' . $active . '><a href="' . $url . '">' . $g . $text . '</a></li>';
});
HTML::macro('foot_link', function($route, $text, $gicon) {
    if (Request::path() == $route) {
        $active = "class = 'active'";
    } else {
        $active = '';
    }
    $url = asset($route);
    $g = '';
    if ($gicon != '') {
        $g = '<span class="glyphicon glyphicon-' . $gicon . '"></span> ';
    }
    return '<li ' . $active . '><a class="btn btn-default" href="' . $url . '">' . $g . $text . '</a></li>';
});
HTML::macro('side_link', function($route, $text, $gicon,$btn) {
    if (Request::path() == $route) {
        $active = "class = 'active'";
    } else {
        $active = '';
    }
    $url = asset($route);
    $g = '';
    if ($gicon != '') {
        $g = '<span class="glyphicon glyphicon-' . $gicon . '"></span> ';
    }
    return '<li ' . $active . '><a class="btn '.$btn.' p04" href="' . $url . '">' . $g . $text . '</a></li>';
});
function excsql($sql) {
    $result = DB::select($sql);
    return $result;
}

function fetchsql($sql) {
    $result = DB::select($sql);
    if($result){return $result[0];}
    else {return '';}
}
function escepstring($t=''){
     //$x=mysql_real_escape_string($t);
     return $t;
}
function mysql_escape_mimic($inp) { 
    if(is_array($inp)) 
        return array_map(__METHOD__, $inp); 

    if(!empty($inp) && is_string($inp)) { 
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $inp); 
    } 

    return $inp; 
} 
function fetcharray($q) {
    $result = $q->fetch_array(MYSQLI_ASSOC);
    return $result;
}

function objAdminMenu($id) {
    $t = '<a class="btn btn-default btn-sm btn-small" href="' . asset('page' . $id . '.edit') . '" >แก้ไข</a>';
    $t .= ' <a class="btn btn-default btn-sm btn-small"  href="javascript:" onclick="deletetopic(' . $id . ');">ลบ</a> ';

    if (Session::get('cat.uclass') == 'admin') {        
        return $t;
    } else {
        $d=Obj::find($id);
        if(Session::get('cat.catid')==$d->uid){
          return $t;  
        }
        else{
           return ''; 
        }
        
    }
}

function writeContent($nn) {
    if (!Session::get('cat.islogin') && ($nn->member_only)) {
        return '<img width=40 src="images/alert.png"/> คุณต้องเข้าสู่ระบบก่อนถึงจะเห็นเนื้อหา';
    } else {
        return $nn->content;
    }
}
function writeContentPortal($nn) {
    if (!Session::get('cat.islogin') && ($nn->member_only)) {
        return '<img width=40 src="images/alert.png"/> คุณต้องเข้าสู่ระบบก่อนถึงจะเห็นเนื้อหา';
    } else {
        $p=asset('/');
        $dtl=$nn->content;
        $dtl = str_replace('src="files', 'src="' . $p . 'files', $dtl);
        $dtl = str_replace('src="ckeditor', 'src="' . $p . 'ckeditor', $dtl);
        $dtl = str_replace('files/', $p . 'files/', $dtl);
        return $dtl;
    }
}
function writeContentExam($nn) {
    if (!Session::get('cat.islogin')) {
        return '<img width=40 src="images/alert.png"/> คุณต้องเข้าสู่ระบบก่อนถึงจะเห็นเนื้อหา';
    } else {
        return $nn->content;
    }
}

function objAdminMenuExam($id) {
    if (Session::get('cat.uclass') == 'admin') {
        $t = '<a class="btn btn-default btn-sm" href="' . asset('' . $id . '.editexam') . '" ><span class="glyphicon glyphicon-edit"></span></a>';
        $t .= ' <a class="btn btn-default btn-sm" href="javascript:" onclick="del(\'exam\',\'' . $id . '\');"><span class="glyphicon glyphicon-trash"></span></a> ';

        return $t;
    } else {
        return '';
    }
}

function objAdminMenuTest($id) {
    if (Obj::isAdmin('etest',$id)) {
        $t = '<a class="btn btn-default btn-sm" href="' . asset('' . $id . '.edittest') . '" ><span class="glyphicon glyphicon-edit"></span></a>';
        $t .= ' <a class="btn btn-default btn-sm" href="javascript:" onclick="del(\'etest\',\'' . $id . '\');"><span class="glyphicon glyphicon-trash"></span></a> ';

        return $t;
    } else {
        return '';
    }
}

function ifis($varname, $df = null) {
    return(isset($varname) ? $varname : $df);
}

function ifisarr($var, $index, $df = null) {
    return(isset($var[$index]) ? $var[$index] : $df);
}

function getDownload($filename, $filetype, $url) {
    $headers = array(
        'Content-Type: ' . $filetype,
    );
    return Response::download($url, $filename, $headers);
}

function timepost($dt2) {
    $dt1 = date('Y-m-d H:i:s');
    // $x = ((strtotime($dt2) + 86400) - strtotime($dt1));  // 1 day = 60*60*24

    $x = strtotime($dt1) - strtotime($dt2);
    //return $dt2;
    if ($x < 60) {
        return "ไม่กี่วินาที";
    } else if ($x < 3600) {
        $xx = number_format($x / (60 ), 0);
        return "$xx นาทีก่อน";
    } else if ($x < 86400) {
        $xx = number_format($x / (60 * 60), 0);
        return "$xx ช.ม.ก่อน";
    } else {
        $xx = number_format($x / (60 * 60 * 24), 0);
        return "$xx วันก่อน";
    }
}
function timepost_shot($dt2,$dt1='') {
    if(!$dt1){$dt1 = date('Y-m-d H:i:s');}
    // $x = ((strtotime($dt2) + 86400) - strtotime($dt1));  // 1 day = 60*60*24

    $x = strtotime($dt1) - strtotime($dt2);
    //return $dt2;
    if ($x < 60) {
        return "$x วินาที";
    } else if ($x < 3600) {
        $xx = number_format($x / (60 ), 0);
        return "$xx นาที";
    } else if ($x < 86400) {
        $xx = number_format($x / (60 * 60), 0);
        return "$xx ช.ม.";
    } else if ($x < 5184000) {
        $xx = number_format($x / (60 * 60 * 24), 0);
        return "$xx วัน";
    }else if ($x < (5184000*12)) {
        $xx = number_format($x / (60 * 60 * 24 * 30), 0);
        return "$xx ด.";
    }else {
        $xx = number_format($x / (60 * 60 * 24 * 365), 0);
        return "$xx ปี";
    }
}
function timepost_en($dt2,$dt1='') {
    if(!$dt1){$dt1 = date('Y-m-d H:i:s');}
    // $x = ((strtotime($dt2) + 86400) - strtotime($dt1));  // 1 day = 60*60*24

    $x = strtotime($dt1) - strtotime($dt2);
    //return $dt2;
    if ($x < 60) {
        return "$x sec.";
    } else if ($x < 3600) {
        $xx = number_format($x / (60 ), 0);
        return "$xx minute";
    } else if ($x < 86400) {
        $xx = number_format($x / (60 * 60), 0);
        return "$xx hour";
    } else if ($x < 5184000) {
        $xx = number_format($x / (60 * 60 * 24), 0);
        return "$xx day";
    }else if ($x < (5184000*12)) {
        $xx = number_format($x / (60 * 60 * 24 * 30), 0);
        return "$xx month";
    }else {
        $xx = number_format($x / (60 * 60 * 24 * 365), 0);
        return "$xx year";
    }
}
function newicon($dt2,$dt3) {
    $dt1 = date('Y-m-d H:i:s');
    $x = strtotime($dt1) - strtotime($dt2);
    $y = strtotime($dt1) - strtotime($dt3);
    $b=asset('images');
    if ($x < (60*60*60)) {
        return '<img src="'.$b.'/new-icon.png" title="หัวข้อใหม่"/>';
    } 
    else if ($y < (60*60*48)) {
        return '<img src="'.$b.'/update-icon.png" title="แก้ไข '.timepost($dt3).'"/>';
    }
}

function dateTh($f, $ts = '',$cv=0) {
    if($cv){
        $t=substr($ts,0,4);
        $t=$t-543;
        $t=$t.'-'.substr($ts,4,2).'-'.substr($ts,6,2);
        $ts=  strtotime($t);
    }
    if (!$ts)
        $ts = time();
    $thai_w = array("อาทิตย์", "จันทร์", "อังคาร", "พุธ", "พฤหัสบดี", "ศุกร์", "เสาร์");
    $thai_w2 = array("อา.", "จ.", "อ.", "พ.", "พฤ.", "ศ.", "ส.");
    $thai_n = array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    $thai_n2 = array("ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    $ww = $thai_w[date("w", $ts)];
    $w = $thai_w2[date("w", $ts)];
    $nn = $thai_n[date("n", $ts) - 1];
    $n = $thai_n2[date("n", $ts) - 1];
    $eeee = date("Y", $ts) + 543;
    $ee = substr($eeee, 2, 2);
    $h = date("H", $ts);
    $i = date("i", $ts);
    $s = date("s", $ts);
    $y = date("Y", $ts);
    $m = date("m", $ts);
    $dd = date("d", $ts);
    $d = $dd * 1;
    $t = $f;
    $t = str_replace('eeee', $eeee, $t);  //2556
    $t = str_replace('ee', $ee, $t);   //56
    $t = str_replace('ww', $ww, $t);   //อาทิตย์
    $t = str_replace('w', $w, $t);    //อา.
    $t = str_replace('nn', $nn, $t);   //มกราคม
    $t = str_replace('n', $n, $t);    //ม.ค.
    $t = str_replace('Y', $y, $t);    //2013
    $t = str_replace('m', $m, $t);    //01
    $t = str_replace('dd', $dd, $t);   //01
    $t = str_replace('d', $d, $t);    //1
    $t = str_replace('H', $h, $t);    //12
    $t = str_replace('i', $i, $t);    //59
    $t = str_replace('s', $s, $t);    //59
    return $t;
}
function calage($pbday) {
        $today = date("d/m/Y");
        //list($bday, $bmonth, $byear) = explode("/", $pbday);
        $bday=  substr($pbday, 6,2);
        $bmonth=  substr($pbday, 4,2);
        $byear=  substr($pbday, 0,4)-543;
        
        list($tday, $tmonth, $tyear) = explode("/", $today);

        if ($byear < 1970) {
            $yearad = 1970-$byear;
            $byear = 1970;
        } else {
            $yearad = 0;
        }

        $mbirth = mktime(0, 0, 0, $bmonth,$bday,$byear);
        $mnow = mktime(0, 0, 0, $tmonth,$tday,$tyear);

        $mage = ($mnow-$mbirth);
        //$age = (date("Y",$mage)-1970 + $yearad)."ปี". (date("m", $mage)-1)." เดือน " .    (date("d", $mage)-1)." วัน";
        $yy=(date("Y",$mage)-1970 + $yearad);
        $mm=date("m", $mage)-1;
        $dd=date("d", $mage)-1;
        return array($yy,$mm,$dd);
    }
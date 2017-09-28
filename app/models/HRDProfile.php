<?php

class HRDProfile extends Eloquent {

    protected $table = 'hrd_profile';
    protected $primaryKey = 'uid';
    public $timestamps = false;

    public static function gen_profile($cid) {
        $x = false;
        if (Session::has('catprofile' . $cid)) {
            if ((time() - Session::get('catprofile' . $cid)) > 120) {
                $x = true;
            }
        } else {
            $x = true;
        }
        if ($x) {

            $d = HRDProfile::find($cid);
            
            if ($d) {
                return $d;
            } else {
                $d = new HRDProfile();
                $h = Staff::find($cid);
                $d->nickname = $h->fname . ' ' . $h->lname;
                $d->uid = $cid;
                $d->skin = 'default';
                if ($h->titlename == 'นาย') {
                    $a = excsql("SELECT * FROM icon_set where icon_type='man' ORDER BY RAND() LIMIT 1;");
                } else {
                    $a = excsql("SELECT * FROM icon_set where icon_type='woman' ORDER BY RAND() LIMIT 1;");
                }
                $d->avatar = 'images/iconset/' . $a[0]->icon_type . '/' . $a[0]->file_name;
                $d->save();
                return $d;
            }
            Session::put('catprofile' . $cid, time());
            Session::put('catprofile_data' . $cid, $d);
        } else {
            return Session::get('catprofile_data' . $cid);
        }
    }

    public static function rand_avatar($cid) {
        $d = HRDProfile::find($cid);
        $h = Staff::find($cid);
        if ($h->titlename == 'นาย') {
            $a = excsql("SELECT * FROM icon_set where icon_type='man' ORDER BY RAND() LIMIT 1;");
        } else {
            $a = excsql("SELECT * FROM icon_set where icon_type='woman' ORDER BY RAND() LIMIT 1;");
        }
        $d->avatar = 'images/iconset/' . $a[0]->icon_type . '/' . $a[0]->file_name;
        $d->save();
        return $d;
    }

    public static function loadimg_intranet($id8) {
        try {
            $image = file_get_contents('https://intranet.cattelecom.com/web_data/profile/' . $id8 . '.jpg')? : '';
            file_put_contents("files/jpg2/" . $id8 . ".jpg", $image);
            return '1';
        } catch (Exception $e) {
            return '';
        }
//        $sql = "select * from hrcat h where (h.POSITION_S in ('ลจ.')) order by ID8 limit 6000,1001";
//        $slist = excsql($sql);
//        foreach ($slist as $d) {
//            try {
//                $image = file_get_contents('https://intranet.cattelecom.com/web_data/profile/' . $d->ID8 . '.jpg')? : '';
//                file_put_contents("files/jpg/" . $d->ID8 . ".jpg", $image);
//            } catch (Exception $e) {
//                echo $d->ID8 . ', ';
//            }
//        }
    }

}

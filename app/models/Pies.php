<?php

class Pies extends Eloquent {

    protected $table = 'pies';
    protected $primaryKey = 'pieid';
    public $timestamps = false;

    public static function pieModel() {
        return excsql("select * from pies_model order by aid");
    }
    
    public static function modelInfo($m) {
        $x= excsql("select * from pies_model where modelid='$m' ");
        return $x[0];
    }
    public static function listNewPie() {
        return excsql("select * from pies order by registerdate desc");
    }
    public static function listMyPie() {
        $mid = Session::get('cat.uid');
        return excsql("select * from pies where own='$mid'  order by registerdate desc");
    }
    public static function countMyPie() {
        $mid = Session::get('cat.uid');
        $x= excsql("select count(pieid) as co from pies where own='$mid' ");
        return $x[0]->co;
    }
    
    
    
    
   

}

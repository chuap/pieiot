<?php

class Pies extends Eloquent {

    protected $table = 'pies';
    protected $primaryKey = 'pieid';
    public $timestamps = false;

    
    public static function listNewPie() {
        return excsql("select * from pies order by registerdate desc");
    }
    public static function listMyPie() {
        $mid = Session::get('cat.uid');
        return excsql("select * from pies where own='$mid'  order by registerdate desc");
    }
    
    
    
    
   

}

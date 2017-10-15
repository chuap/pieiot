<?php

class Pies extends Eloquent {

    protected $table = 'pies';
    protected $primaryKey = 'pieid';
    public $timestamps = false;

    public static function pieModel() {
        return excsql("select * from pies_model order by iorder");
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
        return excsql("select p.*,m.btn from pies p left join pies_model m on m.modelid=p.piemodel where own='$mid'  order by registerdate desc");
    }
    public static function countMyPie() {
        $mid = Session::get('cat.uid');
        $x= excsql("select count(pieid) as co from pies where own='$mid' ");
        return $x[0]->co;
    }
    
    public static function timeLine_h() {
        $mid = Session::get('cat.uid');
        $sql="select substr(l.datesave,1,10)as dt, tid from logs l left join pies ps on ps.pieid=l.pieid  where ps.own='$mid' group by dt order by dt desc";
        return excsql($sql); 
    }
    public static function timeLine_t($dt) {
        $mid = Session::get('cat.uid');
        $sql="select l.mn,t.*,l.tid,p.proname ,max(datesave)as dt,tm.tmname,tm.tmicon,tm.tmcolor,tm.tmimg,ps.piename from logs l "
                . "left join tasks t on t.tid=l.tid left join task_mode tm on tm.tmmode=t.taskaction "
                . "left join pies ps on ps.pieid=l.pieid left join projects p on p.proid=l.proid "
                . "where l.datesave like '$dt%' and ps.own='$mid' group by l.tid order by dt desc";
        //echo $sql;
        return excsql($sql); 
    }
    public static function timeLine_dXX() {
        $mid = Session::get('cat.uid');
        $sql="select substr(l.datesave,1,10)as dt,d0,d1,d2,mn,p.proname, t.taskname,tm.tmname,t.date_save,ps.own from logs l 
left join projects p on p.proid=l.proid left join tasks t on t.tid=l.tid 
left join task_mode tm on tm.tmmode=t.taskaction left join pies ps on ps.pieid=t.pieid
where ps.own='$mid'
order by dt desc,t.date_save desc";
        return excsql($sql); 
    }
    public static function timeLine_d($tid,$dt) {
        $mid = Session::get('cat.uid');
        $sql="select * from logs l where l.tid='$tid' and l.datesave like '$dt%'
order by l.datesave desc";
        //echo $sql;
        return excsql($sql); 
    }
    
   

}

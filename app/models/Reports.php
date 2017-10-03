<?php

class Reports extends Eloquent {

    protected $table = 'report_h';
    protected $primaryKey = 'rid';
    public $timestamps = false;

    public static function listData($rtype,$tid='') {
        if($rtype=='Album'){
            $pa="and a.mn in('capture')";
        }else if($rtype=='Chart'){
            $pa="and a.mn in('temp')";
        }else if($rtype=='Table'){
            $pa="and a.mn in('temp','capture')";
        }else{
            $pa='';
        }
        if($tid){
            $pa.=" and a.tid in($tid)";            
        }
        $mid = Session::get('cat.uid');
        return excsql("select a.tid,a.dataname,a.mn,min(a.datesave)as mind,max(a.datesave)as maxd from alldata a left join pies p on p.pieid=a.pieid where p.own='$mid' $pa group by a.tid order by a.dataname");
    }
    
    public static function listReport() {
        $mid = Session::get('cat.uid');
        return excsql("select * from report_h where mid='$mid' order by rname");
    }
    public static function listImages($r) {
        $tid=$r->tid;
        $sdate=$r->sdate;
        $edate=$r->edate;
        $sql="select * from alldata where tid in ($tid) and datesave >= '$sdate' and datesave <= '$edate' and mn='capture'";
        return excsql($sql);
    }
    

}

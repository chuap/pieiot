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
    public static function reportInfo($r) {
        
        $x= excsql("select * from report_h r left join report_type t on t.rtid=r.rtype where rid='$r' order by rname");
        if($x){
            return $x[0];
        }else{
            return '';
        }
    }
    public static function rType($r) {
        
        $x= excsql("select * from report_type where rtid='$r' ");
        if($x){
            return $x[0];
        }else{
            return '';
        }
    }
    public static function listReport() {
        $mid = Session::get('cat.uid');
        return excsql("select * from report_h r left join report_type t on t.rtid=r.rtype where mid='$mid' order by rname");
    }
    public static function listImages($r) {
        $tid=$r->tid;
        $sdate=$r->sdate;
        $edate=$r->edate;
        $sql="select * from alldata where tid in ($tid) and datesave >= '$sdate' and datesave <= '$edate' and mn='capture' order by datesave";
        return excsql($sql);
    }public static function dataTable($r) {
        $tid=$r->tid;
        $sdate=$r->sdate;
        $edate=$r->edate;
        $sql="select * from alldata where tid in ($tid) and datesave >= '$sdate' and datesave <= '$edate'  order by datesave desc limit 0,1000";
        return excsql($sql);
    }
    public static function dataChart($r) {
        $tid=$r->tid;
        $sdate=$r->sdate;
        $edate=$r->edate;
        $sql="select dataname,data,data2 ,SUBSTR(datesave,1,16) as sdt from alldata where tid in ($tid) and datesave >= '$sdate' and datesave <= '$edate' group by sdt  order by sdt  limit 0,1000";
        return excsql($sql);
    }
    public static function dataDemo($r) {
        $tid=$r->tid;
        $sdate=$r->sdate;
        $edate=$r->edate;
        $sql="select * from alldata where tid in ($tid) and datesave >= '$sdate' and datesave <= '$edate' order by datesave desc limit 0,3";
        return excsql($sql);
    }
    public static function countMyReport() {
        $mid = Session::get('cat.uid');
        $x= excsql("select count(*)as co from report_h where mid='$mid' ");
        return $x[0]->co;
    }
    public static function portValue($d,$h='h40') {
        $v=$d->data;
        $v2=$d->data2;
        if($d->mn=='setbit'){
            if ($v == '1') {
                return '<span class="badge bg-green">On</span>';
            } else if ($v == '0') {
                return '<span class="badge bg-red">Off</span>';
            }
        }else if($d->mn=='capture'){
            if ($v) {
                return '<a href="'.asset($v).'" title="" class="imcolorbox gallery p0"><img class="'.$h.'" src="'.$v.'"></a>';
            }
        }else if($d->mn=='temp'){
            $dx=''.'<span class="badge bg-yellow">'.  number_format($v,2).'</span>*c';
            $dx.=' '.'<span class="badge bg-info">'.  number_format($v2,2).'</span>%';
            
            return $dx;
        }
        
        
        
    }
    

}

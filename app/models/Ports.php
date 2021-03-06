<?php

class Ports extends Eloquent {

    protected $table = 'ports';
    protected $primaryKey = 'portid';
    public $timestamps = false;

    public static function portByPie($p) {
        $sql="select p.*,d.portname as oname,d.modes,d.portname as pmname  from ports p left join ports_model d on d.portno=p.portno and d.piemodel=p.piemodel where pieid='$p' order by isnull(assigned),p.portname";
        //echo $sql;
        return excsql($sql);
    }
    public static function portTimeline() {
        $mid = Session::get('cat.uid');
        $sql="select p.*,pi.piename,d.portname as oname,d.modes,d.portname as pmname  from ports p "
                . "left join ports_model d on d.portno=p.portno and d.piemodel=p.piemodel "
                . "left join pies pi on pi.pieid=p.pieid  "
                . "where pi.own='$mid' and not(p.lastupdate is null) order by p.lastupdate  desc,p.portname limit 100";
        //echo $sql;
        return excsql($sql);
    }
    public static function allMode() {
        return excsql("select * from modes order by mode_order");
    }
    public static function portModel($m) {
        return excsql("select * from ports_model where piemodel='$m' order by portname");
    }

    public static function updatePort($pie, $pno,$mn, $v,$v2) {
        $p2="'".str_replace(",","','",$pno)."'";
        $dt = date('Y-m-d H:i:s');
        if($mn=='temp'){
          $v=$v.','.$v2;   
        }
        $sql = "update ports set portvalue='$v',porttype='$mn',lastupdate='$dt' where pieid='$pie' and portno in ($pno)";
        excsql($sql);
    }
    
    public static function ckkAssignPort($pie) {
        excsql("update ports set assigned=null where pieid='$pie'");
        $x = excsql("select * from tasks where pieid='$pie' ");
        foreach($x as $d){
            $t=$d->action_ports;
            $tid=$d->tid;
            $sql = "update ports set assigned='$tid' where pieid='$pie' and portno in($t)"; 
            excsql($sql);
        }
        
        
    }

    public static function getColor($st) {
        if ($st == 'On') {
            return 'green';
        } else if ($st == 'Off') {
            return 'red';
        } else {
            return 'aqua';
        }
    }

    public static function decodeValue($v,$h='h40') {
        if ($v == '1') {
            return '<span class="badge bg-green">On</span>';
        } else if ($v == '0') {
            return '<span class="badge bg-red">Off</span>';
        } else {
            if (!$v) {
                return '';
            } else if (substr($v, -4) == '.png') {
                return '<a href="'.asset($v).'" title="" class="imcolorbox gallery p0"><img class="'.$h.'" src="'.$v.'"></a>';
            } else {
                return $v;
            }
        }
    }
    public static function portValue($d,$h='h40') {
        $v=$d->portvalue;
        if(($d->porttype=='bitout')||($d->porttype=='bitin')){
            if ($v == '1') {
                return '<span class="badge bg-green">On</span>';
            } else if ($v == '0') {
                return '<span class="badge bg-red">Off</span>';
            }
        }else if($d->porttype=='capture'){ 
            if ($v) {
                return '<a href="'.asset($v).'" title="" class="imcolorbox gallery p0"><img class="'.$h.'" src="'.$v.'"></a>';
            }
        }else if($d->porttype=='temp'){
            $tt = explode(",", $v);
            $dx='';
            if(isset($tt[0])){
                $dx.=''.'<span class="badge bg-yellow">'.  number_format($tt[0],2).'</span>*c';
            }
            if(isset($tt[1])){
                $dx.=' '.'<span class="badge bg-info">'.  number_format($tt[1],2).'</span>%';
            }
            return $dx;
        }
        
        
        
    }

}

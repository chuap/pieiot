<?php

class Ports extends Eloquent {

    protected $table = 'ports';
    protected $primaryKey = 'portid';
    public $timestamps = false;

    public static function portByPie($p) {
        return excsql("select * from ports where pieid='$p' order by isnull(assigned),portno");
    }
    public static function allMode() {
        return excsql("select * from modes order by mode_order");
    }

    public static function updatePort($pie, $pno, $v) {
        $p2="'".str_replace(",","','",$pno)."'";
        $dt = date('Y-m-d H:i:s');
        $sql = "update ports set portvalue='$v',lastupdate='$dt' where pieid='$pie' and portno in ($pno)";
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

}

<?php

class Logs extends Eloquent {

    protected $table = 'logs';
    protected $primaryKey = 'aid';
    public $timestamps = false;

    public static function upLog($p, $tid, $pno, $mn, $a, $b,$pro,$d0,$asdata=null) {
        
        $dt = date('Y-m-d H:i:s');
        $sql = "insert into logs (datesave,pieid,tid,portno,mn,d1,d2,proid,asdata,d0) values('$dt','$p','$tid','$pno','$mn','$a','$b','$pro','$asdata',$d0)";
        $x = excsql($sql);
    }

    public static function updateLog($p, $tid, $pno, $mn, $a, $b,$pro,$asdata=null) {
        $dt = date('Y-m-d H:i:s');
        $d0=$a;
        excsql("update pies set lastupdate='$dt' where pieid='$p'");
        if($mn=='setbit'){
            $asdata=$a;
        }else if($mn=='image'){
            $asdata=$b;
            $d0='0';
        }else if($mn=='temp'){
            $asdata=$a.','.$b;
            $d0='0';
        }else {
            $asdata=null;
        }
        if($asdata != null){
            $t1=",asdata='$asdata' ";
        }else{
            $t1=",asdata=null ";
        } 
        if (1) {
            $n = DB::update("update logs set datesave='$dt',d2='$b',d1='$a' $t1 where portno='$pno' and tid='$tid' and d0='$d0' and mn='$mn'");
            if ($n < 1) {
                Logs::upLog($p, $tid, $pno, $mn, $a, $b,$pro,$d0,$asdata);
            }
        } 
    }

}

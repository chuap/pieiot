<?php

class Logs extends Eloquent {

    protected $table = 'logs';
    protected $primaryKey = 'aid';
    public $timestamps = false;

    public static function upLog($p, $tid, $pno, $mn, $a, $b,$pro,$asdata=null) {
        $dt = date('Y-m-d H:i:s');
        $sql = "insert into logs (datesave,pieid,tid,portno,mn,d1,d2,proid,asdata) values('$dt','$p','$tid','$pno','$mn','$a','$b','$pro','$asdata')";
        $x = excsql($sql);
    }

    public static function updateLog($p, $tid, $pno, $mn, $a, $b,$pro,$asdata=null) {
        $dt = date('Y-m-d H:i:s');
        if($mn=='setbit'){
            $asdata=$a;
        }else if($mn=='image'){
            $asdata=$b;
        }else {
            $asdata=null;
        }
        if($asdata != null){
            $t1=",asdata='$asdata' ";
        }else{
            $t1=",asdata=null ";
        } 
        if (1) {
            $n = DB::update("update logs set datesave='$dt',d2='$b' $t1 where portno='$pno' and tid='$tid' and d1='$a' and mn='$mn'");
            if ($n < 1) {
                Logs::upLog($p, $tid, $pno, $mn, $a, $b,$pro,$asdata);
            }
        } 
    }

}

<?php

class DataAll extends Eloquent {

    protected $table = 'data_all';
    protected $primaryKey = 'aid';
    public $timestamps = false;

    public static function upData($p, $tid, $pno,$dataname ,$data,$mn,$pro) {
        $dt = date('Y-m-d H:i:s');
        $sql = "insert into alldata (datesave,pieid,tid,portno,dataname,data,mn,proid) values('$dt','$p','$tid','$pno','$dataname','$data','$mn','$pro')";
        $x = excsql($sql);
    }

    

}

<?php

class DataAll extends Eloquent {

    protected $table = 'data_all';
    protected $primaryKey = 'aid';
    public $timestamps = false;

    public static function upData($p, $tid, $pno,$dataname ,$data,$data2,$mn,$pro) {
        $dt = date('Y-m-d H:i:s');
        $sql = "insert into alldata (datesave,pieid,tid,portno,dataname,data,mn,proid,data2) values('$dt','$p','$tid','$pno','$dataname','$data','$mn','$pro','$data2')";
        $x = excsql($sql);
    }

    

}

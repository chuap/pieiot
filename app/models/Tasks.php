<?php

class Tasks extends Eloquent {

    protected $table = 'tasks';
    protected $primaryKey = 'tid';
    public $timestamps = false;

    public static function taskInfo($p) {
        return excsql("select * from tasks where tid='$p' ");
    }
    public static function tmInfo($p) {
        $x= excsql("select * from task_mode where tmid='$p' ");
        return $x[0];
    }
    public static function listMode() {
        return excsql("select * from task_mode tm left join modes m on m.mode_id=tm.tmmode order by aid ");
    }
    public static function actionPorts($tsk) {
        $pie = $tsk->pieid;
        $pp = $tsk->action_ports;
        return excsql("select * from ports where pieid='$pie' and portno in($pp)");
    }
    public static function ckkTime($tx) {
        $tt = explode(",", $tx);
        $dt=date("Y-m-d "); $t='';
        foreach($tt as $d){
            if($t){$t.=',';}
            $t.= date("H:i",strtotime($dt.$d));
        } 
        return $t;
        
        
    }
    public static function taskSynced($tid,$a) {
        $dt = date('Y-m-d H:i:s');
        if($a){
            $d="Disable";
        }else{
            $d="Synced";
        }
        $sql = "update tasks set synced='$dt',task_status='$d' where tid='$tid'";
        excsql($sql);
    }
    public static function valueLabel($v,$h='h40') {
        if (($v->mn=='bitout')||($v->mn=='bitin')) {
            if($v->d1){
                return '<span class="badge bg-green">On</span>';
            }else{
                return '<span class="badge bg-red">Off</span>';
            }
            
        }else if (($v->mn=='image')or($v->mn=='face')) {
            return '<a href="'.asset($v->d2).'" title="" class="p0 gallery imcolorbox "><img class="'.$h.'" src="'.$v->d2.'"></a>';
            
        }else if($v->mn=='temp'){            
            $dx='';
            if($v->d1){
                $dx.=''.'<span class="badge bg-yellow">'.  number_format($v->d1,2).'</span>*c';
            }
            if($v->d2){
                $dx.=' '.'<span class="badge bg-info">'.  number_format($v->d2,2).'</span>%';
            }
            return $dx;
        }else if($v->mn=='synced'){  
            return 'Task synced. ';
        } else {
            return '';
        }
    }
    public static function portLabel($v) {
        $t=str_replace(",","_",$v->portno);
        return $t;
    }
    public static function syncedLabel($v) {
        if ($v->synced) {
            if($v->task_disable){
                return '<i class="fa fa-times red"></i> <small>' . $v->task_status . '</small>';
            }else{
                return '<i class="fa fa-check green"></i> <small>' . $v->task_status . '</small>';
            }
            
        } else {
            return '<img class="w20" src="' . asset('/') . 'images/loading2.gif" /> <small>' . $v->task_status . '</small>';
        }
    }

}

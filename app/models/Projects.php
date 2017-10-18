<?php

class Projects extends Eloquent {

    protected $table = 'projects';
    protected $primaryKey = 'proid';
    public $timestamps = false;

    public static function myProjects() {
        $mid = Session::get('cat.uid');
        return excsql("select * from projects p left join project_type t on t.tyid=p.protype where p.mid='$mid' order by p.lastupdate desc, p.proactive desc,proid");
    }

    public static function projectType($t = '') {
        if ($t) {
            $x = excsql("select * from project_type where tyid like '%$t%' ");
            return $x[0];
        } else {
            $x = excsql("select * from project_type order by tyid ");
            return $x;
        }
    }

    public static function countMyProject() {
        $mid = Session::get('cat.uid');
        $x = excsql("select count(*)as co from projects where mid='$mid' ");
        return $x[0]->co;
    }

    public static function projectList($p) {
        //return excsql("select * from projects where pieid='$p' order by lastupdate desc, proactive desc,proid");

        $sql = "select p.* from tasks t left join projects p on p.proid=t.proid where t.pieid='$p' group by p.proid order by p.lastupdate desc, p.proactive desc,p.proid";
        return excsql($sql);
    }

    public static function project_DAll($pro) {
        return excsql("select d.*,pi.piename,p.portvalue,p.lastupdate from projects_d d  left join ports p on p.pieid=d.pieid and d.portno=p.portno left join pies pi on pi.pieid=d.pieid where d.proid='$pro' order by d.portno");
    }

    public static function getTask($pro) {
        return excsql("select d.*,pi.piename,m.tmimg,pi.img,pi.color as picolor from tasks d  left join pies pi on pi.pieid=d.pieid left join task_mode m on m.tmid=d.tmid where d.proid='$pro' order by d.taskname");
    }

    public static function project_D($p, $pro) {
        return excsql("select d.*,p.portvalue,p.lastupdate from projects_d d  left join ports p on p.pieid=d.pieid and d.portno=p.portno where d.pieid='$p' and d.proid='$pro' order by d.portno");
    }

    public static function taskDesc($t) {
        $l = '';
        if ($t->taskmode == 'count') {

            $l = 'เปิด ' . Projects::decodeSec($t->d1) . '  ปิด ' . Projects::decodeSec($t->d2) . '   ช่วงเวลา ' . $t->stime . ' - ' . $t->etime . ' น.';
        } else if ($t->taskmode == 'capture') {
            $l = 'ถ่ายภาพเวลา ' . $t->d1 . ' น.';
        }
        return $l;
    }

    public static function taskDesc2($t) {
        $l = '';
        if ($t->taskaction == 'bitout') {
            if ($t->ck1 != 1) {
                $l = 'เปิดช่วงเวลา ' . $t->stime . ' - ' . $t->etime . ' น.';
            } else {
                $l = 'เปิด ' . Projects::decodeSec($t->tx1) . '  ปิด ' . Projects::decodeSec($t->tx2) . '   ช่วงเวลา ' . $t->stime . ' - ' . $t->etime . ' น.';
            }
        } else if ($t->taskaction == 'capture') {

            if ($t->op1 == 1) {
                $l = 'ถ่ายภาพเวลา ' . $t->tx1 . ' น.';
            } else {
                $l = 'ถ่ายภาพ ทุกๆ ' . Projects::decodeMin($t->tx2) . '   ช่วงเวลา ' . $t->stime . ' - ' . $t->etime . ' น.';
            }
        } else if ($t->taskaction == 'temp') {

            if ($t->op1 == 1) {
                $l = 'อ่านค่าอุณหภูมิและความชื้น เวลา ' . $t->tx1 . ' น.';
            } else {
                $l = 'อ่านค่าอุณหภูมิและความชื้น ทุกๆ ' . Projects::decodeSec($t->tx2) . '   ช่วงเวลา ' . $t->stime . ' - ' . $t->etime . ' น.';
            }
        }else if ($t->taskaction == 'bitin') {
            $l = 'อ่านค่าช่วงเวลา ' . $t->stime . ' - ' . $t->etime . ' น.';
        }else if ($t->taskaction == 'face') {
            $l = 'ตรวจจับใบหน้าเวลา ' . $t->stime . ' - ' . $t->etime . ' น.';
        }
        return $l;
    }

    public static function decodeMin($s) {
        $x = $s;
        $m = floor($x / 60);
        if ($s > 60) {
            return "<span class='label label-default'>$m." . ($x % 60) . '</span> ช.ม.';
        } else {
            return "<span class='label label-default'>$s" . '</span> นาที';
        }
    }

    public static function decodeSec($s) {
        $x = $s % 3600;
        $h = floor($s / 3600);
        $m = floor($x / 60);
        if ($s > 3600) {
            return "<span class='label label-default'>$h.$m." . ($x % 60) . '</span> ช.ม.';
        } else if ($s > 60) {
            return "<span class='label label-default'>$m." . ($x % 60) . '</span> นาที';
        } else {
            return "<span class='label label-default'>$s" . '</span> วินาที';
        }
    }

    public static function getActiveTask($p, $f = 't.*') {
        //return excsql("select $f from projects_d d left join projects p on p.pieid=d.pieid  where d.pieid='$p' and p.proactive='1'  order by d.portno");
        return excsql("select $f from tasks t left join projects p on p.proid=t.proid  where t.pieid='$p' and p.proactive='1'  order by t.tid");
    }

    public static function getActiveTaskUpdate($p, $f = 't.*') {
        //return excsql("select $f from projects_d d left join projects p on p.pieid=d.pieid  where d.pieid='$p' and p.proactive='1'  order by d.portno");
        return excsql("select $f from tasks t left join projects p on p.proid=t.proid  where t.pieid='$p' and p.proactive='1' and t.synced is null  order by t.tid");
    }

    public static function taskInfo($p, $pno, $f = 'd.*') {
        return excsql("select $f from projects_d d left join projects p on p.pieid=d.pieid  where d.pieid='$p' and portno='$pno'");
    }

}

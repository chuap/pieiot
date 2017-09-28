<?php  

class Std {

    public static function getVal($key, $arr) {
        if (isset($arr[$key])) {
            return $arr[$key];
        } else {
            return '';
        }
    }
    public static function dateTh($f,$ts=''){
	if(!$ts)$ts=time();
	$thai_w=array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
	$thai_w2=array("อา.","จ.","อ.","พ.","พฤ.","ศ.","ส.");
	$thai_n=array("มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
	$thai_n2 = array("ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
	$ww=$thai_w[date("w",$ts)];
	$w=$thai_w2[date("w",$ts)];
	$nn=$thai_n[date("n",$ts) -1];
	$n=$thai_n2[date("n",$ts) -1];
	$eeee=date("Y",$ts) +543;
	$ee=substr($eeee,2,2);
	$h=date("H",$ts);
	$i=date("i",$ts);
	$s=date("s",$ts);
	$y=date("Y",$ts);
	$m=date("m",$ts);
	$dd=date("d",$ts);
	$d=$dd*1;
	$t=$f;
	$t=str_replace('eeee',$eeee,$t); 	//2556
	$t=str_replace('ee',$ee,$t);			//56
	$t=str_replace('ww',$ww,$t);			//อาทิตย์
	$t=str_replace('w',$w,$t);				//อา.
	$t=str_replace('nn',$nn,$t);			//มกราคม
	$t=str_replace('n',$n,$t);				//ม.ค.
	$t=str_replace('Y',$y,$t);				//2013
	$t=str_replace('m',$m,$t);				//01
	$t=str_replace('dd',$dd,$t);			//01
	$t=str_replace('d',$d,$t);				//1
	$t=str_replace('H',$h,$t);				//12
	$t=str_replace('i',$i,$t);				//59
	$t=str_replace('s',$s,$t);				//59
	return $t;
}

}


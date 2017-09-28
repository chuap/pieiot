<?php

class Car extends Eloquent {

    protected $table = 'cars';
    protected $primaryKey = 'cid';
    public $timestamps = false;

    
    public static function brand_list() {
        return excsql("select * from brands order by bid");
    }
    public static function brand_list_eb() {
        return excsql("select b.*,count(c.cid)as co from brands b right join cars c on c.brand=b.bid group by b.bid order by bid");
    }
    public static function model_by_brand($b) {
        return excsql("select car_model,count(cid)as co from cars where brand='$b' group by car_model order by car_model");
    }
    public static function type_list() {
        return excsql("select * from types order by iorder");
    }
    public static function option_list() {
        return excsql("select * from options order by iorder,oid");
    }
    public static function car_list() {
        $uid = Session::get('cat.uid');
        return excsql("select c.*,b.* from cars c left join brands b on b.bid=c.brand where uid='$uid' order by date_save desc, cid desc");
    }
    public static function car_by_model($bid,$m,$co=100) {
        return excsql("select c.*,b.* from cars c left join brands b on b.bid=c.brand where brand='$bid' and car_model='$m' order by date_save desc, cid desc limit 0,$co");
    }
    public static function car_list_top($n=20,$r=0) {
        if($r){
            return excsql("select * from(select c.*,b.* from cars c left join brands b on b.bid=c.brand order by date_save desc, cid desc limit 0,$n)as tb1 ORDER BY rand()");
        }else{
            return excsql("select c.*,b.* from cars c left join brands b on b.bid=c.brand order by date_save desc, cid desc limit 0,$n");
        
        }
        
    }
    public static function brand_list_top($n=20) {
        return excsql("select brand,brandname,logo from( SELECT c.brand,b.brandname,b.logo FROM cars c  LEFT JOIN brands b on b.bid=c.brand limit 0,$n)as tb1 GROUP BY brand ORDER BY rand()");
    }
    public static function year_list($bid,$m) {
        return excsql("select year from cars where brand='$bid' and car_model='$m' order by year");
    }
    public static function user_list() {
        $uid = Session::get('cat.uid');
        return excsql("select * from staff s left join hrd_profile h on h.uid=s.uid order by s.uid");
    }
    public static function find_car($p) {
        $q= excsql("select c.*,b.* from cars c left join brands b on b.bid=c.brand where cid='$p' order by date_save desc, cid desc");
        if(count($q)>0){
            return $q[0];
        }else{
            return null;
        }
    }
    public static function car_dfimg($p) {
        $q= excsql("select * from files_book where bookid='$p' and df='1'");
        if(count($q)>0){
            return $q[0];
        }else{
            return null;
        }
    }
    public static function get_brandid($b) {
        $q= excsql("select * from brands where brandname='$b' ");
        if(count($q)>0){
            return $q[0];
        }else{
            return '';
        }
    }
    public static function car_imgs($p) {
        $q= excsql("select * from files_book where bookid='$p' and not(thumb is null) order by df desc,id ");
        return $q;
    }
    public static function car_name($d,$nob=1) {
        $t='';
        if($nob){
            $t=$d->brandname.' ';
        }
        $t.=$d->car_model .' '. $d->year;
       
       Return $t;
    }
   

}

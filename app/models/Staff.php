<?php  

class Staff extends Eloquent {

    protected $table = 'staff';
    protected $primaryKey = 'uid';    
    public $timestamps = false;
    public static function findUser($find,$co,$page) {
        if ($page) {
            $pg = "limit " . ($page * $co) . ",$co";
        } else {
            $pg = "limit $co";
        }
        if($find){
            $p1 = "where fname like '%$find%' or lname like '%$find%' or uname like '$find%' or placename like '$find%'  ";
        }else{
            $p1 = "";
        }
        
        $sql = "select * from staff  $p1 order by  fname $pg";
        $ff = excsql($sql);
        return $ff;
    }

    

}

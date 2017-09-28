<?php  
	class user {
    public static function getVal($key,$arr)
    {
        if(isset($arr[$key])){
					return $arr[$key];
				}
				else{
					return '';
				}
					
    }

}

<?php

class FilesBook extends Eloquent {

    protected $table = 'files_book';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function addNewFile($file, $file_path) {
        $curr_p = Session::get('curr_p');
        $obj = new FilesBook();
        //$l=  strlen(asset('/'));    
        $obj->file_name = $file->name;
        $obj->old_name = Session::get('lfname');
        $obj->file_type = $file->type;
        $obj->file_size = $file->size;
        if ($curr_p) {
            $obj->bookid = $curr_p;
        }
        //$obj->url = substr($file->url,$l,strlen($file->url)-$l);
        //$obj->url = $file->url;
        $obj->url = str_replace(asset('/'), '', $file->url);
        $obj->url = urldecode($obj->url);
        $obj->path = $file_path;
        $obj->date_upload = date('Y-m-d H:i:s');
        $obj->uid = Session::get('cat.uid');
        if (substr($file->type, 0, 5) == 'image') {
            $obj->thumb = str_replace('/' . $obj->uid . '/', '/' . $obj->uid . '/thumbnail/', $obj->url);
        } else {
            $obj->thumb = NULL;
        }
        
        if ($curr_p) {
            $car = Car::find($curr_p);
            if ((!$car->dfimg) && ($obj->thumb)) {
                $car->dfimg = $obj->thumb;
                $car->save();
                $obj->df=1;
            }else{
                $obj->df=0;
            }
        }
        $obj->save();
    }

    public static function deleteFile($file_name) {
        $q = excsql("select * from files_book where file_name='$file_name'");        
        if (count($q) > 0) {
            $sf= $q[0]->thumb;
            if($sf){
               excsql('update cars set dfimg=null where dfimg="'.$sf.'"'); 
            }
        } 
        return FilesBook::whereRaw('file_name=? ', array($file_name))->delete();
    }

    public static function lastFile($co, $p) {
        if ($p) {
            return FilesBook::whereRaw('uid=? and bookid=?', array(Session::get('cat.uid'), $p))->orderBy('id', 'desc')->take($co)->get();
        } else {
            return FilesBook::whereRaw('uid=? and bookid="0"', array(Session::get('cat.uid')))->orderBy('id', 'desc')->take($co)->get();
        }
    }

    public static function lastid() {
        //return 555;
        if (0 && Session::has('lastbookid')) {
            $x= Session::get('lastbookid')+1 ;       
            //$x= 99 ;   
        } else {
            $q = excsql("select max(id)as mx from files_book ");        
            if (count($q) > 0) {
                $x= $q[0]->mx+1;
            } else {
                $x= 1;
            }
        }       
        Session::put('lastbookid', $x);
        return $x;
    }

    public static function getall($p) {
        if ($p < 1) {
            $catid = Session::get('cat.uid');
            return FilesBook::whereRaw("bookid='0' and uid=?", array($catid))->orderBy('id', 'asc')->get();
        } else {
            return FilesBook::whereRaw('bookid=?', array($p))->orderBy('id', 'asc')->get();
        }
    }

    public static function getimg($p) {
        return FilesBook::whereRaw('bookid=? and not(thumb is null)', array($p))->orderBy('id', 'asc')->get();
    }

    public static function getfile($p) {
        return FilesBook::whereRaw('bookid=? and thumb is null', array($p))->orderBy('id', 'asc')->get();
    }

    public static function geticon($f) {
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (($ext == 'xlsx') || ($ext == 'xls')) {
            return asset('images/ext/xls.png');
        } else if (($ext == 'doc') || ($ext == 'docx')) {
            return asset('images/ext/doc.png');
        } else if (($ext == 'zip') || ($ext == '7z')) {
            return asset('images/ext/zip.png');
        } else if (($ext == 'ppt') || ($ext == 'pptx')) {
            return asset('images/ext/ppt.png');
        } else if ($ext == 'rar') {
            return asset('images/ext/rar.png');
        } else if ($ext == 'pdf') {
            return asset('images/ext/pdf.png');
        } else {
            return asset('images/ext/etc.png');
        }
    }

}

@extends('edoc.ace') 
@section('head_meta')
<title>ฝ่ายพัฒนาทรัพยากรบุคคล</title> 
{{ HTML::style(asset('fileupload/css/jquery.fileupload.css'))}}
{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui.css'))}}
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-noscript.css'))}}</noscript>
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui-noscript.css'))}}</noscript>
{{ HTML::script(asset('ckeditor/ckeditor.js'))}}
{{ HTML::style(asset('css/autocomplete.css'))}}

@stop
<?php
$page_title = 'รายการเอกสาร';
$p = Input::get('p');
$page = Input::get('page');
if ($page > 0) {
    $page = $page - 1;
}
$find = Input::get('find');
$ii = 0;
if ($p == 'me') {
    $type_name = 'My Document';
    $css = '';
} else if ($p) {
    $obj = EDocType::find($p);
    $type_name = $obj->type_name;
    $css = $obj->css;
} else {
    $type_name = '';
    $css = '';
}
if ($find) {
    $type_name = "Search: $find";
}
$pcount = 20;
//$alllist = EDoc::getList($p, $pcount, $find, $page, 'timeline');
$alllist = EDoc::timeline('', $pcount, $page, '');
$notification = EDoc::notification();

$alltop = EDoc::getListPin('', 10, '', '');
?>
@section('breadcrumbs')
<li class="active">{{$type_name}}</li>
@stop
@section('body')

<div class="row-fluid">
    <div class="span8">
        <h3 class="header smaller lighter blue">e-Doc Timeline
            <span class="label label-large arrowed-right label-{{$css}}">{{$type_name}}</span>  
            @if (Session::get('cat.position_s') != 'ลจ.') 
            <a class="f14 grey ml2" href="{{asset('edoc/all')}}">แสดงข้อมูลทั้งหมดในรูปแบบตาราง</a>
            @endif
            <div class="pull-right f16">
                @if($page>0)
                <a class="btn btn-mini" href="{{asset("edoc?page=".($page))}}"><i class="icon-chevron-left"></i> ก่อนหน้า</a>
                @endif
                @if(count($alllist)>=$pcount)
                <a class="btn btn-mini" href="{{asset("edoc?page=".($page+2))}}">หน้าถัดไป <i class="icon-chevron-right"></i></a>
                @endif
            </div>
        </h3> 


    </div>
    <div class="span4">
        <a href="{{asset('edoc/new')}}" class="btn btn-info pull-right"><i class="icon-pencil"></i> สร้างเอกสาร</a>
    </div>
</div>

<div class="row-fluid">
    <div class="span8">
        <diV>
            <table id="sample-table-2" class="table table-striped table-bordered table-hover datatablesX">
                <thead>
                    <tr>
                        <th class="w50"><img src="{{asset("images/pin.png")}}" class="w30"></th>
                        <th>เรื่องสำคัญที่ถูกปักหมุดไว้ <small>10 รายการล่าสุด</small></th> 
                    </tr>
                </thead>
                <tbody>
                    @foreach($alltop as $d)
                    <?php
                    $ii++;
                    $css1 = "";
                    $tlb = $d->etitle;
                    ?>
                    <tr id="delidedoc{{$d->id}}">
                        <td class="pl04 pr0" ><i title="{{$d->type_name}}" class="f14 {{$d->icon}} {{$d->color}}"></i><span class="muted"> #{{$d->eno}}</span></td>
                        <td>
                            @if($d->assign_id)
                            <span class=""><i class="icon-cloud-download red"></i></span>
                            @endif 
                            <a class="f14 {{$css1}}" href="{{asset('edoc/view?p='.$d->id)}}">{{$tlb}}</a> {{Edoc::label_pri($d->epri)}} {{Edoc::label_secret($d->esecret)}}  
                           
                            @if($d->fcount)
                            <i class="icon-paper-clip"></i>
                            @endif
                        </td>                       
                    </tr> 
                    @endforeach
                </tbody>
            </table>
        </diV>
        @foreach($alllist as $d)
        <?php
        $profile = HRDProfile::gen_profile($d->catid);
        $msg = excsql("select count(id)as co from edoc_msg where doc_id='" . $d->id . "'");
        $msg_co = $msg[0]->co;
        $readcount = $d->read_count;
        $likecount = $d->like_count;
        $sw_timeline = $d->sw_timeline;
        $hidden_file = $d->hidden_file;
        $dtl = str_replace('src="files', 'src="' . asset('') . 'files', $d->dtl);
        $dtl = str_replace('src="ckeditor', 'src="' . asset('') . 'ckeditor', $dtl);
        $dtl = str_replace('@root/', asset('/'), $dtl);
        $canedit = EDoc::can_edit($d);
        $isassign = EDoc::isassign($d->id);
        ?>
        <div id="tl_{{$d->id}}" class="shadow-mini p1">
            <div class=" ">
                <div class="clearfix">
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="pull-left">
                                <img class="pull-left w40 mr1" alt="" src="{{asset($profile->avatar)}}" />
                                <a class="" href="#"> {{$profile->nickname}} </a>
                                ได้สร้าง <i title="{{$d->type_name}}" class="f14 {{$d->icon}} {{$d->color}}"></i> <a href="{{asset('edoc/all?p='.$d->etype)}}">{{$d->type_name}}</a>
                                @if($d->eno)
                                #{{$d->eno}}
                                @endif  
                                <div class="time">
                                    <i class="icon-time bigger-110"></i>
                                    {{timepost_shot($d->date_create)}}ก่อน
                                </div>
                            </div>
                            <div class="pull-right">
                                <span class="tools action-buttons text-right">
                                    @if($d->rcount)
                                    <a href="{{asset('edoc/view?p='.$d->id)}}" class="btn btn-light btn-mini" >อ่านต่อ</a>
                                    @else
                                    <a href="{{asset('edoc/view?p='.$d->id)}}" class="btn btn-success btn-mini" >เปิด<span class="hidden-phone">เอกสาร</span></a>
                                    @endif
                                </span>
                                <div class="btn-group">
                                    <a data-toggle="dropdown" class="btn-light btn btn-mini dropdown-toggle">
                                        <i class="icon-gear"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-default">
                                        <li>
                                            <a href="javascript:"onclick="hidetoppic({{$d->id}})"><i class="icon-ban-circle"></i> ซ่อนเรื่องนี้จาก Timeline</a>
                                        </li>
                                        <li>
                                            <a href="{{asset('edoc/new?ref='.$d->id)}}"><i class="icon-mail-forward red"></i> สร้างเอกสารใหม่โดยอ้างอิงเรื่องนี้ </a>
                                        </li> 
                                        @if($isassign || $canedit)
                                        <li>
                                            <a class="doc_status" href="{{asset('edoc/share_sec?r=msg&p='.$d->id)}}"><i class="icon-bullhorn blue"></i> แบ่งปันเรื่องนี้ให้พนักงานในส่วน </a>
                                        </li>
                                        @endif
                                        @if($canedit)
                                        <li class="divider"></li>  
                                        <li>
                                            <a href="{{asset('edoc/new?p='.$d->id)}}"><i class="icon-pencil"></i> แก้ไขเอกสาร </a>
                                        </li>
                                        @endif
                                        @if(0)
                                        <li>
                                            <a href="javascript:"onclick="addfavorites({{$d->id}})"><i class="icon-star"></i> เพิ่มในรายการโปรด</a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>

                    <hr class="m04">
                    <div class="mt1">
                        <a class="f16 blue-dark" href="{{asset('edoc/view?p='.$d->id)}}">{{$d->etitle}} </a> {{Edoc::label_pri($d->epri)}} {{Edoc::label_secret($d->esecret)}}
                        @if($d->fcount)
                        <i class="icon-paper-clip icon-large"></i>
                        @endif
                        <i id="star_{{$d->id}}" class="icon-star text-warning f16 hidden"></i>
                    </div>
                    @if($d->rcount||$sw_timeline)
                    <div class="">
                        <span>
                            <i class="icon-eye-open"></i> อ่าน 
                            <a class="doc_status" href="{{asset("edoc/doc_status?t=1&p=".$d->id)}}">{{$readcount}} <small>ครั้ง</small></a>
                        </span>
                        <span class="ml1">
                            <i class="icon-heart"></i> ชอบ 
                            <a class="doc_status green" href="{{asset("edoc/doc_status?t=2&p=".$d->id)}}"><span id="likeval{{$d->id}}">{{$likecount}}</span> <small> คน</small></a>
                            <?php
                            $ll = EDoc::liked($d->id, Session::get('cat.catid'));
                            if ($ll > 0) {
                                $cs1 = "hidden";
                                $cs2 = "";
                            } else {
                                $cs2 = "hidden";
                                $cs1 = "";
                            }
                            ?>
                            <div class="badge badge-pink p04 likebtn{{$d->id}} {{$cs1}}">                            
                                <a href="javascript:" onclick="likeclick('{{$d->id}}')" class="white"><i class="icon-thumbs-up-alt"></i> ชอบ</a>
                            </div>
                            <div class="badge badge-light p04 unlikebtn{{$d->id}} {{$cs2}}">                            
                                <a href="javascript:" onclick="unlikeclick('{{$d->id}}')" class="white"><i class="icon-thumbs-down-alt"></i> ไม่ชอบ</a>
                            </div>
                        </span>
                    </div>

                    @if($d->eaction=='a2')
                    <?php
                    $isapcept = EDoc::isapcept($d->id, Session::get('cat.catid'));
                    ?>
                    <div id="divapcept" class="alert alert-dismissible clearfix mt1">
                        <span class="loading2 hidden" ><img class="w30" src="{{asset('images/loading.gif')}}"/></span>
                        <div class="apceptbtn {{$isapcept?'hidden':''}}">
                            <a href="javascript:" onclick="apcept_click('{{$d->id}}');" class="btn btn-primary mt1 btn-primary ">
                                <i class="icon-check-sign icon-animated-wrench bigger-160"></i>
                                รับทราบเรื่องนี้
                            </a>
                        </div>
                        <div class="{{$isapcept?'':'hidden'}}" id="showapcept"> จำนวนผู้ที่รับทราบเรื่องนี้แล้ว <a id="apcept_val" href="{{asset("edoc/doc_status?t=3&p=".$d->id)}}" class="btn btn-warning btn-mini doc_status">{{EDoc::apcept_count($d->id)}}</a> คน</div>

                    </div>
                    @endif
                    @if($d->eaction=='a4')
                    <?php
                    $isagree = EDoc::isagree($d->id, Session::get('cat.catid'));
                    ?>
                    <div id="divapcept" class="alert alert-dismissible clearfix  mt1">
                        <span class="loading2 hidden" ><img class="w30" src="{{asset('images/loading.gif')}}"/></span>
                        <div class="apceptbtn {{$isagree?'hidden':''}} p1 ">
                            <a href="javascript:" onclick="agree_click('{{$d->id}}');" class="btn btn-success  btn-primary ">
                                <i class="icon-check-sign icon-animated-wrench bigger-160"></i>
                                เห็นด้วย
                            </a>
                            <a href="javascript:" onclick="disagree_click('{{$d->id}}');" class="btn btn-danger  btn-primary ">
                                <i class="icon-remove-sign icon-animated-wrench bigger-160"></i>
                                ไม่เห็นด้วย
                            </a>
                        </div>
                        <table  class="showapcept {{$isagree?'':'hidden'}} ">
                            <tr>
                                <td class="text-left">จำนวนผู้ที่<span class="green textuline">เห็นด้วย</span>กับเรื่องนี้</td>
                                <td><a href="{{asset("edoc/doc_status?t=4&p=".$d->id)}}" id="agree_val" class="doc_status btn btn-success btn-mini ml1">{{EDoc::agree_count($d->id)}}</a> คน</td>
                            </tr>
                            <tr>
                                <td class="text-left">จำนวนผู้ที่<span class="red textuline">ไม่เห็นด้วย</span>กับเรื่องนี้ </td>
                                <td><a href="{{asset("edoc/doc_status?t=4&p=".$d->id)}}" id="disagree_val" class="doc_status btn btn-danger btn-mini ml1">{{EDoc::disagree_count($d->id)}}</a> คน</td>
                            </tr>
                        </table>                    
                    </div>
                    @endif


                    <div class="mt1">
                        <div class="overflow-auto">{{$dtl}}</div>
                        @if(!$hidden_file)
                        <div class="">  
                            <?php
                            $f_img = FilesBook::getimg($d->id);
                            $img_co = count($f_img);
                            if ($img_co == 1) {
                                $sty1 = '';
                            } else if ($img_co == 2) {
                                $sty1 = 'height:224px;';
                            } else if ($img_co == 3) {
                                $sty1 = 'height:162px;';
                            } else {
                                $sty1 = 'height:108px;';
                            }
                            ?>
                            <ul class="ace-thumbnails clearfix">
                                @foreach ($f_img as $f)
                                <li style="" >
                                    <a href="{{asset($f->url)}}" title="" data-rel="colorbox">
                                        <img alt="150x150" style="{{$sty1}}" src="{{asset($f->url)}}" />
                                        <div class="tags">
                                            <span class="label label-info">{{$f->file_name}}</span>
                                        </div>
                                    </a>
                                </li>  
                                @endforeach
                                @foreach (FilesBook::getfile($d->id) as $f)
                                @if(strtolower( pathinfo($f->file_name, PATHINFO_EXTENSION))=='mp3')
                                <div class="clearfix">
                                    <audio class="pull-left" controls="" src="{{asset($f->url)}}" type="audio/mp3"></audio>
                                    <div class="pull-left red p1">{{$f->file_name}}</div>
                                </div>
                                @else
                                <li class="p0 m0" style="border: none;">                            
                                    <div class="alert alert-info  m04 p04">
                                        @if($f->topdf)
                                        <a title="{{$f->topdf}}" href="{{asset('files/'.$f->uid.'/topdf/'.$f->file_name.'.pdf')}}">
                                            <img alt="150x150" class="w40" src="{{FilesBook::geticon($f->topdf)}}" />                                        
                                        </a>
                                        <a title="{{$f->file_name}}" href="{{asset($f->url)}}">
                                            <img alt="150x150" class="w40" src="{{FilesBook::geticon($f->file_name)}}" />
                                            <span class="f12">{{$f->file_name}}</span>
                                        </a>
                                        @else
                                        <a title="{{$f->file_name}}" href="{{asset($f->url)}}">
                                            <img alt="150x150" class="w40" src="{{FilesBook::geticon($f->file_name)}}" />
                                            <span class="f12">{{$f->file_name}}</span>
                                        </a>
                                        @endif
                                    </div>
                                </li>
                                @endif
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>   

                    @if($d->sw_comment)

                    <div>
                        <img class="w20 p0 m0 userimg" title="" src="{{asset(Session::get('cat.avatar'))}}" alt="" />
                        <input  id="tx_message{{$d->id}}" name="tx_message{{$d->id}}" onkeydown="return tx_message_tl(event,{{$d->id}});" placeholder="แสดงความคิดเห็น" type="text" class="width-75 f12" style="margin-top:10px; padding: 2px 8px;" />
                        <img  id="loading_msg{{$d->id}}" class="w20 hidden" src="{{asset('images/loading.gif')}}"/>
                    </div>
                    <div class="well p1 mt04 mb1 {{$msg_co?'':'hidden'}}" id="div_msg{{$d->id}}">
                        <?php
                        $sx = '';
                        $m = '';
                        ?>
                        @foreach(EDocMsg::getMsg($d->id)as $d2)
                        <?php
                        if (!$sx) {
                            $sx = $d2->catid;
                        }
                        if (($sx != $d2->catid) && $m) {
                            $hr = HRDProfile::gen_profile($sx);
                            ?>
                            <div>
                                <img class="w20 p0 m0 userimg" title="{{$hr->nickname}}" src="{{asset($hr->avatar)}}" />
                                {{$m}}
                            </div>   
                            <?php
                            $m = $m = $d2->msg;
                            $sx = $d2->catid;
                        } else {
                            if ($m) {
                                $m = $d2->msg . '<br/><span style="padding-left: 26px;"></span>' . $m;
                            } else {
                                $m = $d2->msg;
                            }
                        }
                        ?>
                        @endforeach
                        @if($m)
<?php $hr = HRDProfile::gen_profile($sx); ?>
                        <div>
                            <img class="w20 p0 m0 userimg" title="{{$hr->nickname}}" src="{{asset($hr->avatar)}}" />
                            {{$m}}
                        </div>  
                        @endif
                    </div>
                    @endif
                    @endif

                </div>



            </div>

        </div>
        @endforeach
        <div class="clearfix" >
            <div class="pull-right f16">
                @if($page>0)
                <a class="btn btn-mini" href="{{asset("edoc?page=".($page))}}"><i class="icon-chevron-left"></i> ก่อนหน้า</a>
                @endif
                @if(count($alllist)>=$pcount)
                <a class="btn btn-mini" href="{{asset("edoc?page=".($page+2))}}">หน้าถัดไป <i class="icon-chevron-right"></i></a>
                @endif
            </div>
        </div>
    </div>
    <div class="span4" style="background-color:#f5f5f5; ">
        <div class="row-fluid">  
            @if(count($notification)<1)
            <span class="text-error p1">ยังไม่มีการแจ้งเตือน!!</span>
            @endif
            <ul class="nav_sbar">                
<?php $ii = 0; ?>
                @foreach($notification as $d)
                @if(++$ii<41)
                <li class="p1">
                    <a href="{{asset('edoc/view?p='.$d->id)}}" onclick="setlastnotification()">
                        <table style="width: 100%;">
                            <td class="w30 p0"><img class="w30 userimg" src="{{asset(HRDProfile::gen_profile($d->cat)->avatar)}}" /></td>
                            <td>
                                <span class="text-info">{{$d->nickname}}</span>
                                @if($d->like=='like')
                                <span class="pink"><i class="icon-thumbs-up-alt bigger-110"></i> <small class="hidden-tablet">ชอบ</small></span>
                                @elseif($d->like=='agree')
                                <span class="green"><i class="icon-check bigger-110"></i> <small class="hidden-tablet">เห็นด้วย</small></span>                                                
                                @elseif($d->like=='disagree')
                                <span class="red"><i class="icon-remove-circle bigger-110"></i> <small class="hidden-tablet">ไม่เห็นด้วย</small></span>                                                
                                @elseif($d->like=='apcept')
                                <span class="text-info"><i class="icon-ok bigger-110"></i> <small class="hidden-tablet">รับทราบ</small></span>                                                
                                @elseif($d->like=='msg')
                                <span class="text-primary"><i class="icon-comments bigger-110"></i> <small class="hidden-tablet">คิดเห็น</small></span>                                                
                                @endif
                                <div style="line-height:14px; "><span class="grey">{{$d->etitle}}</span></div>
                            </td>
                            <td class="w40 font10">
                                <span class="">{{timepost_shot($d->like_date)}}</span>
                            </td>
                        </table>                        
                    </a>
                </li>
                @endif
                @endforeach
            </ul>
        </div>
    </div>    
</div>
</div>
@stop
@section('foot')
{{ HTML::script(asset('/').'js/action.js')}}
@stop
@section('foot_meta')
<script src="{{asset('/')}}js/jquery.dataTables.min.js"></script>
<script src="{{asset('/')}}js/jquery.dataTables.bootstrap.js"></script>


{{ HTML::script(asset('js/newpost.js'))}}
<script type="text/javascript">
                        $(".doc_status").colorbox({iframe: true, width: "800px", height: "90%", overlayClose: true, onComplete : function() {   resizeColorBox; } });

</script>


@stop
@section('page_title'){{$page_title}} @stop


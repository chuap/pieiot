@extends('edoc.ace') 
@section('head_meta')
<title>ฝ่ายพัฒนาทรัพยากรบุคคล</title> 
{{ HTML::style(asset('css/autocomplete.css'))}}

@stop
<?php
$page_title = 'สร้างรายการใหม่';
$p = Input::get('p');
$canedif = false;
$canview = false;
$refid = 0;
if ($p) {
    EDoc::upread($p);
    $obj = EDOc::find($p);
    $canview = EDoc::can_view($obj);
    if (!$canview) {
        echo "<meta http-equiv='refresh' content='0;url=" . asset('edoc') . "'>";
        exit();
    }
    $etitle = $obj->etitle;
    $efrom = $obj->efrom;
    $eto = $obj->eto;
    $eno = $obj->eno;
    $eno = $obj->eno;
    $etype = $obj->etype;
    $type_name = $obj->doctype->type_name;
    $act_name = $obj->getaction->act_name;
    $sw_comment = $obj->sw_comment;
    $hidden_file = $obj->hidden_file;
    $eaction = $obj->eaction;
    $dtl = str_replace('src="files', 'src="' . asset('') . 'files', $obj->dtl);
    $dtl = str_replace('src="ckeditor', 'src="' . asset('') . 'ckeditor', $dtl);
    $dtl = str_replace('@root/', asset('/'), $dtl);
    $page_title = $obj->etitle;
    $fname = $obj->Hrcat->TITLE . $obj->Hrcat->BNAME . ' ' . $obj->Hrcat->SURNAME;
    $readcount = $obj->read_count;
    $likecount = $obj->like_count;
    $place = $obj->place;
    $doc_date = $obj->doc_date;
    $refid = $obj->from_doc;
    $canedit = EDoc::can_edit($obj);
    $list_status = EDocStatus::getStatus($p);
    $isassign = EDoc::isassign($p);
    $forword = EDoc::getforword($p);
} else {
    $etype = '';
    $eaction = '';
    $type_name = '';
}
if ($sw_comment != 111) {
    $csst1 = 'span8';
    $csst2 = 'span4';
} else {
    $csst1 = 'span12';
    $csst2 = 'hidden';
}
if ($refid) {
    $dref = EDoc::find($refid);
    $etitle = $etitle ? $etitle : '[F]' . $dref->etitle;
    //$efrom = $dref->efrom;
} else {
    $dref = 0;
}
?>
@section('breadcrumbs')
<li>
    <a href="{{asset('edoc/all?p='.$etype)}}">{{$type_name}}</a>
    <span class="divider">
        <i class="icon-angle-right arrow-icon"></i>
    </span>
</li>
<li class="active hidden-phone">{{$etitle}}</li>
@stop
@section('body')
<div class="row-fluid">
    <!-- main col left --> 
    <div class="span12 ">
        <div class="page-header">
            <div class="row-fluid">
                <div class="span12">
                    <h1>
                        <span class="{{$obj->doctype->color}}"><i class="{{$obj->doctype->icon}} "></i></span>
                        {{$etitle}}  
                    </h1>
                </div>

            </div>
        </div>

        <div class="row-fluid">
            <div class="{{$csst1}}">
                <div class="row-fluid">
                    <div class="span8">
                        <div class="profile-user-info profile-user-info-striped m0">
                            <div class="profile-info-row ">
                                <div class="profile-info-name"> ประเภท </div>
                                <div class="profile-info-value">
                                    <span>{{$obj->doctype->type_name}}<span  class="p1 muted"> เลขที่</span>
                                        {{$obj->eno?$obj->eno:'-'}}
                                        <span  class="p1 muted"> ลว.</span>
                                        {{$obj->doc_date?dateth('d n ee',strtotime($obj->doc_date)):'-'}}
                                        {{Edoc::label_pri($obj->epri)}} {{Edoc::label_secret($obj->esecret)}}
                                    </span>

                                </div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> เจ้าของเรื่อง </div>
                                <div class="profile-info-value">
                                    <span>{{$obj->efrom?:'-'}}
                                        <span  class="p1 muted"> ที่</span>
                                        {{$obj->place?$place:'-'}}</span>
                                </div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> เรียน </div>
                                <div class="profile-info-value">
                                    <span>{{$obj->eto?:'-'}}</span>
                                </div>
                            </div>
                            <div class="alert alert-success p04 m0 f12">
                                <div>
                                    <i class="icon-pencil"></i> <span  class="p1 muted">บันทึกโดย </span> {{$fname}}
                                    <span  class="p1 muted">เมื่อ </span>
                                    {{dateth('d n ee เวลา H:i',strtotime($obj->date_create))}} น.
                                    @if($canview || $isassign)
                                    <a href="#postModal" role="button" data-toggle="modal" class="pull-right btn btn-info btn-minier "><i class="icon-plus-sign-alt"></i> status </a>
                                    @endif
                                    @if($canedit)
                                    <a class="pull-right btn btn-warning btn-minier" href="{{asset('edoc/new?p='.$p)}}"><i class="icon-pencil"></i>แก้ไข</a>
                                    @endif
                                </div>
                            </div>
                            <div class="p04 m0 clearfix " id="div_status">                        
                                @foreach($list_status as $d)
                                <span id="delid{{$d->id}}" class="pr0 m04 pull-left label label-large label-{{$d->st_color}} arrowed-right">
                                    <a class="white doc_status" href="{{asset("edoc/doc_status?t=5&p=$p&stid=".$d->id)}}" title="{{$d->st_dtl}}&#013;บันทึกโดย {{$d->BNAME.' '.$d->SURNAME}} วันที่ {{dateth('d n ee',strtotime($d->date_save))}} "><i class="{{$d->st_icon}}"></i>  {{$d->st_name}} </a>
                                    @if(($d->catid==Session::get('cat.catid'))||($canedit))
                                    <a class="ml1 black" onclick="edoc_del('status',{{$d->id}}, '')" href="javascript:">x</a>
                                    @endif
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="">
                            <span>
                                <i class="icon-eye-open"></i> อ่าน 
                                <a class="doc_status" href="{{asset("edoc/doc_status?t=1&p=$p")}}">{{$readcount}} <small>ครั้ง</small></a>
                            </span>
                            <span class="ml1">
                                <i class="icon-heart"></i> ชอบ 
                                <a class="doc_status green" href="{{asset("edoc/doc_status?t=2&p=$p")}}"><span id="likeval{{$p}}">{{$likecount}}</span> <small> คน</small></a>
                                <?php
                                $ll = EDoc::liked($p, Session::get('cat.catid'));
                                if ($ll > 0) {
                                    $cs1 = "hidden";
                                    $cs2 = "";
                                } else {
                                    $cs2 = "hidden";
                                    $cs1 = "";
                                }
                                ?>
                                <div class="badge badge-pink p04 likebtn{{$p}} {{$cs1}}">                            
                                    <a href="javascript:" onclick="likeclick('{{$p}}')" class="white"><i class="icon-thumbs-up-alt"></i> ชอบ</a>
                                </div>
                                <div class="badge badge-light p04 unlikebtn{{$p}} {{$cs2}}">                            
                                    <a href="javascript:" onclick="unlikeclick('{{$p}}')" class="white"><i class="icon-thumbs-down-alt"></i> ไม่ชอบ</a>
                                </div>
                            </span>
                        </div>
                        <div class="mt1">
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-small dropdown-toggle">
                                    <i class="icon-gears black"></i> จัดการเอกสาร
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-info pull-right">                                    
                                    <li>
                                        <a href="{{asset('edoc/new?ref='.$p)}}"><i class="icon-mail-forward red"></i> สร้างเอกสารใหม่โดยอ้างอิงเรื่องนี้ </a>
                                    </li>                                    
                                    @if($isassign || $canedit)
                                    <li>
                                        <a class="doc_status" href="{{asset('edoc/share_sec?p='.$p)}}"><i class="icon-bullhorn blue"></i> แบ่งปันเรื่องนี้ให้พนักงานในส่วน </a>
                                    </li>
                                    @endif
                                    <li class="divider"></li>                                    
                                    @if($canedit)
                                    <li>
                                        <a href="{{asset('edoc/new?p='.$p)}}"><i class="icon-pencil"></i> แก้ไขเอกสาร </a>
                                    </li>
                                    @endif
                                    @if(Session::get(('cat.department')=='พบ.')&& 0)                            
                                    <li>
                                        <span id="tohrd_success_cl" class="label label-success {{$obj->tohrd_cl?'':'hidden'}}"><i class="icon-check"></i> หนังสือเวียน พบ. </span>
                                        @if(!$obj->tohrd_cl)
                                        <a href="javascript:" onclick="tohrd({{$obj->id}}, 'cl')" id="btn_tohrd_cl" class=""><i class="icon-bullhorn"></i> บันทึกเป็นหนังสือเวียน พบ. </a>
                                        @endif
                                    </li>  
                                    <li>
                                        <span id="tohrd_success_course" class="label label-pink {{$obj->tohrd_course?'':'hidden'}}"><i class="icon-check"></i> คำสั่งฝึกอบรม </span>
                                        @if(!$obj->tohrd_course)
                                        <a href="javascript:" onclick="tohrd({{$obj->id}}, 'course')" id="btn_tohrd_course" class=""><i class="icon-road"></i> บันทึกเป็นคำสั่งฝึกอบรม </a>
                                        @endif
                                    </li>
                                    @endif
                                </ul>

                            </div><!--/btn-group-->

                        </div> 
                    </div>
                </div>

                <?php
                $ah = EDocAssign::assign_h($p, '1');
                if (count($ah) > 0) {
                    $assign_dtl = $ah[0]->assign_dtl;
                }
                ?>
                @if(count($ah) > 0)
                <div class="widget-box transparent clearfix" id="recent-box">
                    <div class="widget-header">
                        <h4 class="lighter smaller">
                            <i class="icon-foursquare orange"></i>
                            Assignment
                        </h4>
                    </div>
                    <div class="widget-main p0">
                        @if($assign_dtl)
                        <div class="alert alert-dismissable black mb0">{{$assign_dtl}}</div>
                        @endif
                        <table class="table m0">
                            <thead>
                                <tr>
                                    <th class="pt0 pb0 w200">ผู้รับมอบหมาย</th>
                                    <th class="pt0 pb0"> ผลดำเนินการ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <?php
                            $i = 0;
                            ?>
                            @foreach (EDocAssign::assign_listlv1($p) as $d) 
                            <tr>
                                <td>{{++$i}}. {{$d->BNAME.' '.$d->SURNAME.' ('.$d->POSITION.')' }}</td>
                                <td>
                                    <div id="assign_dtl_{{$d->assign_id_d}}">
                                        <?php
                                        $a_assign = $d->st;
                                        $aid = $d->assign_id_d;
                                        $assign_dtl = $d->dtl;
                                        $msg = EDocAssign::assign_label($a_assign);
                                        $msg.='<input name="assign_val_' . $aid . '" id="assign_val_' . $aid . '" type="hidden" value="' . $a_assign . '">';
                                        if ($d->date_save) {
                                            $msg.='<small class="muted"> ' . dateth('d n ee', strtotime($d->date_save)) . '</small>';
                                        }
                                        if ($assign_dtl) {
                                            $msg.='<div id="assign_tx_' . $aid . '" class="red">' . $assign_dtl . '</div>';
                                        } else {
                                            $msg.='<span id="assign_tx_' . $aid . '"></span>';
                                        }
                                        echo $msg;
                                        ?>
                                    </div>
                                </td>
                                <td>
                                    @if(Session::get('cat.catid')==$d->catid)
                                    @if($a_assign>0)
                                    <a href="javascript:" class="pink" onclick="assign_edit('{{$d->assign_id_d}}')"><i class="icon-pencil"></i>แก้ไข</a>
                                    @else
                                    <a href="javascript:" class="btn btn-pink btn-small icon-animated-vertical" onclick="assign_edit('{{$d->assign_id_d}}')"><i class="icon-pencil"></i>บันทึกผล</a>
                                    @endif
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                @endif

                @if($dtl)
                <div class="widget-box transparent clearfix" id="recent-box">
                    <div class="widget-header">
                        <h4 class="lighter smaller">
                            <i class="icon-rss orange"></i>
                            Content
                        </h4>
                    </div>
                    <div class="widget-main f14 shadow ">
                        <div class="overflow-auto">{{$dtl}}</div>
                    </div>
                </div>
                @endif
                <div class="widget-box transparent clearfix" id="recent-box">
                    <div class="widget-header">
                        <h4 class="lighter smaller">
                            <i class="icon-rss orange"></i>
                            Files
                        </h4>
                    </div>
                    <div class="widget-main p0 mt1">
                        @if(!$hidden_file)
                        <ul class="ace-thumbnails clearfix">
                            @foreach (FilesBook::getimg($p) as $f)
                            <li>
                                <a href="{{asset($f->url)}}" title="" data-rel="colorbox">
                                    <img alt="150x150" style="height: 108px;" src="{{asset($f->url)}}" />
                                    <div class="tags">
                                        <span class="label label-info">{{$f->file_name}}</span>
                                    </div>
                                </a>
                                <div class="tools">
                                    <a href="{{asset($f->url)}}">
                                        <i class="icon-link"></i>
                                    </a>
                                </div>
                            </li>  
                            @endforeach
                            @foreach (FilesBook::getfile($p) as $f)
                            <?php
                            $f2 = iconv("utf-8", "tis-620//TRANSLIT", $f->url);
                            $n2 = "files/" . $f->uid . '/' . $f->file_name;
                            ?>
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
                        @else
                        <div class="alert alert-warning">ไฟล์แนบถูกซ่อนไว้</div>
                        @endif

                    </div>

                </div>

            </div>
            <div class="{{$csst2}}">
                @if($canedit || $canview)
                <div class="widget-box transparent clearfix mt0" id="recent-box">
                    <div class="widget-header">
                        <h4 class="lighter smaller">
                            <i class="icon-exclamation green"></i>
                            {{$act_name}}                             
                        </h4>
                    </div>
                    <div class="widget-main f14 clearfix">
                        @if($obj->tag_type=='a')
                        <div class="label label-purple mr1 pull-left label-large"><i class="icon-group"></i> ทุกคนในองค์กร</div>
                        @elseif($obj->tag_type=='d')
                        <div class="label label-info mr1 pull-left label-large"><i class="icon-group"></i> ทุกคนใน {{$obj->dep}} </div>
                        @elseif($obj->tag_type=='s')
                        <div class="label label-yellow mr1 pull-left label-large"><i class="icon-group"></i> {{$obj->section}} </div>
                        @endif
                        @foreach(EDoc::tag_list($p)as $d)   
                        @if($d->TITLE=='นาย')
                        <div class="pull-left font12 mr1"><i class="icon-male green"></i> {{$d->BNAME.' '.$d->SURNAME}}</div>
                        @else
                        <div class="pull-left font12 mr1"><i class="icon-female pink"></i> {{$d->BNAME.' '.$d->SURNAME}}</div>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="infobox-container">
                    @if($obj->eaction=='a2')
                    <?php
                    $isapcept = EDoc::isapcept($p, Session::get('cat.catid'));
                    ?>
                    <div id="divapcept" class="alert alert-dismissible clearfix">
                        <span class="loading2 hidden" ><img class="w30" src="{{asset('images/loading.gif')}}"/></span>
                        <div class="apceptbtn {{$isapcept?'hidden':''}}">
                            <a href="javascript:" onclick="apcept_click('{{$p}}');" class="btn btn-primary mt1 btn-primary ">
                                <i class="icon-check-sign icon-animated-wrench bigger-160"></i>
                                รับทราบเรื่องนี้
                            </a>
                        </div>
                        <div class="{{$isapcept?'':'hidden'}}" id="showapcept"> จำนวนผู้ที่รับทราบเรื่องนี้แล้ว <a id="apcept_val" href="{{asset("edoc/doc_status?t=3&p=$p")}}" class="btn btn-warning btn-mini doc_status">{{EDoc::apcept_count($p)}}</a> คน</div>

                    </div>
                    @endif
                    @if($obj->eaction=='a4')
                    <?php
                    $isagree = EDoc::isagree($p, Session::get('cat.catid'));
                    ?>
                    <div id="divapcept" class="alert alert-dismissible clearfix">
                        <span class="loading2 hidden" ><img class="w30" src="{{asset('images/loading.gif')}}"/></span>
                        <div class="apceptbtn {{$isagree?'hidden':''}} p1 ">
                            <a href="javascript:" onclick="agree_click('{{$p}}');" class="btn btn-success  btn-primary ">
                                <i class="icon-check-sign icon-animated-wrench bigger-160"></i>
                                เห็นด้วย
                            </a>
                            <a href="javascript:" onclick="disagree_click('{{$p}}');" class="btn btn-danger  btn-primary ">
                                <i class="icon-remove-sign icon-animated-wrench bigger-160"></i>
                                ไม่เห็นด้วย
                            </a>
                        </div>
                        <table  class="showapcept {{$isagree?'':'hidden'}} ">
                            <tr>
                                <td class="text-left">จำนวนผู้ที่<span class="green textuline">เห็นด้วย</span>กับเรื่องนี้</td>
                                <td><a href="{{asset("edoc/doc_status?t=4&p=$p")}}" id="agree_val" class="doc_status btn btn-success btn-mini ml1">{{EDoc::agree_count($p)}}</a> คน</td>
                            </tr>
                            <tr>
                                <td class="text-left">จำนวนผู้ที่<span class="red textuline">ไม่เห็นด้วย</span>กับเรื่องนี้ </td>
                                <td><a href="{{asset("edoc/doc_status?t=4&p=$p")}}" id="disagree_val" class="doc_status btn btn-danger btn-mini ml1">{{EDoc::disagree_count($p)}}</a> คน</td>
                            </tr>
                        </table>                    
                    </div>
                    @endif
                </div>
                @if($dref)
                <div class="widget-box mt0 mb1">
                    <div class="widget-header widget-header-flat widget-header-small">
                        <h5 class="green">
                            <i class="icon-file-text"></i>
                            ต้นเรื่อง
                        </h5>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main pl1">
                            <div class="font12"><a href="{{asset('edoc/view?p='.$dref->id)}}" target="_blank"><span class="{{$dref->doctype->color}}"><i class="{{$dref->doctype->icon}} "></i> </span>{{$dref->etitle;}}</a></div>
                            <div style="margin-left: 16px;">
                                <span>{{$dref->doctype->type_name}}<span  class="p1 muted"> เลขที่</span>
                                    {{$dref->eno?$dref->eno:'-'}}
                                    <span  class="p1 muted"> ลว.</span>
                                    {{$dref->doc_date?dateth('d n ee',strtotime($dref->doc_date)):'-'}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if(count($forword)>0)
                <div class="widget-box mt0 mb1">
                    <div class="widget-header widget-header-flat widget-header-small">
                        <h5 class="red">
                            <i class="icon-mail-forward"></i>
                            ส่งออก
                        </h5>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main pl1">
                            @foreach($forword as $d)
                            <div class="font12"><a href="{{asset('edoc/view?p='.$d->id)}}" target="_blank"><span class="{{$d->color}}"><i class="{{$d->icon}} "></i> </span>{{$d->etitle;}}</a></div>
                            <div style="margin-left: 16px;">
                                <span>{{$d->type_name}}<span  class="p1 muted"> เลขที่</span>
                                    {{$d->eno?$d->eno:'-'}}
                                    <span  class="p1 muted"> ลว.</span>
                                    {{$d->doc_date?dateth('d n ee',strtotime($d->doc_date)):'-'}}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif


                @if($sw_comment==1)
                <div class="widget-box  ">
                    <div class="widget-header ">
                        <h4 class="lighter smaller">
                            <i class="icon-comment blue"></i>
                            แสดงความคิดเห็น
                            <span class="loading hidden" ><img class="w30" src="{{asset('images/loading.gif')}}"/></span>
                        </h4>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main no-padding">
                            <form id="form_msg" action="">
                                {{ Form::hidden('at','addmsg') }}
                                {{ Form::hidden('doc_id',$p) }}    
                                <div class="form-actions input-append ">
                                    <input  id="tx_message" name="tx_message" onkeydown="return tx_message_key(event);" placeholder="เขียนข้อความที่นี่ ..." type="text" class="width-75" />
                                    <a class="btn btn-small btn-info no-radius" onclick="addmsg();">
                                        <i class="icon-share-alt"></i>
                                        <span class="hidden-phone">Send</span>
                                    </a>                                    
                                </div>                                
                            </form>
                            <div id="div_msg" class="dialogs pb1">
                                @foreach(EDocMsg::getMsg($p)as $d)
                                <div id="delidmsg{{$d->id}}" class="itemdiv dialogdiv  mb1 p0">
                                    <div class="user">
                                        <img alt="" src="{{asset(HRDProfile::gen_profile($d->catid)->avatar)}}" />
                                    </div>
                                    <div class="body">
                                        <div class="time">
                                            <i class="icon-time"></i>
                                            <span class="green">{{timepost_shot($d->date_save)}}</span>
                                        </div>
                                        <div class="name">
                                            <a href="#">{{$d->BNAME.' '.$d->SURNAME}}</a>
                                        </div>
                                        <div class="text">{{$d->msg}}</div>
                                        @if(($d->catid==Session::get('cat.catid'))||($canedit))
                                        <div class="tools">
                                            <a href="javascript:delete" onclick="edoc_del('msg',{{$d->id}}, 'msg')" class="btn btn-minier btn-danger">
                                                <i class="icon-remove"></i>
                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>


                        </div><!--/widget-main-->
                    </div><!--/widget-body-->
                </div><!--/widget-box-->   
                @endif
            </div>
        </div>



    </div>
</div>

</div>
<!--post modal-->
<div id="postModal" class="modal fade " tabindex="-1000" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="f1">
                {{ Form::hidden('at','addstatus') }}
                {{ Form::hidden('doc_id',$p) }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    Update Status
                </div>
                <div class="modal-body ">
                    <select id="atype" class="span2 " onchange="atype_click()" name="atype" >
                        <option  value=""></option>
                        <option  value="t01">ร่าง</option>
                        <option  value="t01">รับเข้า</option>
                        <option  value="t0">เสนอ ชฝ. พิจารณา</option>
                        <option  value="t1">เสนอ ฝ. ลงนาม</option>
                        <option  value="t2">เสนอ ชจญ. ลงนาม</option>
                        <option  value="t2">เสนอ รจญ. ลงนาม</option>
                        <option  value="t2">เสนอ กจญ. ลงนาม</option>
                        <option  value="sent">ลงนามแล้ว</option>
                        <option  value="sent">อนุมัติ</option>
                        <option  value="sent">ส่งออก</option>
                        <option  value="success">แล้วเสร็จ</option>
                        <option  value="cancel">ยกเลิก</option>
                    </select> &nbsp; 
                    {{ Form::text('status_date', date('d-m-Y H:i:s'), array('placeholder'=>'dd-mm-yyyy', 'class' => 'datetimepicker span2 blue')) }}
                    <div class="row-fluid">
                        {{ Form::text('st_name', '', array('placeholder'=>'<ระบุสถานะ>','id'=>'st_name', 'class' => 'span12 blue')) }}
                    </div>
                    <div class="row-fluid">
                        <textarea  class="span12 limited" placeholder="<รายละเอียด>" id="st_dtl" name="st_dtl" data-maxlength="50"></textarea>
                    </div>
                </div>
                <div class="modal-footer  ">
                    <div class="row-fluid ">
                        <div class="span8 text-left">
                            <div class="control-group m0 ">
                                <div class="controls clearfix">
                                    <label class="control-label pull-left mr1">เลือกสี </label>
                                    <label class="pull-left">
                                        <input name="st_color" type="radio" value="default" />
                                        <span class="lbl label label-default"> C1</span>
                                    </label>
                                    <label class="pull-left">
                                        <input name="st_color" type="radio" value="warning" />
                                        <span class="lbl label label-warning"> C2</span>
                                    </label>
                                    <label class="pull-left">
                                        <input name="st_color" type="radio" value="success" />
                                        <span class="lbl label label-success"> C3</span>
                                    </label>
                                    <label class="pull-left">
                                        <input name="st_color" type="radio" value="info" />
                                        <span class="lbl label label-info"> C4</span>
                                    </label>                                
                                    <label class="pull-left">
                                        <input name="st_color" type="radio" value="pink" />
                                        <span class="lbl label label-pink"> C5</span>
                                    </label>                               
                                    <label class="pull-left">
                                        <input name="st_color" type="radio" value="purple" />
                                        <span class="lbl label label-purple"> C6</span>
                                    </label>                             
                                    <label class="pull-left">
                                        <input name="st_color" type="radio" value="primary" />
                                        <span class="lbl label label-primary"> C7</span>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group m0 "> 
                                <div class="controls clearfix">
                                    <label class="control-label pull-left mr1">icon </label>
                                    <label class="pull-left mr1">
                                        <input name="st_icon" type="radio" value="icon-info" />
                                        <span class="lbl"><i class="icon-info icon-large"></i></span>
                                    </label>
                                    <label class="pull-left mr1">
                                        <input name="st_icon" type="radio" value="icon-ok" />
                                        <span class="lbl"><i class="icon-ok icon-large"></i></span>
                                    </label>
                                    <label class="pull-left mr1">
                                        <input name="st_icon" type="radio" value="icon-exclamation-sign" />
                                        <span class="lbl"><i class="icon-exclamation-sign icon-large"></i></span>
                                    </label>
                                    <label class="pull-left mr1">
                                        <input name="st_icon" type="radio" value="icon-comment" />
                                        <span class="lbl"><i class="icon-comment icon-large"></i></span>
                                    </label>
                                    <label class="pull-left mr1">
                                        <input name="st_icon" type="radio" value="icon-remove" />
                                        <span class="lbl"><i class="icon-remove icon-large"></i></span>
                                    </label>
                                    <label class="pull-left mr1">
                                        <input name="st_icon" type="radio" value="icon-plus" />
                                        <span class="lbl"><i class="icon-plus icon-large"></i></span>
                                    </label>
                                    <label class="pull-left mr1">
                                        <input name="st_icon" type="radio" value="icon-smile" />
                                        <span class="lbl"><i class="icon-smile icon-large"></i></span>
                                    </label>
                                    <label class="pull-left mr1">
                                        <input name="st_icon" type="radio" value="icon-off" />
                                        <span class="lbl"><i class="icon-off icon-large"></i></span>
                                    </label>


                                </div>
                            </div>
                        </div>
                        <div class="span4">
                            <a class="btn btn-app btn-grey btn-mini radius-4 " onclick="addstatus()"  aria-hidden="true">
                                <i class="icon-save bigger-160"></i>
                                Save
                            </a>
                        </div>	
                    </div>
                </div>
            </form>    
        </div>
    </div>
</div>
@stop
@section('foot')

@stop
@section('foot_meta')
{{ HTML::script(asset('js/newpost.js'))}}
<script type="text/javascript">
                                                                    $('#postModal').modal('toggle');
                                                                    $('#postModal').modal('toggle');
                                                                    $(".doc_status").colorbox({iframe: true, width: g_width(), height: g_height(), overlayClose: true, onComplete : function() {   resizeColorBox(); }});
</script>
@stop
@section('page_title'){{$page_title}} @stop


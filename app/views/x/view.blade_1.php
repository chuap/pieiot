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
$page_title = 'สร้างรายการใหม่';
$p = Input::get('p');
$canedif = false;
if ($p) {
    EDoc::upread($p);
    $obj = EDOc::find($p);
    if (!EDoc::can_view($obj)) {
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
    $eaction = $obj->eaction;
    $dtl = $obj->content = str_replace('src="files', 'src="' . asset('') . 'files', $obj->dtl);
    $page_title = $obj->etitle;
    $fname = $obj->Hrcat->TITLE . $obj->Hrcat->BNAME . ' ' . $obj->Hrcat->SURNAME;
    $readcount = $obj->read_count;
    $likecount = $obj->like_count;
    $place = $obj->place;
    $doc_date = $obj->doc_date;
    $canedit = EDoc::can_edit($obj);
    $list_status = EDocStatus::getStatus($p);
} else {
    $etype = '';
    $eaction = '';
    $type_name = '';
}
?>
@section('breadcrumbs')
<li>
    <a href="{{asset('edoc/all?p='.$etype)}}">{{$type_name}}</a>
    <span class="divider">
        <i class="icon-angle-right arrow-icon"></i>
    </span>
</li>
<li class="active">{{$etitle}}</li>
@stop
@section('body')
<div class="row-fluid">
    <!-- main col left --> 
    <div class="span12">
        <div class="page-header">
            <h1>
                <span class="{{$obj->doctype->color}}"><i class="{{$obj->doctype->icon}} "></i></span>
                {{$etitle}}
            </h1>

        </div>

        <div class="row-fluid">
            <div class="span6">
                <div class="profile-user-info profile-user-info-striped m0">
                    <div class="profile-info-row ">
                        <div class="profile-info-name"> ประเภท </div>
                        <div class="profile-info-value">
                            <span>{{$obj->doctype->type_name}}<span  class="p1 muted"> เลขที่</span>
                                {{$obj->eno?$obj->eno:'-'}}
                                <span  class="p1 muted"> ลว.</span>
                                {{$obj->doc_date?dateth('d n ee',strtotime($obj->doc_date)):'-'}}</span>
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
                            <a href="#postModal" role="button" data-toggle="modal" class="pull-right btn btn-info btn-minier "><i class="icon-plus-sign-alt"></i> status </a>
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

            <div class="span6 infobox-container">
                <div class="row-fluid">
                    <div class="infobox infobox-blue  ">
                        <div class="infobox-icon">
                            <i class="icon-eye-open"></i>
                        </div>
                        <div class="infobox-data">
                            <span class="infobox-data-number"><a class="doc_status" href="{{asset("edoc/doc_status?t=1&p=$p")}}">{{$readcount}} <small>ครั้ง</small></a></span>
                            <div class="infobox-content">จำนวนผู้อ่านเรื่องนี้</div>
                        </div>
                    </div>
                    <div class="infobox infobox-green  ">
                        <div class="infobox-icon">
                            <i class="icon-heart"></i>
                        </div>

                        <div class="infobox-data">
                            <span class="infobox-data-number"><a class="doc_status green" href="{{asset("edoc/doc_status?t=2&p=$p")}}"><span id="likeval">{{$likecount}}</span> <small> คน</small></a></span>
                            <div class="infobox-content">ชื่นชอบเรื่องนี้ </div>
                        </div>
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
                        <div class="badge badge-pink p04 likebtn {{$cs1}}">                            
                            <a href="javascript:" onclick="likeclick('{{$p}}')" class="white"><i class="icon-thumbs-up-alt"></i> ชอบ</a>
                        </div>
                        <div class="badge badge-light p04 unlikebtn {{$cs2}}">                            
                            <a href="javascript:" onclick="unlikeclick('{{$p}}')" class="white"><i class="icon-thumbs-down-alt"></i> ไม่ชอบ</a>
                        </div>
                    </div>
                </div>
                @if($obj->eaction=='a2')
                <?php
                $isapcept = EDoc::isapcept($p, Session::get('cat.catid'));
                ?>
                <div id="divapcept" class="alert alert-dismissible mt1  clearfix">
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
                <div id="divapcept" class="alert alert-dismissible mt1 clearfix">
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
        </div>

        <div class="row-fluid">
            <div class="span8">
                @if($dtl)
                <div class="widget-box transparent clearfix" id="recent-box">
                    <div class="widget-header">
                        <h4 class="lighter smaller">
                            <i class="icon-rss orange"></i>
                            Content
                        </h4>
                    </div>
                    <div class="widget-main f14 shadow ">
                        <div class="">{{$dtl}}</div>
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
                        <ul class="ace-thumbnails clearfix">
                            @foreach (FilesBook::getimg($p) as $f)
                            <li>
                                <a href="{{asset($f->url)}}" title="" data-rel="colorbox">
                                    <img alt="150x150" style="height: 114px;" src="{{asset($f->url)}}" />
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
                            @endforeach
                        </ul>

                    </div>

                </div>
                @if($canedit)
                <div class="widget-box transparent clearfix mt2" id="recent-box">
                    <div class="widget-header">
                        <h4 class="lighter smaller">
                            <i class="icon-sitemap green"></i>
                            {{$act_name}}
                        </h4>
                    </div>
                    <div class="widget-main f14 clearfix">
                        @if($obj->tag_type=='a')
                        <div class="label label-purple mr1 pull-left label-large"><i class="icon-group"></i> ทุกคนในองค์กร</div>
                        @elseif($obj->tag_type=='d')
                        <div class="label label-info mr1 pull-left label-large"><i class="icon-group"></i> ทุกคนในฝ่าย </div>
                        @elseif($obj->tag_type=='s')
                        <div class="label label-yellow mr1 pull-left label-large"><i class="icon-group"></i> ทุกคนในส่วน </div>
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
            </div>
            <div class="span4">
                <div class="widget-box  ">
                    <div class="widget-header ">
                        <h4 class="lighter smaller">
                            <i class="icon-comment blue"></i>
                            Comment
                            <span class="loading hidden" ><img class="w30" src="{{asset('images/loading.gif')}}"/></span>
                        </h4>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main no-padding">
                            <form id="form_msg" action="">
                                {{ Form::hidden('at','addmsg') }}
                                {{ Form::hidden('doc_id',$p) }}    
                                <div class="form-actions input-append ">
                                    <input  id="tx_message" name="tx_message" onkeydown="return tx_message_key(event);" placeholder="Type your message here ..." type="text" class="width-75" />
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
            </div>
        </div>



    </div>
</div>

</div>
<!--post modal-->
<div id="postModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="f1">
                <div class="modal-header">
                    {{ Form::hidden('at','addstatus') }}
                    {{ Form::hidden('doc_id',$p) }}
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    Update Status
                </div>
                <div class="modal-body">
                    <select id="atype" class="" onchange="atype_click()" name="atype" >
                        <option  value=""></option>
                        <option  value="t01">ร่าง</option>
                        <option  value="t0">เสนอ ชฝ.(1) ลงนาม</option>
                        <option  value="t0">เสนอ ชฝ.(2) ลงนาม</option>
                        <option  value="t1">เสนอ ฝ. ลงนาม</option>
                        <option  value="t2">เสนอ ชจญ. ลงนาม</option>
                        <option  value="t2">เสนอ รจญ. ลงนาม</option>
                        <option  value="t2">เสนอ กจญ. ลงนาม</option>
                        <option  value="sent">ลงนามแล้ว</option>
                        <option  value="sent">ส่งออกแล้ว</option>
                        <option  value="success">แล้วเสร็จ</option>
                        <option  value="cancel">ยกเลิก</option>
                    </select> &nbsp; 
                    {{ Form::text('status_date', date('d-m-Y'), array('placeholder'=>'dd-mm-yyyy', 'class' => 'datepicker span2 blue')) }}
                    <div class="row-fluid">
                        {{ Form::text('st_name', '', array('placeholder'=>'<ระบุสถานะ>','id'=>'st_name', 'class' => 'span12 blue')) }}
                    </div>
                    <div class="row-fluid">
                        <textarea class="span12 limited" placeholder="<รายละเอียด>" id="st_dtl" name="st_dtl" data-maxlength="50"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="row-fluid">
                        <div class="span8 text-left">
                            <div class="control-group m0">
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
                            <div class="control-group m0">                           

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
                            <a class="btn btn-app btn-grey btn-mini radius-4" onclick="addstatus()"  aria-hidden="true">
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
  $(".doc_status").colorbox({iframe: true, width: "800px", height: "540px", overlayClose: false});                                                    
</script>
@stop
@section('page_title'){{$page_title}} @stop


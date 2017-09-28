@extends('edoc.ace_blank') 
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
$rf = Input::get('r') ? Input::get('r') : 'refresh';
$t = Input::get('t')? : 1;
$stid = Input::get('stid')? : 0;

$canedit = false;
if ($p) {
    $obj = EDoc::find($p);
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
    //$list_status = EDocStatus::getStatus($p);
    //$list_read = EDoc::list_read($p);
    //$list_like = EDoc::list_like($p);
    //$list_apcept = EDoc::list_apcept($p);
    //$list_agree = EDoc::list_agree($p);
    $sec = Session::get('cat.section');
    $dep = Session::get('cat.department');
    if ((($sec == $obj->section) && ($dep == $obj->dep) && ('s' == $obj->tag_type)) || ($obj->tag_type == 'd') || ($obj->tag_type == 'a')) {
        $allsec = true;
    } else {
        $allsec = false;
    }
} else {
    $etype = '';
    $eaction = '';
    $type_name = '';
}
if ((($t == 3) || ($t == 4)) && (!$canedit)) {
    $t = 1;
}
$tab[$t] = 'active';
$ii = 0;
?>

@section('body')
<div class="row-fluid">
    <!-- main col left --> 
    <div class="span12">
        <div class="page-header">
            <h3 class="blue m0">
                <span class="{{$obj->doctype->color}}"><i class="{{$obj->doctype->icon}} "></i></span>
                {{$etitle}}
            </h3>
        </div>
        @if(!$allsec)
        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="{{isset($tab[1])?'active':''}}">
                    <a data-toggle="tab" href="#t1">
                        <i class="green icon-home bigger-110"></i>
                        {{Session::get('cat.section')}}
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="t1" class="tab-pane {{isset($tab[1])?'active':''}} p0">
                    <form id="f1">
                        <div>
                            <div class="label label-info">กำหนดผู้ที่สามมารถอ่านเรื่องนี้ได้</div>
                            <a href="javascript:selectall(false)" class="pull-right f12 ml1 red">ไม่เลือกเลย</a>
                            <a href="javascript:selectall(true)" class="pull-right f12 green">เลือกทั้งหมด</a>
                        </div>
                        <div class="clearfix alert alert-info black ">
                            @foreach(EDoc::section_tag($p) as $d)
                            <?php
                            $ii++;
                            ?>
                            <div class="w200 pull-left">                            
                                <label>
                                    <input class="ck" name="ck_{{$d->ID8}}" id="ck_{{$d->ID8}}" value="{{$d->ID8}}" {{$d->tag_id||$allsec?'checked':''}} name="form-field-checkbox" type="checkbox" />
                                    <span class="lbl"> {{$d->BNAME.' '.$d->SURNAME}} ({{$d->POSITION}})</span>
                                </label>

                            </div>
                            @endforeach
                            {{ Form::hidden('maxi',$ii,array('id'=>'maxi')) }}
                            {{ Form::hidden('p',$p,array('id'=>'p')) }}
                            {{ Form::hidden('rf',$rf,array('id'=>'rf')) }}
                            {{ Form::hidden('at','share_section',array('id'=>'at')) }}
                        </div>
                    </form>
                </div>                
            </div>
            <div class="text-right">
                <a id="btn_save" class="btn btn-app btn-grey btn-mini radius-4 " onclick="share_section()"  aria-hidden="true">
                    <i class="icon-save bigger-160"></i>
                    Save
                </a>
            </div>	
        </div>
        @else
        <div class="alert alert-info">
            เอกสารนี้ถูกตั้งค่าให้ทุกคนใน{{$sec}} เป็นผู้ติดตามแล้ว
        </div>
        <div class="text-right">
            <a id="btn_save" class="btn btn-app btn-grey btn-mini radius-4 " onclick="close_me()"  aria-hidden="true">

                ปิดหน้านี้
            </a>
        </div>
        @endif

    </div>
</div>
@stop
@section('foot')
<script type="text/javascript">
    function share_section() {
        $('#btn_save').addClass('hidden');
        $.ajax({
            url: rootContext + 'edoc/action',
            type: "POST",
            datatype: "json",
            data: $("#f1").serialize()
        }).success(function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.STATUS == true) {
                bootbox.dialog({
                    title: "เพิ่มผู้ติดตามแล้ว!!",
                    message: '<span class="red">' + obj.MSG + '</span>',
                    buttons: {main: {label: "ดำเนินการต่อ", className: "btn-success",
                            callback: function (result) {
                                parent.$.colorbox.close();
                                if ($('#rf').val() == 'refresh') {
                                    parent.location.reload();
                                } else if ($('#rf').val() == 'msg') {
                                    parent.gritter_show("เพิ่มผู้ติดตามแล้ว!!", obj.MSG, '5000')
                                }
                            }}
                    }

                });
                $('#btn_save').removeClass('hidden');

            } else {
                bootbox.dialog({
                    title: "ล้มเหลว!!",
                    message: 'msg: <span class="red">' + obj.MSG + '</span>',
                    buttons: {main: {label: "OK !", className: "btn-danger"}
                    }
                });
                $('#btn_save').removeClass('hidden');

            }
        });

    }
    function selectall(o) {
        $(".ck").prop('checked', o);
    }
    function close_me() {
        parent.$.colorbox.close();
    }
</script>
@stop
@section('foot_meta')
{{ HTML::script(asset('js/newpost.js'))}}
@stop
@section('page_title'){{$page_title}} @stop


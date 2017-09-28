@extends('tp.lte_blank') 
@section('head_meta')
<title></title> 
{{ HTML::style(asset('fileupload/css/jquery.fileupload.css'))}}
{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui.css'))}}
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-noscript.css'))}}</noscript>
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui-noscript.css'))}}</noscript>
{{ HTML::script(asset('ckeditor/ckeditor.js'))}}
{{ HTML::style(asset('css/autocomplete.css'))}}

@stop
<?php
$page_title = 'เพิ่มอุปกรณ์ใหม่';
$page_icon = "icon-plus";
$rf = Input::get('r') ? Input::get('r') : 'refresh';
$t = Input::get('t') ? Input::get('t') : 1;
//$p = Input::get('p') ? Input::get('p') : '';
$pieinfo = Pies::find($p);

//$sec = Session::get('cat.section');
//$startdate = date('d-m-Y', strtotime('2015-05-06'));
if ($pieinfo) {

    $page_title = ' ' . $p . ' : ' . $pieinfo->piename;
    $page_icon = "icon-pencil";
    $new_data = explode("|", $pieinfo->portslist);
    $i = 0;
    $dataop = array();
    foreach ($new_data as $id => $value) {
        $i++;
        if ($value) {
            $dataop[$value] = 1;
        }
    }
} else {
    $lv = '1';
}
$tab[$t] = 'active';
$ii = 0;

//exit();
?>

@section('body')
<div class="box box-primary">

    <div class="box box-solid box-info">
        <div class="box-header">
            <h3 class="box-title"><small>edit: </small> {{$pieinfo->piename}}</h3>
            <div class="box-tools pull-right">
            </div>
        </div>
        <div class="box-body">
            <form id="f1" >
                <div class="form-group">
                    <input value="{{$pieinfo->piename}}" type="text" class="form-control" id="txpiename" name="txpiename" placeholder="ชื่ออุปกรณ์">
                </div>
                <div class="form-group">
                    <input value="{{$pieinfo->desc}}" type="text" class="form-control" id="txdesc" name="txdesc" placeholder="">
                </div>  
                {{ Form::hidden('p',$p,array('id'=>'p')) }}
                {{ Form::hidden('ac','pieeditsave',array('id'=>'ac')) }}
                {{ Form::hidden('rf',$rf?$rf:'refresh',array('id'=>'rf')) }}
            </form>
        </div><!-- /.box-body -->


        <div class="text-center">
            <div class="p2 hidden text-center divload">
                <img src="{{asset('images/loading.gif')}}" style="width: 30px;">    
                กำลังดำเนินการ...             
            </div>
            <button onclick="pieeditsave()" class="btn_save btn bg-olive margin btn-lg "><i class="fa fa-save"></i> Save</button>
        </div>

    </div>



</div>
@stop
@section('foot')
<script language="javascript" type="text/javascript">
    function pieeditsave() {
        //alertbox($("#f1").serialize());
        //return false;
        if (ckktext('#txpiename', 'กรุณาระบุชื่ออุปกรณ์')) {
            return false;
        }
        $('.btn_save').addClass('hidden');
        $('.divload').removeClass('hidden');
        $.ajax({
            url: rootContext + 'adminaction',
            type: "POST",
            datatype: "json",
            data: $("#f1").serialize()
        }).success(function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.STATUS == true) {
                bootbox.dialog({
                    title: "บันทึกข้อมูลเรียบร้อยแล้ว!!",
                    message: '<span class="red">' + obj.MSG + '</span>',
                    buttons: {main: {label: "ดำเนินการต่อ", className: "btn-success",
                            callback: function (result) {
                                parent.$.colorbox.close();
                                if ($('#rf').val() == 'refresh') {
                                    parent.location.reload();
                                } else if ($('#rf').val() == 'msg') {
                                    parent.gritter_show("บันทึกข้อมูลเรียบร้อยแล้ว!!", obj.MSG, '5000')
                                } else {
                                    //parent.location.href($('#rf').val()+obj.MSG);
                                    parent.window.location.replace($('#rf').val() + obj.MSG);
                                    //alert($('#rf').val());
                                }
                            }}
                    }
                });
                
            } else {
                alertbox(obj.MSG + '');
                
            }
            $('.divload').addClass('hidden');
            $('.btn_save').removeClass('hidden');
        });
    }
    function ckktext(tid, msg) {
        if ($(tid).val().trim() == '') {
            alertbox(msg);
            return true;
        } else {
            return false;
        }
    }
    function rtype_click() {
        var t = $("#rtype option:selected").val();
        //alert(t)
        if ((t == 'hrd')) {
            $("#lbdepgroup").addClass('hidden');
        } else {
            $("#lbdepgroup").removeClass('hidden');
            $("#lbdepgroup").focus();
        }
    }
    function placeid_click() {
        var t = $("#placeid option:selected").val();
        //alert(t)
        if ((t != 'etc')) {
            $("#tplace").addClass('hidden');
        } else {
            $("#tplace").removeClass('hidden');
            $("#tplace").focus();
        }
    }
    function esent_click() {
        var t = $("#esent option:selected").val();
        //alert(t)
        if ((t == '0')) {
            $(".esent").addClass('hidden');
        } else {
            $(".esent").removeClass('hidden');
            //$("#sdt1").focus();

        }
    }
    function importmember(t) {
        //alert(t);
        $('#tfind').val(t);
        findmember();
    }



    function closepage(r) {
        if (r)
            parent.window.location.reload();
        parent.$.fn.colorbox.close();
    }

    function check_key(e) {
        if (e.keyCode == 13) {
            findmember();
            return false;
        }
    }


</script>
@stop
@section('foot_meta')
@stop
@section('page_title'){{$page_title}} @stop


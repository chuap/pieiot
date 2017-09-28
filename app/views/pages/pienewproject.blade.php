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
$page_title = 'เพิ่มสมาชิกใหม่';
$page_icon = "icon-plus";
$rf = Input::get('r') ? Input::get('r') : asset('admin/users?find=');
$t = Input::get('t') ? Input::get('t') : 1;
$p = Input::get('p') ? Input::get('p') : '';
$userinfo = Staff::find($p);

//$sec = Session::get('cat.section');
//$startdate = date('d-m-Y', strtotime('2015-05-06'));
if ($userinfo) {
    //$staff= Staff::find($cid);
    $staff = $userinfo;
    $page_title = 'แก้ไขผู้ใช้งาน ' . $p . ' : ' . $userinfo->fname . ' ' . $userinfo->lname;
    $page_icon = "icon-pencil";
    $ttl = $staff->titlename;
    $uclass = $staff->uclass;
} else {
    $lv = '1';
    $ttl = 'นาย';
    $cdep = '';
    $uclass = '';
}
$tab[$t] = 'active';
$ii = 0;

//exit();
?>

@section('body')
<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Quick Example</h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <form role="form">
        <div class="box box-solid box-info">
            <div class="box-header">
                <h3 class="box-title">Info Solid Box</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-info btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-info btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                </div>                
            </div><!-- /.box-body -->
        </div>

    </form>
</div>
@stop
@section('foot')
<script language="javascript" type="text/javascript">

    function saveuser() {
        if (ckktext('#txusername', 'กรุณาระบุ Username')) {
            return false;
        }
        if (ckktext('#txfname', 'กรุณาระบุชื่อ')) {
            return false;
        }
        $('#btn_save').addClass('hidden');
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
                $('#btn_save').removeClass('hidden');
            } else {
                bootbox.dialog({
                    title: "ล้มเหลว!!",
                    message: 'msg: <span class="red font14">' + obj.MSG + '</span>',
                    buttons: {main: {label: "OK !", className: "btn-danger"}
                    }
                });
                $('#btn_save').removeClass('hidden');
            }
        });
    }
    function ckktext(tid, msg) {
        if ($(tid).val().trim() == '') {
            bootbox.dialog({
                title: "แจ้งเตือน!!",
                message: 'msg: <span class="red font14">' + msg + '</span>',
                buttons: {main: {label: "OK !", className: "btn-danger"}
                }
            });
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


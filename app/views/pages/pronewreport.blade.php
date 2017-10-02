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
$page_title = 'Create new Report';
$page_icon = "icon-plus";
$rf = Input::get('r') ? Input::get('r') : 'refresh';
$t = Input::get('t') ? Input::get('t') : 1;
//$p = Input::get('p') ? Input::get('p') : '';
$pinfo = Projects::find($pro);

//$sec = Session::get('cat.section');
//$startdate = date('d-m-Y', strtotime('2015-05-06'));
if ($pinfo) {

    $page_title = ' ' . $pro . ' : ' . $pinfo->proname;
    $page_icon = "icon-pencil";
    //$new_data = explode("|", $pinfo->portslist);
    $i = 0;
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
            <h3 class="box-title">{{$page_title}}</h3>
            <div class="box-tools pull-right">
            </div>
        </div>
        <div class="box-body">
            <form id="f1">
                <div class="form-group">
                    <input value="{{$pinfo?$pinfo->proname:''}}" type="text" class="form-control" id="txproname" name="txproname" placeholder="ชื่อโครงการ">
                </div>
                <div class="form-group">
                    <input value="{{$pinfo?$pinfo->prodesc:''}}" type="text" class="form-control" id="txprodesc" name="txprodesc" placeholder="รายละเอียด">
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        เริ่มโครงการ
                        <input type="text" placeholder="dd-mm-yyyy" name="daterange" class="datepicker form-control pull-right" id="daterange">
                    </div>
                    <div class="col-sm-4">
                        สิ่นสุดโครงการ
                        <input type="text" placeholder="dd-mm-yyyy" name="daterange" class="datepicker form-control pull-right" id="daterange">
                    </div>
                </div>
                
                {{ Form::hidden('proid',$pro,array('id'=>'proid')) }}
                {{ Form::hidden('pieid',$pie,array('id'=>'pieid')) }}
                {{ Form::hidden('ac','pronewsave',array('id'=>'ac')) }}
                {{ Form::hidden('rf',$rf?$rf:'refresh',array('id'=>'rf')) }}
            </form>
        </div><!-- /.box-body -->


        <div class="text-center">
            <div class="p2 hidden text-center divload">
                <img src="{{asset('images/loading.gif')}}" style="width: 30px;">    
                กำลังดำเนินการ...             
            </div>
            <button onclick="pieeditsave()" class="btn_save btn bg-olive margin btn-lg "><i class="fa fa-save"></i> Save</button>
            <button onclick="closepage(0)" class="btn_save btn btn-default margin btn-lg "> Cancel</button>
        </div>

    </div>



</div>
@stop
@section('foot')
<script language="javascript" type="text/javascript">
    

    function pieeditsave() {
        //alertbox($("#f1").serialize());
        //return false;
        if (ckktext('#txproname', 'กรุณาระบุชื่ออุปกรณ์')) {
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
                parent.$.colorbox.close();
                parent.window.location.replace('{{asset("/")}}'+'pro-'+obj.MSG+'.info');

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


@extends('admin.ace_blank') 
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
$page_title = 'สร้างโครงการใหม่';
$page_icon = "icon-plus";
$rf = Input::get('r') ? Input::get('r') : 'refresh';
$t = Input::get('t') ? Input::get('t') : 1;
$tid = Input::get('tid') ? Input::get('tid') : '';
$pinfo = Projects::find($pro);
$pt = Ports::portByPie($pie);
//echo count($pt);
$tsk = Tasks::find($tid);
$act = '';
//$sec = Session::get('cat.section');
//$startdate = date('d-m-Y', strtotime('2015-05-06'));
if ($tsk) {
    $act = $tsk->taskaction;
    $ck1 = $tsk->ck1;
    $ck2 = $tsk->ck2;
    $ck3 = $tsk->ck3;
    $ck4 = $tsk->ck4;
    $ck5 = $tsk->ck5;
    $op1 = $tsk->op1;
    $op2 = $tsk->op2;
    $op3 = $tsk->op3;
    $op4 = $tsk->op4;
    $op5 = $tsk->op5;
    $page_title = ' ' . $pinfo->proname . ' : ' . 'Edit';
    $page_icon = "icon-pencil";
    //$new_data = explode("|", $pinfo->portslist);
    $i = 0;
} else {
    $page_title = ' ' . $pinfo->proname . ' : ' . 'New Task';
    $ck1 = '';
    $ck2 = '';
    $ck3 = '';
    $ck4 = '';
    $ck5 = '';
    $op1 = 1;
    $op2 = '';
    $op3 = '';
    $op4 = '';
    $op5 = '';
}
$tab[$t] = 'active';
$ii = 0;

//exit();
?>

@section('body')
<?php
$p1 = '';
$tx = '';
$maxi = 0;
$onbit = 1;
$ckdisable = 0;
$assign_r = array();
$tm = date("H:i:s");
if ($tsk) {
    $pp = $tsk->action_ports;
    $onbit = $tsk->onbit;
    $ckdisable = $tsk->task_disable;
    foreach (Tasks::actionPorts($tsk) as $d) {
        $maxi++;
        $lb1 = $d->portname;
        $t0 = '<input id="assignid' . $maxi . '" name="assignid' . $maxi . '" type="hidden" value="' . $d->portno . '">';
        $t = '<div id="ac' . $d->portno . '" class="contact-itm bg_cat">' . $t0 . $lb1 . ' <a onclick="delassign(\'ac' . $d->portno . '\')" class="btn btn-xs btn-minier btn-danger pt0">x</a></div>';

        $tx .= $t;
        $assign_r[$d->portno] = true;
    }
    if ($maxi > 0) {
        $p1 = 'well';
    }
}
?>
<div class="row-fluid">
    <div class="span12">
        <p class="lead">{{$page_title}}</p>
        <form id="f1" onsubmit="return false;">
            <div>
                <div>
                    <input style="background-color: #ffffcc; color: #000;" id="txtaskname" name="txtaskname" type="text" placeholder="Task name" value="{{$tsk?$tsk->taskname:''}}" class=" span12">  
                </div>
                <div class=" form-inline" >
                    <div class="control-group mb1">
                        <select id="action_mode" name="action_mode" onchange="mode_select()" >

                            @foreach (Ports::allMode() as $d)
                            <option {{$act==$d->mode_id?'selected':''}} value="{{$d->mode_id}}"> {{$d->mode_name}}</option>
                            @endforeach
                        </select>
                        <div class="btn-group">
                            <a data-toggle="dropdown" class=" btn btn-info btn-small dropdown-toggle">
                                <i class="icon-plus"></i> Add Port
                            </a>
                            <ul class="dropdown-menu dropdown-default">
                                @foreach ($pt as $n2) 
                                <?php
                                $cl = 'onclick="addassign(\'' . $n2->portno . '\', \'' . $n2->portname . '\')"';
                                ?>
                                <li class="pl2 {{$n2->modes}} ports">
                                    <a class="p0 m0 {{$n2->assigned?'red':''}}" href="javascript:" {{$cl}} ><i class="{{$n2->assigned?'icon-info':'icon-plus-sign'}}"></i> {{$n2->portname}}</a>
                                </li>
                                @endforeach
                            </ul>



                        </div>
                        <label>
                            <input name="ckenable" {{$ckdisable==0?'checked':''}} value="1" class="ace-checkbox-2" type="checkbox">
                            <span class="lbl"> Enable</span>
                        </label>

                    </div>
                </div>

                <div id="divaction_ports" class="clearfix mb0 p04 {{$p1}}">{{$tx}}</div> 
                <input id="tmaxaction_ports" name="tmaxaction_ports" type="hidden" value="{{$maxi}}">

            </div>
            <div id="accordion2" class="accordion  inp ">
                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a href="#collapseOne" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle ">
                            <i class="icon-bullseye"></i>
                            Actions <span class="badge badge-important"></span>
                        </a>
                    </div>
                    <div class="accordion-body" id="collapseOne">

                        <div class="accordion-inner clearfix">
                            <div class="form-inline ports md_bitout"> 
                                <label>
                                    ช่วงเวลาเปิด-ปิด                            
                                </label>
                                <span class="ml2">

                                    <div class="input-append bootstrap-timepicker">
                                        <input id="txtime1" name="txtime1" type="text" value="{{$tsk?$tsk->stime:'00:00:00'}}" class=" timepicker w60">
                                        <span class="add-on">
                                            <i class="icon-time"></i>
                                        </span>
                                    </div>
                                    ถึงเวลา
                                    <div class="input-append bootstrap-timepicker">
                                        <input id="txtime2" name="txtime2" type="text" value="{{$tsk?$tsk->etime:'23:59:59'}}" class=" timepicker w60">
                                        <span class="add-on">
                                            <i class="icon-time"></i>
                                        </span>
                                    </div>
                                </span>
                                <span class="ml1">สัญญาณเมื่อ On </span>
                                <select id="onbit" name="onbit" onchange="" class="w100" >
                                    <option {{$onbit==1?'selected':''}} value="1">1: High</option>
                                    <option {{$onbit==0?'selected':''}} value="0">0: Low</option>                                    
                                </select>
                            </div>
                            <div class="form-inline ports md_bitout mt1"> 
                                <label>
                                    <input name="ckonoff" {{$ck1==1?'checked':''}} value="1" class="ace-checkbox-2" type="checkbox">
                                    <span class="lbl"> สลับการเปิดปิด</span>
                                </label>
                                <span class="ml2">
                                    เปิดนาน
                                    <div class="input-append ">
                                        <input id="txco1" name="txco1" type="text" value="{{$tsk?$tsk->tx1:''}}" class=" w50">                                    
                                    </div>
                                    <small class="pr1">วินาที</small> ปิดนาน
                                    <div class="input-append bootstrap-timepicker">
                                        <input id="txco2" name="txco2" type="text" value="{{$tsk?$tsk->tx2:''}}" class=" w50">
                                    </div>
                                    <small class="pr1">วินาที</small>
                                </span>
                            </div>
                            <div class="form-inline ports md_capture "> 
                                <label>
                                    <input name="ckcapture" {{$op1==1?'checked':''}} value="1" class="" type="radio">
                                    <span class="lbl"> กำหนดเวลาถ่ายภาพ</span>
                                </label>
                                <span class="ml2">
                                    <div class="input-append ">
                                        <input id="txcapture1" name="txcapture1" type="text" value="{{$tsk?$tsk->tx1:''}}" class=" w200">                                    
                                    </div>
                                    <small class="pr1">(เช่น  8:30, 14:00)</small> 
                                </span>
                            </div>
                            <div class="form-inline ports md_capture mt1"> 
                                <label>
                                    <input name="ckcapture" {{$op1==2?'checked':''}} value="2" class="" type="radio">
                                    <span class="lbl"> กำหนดช่วงความถี่ในการถ่ายภาพ</span>
                                </label>
                                <span class="ml2">
                                    ถ่ายภาพทุกๆ
                                    <div class="input-append ">
                                        <input id="txcapture2" name="txcapture2" type="text" value="{{$tsk?$tsk->tx2:''}}" class=" w60">                                    
                                    </div>
                                    <small class="pr1">(นาที)</small> 
                                </span>
                                <div class="input-append bootstrap-timepicker">
                                    <input id="txcapture3" name="txcapture3" type="text" value="{{$tsk?$tsk->stime:'00:00:00'}}" class=" timepicker w60">
                                    <span class="add-on">
                                        <i class="icon-time"></i>
                                    </span>
                                </div>
                                ถึงเวลา
                                <div class="input-append bootstrap-timepicker">
                                    <input id="txcapture4" name="txcapture4" type="text" value="{{$tsk?$tsk->etime:'23:59:59'}}" class=" timepicker w60">
                                    <span class="add-on">
                                        <i class="icon-time"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="form-inline ports md_temp "> 
                                <label>
                                    <input name="cktemp" {{$op1==1?'checked':''}} value="1" class="" type="radio">
                                    <span class="lbl"> กำหนดเวลาอ่านค่า</span>
                                </label>
                                <span class="ml2">
                                    <div class="input-append ">
                                        <input id="txtemp1" name="txtemp1" type="text" value="{{$tsk?$tsk->tx1:''}}" class=" w200">                                    
                                    </div>
                                    <small class="pr1">(เช่น  8:30, 14:00)</small> 
                                </span>
                            </div>
                            <div class="form-inline ports md_temp mt1"> 
                                <label>
                                    <input name="cktemp" {{$op1==2?'checked':''}} value="2" class="" type="radio">
                                    <span class="lbl"> กำหนดช่วงความถี่ในการอ่านค่า</span>
                                </label>
                                <span class="ml2">
                                    อ่านค่าทุกๆ
                                    <div class="input-append ">
                                        <input id="txtemp2" name="txtemp2" type="text" value="{{$tsk?$tsk->tx2:''}}" class=" w60">                                    
                                    </div>
                                    <small class="pr1">(วินาที)</small> 
                                </span>
                                <div class="input-append bootstrap-timepicker">
                                    <input id="txtemp3" name="txtemp3" type="text" value="{{$tsk?$tsk->stime:'00:00:00'}}" class=" timepicker w60">
                                    <span class="add-on">
                                        <i class="icon-time"></i>
                                    </span>
                                </div>
                                ถึงเวลา
                                <div class="input-append bootstrap-timepicker">
                                    <input id="txtemp4" name="txtemp4" type="text" value="{{$tsk?$tsk->etime:'23:59:59'}}" class=" timepicker w60">
                                    <span class="add-on">
                                        <i class="icon-time"></i>
                                    </span>
                                </div>
                            </div>
                            




                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center 04">
                <div class="p2 hidden text-center divload">
                    <img src="{{asset('images/loading.gif')}}" style="width: 30px;">    
                    กำลังดำเนินการ...             
                </div>
                <button  class="btn btn-info btn_save" onclick="tasksave()">
                    <i class="icon-ok bigger-110"></i>
                    Save
                </button>

                &nbsp; &nbsp; &nbsp;
                <button class="btn" onclick="closepage(0)">
                    <i class="icon-remove-circle bigger-110"></i>
                    Cancel
                </button>
            </div>
            {{ Form::hidden('proid',$pro,array('id'=>'proid')) }}
            {{ Form::hidden('pieid',$pie,array('id'=>'pieid')) }}
            {{ Form::hidden('taskid',$tid,array('id'=>'taskid')) }}
            {{ Form::hidden('ac','tasksave',array('id'=>'ac')) }}
            {{ Form::hidden('rf',$rf?$rf:'refresh',array('id'=>'rf')) }}
        </form>
    </div>
</div>
@stop
@section('foot')
<script language="javascript" type="text/javascript">
    $(function () {
        mode_begin();
    });

    function tasksave() {
//        alertbox($("#f1").serialize());
//        return false;
        if (ckktext('#txtaskname', 'กรุณาระบุ Task name')) {
            return false;
        }
        var maxi = sumassign();
        if (maxi < 1) {
            alertbox('กรุณากำหนด Action Port');
            return false;
        }
        $('.btn_save').addClass('hidden');
        $('.divload').removeClass('hidden');
        $.ajax({
            url: rootContext + 'adminaction',
            type: "GET",
            datatype: "json",
            data: $("#f1").serialize()
        }).success(function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.STATUS == true) {
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
    function assign_select() {
        var lb1 = $("#action_ports option:selected").text();
        var bid = $('#action_ports').val();
        addassign(bid, lb1);
    }
    function mode_select() {
        var m = $('#action_mode').val();
        $('.ports').addClass('hidden');
        $('.md_' + m).removeClass('hidden');
        $("#divaction_ports").removeClass('well');
        $("#divaction_ports").text('');
        $('#tmaxaction_ports').val(0);
    }
    function mode_begin() {
        var m = $('#action_mode').val();
        $('.ports').addClass('hidden');
        $('.md_' + m).removeClass('hidden');
    }
    function addassign(bid, lb1) {
        var maxi = parseFloat($('#tmaxaction_ports').val()) + 1;
        var t0 = '<input id="assignid' + maxi + '" name="assignid' + maxi + '" type="hidden" value="' + bid + '">';
        var t = '<div id="ac' + bid + '" class="contact-itm bg_cat">' + t0 + lb1 + ' <a onclick="delassign(\'ac' + bid + '\')" class="btn btn-xs btn-minier btn-danger pt0">x</a></div>';
        if ($('#ac' + bid).length < 1) {
            $("#divaction_ports").append(t);
            $('#tmaxaction_ports').val(maxi);
            sumassign();
            $('#action_ports').val('');
            $('#sac' + bid).addClass('list_sl');
        }

    }
    function sumassign() {
        var maxi = parseFloat($('#tmaxaction_ports').val());
        var x = 0;
        for (var i = 1; i <= maxi; i++) {
            if ($('#assignid' + i).val() > 0) {
                x++;
            }
        }
        if (x < 1) {
            $("#divaction_ports").removeClass('well');
            //$("#assign_dtl").addClass('hidden');
        } else {
            $("#divaction_ports").addClass('well');
            //$("#assign_dtl").removeClass('hidden');

        }
        return x;
    }
    function delassign(tr) {
        //var tr = $(this).closest('tr');
        //alert(tr);
        $('#' + tr).css("background-color", "#FF3700");
        $('#' + tr).fadeOut(400, function () {
            $('#' + tr).remove();
            $('#s' + tr).removeClass('list_sl');
            sumassign();
        });
        //return false;
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
<script>

</script>
@stop
@section('page_title'){{$page_title}} @stop


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
$page_title = 'New Report';
$page_icon = "icon-plus";
$rf = Input::get('r') ? Input::get('r') : 'refresh';
$rtype = Input::get('rtype');
$datasl = Input::get('datasl');
$rid = Input::get('rid');
$step = Input::get('step');
//$step = 1;
$tid = ''; $charttype='Line'; $groupby='10';
 $op1=1;$op2=1;$op3=0;
$re = Reports::find($rid);
if ($re && !$step) {
    if (!$step) {
        $step = 3;
    }
    $rname = $re->rname;
    $groupby = $re->groupby;
    $op1 = $re->op1;
    $op2 = $re->op2;
    $op3 = $re->op3;
    $page_title = 'edit: ' . $rname;
    if (!$rtype) {
        $rtype = $re->rtype;
    }

    $datasl = $re->datasl;
    $stime = date('H:i:s', strtotime($re->sdate));
    $etime = date('H:i:s', strtotime($re->edate));
    $sdate = date('d-m-Y', strtotime($re->sdate));
    $edate = date('d-m-Y', strtotime($re->edate));
    $listData = Reports::listData($rtype, $re->tid);
    foreach ($listData as $d) {
        $datack[$d->tid] = 1;
    }
    if ($step == 2) {
        $listData = Reports::listData($rtype);
    }
} else {
    if (!$rtype) {
        $step = 1;
    } else if (!$datasl) {
        $step = 2;
        $listData = Reports::listData($rtype);
    } else {
        $step = 3;
        $listData = Reports::listData($rtype);
        $rname = '';
        $sdate = '9';
        $edate = '';
        $i = 0;
        $tid = '';
        foreach ($listData as $j => $d) {
            if (Input::get('rdata_' . $j) > 0) {

                if ($i > 0) {
                    $rname .= ', ';
                    $tid .= ',';
                }
                $rname .= $d->dataname;
                $tid .= "'" . Input::get('rdata_' . $j) . "'";
                if ($d->mind < $sdate) {
                    $sdate = $d->mind;
                }
                if ($d->maxd > $edate) {
                    $edate = $d->maxd;
                }
                $i++;
            }
        }


        $listData = Reports::listData($rtype, $tid);

        $stime = date('H:i:s', strtotime($sdate));
        $etime = date('H:i:s', strtotime($edate));
        $sdate = date('d-m-Y', strtotime($sdate));
        $edate = date('d-m-Y', strtotime($edate));
    }
}

if ($rtype) {
    $rt = Reports::rType($rtype);
} else {
    $rt = '';
}
?>

@section('body')
<?php
//echo "$rtype $datasl tid: $tid";
?>

@if($step==1)
<div class="row-fluid">
    <div class="span12">
        <p class="lead">{{$page_title}}</p>        
    </div>
    <div>
        <a href="{{asset('pie.newreport?step=2&rtype=Album&rid='.$rid)}}" class="btn btn-app btn-primary no-radius" style="width: 150px;">
            <i class="icon-picture bigger-230"></i>Photo Album
        </a>
        <a href="{{asset('pie.newreport?step=2&rtype=Chart&rid='.$rid)}}" class="btn btn-app w160 btn-warning no-radius" style="width: 150px;">
            <i class="icon-bar-chart bigger-230"></i> Chart
        </a>
        <a href="{{asset('pie.newreport?step=2&rtype=Table&rid='.$rid)}}" class="btn btn-app w160 btn-success no-radius" style="width: 150px;">
            <i class="icon-table bigger-230"></i>Data Table
        </a>
    </div>
</div>
@elseif($step==2)
<div class="row-fluid ">
    <div class="span12">
        <p class="lead"><i class="{{$rt->rticon}}"></i> {{$rt->rtname}} : Select Data</p>        
    </div>
    <div>
        <form id="f1">
            <table class="table mt0 table-hover" style="border-bottom: #f4f2f2 solid 1px;">
                <thead>
                    <tr>
                        <th class="w20">#</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listData as $i=>$d)
                    <tr>
                        <td>{{$i+1}}</td>
                        <td><label>
                                <input {{isset($datack[$d->tid])?'checked':$i==0?'checked':''}} name="rdata_{{$i}}" value="{{$d->tid}}" class="ace-checkbox-2 rdata" type="checkbox">
                                <span class="lbl font120p"> {{$d->dataname}}</span>
                            </label>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ Form::hidden('rtype',$rtype,array('id'=>'rtype')) }}
            {{ Form::hidden('datasl',$datasl,array('id'=>'datasl')) }}
            {{ Form::hidden('rid',$rid,array('id'=>'rid')) }}

        </form>
        <div class="form-horizontal" >
            <div class="control-group mb1">
                <label class="control-label" for="form-field-1">
                    <a href="{{asset('pie.newreport?step=1&rid='.$rid)}}" class=""><i class="icon-backward bigger-125"></i>  Back </a>
                </label>
                <div class="controls">
                    <a onclick="tostep3()" class="btn btn-info "><i class="icon-forward bigger-125"></i>  Next </a>
                </div>
            </div>
        </div>
    </div>
</div>
@elseif($step==3)
<div class="row-fluid">
    <div class="span12">
        <p class="lead"><i class="{{$rt->rticon}}"></i> {{$rname}} : <small>Options</small></p>        
    </div>
    <div>
        <form id="f1">
            <div class="form-horizontal" >
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">Report name</label>
                    <div class="controls">
                        <input class="span10" id="rname" name="rname" type="text"  value="{{$rname}}">
                    </div>
                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">Date range</label>
                    <div class="controls">
                        <input class="w80 datepicker" id="sdate" name="sdate" type="text"  value="{{$sdate}}">
                        <div class="input-append bootstrap-timepicker">
                            <input id="stime" name="stime" type="text" value="{{$stime}}" class=" timepicker w60">
                            <span class="add-on">
                                <i class="icon-time"></i>
                            </span>
                        </div>
                        <label class="inline" for=""> - </label>
                        <input class="w80 datepicker" id="edate" name="edate" type="text"  value="{{$edate}}">
                        <div class="input-append bootstrap-timepicker">
                            <input id="etime" name="etime" type="text" value="{{$etime}}" class=" timepicker w60">
                            <span class="add-on">
                                <i class="icon-time"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">Group by</label>
                    <div class="controls">
                        <select id="groupby" class="form-control" onchange="" name="groupby">
                            <option {{$groupby=='20'?'selected':''}}  value="20">All Data</option>
                            <option {{$groupby=='16'?'selected':''}}  value="16">Minute</option>
                            <option {{$groupby=='15'?'selected':''}}  value="15">10 Minute</option>
                            <option {{$groupby=='13'?'selected':''}} value="13">Hour</option>
                            <option {{$groupby=='12'?'selected':''}} value="12">10 Hour</option>
                            <option {{$groupby=='10'?'selected':''}} value="10">Day</option>
                            <option {{$groupby=='9'?'selected':''}} value="9">10 Day</option>
                            <option {{$groupby=='7'?'selected':''}} value="7">Month</option>                            
                            <option {{$groupby=='4'?'selected':''}} value="4">Year</option>
                            
                        </select>  
                        <span class="help-inline"></span>
                    </div>
                </div>
                @if($rtype=='Chart')
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">Chart Type</label>
                    <div class="controls pt04">
                        <label class="pull-left">
                            <input name="barchart" {{$op1=='1'?'checked':''}}  value="1" class="ace-checkbox-2" type="checkbox">
                            <span class="lbl"> Bar Chart</span>
                        </label>
                        <label class="pull-left ml1">
                            <input name="linechart" {{$op2=='1'?'checked':''}} value="1" class="ace-checkbox-2" type="checkbox">
                            <span class="lbl"> Line Chart</span>
                        </label>
                        <label class="pull-left ml1">
                            <input name="areachart" {{$op3=='1'?'checked':''}} value="1" class="ace-checkbox-2" type="checkbox">
                            <span class="lbl"> Area Chart</span>
                        </label>
                    </div>
                </div>
                @endif
                <div class="control-group mb1">
                    @if(!$rid)
                    <label class="control-label" for="form-field-1">
                        <a href="{{asset('pie.newreport?step=2&rtype='.$rtype.'&rid='.$rid)}}"><i class="icon-backward"></i> Back</a>
                    </label>
                    @endif
                    <div class="controls">
                        <a onclick="savereport()" class="btn_save btn btn-info "><i class="icon-save bigger-125"></i>  Save report </a>
                        <div class="hidden text-center divload pull-left">
                            <img src="{{asset('images/loading.gif')}}" style="width: 30px;">    
                            Processing...             
                        </div>
                    </div>

                </div>
            </div>

            {{ Form::hidden('rid',$rid,array('id'=>'rid')) }}
            {{ Form::hidden('ac','savereport',array('id'=>'ac')) }}
            {{ Form::hidden('rtype',$rtype,array('id'=>'rtype')) }}
            {{ Form::hidden('datasl',$datasl,array('id'=>'datasl')) }}
            
            @foreach ($listData as $i=>$d)
            {{ Form::hidden('rdata_'.$i,$d->tid,array('id'=>'rdata_'.$i)) }}
            @endforeach
            
        </form>
    </div>
</div>
@endif
@stop
@section('foot')
<script language="javascript" type="text/javascript">
    $(function () {
        mode_begin();
    });

    function tostep3() {
        var x = $('input.rdata:checked').length;
        if (x < 1) {
            alertbox('Please select data  !!');
            return false;
        }
        $('#datasl').val(x);
        window.location.replace('{{asset("pie.newreport")}}?' + $("#f1").serialize());

    }

    function savereport() {
//        alertbox($("#f1").serialize());
//        return false;
        if (ckktext('#rname', 'Please enter Report name')) {
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
                parent.location.reload();
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


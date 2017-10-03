@extends('tp/lte') 
<?php
$pro = Projects::find($p);
?>
@if(!$pro)
@section('body')
<div class="p2">
    <div class="alert alert-info alert-dismissable ">
        <i class="fa fa-info"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Alert!</b> ไม่พบข้อมูล
    </div>
</div>
@stop
@else
@section('page_title')
{{$pro->proname}}
@stop
@section('page_header')
<h1 id="lbpro_{{$p}}">

    {{$pro->proname}}    
    <small>{{$pro->prodesc}}</small>
</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{asset('/')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <li><a href="{{asset('projects')}}"><i class="fa fa-pie-chart"></i>Projects</a></li>
    <li class="active">{{$pro->proname}}</li>
</ol>
@stop
@section('body')
<style>
    /*    .tb1{ background-color: #ffe6eb;}*/
</style>
<div class="row" id="pro_{{$p}}">
    <!-- Left col -->
    <!-- right col (We are only adding the ID to make the widgets sortable)-->
    <section class="col-lg-12 connectedSortable ui-sortable">

        <div class="box box-info">
            <div class="box-header">
                <i class="fa fa-bullseye"></i>
                <h3 class="box-title">Tasks
                    @if($pro->proactive)
                    <label class="font12 label label-success">กำลังทำงาน</label>
                    @else 
                    <label class="font12 label label-danger">ยังไม่เปิดใช้งาน</label>
                    @endif
                </h3>
                <div class="pull-right box-tools">

                    <div class="btn-group">

                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-plus-circle"></i> Task</button>
                        <ul class="dropdown-menu pull-right" role="menu ">                            
                            @foreach(Pies::listMyPie() as $m)
                            <li><a href="{{asset("pro-".$m->pieid.'-'.$pro->proid.".newtask")}}" class="newproject">{{$m->piename}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <a href="{{asset("pro-".$pro->pieid.'-'.$pro->proid.".newproject")}}" class="newproject btn bg-light-blue "><i class="fa fa-pencil"></i> Edit</a>

                    <div class="btn-group">

                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear"></i></button>
                        <ul class="dropdown-menu pull-right" role="menu ">

                            <li><a href="#">เปิดใช้งาน</a></li>
                            <li class="divider"></li>
                            <li><a class="newproject " href="{{asset("pro-".$pro->pieid.'-'.$pro->proid.".newproject")}}" >Edit</a></li>
                            <li><a href="javascript:" onclick="deleteproject('{{$pro->pieid}}','{{$pro->proid}}')">Delete project</a></li>
                        </ul>
                    </div>

                </div>

            </div><!-- /.box-header -->
            <div class="box-body pt0">

                <table class="table table-hover tb1">
                    <tbody><tr>
                            <th style="width: 10px">#</th>
                            <th>Task</th>
                            <th>Pie</th>
                            <th>Port</th>
                            <th>Data</th>
                            <th>Status/Update</th>
                            <th></th>
                            <th></th>
                        </tr>
                        @foreach(Projects::getTask($p) as $i=>$d2)
                        <?php
                        $edit_url = asset("pro-" . $d2->pieid . '-' . $d2->proid . ".newtask?tid=" . $d2->tid);
                        $port_n = str_replace("'", "", $d2->action_ports);
                        $port_n = str_replace(",", "_", $port_n);
                        ?>
                        <tr id="tr_{{$d2->tid}}" class="{{$d2->task_disable?'danger':''}}">
                            <td>{{$i+1}}.</td>
                            <td><a id="lb_{{$d2->tid}}" class="newproject" href="{{$edit_url}}">{{$d2->taskname}}</a>
                                <p><small>{{Projects::taskDesc2($d2)}}</small></p>
                            </td>
                            <td>
                                <a href="{{asset('pie-'.$d2->pieid.'.info')}}">{{$d2->piename}}</a>
                            </td>
                            <td>
                                @foreach (Tasks::actionPorts($d2) as $d)
                                <div>{{$d->portname}}

                                </div>
                                @endforeach
                            </td>
                            <td >
                                <span id="port_{{$port_n}}">
                                    <span class="port_{{$port_n}}">
                                        {{Ports::portValue($d,'h50')}}
                                    </span>     
                                </span>
                                
                            </td>
                            <td><div id="sync_{{$d2->tid}}">
                                    <span class="sync_{{$d2->tid}}">
                                        {{Tasks::syncedLabel($d2)}}
                                    </span>
                                </div>
                                <div class="font10 taskupdate_{{$d2->tid}}">{{$d->lastupdate}}</div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <?php
                                    if ($d2->task_disable && ($d2->synced != null)) {
                                        $dsdel = '';
                                    } else {
                                        $dsdel = 'hidden';
                                    }
                                    ?>
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear"></i></button>
                                    <ul class="dropdown-menu pull-right" role="menu ">
                                        <li class="{{'mnenable_'.$d2->tid}} {{$d2->task_disable?'':'hidden'}}"><a class="ml04" title="Remove Task" href="javascript:" onclick="enabletask('{{$d2->tid}}','{{$d2->pieid}}')" ><i class="fa fa-check"></i> Enable</a></li>
                                        <li><a class="newproject " href="{{$edit_url}}"><i class="fa fa-edit"></i> Edit</a></li>
                                        <li class="divider "></li>
                                        <li class="{{'mndisable_'.$d2->tid}} {{$d2->task_disable?'hidden':''}}"><a class="ml04" title="Disable Task" href="javascript:" onclick="disabletask('{{$d2->tid}}','{{$d2->pieid}}')" ><i class="fa fa-minus-circle"></i> Disable</a></li>
                                        <li class="{{'mndelete_'.$d2->tid}} {{$dsdel?$dsdel:''}}"><a class="ml04" title="Remove Task" href="javascript:" onclick="deletetask('{{$d2->tid}}','{{$d2->pieid}}')" ><i class="fa fa-minus-circle"></i> Delete</a></li>
                                    </ul>
                                </div>



                            </td>
                        </tr>
                        @endforeach
                    </tbody></table>


            </div><!-- /.box-body -->
        </div>
        <div id="time">
            <h1 class="time"></h1>
        </div>

    </section><!-- right col -->

</div>

@stop
@endif
@section('foot')

<script type="text/javascript">
    $(function () {
    startMonitor();
    startMonitorData();
    });
    /*  */
    function startMonitor()
    {
    $.ajax({
    url: rootContext + 'monitoraction',
            type: "GET",
            datatype: "json",
            data: "ac=check_sync&pro={{$p}}"
    }).success(function (rt) {

    var obj = jQuery.parseJSON(rt);
    //alert(result); 
    //effectdata('time', obj.DATA.length);
    if (obj.STATUS == true) {
    for (var i = 0, len = obj.DATA.length; i < len; i++) {
    effectdata('sync_' + obj.DATA[i]['tid'], obj.DATA[i]['synced']);
    $('.taskupdate_' + obj.DATA[i]['tid']).html(obj.DATA[i]['datesave']);
    }


    } else {
    //alertbox(obj.MSG + '');
    }
    });
    setTimeout(function () {
    startMonitor()
    }, 3000);
    }

    function startMonitorData()
    {
    $.ajax({
    url: rootContext + 'monitoraction',
            type: "GET",
            datatype: "json",
            data: "ac=check_data&pro={{$p}}"
    }).success(function (rt) {

    var obj = jQuery.parseJSON(rt);
    //alert(result); 
    //effectvalue('time', obj.DATA.length);
    if (obj.STATUS == true) {
    for (var i = 0, len = obj.DATA.length; i < len; i++) {
    effectvalue('port_' + obj.DATA[i]['portno'], obj.DATA[i]['d1']);
    $('.taskupdate_' + obj.DATA[i]['tid']).html(obj.DATA[i]['datesave']);
    }


    } else {
    //alertbox(obj.MSG + '');
    }
    });
    setTimeout(function () {
    startMonitorData()
    }, 3000);
    }

    function effectdata(itm, dt){
    var x = $('.' + itm).html();
    if (x != dt){
    $('.' + itm).css("background-color", "#FF3700");
    $('.' + itm).fadeOut(400, function () {
    $('.' + itm).remove();
    $('#' + itm).html('<span class="' + itm + '">' + dt + '</span>');
    }); }
    }
    function effectvalue(itm, dt){
    var x = $('.' + itm).html();
    if (x != dt){
    $('.' + itm).css("background-color", "#FF3700");
    $('.' + itm).fadeOut(400, function () {
    $('.' + itm).remove();
    $('#' + itm).html('<span class="' + itm + '">' + dt + '</span>');
    }); }
    $('a.gallery').colorbox({rel:'gal'});
    }
</script>
<script src="{{asset('/')}}js/pieactions.js" type="text/javascript"></script>
@stop
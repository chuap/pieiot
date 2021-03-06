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
                    <label class="font12 label label-success"></label>
                    @else 
                    <label class="font12 label label-danger"></label>
                    @endif
                </h3>
                <div class="pull-right box-tools">
                    <a href="{{asset('pie.newtask?proid='.$pro->proid)}}" class="btn btn-warning white newproject" ><i class="fa fa-plus-circle"></i> Task</a>
                    <!--                    <div class="btn-group">
                    
                                            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="fa fa-plus-circle"></i> Task</button>
                                            <ul class="dropdown-menu pull-right" role="menu ">                            
                                                @foreach(Pies::listMyPie() as $m)
                                                <li><a href="{{asset("pro-".$m->pieid.'-'.$pro->proid.".newtask")}}" class="newproject">{{$m->piename}}</a></li>
                                                @endforeach
                                            </ul>
                                        </div>-->
                    <a href="{{asset("pro-".$pro->pieid.'-'.$pro->proid.".newproject")}}" class="newproject btn bg-light-blue "><i class="fa fa-pencil"></i> Edit</a>

                    <div class="btn-group">

                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear"></i></button>
                        <ul class="dropdown-menu pull-right" role="menu ">


                            <li><a class="newproject " href="{{asset("pro-".$pro->pieid.'-'.$pro->proid.".newproject")}}" >Edit</a></li>
                            <li><a href="javascript:" onclick="deleteproject('{{$pro->pieid}}','{{$pro->proid}}')">Delete project</a></li>
                        </ul>
                    </div>

                </div>

            </div><!-- /.box-header -->
            <div class="box-body pt0">
                @if($pro->protype=='fix')
                <table class="table table-hover tb1">
                    <tbody><tr>
                            <th style="width: 10px">#</th>
                            <th>Task</th>
                            <th class="hidden-xs" >Pie</th>
                            <th class="hidden-xs">Port</th>
                            <th>Data</th>
                            <th >Status</th>
                            <th></th>
                        </tr>
                        @foreach(Projects::getTask($p) as $i=>$d2)
                        <?php
                        $edit_url = asset("pie.newtask?proid=" . $d2->proid . "&tid=" . $d2->tid);
                        //$edit_url = asset("pro-" . $d2->pieid . '-' . $d2->proid . ".newtask?tid=" . $d2->tid);
                        $port_n = str_replace("'", "", $d2->action_ports);
                        $port_n = str_replace(",", "_", $port_n);
                        ?>
                        <tr id="tr_{{$d2->tid}}" class="{{$d2->task_disable?'danger':''}}">
                            <td><img class="w20" src="{{asset($d2->tmimg)}}" /></td>
                            <td><a id="lb_{{$d2->tid}}" class="newproject" href="{{$edit_url}}">{{$d2->taskname}}</a>
                                <p class="hidden-xs"><small>{{Projects::taskDesc2($d2)}}</small></p>
                                <div class="hidden-lg hidden-md hidden-sm">
                                    <div class="font10" style="color:{{$d2->picolor}} ">{{$d2->piename}}</div>
                                    @foreach (Tasks::actionPorts($d2) as $d)
                                    <div class=" font10"> {{$d->portname}}</div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="hidden-xs">
                                <div class="text-center pull-left" style=" padding: 2px; background-color: {{$d2->picolor}}; ">
                                    <a title="{{$d2->piename}}" href="{{asset('pie-'.$d2->pieid.'.info')}}">
                                        <img  class="w40" src="{{asset($d2->img)}}" /></a></div>
                            </td>
                            <td class="hidden-xs">
                                @foreach (Tasks::actionPorts($d2) as $d)
                                <div>{{$d->portname}}</div>
                                @endforeach
                            </td>
                            <td  >
                                <span class="x_{{$d2->tid}}_port_{{$port_n}}">
                                    <span class="{{$d2->tid}}_port_{{$port_n}}">
                                        {{Ports::portValue($d,'h50')}}
                                    </span>     
                                </span>

                            </td>
                            <td><div id="sync_{{$d2->tid}}">
                                    <span class="sync_{{$d2->tid}}">
                                        {{Tasks::syncedLabel($d2)}}
                                    </span>
                                </div>
                                <div class="hidden-xs font10 taskupdate_{{$d2->tid}}">{{$d->lastupdate}}</div>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <?php
                                    if ($d2->task_disable) {
                                        $dsdel = '';
                                    } else if ($d2->synced == null) {
                                        $dsdel = '';
                                    } else {
                                        $dsdel = 'hidden';
                                    }
                                    ?>
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear"></i></button>
                                    <ul class="dropdown-menu pull-right" role="menu ">
                                        <li class="{{'mnenable_'.$d2->tid}} {{$d2->task_disable?'':'hidden'}}"><a class="ml04" title="Remove Task" href="javascript:" onclick="enabletask('{{$d2->tid}}','{{$d2->pieid}}')" ><i class="fa fa-check"></i> Enable</a></li>
                                        <li><a class="newproject " href="{{$edit_url}}"><i class="fa fa-edit"></i> Edit</a></li>
                                        <li><a class="newproject " href="{{$edit_url}}"><i class="fa fa-play"></i> Task Action</a></li>
                                        <li class="divider "></li>
                                        <li class="{{'mndisable_'.$d2->tid}} {{$d2->task_disable?'hidden':''}}"><a class="ml04" title="Disable Task" href="javascript:" onclick="disabletask('{{$d2->tid}}','{{$d2->pieid}}')" ><i class="fa fa-minus-circle"></i> Disable</a></li>
                                        <li class="{{'mndelete_'.$d2->tid}} {{$dsdel?$dsdel:''}}"><a class="ml04" title="Remove Task" href="javascript:" onclick="deletetask('{{$d2->tid}}','{{$d2->pieid}}')" ><i class="fa fa-minus-circle"></i> Delete</a></li>
                                    </ul>
                                </div>



                            </td>
                        </tr>
                        @endforeach
                    </tbody></table>
                @elseif($pro->protype=='flow')
                <div id="drawing" style="margin:30px auto; width:900px;"></div>
                @endif

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
@if($pro->protype=='flow')
@section('head_meta')
<script src="{{asset('js/svg')}}/jquery.scrollTo.min.js"></script>
<script src="{{asset('js/svg')}}/svg.min.js"></script>
<script src="{{asset('js/svg')}}/flowsvg.min.js"></script>
@stop
<script>
///////////////////// start flow chart ////////////////////////////////////////////////////////////
                                            flowSVG.draw(SVG('drawing').size(900, 1100));
                                            flowSVG.config({
                                            interactive: true,
                                                    showButtons: true,
                                                    connectorLength: 60,
                                                    scrollto: true
                                            });
                                            flowSVG.shapes(
                                            [
                                            {
                                            label: 'knowPolicy',
                                                    type: 'decision',
                                                    text: [
                                                            'Condition l ?'
                                                    ],
                                                    yes: 'hasOAPolicy',
                                                    no: 'checkPolicy'
                                            },
                                            {
                                            label: 'hasOAPolicy',
                                                    type: 'decision',
                                                    text: [
                                                            'Condition 2 ?'
                                                    ],
                                                    yes: 'CCOffered',
                                                    no: 'canWrap'
                                            },
                                            {
                                            label: 'CCOffered',
                                                    type: 'decision',
                                                    text: [
                                                            'Condition 3 ?'
                                                    ],
                                                    yes: 'canComply',
                                                    no:'checkGreen'
                                            },
                                            {
                                            label: 'canComply',
                                                    type: 'finish',
                                                    text: [
                                                            'Finish'
                                                    ]
                                            },
                                            {
                                            label: 'canWrap',
                                                    type: 'decision',
                                                    text: [
                                                            'Condition 2.1 ?'
                                                    ],
                                                    yes: 'checkTimeLimits',
                                                    no: 'doNotComply'
                                            },
                                            {
                                            label: 'doNotComply',
                                                    type: 'finish',
                                                    text: [
                                                            'finish'
                                                    ]
                                            },
                                            {
                                            label: 'checkGreen',
                                                    type: 'process',
                                                    text: [
                                                            'Process 2'
                                                    ],
                                                    next: 'journalAllows',
                                            },
                                            {
                                            label: 'journalAllows',
                                                    type: 'decision',
                                                    text: ['Condition 5 ?'],
                                                    yes: 'checkTimeLimits',
                                                    no: 'cannotComply',
                                                    orient: {
                                                    yes:'r',
                                                            no: 'b'
                                                    }

                                            },
                                            {
                                            label: 'checkTimeLimits',
                                                    type: 'process',
                                                    text: [
                                                            'Process 3'
                                                    ],
                                                    next: 'depositInWrap'
                                            },
                                            {
                                            label: 'cannotComply',
                                                    type: 'finish',
                                                    text: [
                                                            'Finish'
                                                    ]
                                            },
                                            {
                                            label: 'depositInWrap',
                                                    type: 'finish',
                                                    text: [
                                                            'Finish'
                                                    ]
                                            },
                                            {
                                            label: 'checkPolicy',
                                                    type: 'process',
                                                    text: [
                                                            'Process 1 '
                                                    ],
                                                    
                                                    next: 'hasOAPolicy'
                                            }
                                            ]);</script>
@endif
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
    effectvalue(obj.DATA[i]['tid'] + '_port_' + obj.DATA[i]['portno'], obj.DATA[i]['d1']);
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
    $('.x_' + itm).html('<span class="' + itm + '">' + dt + '</span>');
    }); }
    $('a.gallery').colorbox({rel:'gal'});
    }
</script>
<script src="{{asset('/')}}js/pieactions.js" type="text/javascript"></script>
@stop
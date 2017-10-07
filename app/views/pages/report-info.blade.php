@extends('tp/lte') 
<?php
$rid = Input::get('rid');
$rinfo = Reports::reportInfo($rid);
$listData = Reports::listData($rinfo->rtype, $rinfo->tid);
foreach ($listData as $d) {
    $dataname[$d->tid] = $d->dataname;
}
?>
@section('head_meta')
<link href="{{asset('themes/lte')}}/css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
@stop
@section('page_header')
<h1>

    <i class="{{$rinfo->rticon}} red font180p"></i>
    <span id="lbreport_{{$rinfo->rid}}">{{$rinfo->rname}}  </span>

    <div class="btn-group">

        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear"></i></button>
        <ul class="dropdown-menu pull-right" role="menu ">


            <li><a class="newproject " href="{{asset("pie.newreport?rid=".$rinfo->rid)}}" >Edit</a></li>
            <li><a href="javascript:" onclick="deletereport('{{$rinfo->rid}}')">Delete report</a></li>
        </ul>
    </div>
    <!--<a href="{{asset("pie.newreport?rid=".$rinfo->rid)}}" class="btn btn-default btn-sm newproject"><i class="fa fa-edit"></i></a>-->

</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{asset('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{asset('reports')}}"><i class="fa fa-bar-chart-o"></i> Reports</a></li>
    <li class="active">Reports</li>
</ol>
@stop
@section('body')
<?php
//$rlist=Reports::listReport();
?>
@if($rinfo->rtype=='Album')
<?php
$plist = Reports::listImages($rinfo);
?>
<div class="row">  
    <div class="col-lg-12">
        <div class="callout callout-info">
            <p><span class="">{{date('d M Y H:i:s',strtotime($rinfo->sdate))}}</span> - 
                <span class="">{{date('d M Y H:i:s',strtotime($rinfo->edate))}}</span></p>
        </div>  
    </div>
    @foreach($plist as $i=>$d)
    @if((file_exists($d->data))||1)
    <div class="col-md-3 col-sm-4 col-xs-6 col-lg-2">
        <div class="box box-solid">
            <div class="p1">
                <a class="imcolorbox " rel="A" href="{{asset($d->data)}}"><img style="width: 100%" src="{{asset($d->data)}}"></a>
            </div>
            <div class="bg-gray p04">
                <i class="fa fa-camera"></i> <small>{{$d->sdt}}</small>
            </div>
        </div>

    </div>
    @endif
    @endforeach
</div>
@elseif($rinfo->rtype=='Table')
<?php
$plist = Reports::dataTable($rinfo);
?>
<div class="row">  
    <div class="col-lg-12">
        <div class="callout callout-info mb0">
            <p><span class="">{{date('d M Y H:i:s',strtotime($rinfo->sdate))}}</span> - 
                <span class="">{{date('d M Y H:i:s',strtotime($rinfo->edate))}}</span></p>
        </div>  
        <table id="example2" class="table dataTable ">
            <thead>
                <tr>
                    <th class="w30"></th>
                    <th>Date</th>
                    <th>Source</th>
                    <th >Data</th>
                    <th >Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($plist as $i=>$d)
                <tr>
                    <td>{{$i+1}}</td> 
                    <td class="">{{$d->sdt}}</td> 
                    <td class="font12 green">{{$dataname[$d->tid]}}</td>
                    <td> 
                        {{$d->mn=='temp'?number_format($d->data,2):$d->data}}
                    </td>
                    <td> 
                        {{$d->mn=='temp'?number_format($d->data2,2):$d->data2}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="h100"></div>
    </div> 
</div>

@elseif($rinfo->rtype=='Chart')
<?php
$plist = Reports::dataChart($rinfo);
?>
<div class="row">  
    <div class="col-lg-12">
        <div class="callout callout-info mb0">
            <p><span class="">{{date('d M Y H:i:s',strtotime($rinfo->sdate))}}</span> - 
                <span class="">{{date('d M Y H:i:s',strtotime($rinfo->edate))}}</span></p>
        </div>
        <div class="row">
            @if($rinfo->op1==1)
            <div class="col-lg-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Bar Chart: {{$rinfo->rname}}</h3>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="bar-chart" style="height: 300px;"></div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            @endif 
            @if($rinfo->op2==1)
            <div class="col-lg-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Line Chart: {{$rinfo->rname}}</h3>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="line-chart" style="height: 300px;"></div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            @endif 
            @if($rinfo->op3==1)
            <div class="col-lg-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Area Chart: {{$rinfo->rname}}</h3>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="area-chart" style="height: 300px;"></div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            @endif
        </div>
    </div>
</div>
@endif


@stop

@section('foot')
<script src="{{asset('themes/lte')}}/js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="{{asset('themes/lte')}}/js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
@if($rinfo->rtype=='Chart')
<script type="text/javascript">
                var cdata = [
                {y: '2011 Q1', item1: 2666, item2: 2666},
                {y: '2011 Q2', item1: 2778, item2: 2294},
                {y: '2011 Q3', item1: 4912, item2: 1969},
                {y: '2011 Q4', item1: 3767, item2: 3597},
                {y: '2012 Q1', item1: 6810, item2: 1914},
                {y: '2012 Q2', item1: 5670, item2: 4293},
                {y: '2012 Q3', item1: 4820, item2: 3795},
                {y: '2012 Q4', item1: 15073, item2: 5967},
                {y: '2013 Q1', item1: 10687, item2: 4460},
                {y: '2013 Q2', item1: 8432, item2: 5713}];
                $(function () {
                "use strict";
// AREA CHART


<?php
echo 'var cdata2=[';
foreach ($plist as $i => $d) {
    echo "{'period': '" . $d->sdt . "', item1: " . number_format($d->avg1, 2) . ", item2: " . number_format($d->avg2, 2) . " },";
}
echo '];';
?>
<?php if ($rinfo->op1 == 1) { ?>
                    var area = new Morris.Bar({
                    element: 'bar-chart',
                            resize: true,
                            data: cdata2,
                            xkey: 'period',
                            ykeys: ['item1', 'item2'],
                            labels: ['Temperature', 'Humidity'],
                            xLabels:'Year',
                            xLabelAngle: 60,
                            lineColors: ['#de2828', '#3c8dbc'],
                            hideHover: 'auto'
                    });
    <?php
}
if ($rinfo->op2 == 1) {
    ?>

                    var area = new Morris.Line({
                    element: 'line-chart',
                            resize: true,
                            data: cdata2,
                            xkey: 'period',
                            ykeys: ['item1', 'item2'],
                            labels: ['Temperature', 'Humidity'],
                            xLabels:'Year',
                            xLabelAngle: 60,
                            lineColors: ['#de2828', '#3c8dbc'],
                            hideHover: 'auto'
                    });
    <?php
}
if ($rinfo->op3 == 1) {
    ?>
                    var area = new Morris.Area({
                    element: 'area-chart',
                            resize: true,
                            data: cdata2,
                            xkey: 'period',
                            ykeys: ['item1', 'item2'],
                            labels: ['Temperature', 'Humidity'],
                            xLabels:'Year',
                            xLabelAngle: 60,
                            lineColors: ['#de2828', '#3c8dbc'],
                            hideHover: 'auto'
                    });
<?php } ?>


                });</script>

@endif

<script type="text/javascript">
    $(function () {

    //$("#example1").dataTable();
    $('#example2').dataTable({
    "bPaginate": true,
            "iDisplayLength": 10,
            "bFilter": false,
            "bSort": false,
            "bInfo": true,
            "bAutoWidth": false
    });
    });
</script> 
<script src="{{asset('/')}}js/pieactions.js" type="text/javascript"></script>
@stop
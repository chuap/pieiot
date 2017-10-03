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
    {{$rinfo->rname}}  {{$rinfo->rticon}} 
    <a href="{{asset("pie.newreport?rid=".$rinfo->rid)}}" class="btn btn-default btn-sm newproject"><i class="fa fa-edit"></i></a>
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
                <i class="fa fa-camera"></i> <small>{{dateth(' d/m/Y H:i:s',strtotime($d->datesave))}}</small>
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
                    <td class="">{{date('d M Y H:i:s',strtotime($d->datesave))}}</td> 
                    <td class="font12 green">{{$dataname[$d->tid]}}</td>
                    <td> 
                        {{$d->data}}
                    </td>
                    <td> 
                        {{$d->data2}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="h100"></div>
    </div> 
</div>

@endif




@stop

@section('foot')
<script src="{{asset('themes/lte')}}/js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
<script src="{{asset('themes/lte')}}/js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
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
@stop
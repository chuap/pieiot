@extends('tp/lte') 
<?php
$rid = Input::get('rid');
$rinfo = Reports::find($rid);
?>
@section('page_header')
<h1>
    <small>Report:</small>
    {{$rinfo->rname}} 
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
$plist=Reports::listImages($rinfo);
?>
<div class="row">  
    <div class="col-lg-12">
        <div class="callout callout-info">
            <p>No Connection !!</p>
        </div>  
    </div>
    @foreach($plist as $i=>$d)
    @if(file_exists($d->data))
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
@endif

@stop
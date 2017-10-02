@extends('tp/lte') 
@section('page_header')
<h1>
    Reports
    <small>PieIoT.com</small>
     <a href="{{asset("pie.newreport")}}" class="btn btn-info newproject"><i class="fa fa-plus"></i> Create new report</a> 
</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{asset('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Reports</li>
</ol>
@stop
@section('body')
<?php
    $rlist=Reports::listReport();
?>
<div class="row">  
    <div class="col-lg-12">
        
        @if(count($rlist)<1)
        <div class="p2">
            <div class="alert alert-info alert-dismissable ">
                <i class="fa fa-info"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <b>Alert!</b> No Report..
            </div>
        </div>
        @else        
        <div class="row">
            @foreach($rlist as $i=>$d)
            <div class="col-md-4">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title text-danger">{{$d->rname}}</h3>
                        <div class="box-tools pull-right">
                            <a href="{{asset("pie.newreport?rid=".$d->rid)}}" class="btn btn-default btn-sm newproject"><i class="fa fa-edit"></i></a>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body text-center">
                        <div class="sparkline" data-type="pie" data-offset="90" data-width="100px" data-height="100px"><canvas width="100" height="100" style="display: inline-block; width: 100px; height: 100px; vertical-align: top;"></canvas></div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            @endforeach
            
        </div>
        @endif
    </div>
</div>

@stop
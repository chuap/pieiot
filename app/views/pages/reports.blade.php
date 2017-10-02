@extends('tp/lte') 
@section('page_header')
<h1>
    Reports
    <small>PieIoT.com</small>
     <a href="{{asset("pro-0-0.newreport")}}" class="btn btn-info newproject"><i class="fa fa-plus"></i> Create new report</a> 
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
$plist = Projects::myProjects();
?>
<div class="row">  
    <div class="col-lg-12">
        
        @if(count($plist)<1)
        <div class="p2">
            <div class="alert alert-info alert-dismissable ">
                <i class="fa fa-info"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <b>Alert!</b> No Report..
            </div>
        </div>
        @else        
        <div class="row">
            <div class="col-md-4">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title text-danger">Sparkline Pie</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body text-center">
                        <div class="sparkline" data-type="pie" data-offset="90" data-width="100px" data-height="100px"><canvas width="100" height="100" style="display: inline-block; width: 100px; height: 100px; vertical-align: top;"></canvas></div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->

            <div class="col-md-4">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title text-blue">Sparkline line</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body text-center">
                        <div class="sparkline" data-type="line" data-spot-radius="3" data-highlight-spot-color="#f39c12" data-highlight-line-color="#222" data-min-spot-color="#f56954" data-max-spot-color="#00a65a" data-spot-color="#39CCCC" data-offset="90" data-width="100%" data-height="100px" data-line-width="2" data-line-color="#39CCCC" data-fill-color="rgba(57, 204, 204, 0.08)"><canvas style="display: inline-block; width: 326px; height: 100px; vertical-align: top;" width="326" height="100"></canvas></div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->

            <div class="col-md-4">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title text-warning">Sparkline Bar</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body text-center">
                        <div class="sparkline" data-type="bar" data-width="97%" data-height="100px" data-bar-width="14" data-bar-spacing="7" data-bar-color="#f39c12"><canvas width="224" height="100" style="display: inline-block; width: 224px; height: 100px; vertical-align: top;"></canvas></div>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div>
        @endif
    </div>
</div>

@stop
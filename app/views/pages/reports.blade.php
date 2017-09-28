@extends('tp/lte') 
@section('page_header')
<h1>
    Reports
    <small>PieIoT.com</small>
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
        <div class="box">
            <div class="box-body ">
                <a href="{{asset("pro-0-0.newproject")}}" class="btn btn-info newproject pull-right"><i class="fa fa-plus"></i> Create new report</a>
                
            </div><!-- /.box-body -->
        </div>
        @if(count($plist)<1)
        <div class="p2">
            <div class="alert alert-info alert-dismissable ">
                <i class="fa fa-info"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <b>Alert!</b> No Report..
            </div>
        </div>
        @endif
    </div>
</div>

@stop
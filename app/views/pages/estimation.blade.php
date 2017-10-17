@extends('tp/lte') 
@section('page_header')
<h1>
    Estimation
    <small>PieIoT.com</small>
    <a href="" class="btn btn-success newproject"><i class="fa fa-plus"></i> Create estimation</a> 
</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{asset('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Estimation</li>
</ol>
@stop
@section('body')
<?php
$rlist = Reports::listReport();
$cco = 0;
$tt1 = array();
?>
<div class="row">  
    <div class="col-lg-12">

        <div class="p2">
            <div class="alert alert-info alert-dismissable ">
                <i class="fa fa-info"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <b>Alert!</b> No Data..
            </div>
        </div>
    </div>
</div>

@stop

@section('foot')
@if(1)
<script type="text/javascript">
    $(function () {
        "use strict";
        $('.sparkbar').sparkline('html', {type: 'bar'});
        //$('.sparkline').sparkline('html', {type: 'bar', barColor: '#aaf'});
        $(".sparkline").each(function () {
            var $this = $(this);
            $this.sparkline('html', $this.data());
        });



    });
</script>

@endif


<script src="{{asset('/')}}js/pieactions.js" type="text/javascript"></script>
@stop
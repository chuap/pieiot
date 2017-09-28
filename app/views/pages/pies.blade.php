@extends('tp/lte') 
@section('page_header')
<h1>
    Pies
    <small>PieIoT.com</small>
</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{asset('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Pies</li>
</ol>
@stop
@section('body')
<?php
$pies = Pies::listNewPie();
?>
<div class="row">
    @foreach($pies as $d)
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-{{$d->color?$d->color:'yellow'}}">
            <div class="inner">
                <a href="{{asset('pie-'.$d->pieid.'.info')}}" class="white">

                    <h3 style="font-size: 24px;">
                        {{$d->piename}}
                    </h3>
                    <p>
                        {{$d->piemodel}}
                    </p>
                </a></div>
            <div class="icon">
                @if($d->img)
                <img class="h50" src="{{asset($d->img)}}">
                @else
                <i class="ion {{$d->icon}}"> </i>
                @endif
            </div>
            <a href="{{asset('pie-'.$d->pieid.'.info')}}" class="small-box-footer">
                Active: {{dateth('d n ee H:i',strtotime($d->lastupdate))}} <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    @endforeach
    @if(count($pies)<1)
    <div class="p2">
        <div class="alert alert-info alert-dismissable ">
            <i class="fa fa-info"></i>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <b>Alert!</b> ยังไม่มีรายการอุปกรณ์
        </div>
    </div>
    @endif

</div>

@stop
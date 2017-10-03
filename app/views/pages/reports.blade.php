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
$rlist = Reports::listReport();
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
            <?php
            $rlink = asset('/pie.report-info') . "?rid=" . $d->rid;
            ?>
            <div class="col-md-6 col-sm-6 col-xs-12 col-lg-4">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title "> <a class="text-light-blue" href="{{$rlink}}">{{$d->rname}}</a>
                            <div class="font70p mt1">
                                <span class="">{{date('d M Y H:i:s',strtotime($d->sdate))}}</span> - 
                                <span class="">{{date('d M Y H:i:s',strtotime($d->edate))}}</span>
                            </div>
                        </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body pt0">
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-8">
                                <div class="row pl2">
                                    @foreach(Reports::dataDemo($d)as $dm)
                                    <div class="col-md-4 p04 col-sm-3 col-xs-4">
                                        @if($dm->mn=='capture')
                                        <img title="{{dateth(' d/m/Y H:i:s',strtotime($dm->datesave))}}" style="width: 100%;" src="{{asset($dm->data)}}">
                                        @elseif ($dm->mn=='temp')
                                        <div class="font11">
                                            T: <span class="label label-danger">{{number_format($dm->data,1)}}</span> *C 
                                        </div>
                                        <div class="font11 pt04">
                                            H: <span class="label label-info">{{number_format($dm->data2,1)}}</span> % 
                                        </div>
                                        <div class="red" style="line-height: 11px; font-size: 10px; margin-top: 4px;">
                                            {{dateth(' d/m/Y H:i:s',strtotime($dm->datesave))}}
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <a href="{{$rlink}}" class="btn btn-app btn-info">
                                    <i class="{{$d->rticon}} font30 red"></i> View
                                </a>
                            </div>
                        </div>

                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
            @endforeach

        </div>
        @endif
    </div>
</div>

@stop
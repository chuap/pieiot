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
$cco = 0;
$tt1 = array();
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
                <div class="box box-{{$d->rtcolor}}" style="overflow: hidden;">
                    <div class="box-header">
                        <h3 class="box-title" style="width: 500px;"> <a class="text-light-blue" href="{{$rlink}}">{{$d->rname}}</a>
                            <div class="font70p mt1">
                                <span class="">{{date('d M Y H:i:s',strtotime($d->sdate))}}</span> - 
                                <span class="">{{date('d M Y H:i:s',strtotime($d->edate))}}</span>
                            </div>
                        </h3>
                    </div><!-- /.box-header -->
                    <div class="box-body pt0">
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-8">
                                <div class="row">

                                    @if($d->rtype=='Chart')

                                    <?php
                                    $dt1 = '';
                                    $dt2 = '';
                                    foreach (Reports::dataDemo($d)as $j => $dm) {

                                        if ($dt1) {
                                            $dt1.=',';
                                        }
                                        $dt1.=number_format($dm->data, 2);

                                        if ($dm->mn == 'temp') {
                                            if ($dm->data2 > 0) {
                                                if ($dt2) {
                                                    $dt2.=',';
                                                }
                                                $dt2.=number_format($dm->data2, 2);
                                            }
                                        }
                                    }
                                    ?>
                                    <div class="{{$dm->mn == 'temp'?'col-xs-6 col-lg-6':'col-xs-12 col-lg-12'}} ">
                                        <div class="sparkline" data-type="line" data-width="100%" data-height="60px" data-spot-Radius="3" data-highlight-Spot-Color="#f39c12" data-highlight-Line-Color="#de1b1b" data-min-Spot-Color="#f56954" data-max-Spot-Color="#00a65a" data-spot-Color="#39CCCC" data-offset="90"   data-line-Width='2' data-line-Color='red' data-fill-Color='#ebd5de'>
                                            {{$dt1}}
                                        </div>
                                    </div>
                                    @if($dm->mn == 'temp')
                                    <div class="col-xs-6 col-lg-6 ">
                                        <div class="sparkline" data-type="line" data-width="100%" data-height="60px" data-spot-Radius="3" data-highlight-Spot-Color="#f39c12" data-highlight-Line-Color="#222" data-min-Spot-Color="#f56954" data-max-Spot-Color="#00a65a" data-spot-Color="#39CCCC" data-offset="90"   data-line-Width='2' data-line-Color='#39CCCC' data-fill-Color='rgba(57, 204, 204, 0.08)'>
                                            {{$dt2}}
                                        </div>
                                    </div>
                                    @endif
                                    @else
                                    @foreach(Reports::dataDemo($d)as $j=>$dm)
                                    @if($j<3)
                                    <div class="pl2">
                                        <div class="col-md-4 p04 col-sm-3 col-xs-4">

                                            @if($dm->mn=='capture')
                                            <img title="{{$dm->sdt}}" style="width: 100%;" src="{{asset($dm->data)}}">
                                            @elseif ($dm->mn=='temp')
                                            <div class="font11">
                                                T: <span class="label label-danger">{{number_format($dm->data,1)}}</span> *C 
                                            </div>
                                            <div class="font11 pt04">
                                                H: <span class="label label-info">{{number_format($dm->data2,1)}}</span> % 
                                            </div>
                                            <div class="red" style="line-height: 11px; font-size: 10px; margin-top: 4px;">
                                                {{$dm->sdt}}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4">
                                <a href="{{$rlink}}" class="btn btn-app btn-info p04">
                                    <i class="{{$d->rticon}} font30 red"></i><br />View
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
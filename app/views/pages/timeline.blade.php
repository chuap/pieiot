@extends('tp/lte') 
@section('page_header')
<h1>
    Timeline
    <small>PieIoT.com</small>
</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{asset('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Timeline</li>
</ol>
@stop
@section('body')
<?php
$plist = Pies::timeLine_h();
?>
<!-- row -->
<div class="row">                        
    <div class="col-md-12">
        <!-- The time line -->
        <ul class="timeline">
            @foreach($plist as $d)
            <!-- timeline time label -->
            <li class="time-label">
                <span class="bg-red">
                    {{date('d M. Y',strtotime($d->dt))}}
                </span>
            </li>
            <?php
            $tlist = Pies::timeLine_t($d->dt);
            ?>
            <!-- /.timeline-label -->
            @foreach($tlist as $d2)
            @if($d2->taskname)
            <li>
                @if($d2->mn=='synced')
                <i class="fa icon-exchange bg-blue"></i>
                @else 
                <img class="fa {{$d2->tmcolor}} " src="{{asset($d2->tmimg)}} " />
                @endif
                <div class="timeline-item">
                    <span class="time"><i class="fa fa-clock-o"></i> {{date('H:i:s',strtotime($d2->dt))}}</span>
                    <h3 class="timeline-header"> <a href="{{asset("pro-".$d2->proid.".info")}}">{{$d2->taskname}}</a> 
                    <a class="grey" href="{{asset('pie-'.$d2->pieid.'.info')}}">{{$d2->piename}}</a> </h3>
                    <div class="timeline-body">
                        <div class="mb1">{{Projects::taskDesc2($d2)}}</div>
                        <div class="row">
                            <?php
                            $lt = '';
                            ?>
                            @foreach(Pies::timeLine_d($d2->tid,$d->dt) as $j=>$d3)
                            <?php
                            $t1 = date('H:i:s', strtotime($d3->datesave));
                            ?>
                            <div class="col-lg-12">
                                <span class="font90p green">{{($lt==$t1)?'':$t1}}</span>
                                <span>{{Tasks::valueLabel($d3,'h100');}}</span>
                            </div>
                            <?php
                            $lt = $t1;
                            ?>

                            @endforeach
                        </div>
                    </div>
                    <div class='timeline-footer'>
                        <a href="{{asset("pro-".$d2->proid.".info")}}" class="btn btn-warning btn-flat btn-xs">{{$d2->proname}}</a> 
                    </div>
                </div>
            </li>
            @endif
            @endforeach

            @endforeach


        </ul>
    </div><!-- /.col -->
    
</div><!-- /.row -->


<div class="row">

    @if(count($plist)<1)
    <div class="p2">
        <div class="alert alert-info alert-dismissable ">
            <i class="fa fa-info"></i>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <b>Alert!</b> ยังไม่มีโครงการ
        </div>
    </div>
    @endif

</div>



@stop

@section('foot')
<script src="{{asset('/')}}js/pieactions.js" type="text/javascript" />
<script type="text/javascript">


</script>
@stop
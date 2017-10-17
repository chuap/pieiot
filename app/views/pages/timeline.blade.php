@extends('tp/lte') 
@section('page_header')
<h1>
    Summary
    <small>PieIoT.com</small>
    <span class="blue font16"> {{date('d M Y H:i:s')}}</span>
</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{asset('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Summary</li>
</ol>
@stop
@section('body')
<?php
$plist = Pies::timeLine_h();
$pt = Ports::portTimeline();
?>
<!-- row -->
<div class="row">
    <div class="col-md-12">
        <div class="callout callout-info">
            <a href="{{asset('pies')}}" class="btn bg-blue btn-social"><i class="fa fa-th"></i> <span>Controllers</span> <small class="badge pull-right bg-green">{{Pies::countMyPie()}}</small></a>
            <a href="{{asset('projects')}}" class="btn bg-maroon btn-social"><i class="fa fa-dashboard"></i> <span>Projects</span> <small class="badge pull-right bg-blue">{{Projects::countMyProject()}}</small></a>
            <a href="{{asset('reports')}}" class="btn bg-green btn-social"><i class="fa fa-bar-chart-o"></i> <span>Reports</span> <small class="badge pull-right bg-purple">{{Reports::countMyReport()}}</small></a>
        </div>
    </div>
</div>
<div class="row">                        
    <div class="col-md-7">
        <div class="box box-info" id="loading-example">
            <div class="box-header" >
                <i class="icon-time ion"></i>
                <h3 class="box-title ">Timeline</h3>                
            </div><!-- /.box-header -->


            <div class="box-body pt0"> 
                <!-- The time line -->
                <div class="row">
                    <div class="col-md-12">
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
                    </div>

                </div>
            </div>
        </div>
    </div><!-- /.col -->
    <div class="col-md-5">
        <div class="box box-danger" id="loading-example">
            <div class="box-header" >
                <i class="ion ion-arrow-swap"></i>
                <h3 class="box-title ">Active Ports</h3>                
            </div><!-- /.box-header -->


            <div class="box-body pt0">               

                <table class="table table-striped mt0">
                    <tbody><tr>
                            <th class="w100">Controller</th>
                            <th>Portname</th>
                            <th>Data</th>
                            <th>update</th>
                        </tr>
                        @foreach($pt as $i=>$d)
                        <?php
                        $pclick = "editportname('" . $d->pieid . "','" . $d->portno . "','" . $d->portid . "')";
                        ?>
                        <tr>
                            <td class="font12"><a class="green" href="{{asset('pie-'.$d->pieid.'.info')}}">{{$d->piename}}</a></td>
                            <td>
                                <a class="portname_{{$d->portid}}" href="javascript:" onclick="{{$pclick}}">{{$d->portname}}</a>
                            </td>
                            <td class="">
                                <span id="port_{{$d->portno}}">
                                    <span class="port_{{$d->portno}}">
                                        {{Ports::portValue($d)}}
                                    </span>
                                </span>
                            </td>                            
                            <td><small class="portupdate_{{$d->portno}}">{{timepost_en($d->lastupdate)}}</small></td>
                        </tr>
                        @endforeach

                    </tbody></table>
            </div>
        </div><!-- /.box -->  
    </div>

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
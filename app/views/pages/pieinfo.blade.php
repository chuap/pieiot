@extends('tp/lte') 
<?php
$pie = Pies::find($p);
$pt = Ports::portByPie($p);
$pro = Projects::projectList($p);
$minfo = Pies::modelInfo($pie->piemodel);
$rlist = Reports::listReport();
?>
@if(!$pie)
@section('body')
<div class="p2">
    <div class="alert alert-info alert-dismissable ">
        <i class="fa fa-info"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <b>Alert!</b> ไม่พบอุปกรณ์
    </div>
</div>
@stop
@else
@section('page_title')
{{$pie->piename}}
@stop
@section('page_header')
<h1 id="lbpie_{{$p}}">
    @if($pie->img)<img class="h40" src="{{asset($pie->img)}}">@endif
    <span class="red mr1">#{{$pie->pieid}}</span>
    {{$pie->piename}}

</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{asset('/')}}"><i class="fa fa-dashboard"></i>Home</a></li>
    <li><a href="{{asset('/pies')}}"><i class="fa fa-pie-chart"></i>Pies</a></li>
    <li class="active">{{$pie->piename}}</li>
</ol>
@stop
@section('body')
<div class="row">
    <!-- Left col -->

    <!-- right col (We are only adding the ID to make the widgets sortable)-->
    <section class="col-lg-5 connectedSortable ui-sortable">
        <!-- Map box -->
        <div class="box box-primary">
            <div class="box-header " >
                <!-- tools box -->
                <div class="pull-right box-tools">
                    <div class="btn-group">

                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><i class="fa fa-gear"></i></button>
                        <ul class="dropdown-menu pull-right" role="menu ">
                            <li><a class="newproject" href="{{asset("pie.newnode?pieid=$p")}}"><i class="fa fa-pencil"></i> Edit</a></li>
                            <li><a href="{{asset("pro-$p-0.newproject")}}" class="newproject"><i class="fa fa-plus"></i> New Project</a></li>

                            <li class="divider"></li>
                            <li><a href="javascript:" onclick="deletepie('{{$p}}')">Delete Pie</a></li>
                        </ul>
                    </div>


                </div><!-- /. tools -->
                <i class="fa fa-cloud"></i>
                <h3 class="box-title ">{{$pie->piename}} <small>{{$pie->piemodel}}</small></h3>
            </div>
            <div class="box-body mt0 pt0 pb0">

                <p>{{$pie->desc}}</p>
            </div><!-- /.box-body-->
            <div class="box-footer">

                <h4 class="box-title "><i class="fa fa-signal  "></i> MQTT Protocol 
                    <a class="btn btn-sm btn-default" href="#">Setting</a>
                </h4>
                <div class="callout callout-warning">
                    <p>No Connection !!</p>
                </div>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header">
                <i class="fa fa-bullseye"></i>
                <h3 class="box-title"><a href="{{asset('projects')}}">Projects</a>               
                </h3>
                <div class="pull-right box-tools">

                </div>

            </div><!-- /.box-header -->
            <div class="box-body pt0">
                @if($pro)
                <table class="table table-condensed">
                    <tbody>
                        @foreach($pro as $i=>$d)
                        <?php
                        $pro_link = asset('pro-' . $d->proid . '.info');
                        ?>
                        <tr>
                            <td class="w30">{{$i+1}}</td>
                            <td class="font16">
                                <a href="{{$pro_link}}" class="">{{$d->proname}}</a>
                                <p class="pt0 font12 ">{{$d->prodesc}}</p>
                            </td>
                            <td>

                            </td>
                            <td>

                            </td>
                        </tr>
                        @endforeach
                    </tbody></table>
                @else 
                <div class="callout callout-warning">
                    <p>No Project !!</p>
                </div>
                @endif
            </div><!-- /.box-body -->
        </div>  

        <div class="box box-danger">
            <div class="box-header">
                <i class="fa fa-bar-chart-o"></i>
                <h3 class="box-title"><a href="{{asset('reports')}}">Reports</a>               
                </h3>
                <div class="pull-right box-tools">

                </div>

            </div><!-- /.box-header -->
            <div class="box-body pt0">

                
                <?php
                $t = ''; $ii=0;
                foreach ($rlist as $d) {
                    $tid = $d->tid;
                    //echo "$tid ";
                    $x = excsql("select count(*) as co from tasks where tid in ($tid) and pieid='$p' ");
                    if ($x[0]->co > 0) {
                        $ii++;
                        echo '<div><a href="'.asset("pie.report-info?rid=").$d->rid.'"><i class="' . $d->rticon . '"></i> ' . $d->rname . '</a></div>';
                    }
                }
                ?>
                @if($ii<1)
                <div class="callout callout-warning">
                    <p>No Report !!</p>
                </div>
                @endif
            </div><!-- /.box-body -->
        </div> 






    </section><!-- right col -->
    <section class="col-lg-7 "> 
        <!-- Box (with bar chart) -->
        <div class="box box-danger" id="loading-example">
            <div class="box-header" >
                <i class="ion ion-arrow-swap"></i>
                <h3 class="box-title ">Ports</h3>
                @if($minfo->portimg)
                <a href="{{asset($minfo->portimg)}}" target="" class="imcolorbox btn btn-default mt1 pull-right btn-sm mr1"><img class="w20 " src="{{asset('images/piemodel/ic.png')}}"> Ports Info</a>
                @endif
            </div><!-- /.box-header -->


            <div class="box-body pt0">               

                <table class="table table-striped mt0">
                    <tbody><tr>
                            <th class="w80">Port</th>
                            <th>Portname</th>
                            <th>Data</th>
                            <th>update</th>
                        </tr>
                        @foreach($pt as $i=>$d)
                        <?php
                        $pclick = "editportname('" . $d->pieid . "','" . $d->portno . "','" . $d->portid . "')";
                        ?>
                        <tr>
                            <td class="font12">{{$d->oname}}</td>
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
                            <td><small class="portupdate_{{$d->portno}}">{{$d->lastupdate}}</small></td>
                        </tr>
                        @endforeach

                    </tbody></table>
            </div>
        </div><!-- /.box -->  
    </section><!-- /.Left col -->
</div>

@stop

@section('foot')
<script type="text/javascript">
    $(function () {
    startMonitorData();
    });
    /*  */


    function startMonitorData()
    {
    //alert('GO');
    $.ajax({
    url: rootContext + 'monitoraction',
            type: "GET",
            datatype: "json",
            data: "ac=check_piedata&pie={{$p}}"
    }).success(function (rt) {

    var obj = jQuery.parseJSON(rt);
    //effectvalue('time', obj.DATA.length);
    if (obj.STATUS == true) {
    for (var i = 0, len = obj.DATA.length; i < len; i++) {
    effectvalue('port_' + obj.DATA[i]['portno'], obj.DATA[i]['portvalue']);
    $('.portupdate_' + obj.DATA[i]['portno']).html(obj.DATA[i]['lastupdate']);
    }


    } else {
    //alertbox(obj.MSG + '');
    }
    });
    setTimeout(function () {
    startMonitorData()
    }, 3000);
    }

    function effectvalue(itm, dt) {
    var x = $('.' + itm).html();
    if (x != dt) {
    $('.' + itm).css("background-color", "#FF3700");
    $('.' + itm).fadeOut(400, function () {
    $('.' + itm).remove();
    $('#' + itm).html('<span class="' + itm + '">' + dt + '</span>');
    });
    }
    $('a.gallery').colorbox({rel: 'gal'});
    }
</script>
<script src="{{asset('/')}}js/pieactions.js" type="text/javascript"></script>
@stop
@endif
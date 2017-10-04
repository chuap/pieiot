@extends('tp/lte') 
@section('page_header')
<h1>
    Pies
    <small>PieIoT.com</small>
    <a href="{{asset("pie.newnode")}}" class="btn btn-info newproject "><i class="fa fa-plus"></i> New Node device</a>
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
$pies = Pies::listMyPie();
?>
<div class="row">
    @foreach($pies as $d)
    <?php
    $st_bg = $d->color ? $d->color : 'yellow';
    $st_bg = 'gray';
    $st_color = '';
    ?>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div id="pie_{{$d->pieid}}" class="small-box bg-{{$st_bg}}">
            <div class="inner">
                <a href="{{asset('pie-'.$d->pieid.'.info')}}" id="pie_a_{{$d->pieid}}" class="{{$st_color}}">

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
            <a id="pie_f_{{$d->pieid}}" href="{{asset('pie-'.$d->pieid.'.info')}}" class="small-box-footer">
                @if($d->lastupdate)
<!--                <i class="fa fa-check"></i>-->
                <img class="w20 ml2" src="{{asset('images/loading2.gif')}}">
                Active: {{dateth('d n ee H:i',strtotime($d->lastupdate))}} <i class="fa fa-arrow-circle-right"></i>
                @else
                <img class="w20 ml2" src="{{asset('images/loading2.gif')}}">
                Not Active
                @endif
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
<div id="divckk"></div>
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
            data: "ac=check_pieactive&p=0"
        }).success(function (rt) {

            var obj = jQuery.parseJSON(rt);

            //$('#divckk').html('<span class="">' + obj.DATA.length + '</span>');
            if (obj.STATUS == true) {
                for (var i = 0, len = obj.DATA.length; i < len; i++) {                    
                    $('#pie_' + obj.DATA[i]['pieid']).addClass('bg-'+obj.DATA[i]['color']);
                    $('#pie_' + obj.DATA[i]['pieid']).removeClass('bg-gray');
                    $('#pie_a_' + obj.DATA[i]['pieid']).addClass('white');
                    var t='<i class="fa fa-check"></i> ';
                    t=t+'Active: '+obj.DATA[i]['lastupdate'];
                    $('#pie_f_'+ obj.DATA[i]['pieid']).html(t);
                    
                    //alert('#pie_' + obj.DATA[i]['pieid']); 
                }


            } else {
                //alertbox(obj.MSG + '');
            }
        });
        setTimeout(function () {
            startMonitorData()
        }, 10000);
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
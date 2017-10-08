@extends('tp/lte') 
@section('page_header')
<h1>
    My Projects
    <small>PieIoT.com</small>
    <a href="{{asset("pro-0-0.newproject")}}" class="btn btn-info newproject "><i class="fa fa-plus"></i> New Project</a>
</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{asset('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">My Projects</li>
</ol>
@stop
@section('body')
<?php
$plist = Projects::myProjects();
?>
<div class="row">
    @foreach($plist as $i=>$d)
    <?php
    $pro_link = asset('pro-' . $d->proid . '.info');
    $tk = Projects::getTask($d->proid);
    ?>
    <div class="col-lg-6 col-md-6 col-xs-12">
        <div class="box box-success ">
            <div class="box-header p0">
                <h3 class="box-title pb0"><a id="lbpro_{{$d->proid}}" href="{{$pro_link}}">{{$d->proname}}</a></h3>
                <div class="box-tools pull-right">
                    <div class="btn-group">
                        <button class="btn  btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button>
                        <ul class="dropdown-menu pull-right" role="menu">
                            <li><a class="" href="{{$pro_link}}" >Open project</a></li>
                            <li class="divider"></li>
                            <li><a class="newproject " href="{{asset("pro-".$d->pieid.'-'.$d->proid.".newproject")}}" >Edit</a></li>
                            <li><a href="javascript:" onclick="deleteproject('{{$d->pieid}}','{{$d->proid}}')">Delete project</a></li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-9 col-md-8 col-xs-8">
                        <p class="p0 font12 " style="line-height:18px;">{{$d->prodesc}}<span class="white">.</span></p>
                        @if($tk)
                        <div>
                            @foreach($tk as $i=>$d2)
                            <button title="{{$d2->taskname}} " class="btn p04 {{$d2->task_disable==0?'btn-info':''}} "><img class="w30" src="{{asset($d2->tmimg)}}" /></button>
                            @endforeach
                        </div>
                        @else
                        <code class="red">
                            <i class="icon-info"></i> Project is empty.
                        </code>
                        @endif
                    </div>
                    <div class="col-lg-3 col-md-4 col-xs-4 text-right">
                        <a href="{{$pro_link}}" class="btn btn-app green">
                            <i class="fa icon-mail-forward"></i> Open project 
                        </a>
                    </div>
                </div>

            </div><!-- /.box-body -->
        </div>
    </div>
    @endforeach
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
@extends('tp/lte') 
@section('page_header')
<h1>
    My Projects
    <small>PieIoT.com</small>
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
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body ">
                <a href="{{asset("pro-0-0.newproject")}}" class="btn btn-info newproject pull-right"><i class="fa fa-plus"></i> New Project</a>
                <table class="table  table-hover">
                    <thead>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Project Name</th>
                            <th></th>
                            <th style="width: 80px"></th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($plist as $i=>$d)
                        <?php
                        $pro_link = asset('pro-' . $d->proid . '.info');
                        ?>
                        <tr>
                            <td>{{$i+1}}</td>
                            <td class="font16">
                                <a href="{{$pro_link}}" class="">{{$d->proname}}</a>
                                <p class="pt0 font12 ">{{$d->prodesc}}</p>
                            </td>
                            <td>

                            </td>
                            <td>
                                <a href="{{$pro_link}}" class="btn btn-default mb0 p04"><i class="fa fa-credit-card"></i> Info</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody></table>
            </div><!-- /.box-body -->
        </div>
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
</div>

@stop
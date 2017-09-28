@extends('tp.lte') 
@section('head_meta')
<title>ฝ่ายพัฒนาทรัพยากรบุคคล</title> 
{{ HTML::style(asset('fileupload/css/jquery.fileupload.css'))}}
{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui.css'))}}
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-noscript.css'))}}</noscript>
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui-noscript.css'))}}</noscript>
{{ HTML::script(asset('ckeditor/ckeditor.js'))}}
{{ HTML::style(asset('css/autocomplete.css'))}}

@stop
<?php
$page_title = 'Users';
$p = Input::get('p');
Session::put('etype', $p);
$find = Input::get('find');
$ii = 0;

if ($find) {
    $type_name = "Search: $find";
}
$page = Input::get('page');
if ($page > 0) {
    $page = $page - 1;
}
$pcount = 40;
//$alllist = EDoc::getList($p, $pcount, $find, $page);
$alllist = Staff::findUser($find, $pcount, $page);
?>
@section('page_header')
<h1>
    Users
    <small>PieIoT.com</small>
</h1>
@stop
@section('breadcrumb')
<ol class="breadcrumb">
    <li><a href="{{asset('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Users</li>
</ol>
@stop
@section('body')

<div class="row">  
    <div class="col-lg-12">
            <h3 class="header smaller lighter mt0">


                <a class="btn btn-mini btn-info newuser  f14  " href="{{asset('admin/newuser')}}">New Users</a>
                @if($find)
                <a class="btn btn-mini btn-default f12 grey ml2" href="{{asset('admin/users')}}">All User</a>
                @endif
                <form class=" pull-right" action="{{asset('admin/users')}}" method="get" >                    
                    <div class="input-group w160">
                        <input name="find" id="find" type="text" class="form-control nav-search-input" placeholder="Search" >
                        <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    </div>
                </form>

                <div class="pull-right f16 mr2">
                    @if($page>0)
                    <a class="btn btn-mini" href="{{asset("admin/users?p=$p&find=$find&page=".($page))}}"><i class="icon-chevron-left"></i> ก่อนหน้า</a>
                    @endif
                    @if(count($alllist)>=$pcount)
                    <a class="btn btn-mini" href="{{asset("admin/users?p=$p&find=$find&page=".($page+2))}}">หน้าถัดไป <i class="icon-chevron-right"></i></a>
                    @endif
                </div>
            </h3>

            <table id="sample-table-2" class="table table-striped table-bordered table-hover datatablesX">
                <thead>
                    <tr>
                        <th class="w50"></th>
                        <th>ชื่อ</th>     
                        <th class="hidden-phone font11">Address</th>                        
                        <th class="w120">Tel</th>
                        <th class="w80 hidden-phone"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alllist as $d)
<?php
$ii++;
$name1 = $d->fname . ' ' . $d->lname;
if ($find) {
    $name1 = str_replace($find, "<span class='red textuline'>$find</span>", $name1);
}
if ($d->uclass == 'admin') {
    $name1 = '<span class="blue">' . $name1 . '</span>';
}
$ed = ' <a class=" green newuser" href="' . asset('admin/newuser?p=' . $d->uid) . '" title="" onclick=""> แก้ไข</a> ';
$t = ' <a class="ml1 red" href="javascript:" title="ลบ" onclick="deluser(\'' . $d->uid . '\');"> ลบ</a> ';

//$profile = HRDProfile::gen_profile($d->catid);
?>
                    <tr id="delid{{$d->uid}}">
                        <td>{{$d->uname}}</td>
                        <td>{{$name1}}</td>
                        <td class="font11">{{$d->address}}</td>
                        <td class="font11">{{$d->tel}}</td>
                        <td>{{$ed.$t}}</td>
                    </tr> 
                    @endforeach
                </tbody>
            </table>
            @if(!$ii)
            <div class="alert alert-danger">
                ไม่พบข้อมูล !!! 
            </div>
            @endif



    </div>
    <div class="clearfix">
        <div class="pull-right f16">
            @if($page>0)
            <a class="btn btn-mini" href="{{asset("admin/users?p=$p&find=$find&page=".($page))}}"><i class="icon-chevron-left"></i> ก่อนหน้า</a>
            @endif
            @if(count($alllist)>=$pcount)
            <a class="btn btn-mini" href="{{asset("admin/users?p=$p&find=$find&page=".($page+2))}}">หน้าถัดไป <i class="icon-chevron-right"></i></a>
            @endif
        </div>
    </div>
</div>
</div>
@stop
@section('foot')
{{ HTML::script(asset('/').'js/action.js')}}
@stop
@section('foot_meta')
<script src="{{asset('/')}}js/jquery.dataTables.min.js"></script>
<script src="{{asset('/')}}js/jquery.dataTables.bootstrap.js"></script>


{{ HTML::script(asset('js/newpost.js'))}}
<script type="text/javascript">
$(".newuser").colorbox({iframe: true, width: "1030", height: "99%", overlayClose: true, onComplete: function () {
        resizeColorBox();
    }});
$(".doc_status").colorbox({iframe: true, width: "800px", height: "90%", overlayClose: true, onComplete: function () {
        resizeColorBox;
    }});
function deluser(c) {
    //var rootContext = document.body.getAttribute("data-root");
    bootbox.confirm({
        title: "ยืนยันลบข้อมูล?",
        message: "User : " + c,
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function (result) {
            if (result == true) {
                $.ajax({
                    url: rootContext + 'adminaction',
                    type: "POST",
                    datatype: "json",
                    data: "at=deluser&cid=" + c
                }).success(function (result) {
                    var obj = jQuery.parseJSON(result);
                    if (obj.STATUS == true) {
                        removetag('del_' + c);
                    } else {
                        alertbox(obj.MSG + '');
                    }
                });
            }
        }
    });
    
}
</script>


@stop
@section('page_title'){{$page_title}} @stop


@extends('admin.ace') 
@section('head_meta')
<title>ฝ่ายพัฒนาทรัพยากรบุคคล</title> 
{{ HTML::style(asset('fileupload/css/jquery.fileupload.css'))}}
{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui.css'))}}
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-noscript.css'))}}</noscript>
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui-noscript.css'))}}</noscript>
{{ HTML::script(asset('ckeditor/ckeditor.js'))}}
{{ HTML::style(asset('css/autocomplete.css'))}}
<style>
    .link-edit{  color: #ccccff;   }
    .link-edit:hover{  color:#0033cc;   }
    .td-update{
        background-color: #ffff66!important;
    }
</style>

@stop
<?php
$p = Input::get('p')?mysql_escape_mimic(Input::get('p')) : '';

$page_title = 'รายการรถ';
$uinfo=null;
if($p){
    $uinfo= HRDProfile::find($p);
}
$ii = 0;
?>
@section('breadcrumbs')
<li class="active">รายการรถยนต์</li>
@stop
@section('body')
@if(1)
<div class="row-fluid">
    <!-- main col left --> 
    <div class="span12">
        <div class="row-fluid">
            <h3 class="header smaller lighter"> <span class="red">{{$page_title}}</span>
                <small>
                    <i class="icon-double-angle-right"></i>
                    ร้าน
                </small>
                <div class="btn-group">
                    <button data-toggle="dropdown" class="btn dropdown-toggle">
                        {{$uinfo?$uinfo->nickname:'<เลือกร้าน>'}}
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-default">  
                        <li><a href="{{asset("admin/car_list")}}">รถจากทุกร้าน</a></li>
                        @foreach(Car::user_list()as $d)<li><a href="{{asset("admin/car_list?p=".$d->uid)}}">{{$d->fname.' '.$d->lname}}</a></li>@endforeach
                    </ul>
                </div>
               
                              
                <div class="btn-group pull-right">
                    <a class="btn btn-info newclassroomX pull-right" href="{{asset('admin/new')}}"><i class="icon-plus"></i>เพิ่มรายการใหม่</a>
                </div>

            </h3>   
            <div>
                <table class="table table-hover table-striped datatb25">
                    <thead>
                        <tr>
                            <th class="w30">#</th>
                            <th class="w30"></th>
                            <th>รุ่น</th>
                            <th>ราคา</th>
                            <th class="w120">ประเภท</th>
                            <th class="w50"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $q=Car::car_list();
                        ?>
                        @foreach($q as $i=>$d)
                        <tr id="delid{{$d->cid}}">
                            <td>{{$i+1}}</td>
                            <td class="p04"><img class="w30" src="{{asset('images/logo/'.$d->logo)}}"></td>
                            <td class="font14"><a href="{{asset('admin/new?p='.$d->cid)}}">{{Car::car_name($d)}}</td>
                            <td>{{number_format($d->price)}}</td>
                            <td>{{$d->car_type}}</td>
                            <td>
                                <a title="" class="btn btn-mini" href="{{asset("admin/new?p=".$d->cid)}}" tabindex="-1"><i class="icon-pencil purple"></i></a>
                                <a class="red ml1" href="javascript:" onclick="del('admin','car', '{{$d->cid}}');">
                                    <i class="icon-trash bigger-120"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>

                    </tfoot>
                </table>
               
            </div>
            @if(0)
            <div class="alert alert-danger">
                ไม่พบข้อมูล !!! 
            </div>
            @endif
        </div>
    </div>
</div>
@else
@include('portal.no')
@endif
</div>

@stop
@section('foot')
{{ HTML::script(asset('/').'js/action.js')}}
@stop
@section('foot_meta')
<script src="{{asset('/')}}js/jquery.dataTables.min.js"></script>
<script src="{{asset('/')}}js/jquery.dataTables.bootstrap.js"></script>





{{ HTML::script(asset('js/caraction.js'))}}
<script type="text/javascript">
$(".link-editX").colorbox({iframe: true, width: "900", height: "99%", overlayClose: false, onComplete: function () {
        resizeColorBox();
    }});
$(".newclassroom").colorbox({iframe: true, width: "1030", height: "99%", overlayClose: true, onComplete: function () {
        resizeColorBox();
    }});
$(document).ready(function () {


});

jQuery.fn.dataTableExt.oSort['num-html-asc'] = function (a, b) {
    var x = a.replace(/<.*?>/g, "");
    var y = b.replace(/<.*?>/g, "");
    x = parseFloat(x);
    y = parseFloat(y);
    return ((x < y) ? -1 : ((x > y) ? 1 : 0));
};

jQuery.fn.dataTableExt.oSort['num-html-desc'] = function (a, b) {
    var x = a.replace(/<.*?>/g, "");
    var y = b.replace(/<.*?>/g, "");
    x = parseFloat(x);
    y = parseFloat(y);
    return ((x < y) ? 1 : ((x > y) ? -1 : 0));
};
$(document).ready(function () {
    var oTable = $('.datatb25').dataTable({
        "iDisplayLength": 100,
        "oLanguage": {
            "sLengthMenu": "แสดง _MENU_ ต่อหน้า",
            "sZeroRecords": "ไม่เจอข้อมูลที่ค้นหา",
            "sInfo": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
            "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 รายการ",
            "sInfoFiltered": "(จากทั้งหมด _MAX_ รายการ)",
            "sSearch": "ค้นหา :"

        }
    });
    oTable.fnSort([[0, 'asc']]);
});


</script>


@stop
@section('page_title')Admin Tools @stop


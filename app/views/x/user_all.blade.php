@extends('edoc.ace') 
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
$page_title = 'ผู้ใช้งานระบบ';
$p = Input::get('p');
Session::put('etype', $p);
$find = Input::get('find');
$ii = 0;
if ($p == 'me') {
    $type_name = 'My Document';
    $css = '';
} else if ($p == 'tag-me') {
    $type_name = 'Tag Me: เอกสารที่ระบุชื่อของคุณ';
    $css = '';
} else if ($p) {
    $obj = EDocType::find($p);
    $type_name = $obj->type_name;
    $css = $obj->css;
} else {
    $type_name = '';
    $css = '';
}
if ($find) {
    $type_name = "Search: $find";
}
$page = Input::get('page');
if ($page > 0) {
    $page = $page - 1;
}
$pcount = 40;
//$alllist = EDoc::getList($p, $pcount, $find, $page);
$alllist = EDoc::findUser($find, $pcount, $page);
?>
@section('breadcrumbs')
<li class="active">{{$type_name}}</li>
@stop
@section('body')
<div class="row-fluid">
    <!-- main col left --> 
    <div class="span12">
        <div class="row-fluid">
            <h3 class="header smaller lighter blue">{{$page_title}}
                
                    <span class="label label-large arrowed-right label-{{$css}}">{{$type_name}}</span>
                    <a class="btn btn-mini btn-info newuser  f14 grey ml2" href="{{asset('edoc/newuser')}}">เพิ่มผู้ใช้งาน</a>

                <form class=" pull-right" action="{{asset('edoc/user_all')}}" method="get" >
                    <span class="input-icon">
                        <input type="text" placeholder="ค้นหาผู้ใช้งาน" class="input-small nav-search-input" name="find" id="find" autocomplete="off" />
                        <i class="icon-search nav-search-icon"></i>
                    </span>
                </form>

                <div class="pull-right f16 mr2">
                    @if($page>0)
                    <a class="btn btn-mini" href="{{asset("edoc/user_all?p=$p&find=$find&page=".($page))}}"><i class="icon-chevron-left"></i> ก่อนหน้า</a>
                    @endif
                    @if(count($alllist)>=$pcount)
                    <a class="btn btn-mini" href="{{asset("edoc/user_all?p=$p&find=$find&page=".($page+2))}}">หน้าถัดไป <i class="icon-chevron-right"></i></a>
                    @endif
                </div>
            </h3>
            <table id="sample-table-2" class="table table-striped table-bordered table-hover datatablesX">
                <thead>
                    <tr>
                        <th class="w50"></th>
                        <th>ชื่อพนักงาน</th>     
                        <th class="w50 font11">ตำแหน่ง</th>  
                        <th class="hidden-phone font11">ส่วนงาน</th>                        
                        <th class=" w50">ฝ่าย</th>
                        <th class="w80 hidden-phone"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alllist as $d)
                    <?php
                    $ii++;
                    $name1 = $d->BNAME . ' ' . $d->SURNAME;
                    if ($find) {
                        $name1 = str_replace($find, "<span class='red textuline'>$find</span>", $name1, $var);
                    }
                    if($d->uclass=='admin'){
                        $name1='<span class="blue">'.$name1.'</span>';
                    }
                    $ed = ' <a class=" green newuser" href="'.asset('edoc/newuser?cid='.$d->ID8).'" title="" onclick=""> แก้ไข</a> ';
                    $t = ' <a class="ml1 red" href="javascript:" title="ลบ" onclick="deluser(\'' . $d->ID8 . '\');"> ลบ</a> ';

                    //$profile = HRDProfile::gen_profile($d->catid);
                    ?>
                    <tr id="del_{{$d->ID8}}">
                        <td>{{$d->ID8}}</td>
                        <td>{{$name1}}</td>
                        <td>{{$d->POSITION}}</td>
                        <td class="font11">{{$d->SECTION_N}}</td>
                        <td>{{$d->DEPARTMENT_S}}</td>
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



    </div>
    <div class="clearfix">
        <div class="pull-right f16">
            @if($page>0)
            <a class="btn btn-mini" href="{{asset("edoc/user_all?p=$p&find=$find&page=".($page))}}"><i class="icon-chevron-left"></i> ก่อนหน้า</a>
            @endif
            @if(count($alllist)>=$pcount)
            <a class="btn btn-mini" href="{{asset("edoc/user_all?p=$p&find=$find&page=".($page+2))}}">หน้าถัดไป <i class="icon-chevron-right"></i></a>
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
  $.msgBox({
    title: "Are You Sure",
    content: "ยืนยันการลบข้อมูล ?",
    type: "confirm",
    buttons: [{value: "Yes"}, {value: "No"}],
    success: function (result) {
      if (result == "Yes") {
        $.ajax({
          url: '{{asset("edoc/action")}}',
          type: "GET",
          datatype: "json",
          data: "at=deluser&cid=" + c
        }).success(function (result) {
          var obj = jQuery.parseJSON(result);
          if (obj.STATUS == true) {
            removetag('del_' + c);
          } else {
            $.msgBox({title: "CAT-eDoc System", content: obj.ERROR_MSG, type: "error"});
          }
        });
      }
    }
  });
}
</script>


@stop
@section('page_title'){{$page_title}} @stop


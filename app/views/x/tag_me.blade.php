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
$page_title = 'รายการเอกสาร';
$p = Input::get('p');
$find = Input::get('find');
$ii = 0;
if ($p == 'me') {
    $type_name = 'My Document';
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
$m = Input::get('m');
$y = Input::get('y');
if (!$y) {
    //$y = date("Y");
    $y = '';
}
if (!$m) {
    //$m = date("m");
    $m = '';
}
$page = Input::get('page');
if ($page > 0) {
    $page = $page - 1;
}
$pcount = 40;
//$alllist = EDoc::getList('tag-me', $pcount, $find, $page);
$alllist = EDoc::tag_me($y . '-' . $m,$pcount,$page);
?>
@section('breadcrumbs')
<li class="active">tag-me</li>
@stop
@section('body')
<div class="row-fluid">
    <!-- main col left --> 
    <div class="span12">
        <div class="row-fluid">
            <h3 class="header smaller lighter blue">Tag Me <small>: เอกสารที่มีการ Tag ชื่อคุณ</small>
                <span class="label label-large arrowed-right label-{{$css}}">{{$type_name}}</span>
                <div class="pull-right f16">
                    @if($page>0)
                    <a class="btn btn-mini" href="{{asset("edoc/tag_me?y=$y&m=$m&page=".($page))}}"><i class="icon-chevron-left"></i> ก่อนหน้า</a>
                    @endif
                    @if(count($alllist)>=$pcount)
                    <a class="btn btn-mini" href="{{asset("edoc/tag_me?y=$y&m=$m&page=".($page+2))}}">หน้าถัดไป <i class="icon-chevron-right"></i></a>
                    @endif
                </div>
            </h3>
            <div class="btn-group">

                <ul class="dropdown-menu dropdown-default">
                    <li><a class="label label-success" href="{{asset('edoc/tag_me')}}">ข้อมูลทั้งหมด</a></li>
                    <?php
                    if ($y) {
                        $x = ((date("Y") - $y ) * 12) + ( date("m") - $m);
                    } else {
                        $x = 0;
                    }

                    $nts = strtotime(date('Y') . '-' . date('m' . '-01'));
                    if ($x < 10) {
                        $x = 12;
                    } else {
                        $x+=3;
                    }
                    for ($i = 0; $i < $x; $i++) {
                        $ts = strtotime("-" . $i . " month", $nts);
                        $t = dateth("nn eeee", $ts);
                        $t2 = "y=" . date("Y", $ts) . "&m=" . date("m", $ts);
                        if ((date("m", $ts) == $m) && (date("Y", $ts) == $y))
                            $c = "class='active'";
                        else
                            $c = "";
                        echo "<li $c><a href='?$t2'>$t</a></li>";
                    }
                    ?> 
                </ul>
                <button data-toggle="dropdown" class="btn dropdown-toggle">
                    <?php
                    if ($y) {
                        echo dateth("nn eeee", strtotime($y . "-" . $m . "-01"));
                    } else {
                        echo 'ข้อมูลทุกเดือน';
                    }
                    ?>
                    <span class="caret"></span>
                </button>
            </div>
            <table id="sample-table-2" class="table table-striped table-bordered table-hover dataTable">
                <thead>
                    <tr>
                        <th class="w50"></th>
                        <th>Title</th>
                        <th class="hidden-phone">Status</th>
                        <th class="hidden-phone hidden-tablet" style="width: 110px;">View / Msg / Like</th>
                        <th class=" w30">By</th>
                        <th class="hidden-phone hidden-tablet w40"><i class="icon-time bigger-110 hidden-480"></i></th>
                        <th class="w40 hidden-phone"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alllist as $d)
                    <?php
                    $ii++;
                    $profile = HRDProfile::gen_profile($d->catid);
                    $msg = excsql("select count(id)as co from edoc_msg where doc_id='" . $d->id . "'");
                    $msg_co = $msg[0]->co;
                    if ($d->rcount > 0) {
                        $css1 = "grey";
                    } else {
                        $css1 = "";
                    }
                    if ($find) {
                        $tlb = str_replace($find, "<span class='red textuline'>$find</span>", $d->etitle, $var);
                    } else {
                        $tlb = $d->etitle;
                    }
                    ?>
                    <tr id="delidedoc{{$d->id}}">
                        <td class="pl04 pr0" ><i title="{{$d->type_name}}" class="f14 {{$d->icon}} {{$d->color}}"></i><span class="muted"> #{{$d->eno}}</span></td>
                        <td>
                            @if($d->assign_id)
                            <span class=""><i class="icon-cloud-download red"></i></span>
                            @endif                                                      
  
                            <a class="f14 {{$css1}}" href="{{asset('edoc/view?p='.$d->id)}}">{{$tlb}}</a> {{Edoc::label_pri($d->epri)}} {{Edoc::label_secret($d->esecret)}}  
                    
                            @if($d->fcount)
                            <i class="icon-paper-clip"></i>
                            @endif
                        </td>
                        <td class="hidden-phone"><a class="doc_status green" href="{{asset("edoc/doc_status?t=5&p=".$d->id)}}">{{EDoc::last_status($d->id)}}</a></td>
                        <td class="hidden-phone hidden-tablet pr0">
                            <div class="black w30 pull-left">{{$d->read_count?:'-'}}</div>
                            <div class="green w40 pull-left"><i class="icon-comments"></i> {{$msg_co?:'-'}}</div>
                            <div class="pink w40 pull-left"><i class="icon-thumbs-up-alt"></i> {{$d->like_count?:'-'}}</div>
                        </td>  
                        <td class="p04"><img class="w20 userimg" title="{{$profile->nickname}}" src="{{asset($profile->avatar)}}" /></td>
                        <td class="hidden-phone hidden-tablet font12"><a href="#" title="{{$d->date_create}}">{{timepost_shot($d->date_create)}}</a></td>
                        <td class="td-actions hidden-phone pr0">
                            <div class=" action-buttons">
                                @if(EDoc::can_edit($d))
                                <a class="green" href="{{asset('edoc/new?p='.$d->id)}}">
                                    <i class="icon-pencil bigger-120"></i>
                                </a>
                                <a class="red" href="javascript:" onclick="edoc_del('edoc','{{$d->id}}', 'edoc');">
                                    <i class="icon-trash bigger-120"></i>
                                </a>
                                @endif                                
                            </div>
                        </td>
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
            <a class="btn btn-mini" href="{{asset("edoc/tag_me?y=$y&m=$m&page=".($page))}}"><i class="icon-chevron-left"></i> ก่อนหน้า</a>
            @endif
            @if(count($alllist)>=$pcount)
            <a class="btn btn-mini" href="{{asset("edoc/tag_me?y=$y&m=$m&page=".($page+2))}}">หน้าถัดไป <i class="icon-chevron-right"></i></a>
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
                                                            $(".doc_status").colorbox({iframe: true, width: "800px", height: "90%", overlayClose: true, onComplete : function() {   resizeColorBox; } });

</script>


@stop
@section('page_title'){{$page_title}} @stop


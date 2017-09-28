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
Session::put('etype',$p);
$find = Input::get('find');
$ii = 0;
if ($p == 'me') {
    $type_name = 'My Document';
    $css = '';
}else if ($p == 'tag-me') {
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
$alllist = EDoc::getList($p, $pcount, $find, $page);

?>
@section('breadcrumbs')
<li class="active">{{$type_name}}</li>
@stop
@section('body')
<div class="row-fluid">
    <!-- main col left --> 
    <div class="span12">
        <div class="row-fluid">
            <h3 class="header smaller lighter blue">Documents
                <span class="label label-large arrowed-right label-{{$css}}">{{$type_name}}</span>
                @if(($p=='t2')&& Edoc::bossindep())
                <a class="btn btn-mini btn-warning" href="{{asset('edoc/assign_to?fl=t2')}}">ติดตามการมอบหมายงาน</a>
                @elseif(($p=='me')&& Edoc::bossindep())
                <a class="btn btn-mini btn-warning" href="{{asset('edoc/assign_to')}}">ติดตามการมอบหมายงาน</a>
                @endif
                
                <div class="pull-right f16">
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
                        <th>ชื่อเรื่อง</th>
                        <th class="hidden-phone">สถานะ</th>
                        @if($p=='t1')
                        <th class="hidden-phone hidden-tablet" style="">เรียน</th>
                        <th class="hidden-phone hidden-tablet w60" style="">ลงวันที่</th>  
                        @elseif($p=='t2')
                        <th class="hidden-phone hidden-tablet" style="">ต้นเรื่อง</th> 
                        <th class="hidden-phone hidden-tablet w60" style="">ลงวันที่</th>  
                        @else
                        <th class="hidden-phone hidden-tablet" style="width: 110px;">View / Msg / Like</th>
                        @endif
                        <th class=" w30">โดย</th>
                        <th class="hidden-phone hidden-tablet w40"><i class="icon-time bigger-110 hidden-480"></i></th>
                        <th class="w40 hidden-phone"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alllist as $d)
                    <?php
                    $ii++;
                    $cv=EDoc::can_view($d);
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
                            @if(!$cv)
                            {{$tlb}}{{Edoc::label_pri($d->epri)}} {{Edoc::label_secret($d->esecret)}}  
                            <i class="icon-lock"></i>
                            @else
                            <a class="f14 {{$css1}}" href="{{asset('edoc/view?p='.$d->id)}}">{{$tlb}}</a> {{Edoc::label_pri($d->epri)}} {{Edoc::label_secret($d->esecret)}}  
                            @endif
                            @if($d->fcount)
                            <i class="icon-paper-clip"></i>
                            @endif
                        </td>
                        <td class="hidden-phone"><a class="doc_status green" href="{{asset("edoc/doc_status?t=5&p=".$d->id)}}">{{EDoc::last_status($d->id)}}</a></td>
                        @if($p=='t1')
                        <td class="hidden-phone hidden-tablet">{{$d->eto}}</td>
                        <td class="hidden-phone hidden-tablet font12">{{dateth('d n ee',strtotime($d->doc_date))}}</td>
                        @elseif($p=='t2')
                        <td class="hidden-phone hidden-tablet">{{$d->efrom}}</td>
                        <td class="hidden-phone hidden-tablet font12">{{dateth('d n ee',strtotime($d->doc_date))}}</td>
                        @else
                        <td class="hidden-phone hidden-tablet pr0">
                            <div class="black w30 pull-left">{{$d->read_count?:'-'}}</div>
                            <div class="green w40 pull-left"><i class="icon-comments"></i> {{$msg_co?:'-'}}</div>
                            <div class="pink w40 pull-left"><i class="icon-thumbs-up-alt"></i> {{$d->like_count?:'-'}}</div>
                        </td>  
                        @endif
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
                                            $(".doc_status").colorbox({iframe: true, width: "800px", height: "90%", overlayClose: true, onComplete : function() {   resizeColorBox; } });

</script>


@stop
@section('page_title'){{$page_title}} @stop


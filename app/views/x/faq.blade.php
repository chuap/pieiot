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
Session::put('etype', 'faq');
$page_title = 'รายการเอกสาร';
$p = Input::get('p');
$find = Input::get('find');
$faqtype = Input::get('faqtype');
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
$pcount = 100;
$alllist = EDoc::faq($faqtype,$y . '-' . $m, $pcount, $page);
?>
@section('breadcrumbs')
<li class="active">FAQ</li>
@stop
@section('body')
<div class="row-fluid">
    <!-- main col left --> 
    <div class="span12">
        <div class="row-fluid">
            <h3 class="header smaller lighter blue">FAQ <small></small>
                <span class="label label-large arrowed-right label-{{$css}}">{{$type_name}}</span>
                <div class="pull-right f16">
                    @if($page>0)
                    <a class="btn btn-mini" href="{{asset("edoc/faq?y=$y&m=$m&page=".($page))}}"><i class="icon-chevron-left"></i> ก่อนหน้า</a>
                    @endif
                    @if(count($alllist)>=$pcount)
                    <a class="btn btn-mini" href="{{asset("edoc/faq?y=$y&m=$m&page=".($page+2))}}">หน้าถัดไป <i class="icon-chevron-right"></i></a>
                    @endif
                </div>
                <div class="btn-group">
                    <ul class="dropdown-menu dropdown-default w300">
                        <li class="{{$faqtype?'':'active'}}">
                            <a class="" href="{{asset("edoc/faq")}}">ข้อมูลทั้งหมด</a>
                        </li>
                        @foreach(EDoc::faqtype()as $d)
                        <li class="{{$faqtype==$d->efrom?'active':''}}">
                            <a class="" href="{{asset('edoc/faq?faqtype='.$d->efrom)}}">{{$d->efrom?$d->efrom:'<ไม่ระบุ>'}}<span class="badge badge- pull-right">{{$d->co}}</span></a>
                        </li>
                        @endforeach
                    </ul>                    
                    <button data-toggle="dropdown" class="btn btn- dropdown-toggle">
                        <i class="icon-star"></i> 
                        {{$faqtype?$faqtype:'เลือกกลุ่ม FAQ'}}
                        <span class="caret"></span>
                    </button>
                </div>
                <small><a href="{{asset('edoc/new?etype=faq')}}" class="green"><i class="icon-pencil"></i> สร้าง FAQ ใหม่</a></small>
            </h3>

            <table id="sample-table-2" class="table table-striped table-bordered table-hover datatables">
                <thead>
                    <tr>
                        <th>FAQs</th>
                        <th class=" w40">โดย</th>
                        <th class="hidden-phone hidden-tablet w50"><i class="icon-time bigger-110 hidden-480"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alllist as $d)
                    <?php
                    //exit();
                    $ii++;
                    $cv = EDoc::can_view($d);
                    $profile = HRDProfile::gen_profile($d->catid);
                    $msg = excsql("select count(id)as co from edoc_msg where doc_id='" . $d->id . "'");
                    $msg_co = $msg[0]->co;
                    $hidden_file = $d->hidden_file;
                    $dtl = str_replace('src="files', 'src="' . asset('') . 'files', $d->dtl);
                    $dtl = str_replace('@root/', asset('/'), $dtl);
                    $dtl = str_replace('src="ckeditor', 'src="' . asset('') . 'ckeditor', $dtl);
                    $css1 = "";
                    if ($find) {
                        $tlb = str_replace($find, "<span class='red textuline'>$find</span>", $d->etitle, $var);
                    } else {
                        $tlb = $d->etitle;
                    }
                    ?>
                    <tr id="delidedoc{{$d->id}}">
                        <td>
                            <div class="mb1 alert alert-info f14 pr1">
                                <i class="icon-question red f16"></i> <a class="{{$css1}}" href="{{asset('edoc/view?p='.$d->id)}}">{{$tlb}}</a>  
                                @if($d->efrom)
                                <small class="grey pull-right">
                                    <i class="icon-star"></i> <a class="green" href="{{asset('edoc/faq?faqtype='.$d->efrom)}}">{{$d->efrom}}</a>
                                    </small>
                                @endif
                            </div>
                            <div class="row-fluid pt1 pl1" style="">                                
                                <div class="black pl1 " style="border-left: 4px solid #cccccc;"><span class="font13 dark">{{$dtl}}</span>
                                    @if(!$hidden_file)
                                    <div class="">  
                                        <?php
                                        $f_img = FilesBook::getimg($d->id);
                                        $img_co = count($f_img);
                                        if ($img_co == 1) {
                                            $sty1 = '';
                                        } else if ($img_co == 2) {
                                            $sty1 = 'height:224px;';
                                        } else if ($img_co == 3) {
                                            $sty1 = 'height:162px;';
                                        } else {
                                            $sty1 = 'height:108px;';
                                        }
                                        $sty1 = 'height:108px;';
                                        ?>
                                        <ul class="ace-thumbnails clearfix">
                                            @foreach ($f_img as $f)
                                            <li style="" >
                                                <a href="{{asset($f->url)}}" title="" data-rel="colorbox">
                                                    <img alt="150x150" style="{{$sty1}}" src="{{asset($f->url)}}" />
                                                    <div class="tags">
                                                        <span class="label label-info">{{$f->file_name}}</span>
                                                    </div>
                                                </a>
                                            </li>  
                                            @endforeach
                                            @foreach (FilesBook::getfile($d->id) as $f)
                                            @if(strtolower( pathinfo($f->file_name, PATHINFO_EXTENSION))=='mp3')
                                            <div class="clearfix">
                                                <audio class="pull-left" controls="" src="{{asset($f->url)}}" type="audio/mp3"></audio>
                                                <div class="pull-left red p1">{{$f->file_name}}</div>
                                            </div>
                                            @else
                                            <li class="p0 m0" style="border: none;">                            
                                                <div class="alert alert-info  m04 p04">
                                                    @if($f->topdf)
                                                    <a title="{{$f->topdf}}" href="{{asset('files/'.$f->uid.'/topdf/'.$f->file_name.'.pdf')}}">
                                                        <img alt="150x150" class="w40" src="{{FilesBook::geticon($f->topdf)}}" />                                        
                                                    </a>
                                                    <a title="{{$f->file_name}}" href="{{asset($f->url)}}">
                                                        <img alt="150x150" class="w40" src="{{FilesBook::geticon($f->file_name)}}" />
                                                        <span class="f12">{{$f->file_name}}</span>
                                                    </a>
                                                    @else
                                                    <a title="{{$f->file_name}}" href="{{asset($f->url)}}">
                                                        <img alt="150x150" class="w40" src="{{FilesBook::geticon($f->file_name)}}" />
                                                        <span class="f12">{{$f->file_name}}</span>
                                                    </a>
                                                    @endif
                                                </div>
                                            </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    
                                </div>

                            </div>
                        </td>
                        <td class=""><img class="w20" title="{{$profile->nickname}}" src="{{asset($profile->avatar)}}" />
                            @if(EDoc::can_edit($d))
                            <div class=" action-buttons mt2">                                
                                <a class="green" href="{{asset('edoc/new?p='.$d->id)}}">
                                    <i class="icon-pencil bigger-120"></i>
                                </a>
                                <a class="red" href="javascript:" onclick="edoc_del('edoc','{{$d->id}}', 'edoc');">
                                    <i class="icon-trash bigger-120"></i>
                                </a>                                                              
                            </div>
                            @endif  
                        </td>
                        <td class="hidden-phone hidden-tablet font12"><a href="#" title="{{$d->date_create}}">{{timepost_shot($d->date_create)}}</a></td>

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
            <a class="btn btn-mini" href="{{asset("edoc/faq?y=$y&m=$m&page=".($page))}}"><i class="icon-chevron-left"></i> ก่อนหน้า</a>
            @endif
            @if(count($alllist)>=$pcount)
            <a class="btn btn-mini" href="{{asset("edoc/faq?y=$y&m=$m&page=".($page+2))}}">หน้าถัดไป <i class="icon-chevron-right"></i></a>
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
                                            $(function () {
                                            $('.datatables').dataTable({
                                            "iDisplayLength": 100,
                                                    "oLanguage": {
                                                    "sLengthMenu": "แสดง _MENU_ ต่อหน้า",
                                                            "sZeroRecords": "ไม่เจอข้อมูลที่ค้นหา",
                                                            "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                                                            "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 รายการ",
                                                            "sInfoFiltered": "(จากทั้งหมด _MAX_ รายการ)",
                                                            "sSearch": "ค้นหา :"
                                                    }
                                            });
                                            });
                                            $(".doc_status").colorbox({iframe: true, width: "800px", height: "90%", overlayClose: true, onComplete: function () {
                                    resizeColorBox;
                                    }});

</script>


@stop
@section('page_title'){{$page_title}} @stop


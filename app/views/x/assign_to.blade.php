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
$fl= Input::get('fl');
if(!$fl){
   $fl='me' ;
}

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
    $y='';
}
if (!$m) {
    //$m = date("m");
    $m='';
}

$pid = Input::get('pid');
if ($pid) {
    $hr = Hrcat::find($pid);
} else {
    $hr = '';
}


$page = Input::get('page');
if ($page > 0) {
    $page = $page - 1;
}
$pcount = 40;
//$alllist = EDoc::getList($p, $pcount,  $page);
$alllist=EDoc::assign_to($pid,$y.'-'.$m,$fl,$pcount,  $page);
?>
@section('breadcrumbs')
<li class="active">assign-to</li>
@stop
@section('body')
<div class="row-fluid">
    <!-- main col left --> 
    <div class="span12">
        <div class="row-fluid">
            <h3 class="header smaller lighter blue">Assign To <small>: มอบหมายงาน</small>
                <span class="label label-large arrowed-right label-{{$css}}">{{$type_name}}</span>
                <div class="pull-right f16">
                    @if($page>0)
                    <a class="btn btn-mini" href="{{asset("edoc/assign_to?pid=$pid&fl=$fl&y=$y&m=$m&page=".($page))}}"><i class="icon-chevron-left"></i> ก่อนหน้า</a>
                    @endif
                    @if(count($alllist)>=$pcount)
                    <a class="btn btn-mini" href="{{asset("edoc/assign_to?pid=$pid&fl=$fl&y=$y&m=$m&page=".($page+2))}}">หน้าถัดไป <i class="icon-chevron-right"></i></a>
                    @endif
                </div>
            </h3>
            <div class="btn-group">
                <ul class="dropdown-menu dropdown-default w200">
                    <li class="{{$pid?'':'active'}}">
                        <a class="" href="{{asset("edoc/assign_to?y=$y&m=$m&fl=$fl")}}">พนักงานทั้งหมด</a>
                    </li>
                    @foreach(EDoc::assign_person()as $d)
                    <li class="{{$pid==$d->catid?'active':''}}">
                        <a class="" href="{{asset('edoc/assign_to?pid='.$d->catid."&y=$y&m=$m&fl=$fl")}}">{{$d->BNAME.' '.$d->SURNAME}}<span class="badge badge- pull-right">{{$d->POSITION_S}}</span></a>
                    </li>
                    @endforeach
                </ul>                    
                <button data-toggle="dropdown" class="btn btn- dropdown-toggle">
                    <i class="icon-user"></i> 
                    @if($pid)
                    {{$hr->BNAME.' '.$hr->SURNAME}} ({{$hr->POSITION_S}})
                    @endif
                    <span class="caret"></span>
                </button>
            </div>
            <div class="btn-group">
                
                <ul class="dropdown-menu dropdown-default">
                    <li><a class="label label-success" href="{{asset('edoc/assign_to?fl=$fl&pid='.$pid)}}">ข้อมูลทั้งหมด</a></li>
                    <?php
                    if($y){
                      $x = ((date("Y") - $y ) * 12) + ( date("m") - $m);  
                    }else{
                      $x = ((date("Y") - date("Y") ) * 12) + ( date("m") - date("m"));  
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
                        echo "<li $c><a href='?pid=$pid&$t2&fl=$fl'>$t</a></li>";
                    }
                    ?> 
                </ul>
                <button data-toggle="dropdown" class="btn dropdown-toggle">
                    <?php 
                    if($y){
                        echo dateth("nn eeee", strtotime($y . "-" . $m . "-01")); 
                    }else{
                        echo 'ข้อมูลทุกเดือน'; 
                    }
                    ?>
                    <span class="caret"></span>
                </button>
            </div>
            <div class="btn-group">
                <ul class="dropdown-menu dropdown-default w200">
                    <li class="{{$fl=='me'?'active':''}}">
                        <a class="" href="{{asset("edoc/assign_to?pid=$pid&fl=me&y=$y&m=$m")}}">แสดงเฉพาะเรื่องที่คุณติดตาม</a>
                    </li>
                    <li class="{{$fl=='all'?'active':''}}">
                        <a class="" href="{{asset("edoc/assign_to?pid=$pid&fl=all&y=$y&m=$m")}}">แสดงเรื่องที่คุณมอบหมายทั้งหมด</a>
                    </li>
                    @if(Edoc::bossindep())
                    <li class="{{$fl=='t2'?'active':''}}">
                        <a class="" href="{{asset("edoc/assign_to?pid=$pid&fl=t2&y=$y&m=$m")}}">มอบหมายจากหนังสือรับเข้า</a>
                    </li>
                    @endif
                </ul>                    
                <button data-toggle="dropdown" class="btn btn- dropdown-toggle">
                    @if($fl=='me')
                    เฉพาะเรื่องที่คุณติดตาม
                    @elseif($fl=='t2')
                    มอบหมายจากหนังสือรับเข้า
                    @else
                    เรื่องที่คุณมอบหมายทั้งหมด
                    @endif
                    <span class="caret"></span>
                </button>
            </div>

            <table id="sample-table-2" class="table table-striped table-bordered table-hover ">
                <thead>
                    <tr>
                        <th class="w50"></th>
                        <th style="width: 420px;">เรื่อง</th>
                        <th class=" w100">มอบหมายโดย</th>
                        <th class="">ผู้ถูกมอบหมาย</th>
                        <th class="hidden-phone hidden-tablet w40"><i class="icon-time bigger-110 hidden-480"></i></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($alllist as $d)
                    <?php
                    $ii++;
                    $profile = HRDProfile::gen_profile($d->assign_by);
                    $ph = Hrcat::find($d->assign_by);
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

                    $aid = $d->assign_id_d;
                    $assign_dtl = $d->dtl;
                    $a_assign = $d->st;
                    $msga = EDocAssign::assign_label($d->st);
                    $msga.= '<input name="assign_val_' . $aid . '" id="assign_val_' . $aid . '" type="hidden" value="' . $a_assign . '">';
                    if ($d->date_save) {
                        $msga.='<small class="muted"> ' . dateth('d n ee', strtotime($d->date_save)) . '</small>';
                    }
                    if ($assign_dtl) {
                        $msga.='<br><span id="assign_tx_' . $aid . '" class="red">' . $assign_dtl . '</span>';
                    } else {
                        $msga.='<span id="assign_tx_' . $aid . '"></span>';
                    }
                    ?>
                    <tr id="delidedoc{{$d->id}}">
                        <td class="pl04 pr0" ><i title="{{$d->type_name}}" class="f14 {{$d->icon}} {{$d->color}}"></i><span class="muted"> #{{$d->eno}}</span></td>
                        <td>
                            @if($d->assign_id)
                            <span class=""><i class="icon-cloud-download red"></i></span>
                            @endif
                            <a class="f14 {{$css1}}" href="{{asset('edoc/view?p='.$d->id)}}">{{$tlb}}</a>                             
                        </td>
                        <td class=""><img class="w20" title="{{$profile->nickname}}" src="{{asset($profile->avatar)}}" /> {{$ph->POSITION_S.'('.$ph->DEPARTMENT_S.')'}}</td>
                        <td class="">
                            <?php
                            $i = 0;
                            ?>
                            @foreach (EDocAssign::assign_listlv1($d->id) as $d2) 
                            <div class="clearfix">
                                <div class="w160 pull-left">{{++$i}}. {{$d2->BNAME.' '.$d2->SURNAME.' ('.$d2->POSITION.')' }}</div> 
                                <span>
                                    <?php
                                    $a_assign = $d2->st;
                                    $aid = $d2->assign_id_d;
                                    $assign_dtl = $d2->dtl;
                                    $msg = EDocAssign::assign_label($a_assign);
                                    $msg.='<input name="assign_val_' . $aid . '" id="assign_val_' . $aid . '" type="hidden" value="' . $a_assign . '">';
                                    if ($d2->date_save) {
                                        $msg.='<small class="muted"> ' . dateth('d n ee', strtotime($d2->date_save)) . '</small>';
                                    }
                                    if ($assign_dtl) {
                                        $msg.='<div id="assign_tx_' . $aid . '" class="text-warning ">' . $assign_dtl . '</div>';
                                    } else {
                                        $msg.='<span id="assign_tx_' . $aid . '"></span>';
                                    }
                                    echo $msg;
                                    ?>

                                </span>
                            </div>                                
                            @endforeach
                        </td>
                        <td class="hidden-phone hidden-tablet font12"><a href="#" title="{{$d->date_create}}">{{timepost_shot($d->date_create)}}</a></td>

                    </tr> 
                    @endforeach
                </tbody>
            </table>
            <div>
                <div class="pull-right f16">
                    @if($page>0)
                    <a class="btn btn-mini" href="{{asset("edoc/assign_to?pid=$pid&fl=$fl&y=$y&m=$m&page=".($page))}}"><i class="icon-chevron-left"></i> ก่อนหน้า</a>
                    @endif
                    @if(count($alllist)>=$pcount)
                    <a class="btn btn-mini" href="{{asset("edoc/assign_to?pid=$pid&fl=$fl&y=$y&m=$m&page=".($page+2))}}">หน้าถัดไป <i class="icon-chevron-right"></i></a>
                    @endif
                </div>
            </div>
            @if(!$ii)
            <div class="alert alert-danger">
                ไม่พบข้อมูล !!! 
            </div>
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
$(".doc_status").colorbox({iframe: true, width: "800px", height: "90%", overlayClose: true, onComplete: function () {
        resizeColorBox;
    }});

</script>


@stop
@section('page_title'){{$page_title}} @stop


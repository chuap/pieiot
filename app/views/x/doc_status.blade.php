@extends('edoc.ace_blank') 
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
$page_title = 'สร้างรายการใหม่';
$p = Input::get('p');
$t = Input::get('t')? : 1;
$stid = Input::get('stid')? : 0;

$canedit = false;
if ($p) {
    $obj = EDoc::find($p);
    if (!EDoc::can_view($obj)) {
        echo "<meta http-equiv='refresh' content='0;url=" . asset('edoc') . "'>";
        exit();
    }
    $etitle = $obj->etitle;
    $efrom = $obj->efrom;
    $eto = $obj->eto;
    $eno = $obj->eno;
    $eno = $obj->eno;
    $etype = $obj->etype;
    $type_name = $obj->doctype->type_name;
    $act_name = $obj->getaction->act_name;
    $eaction = $obj->eaction;
    $dtl = $obj->content = str_replace('src="files', 'src="' . asset('') . 'files', $obj->dtl);
    $page_title = $obj->etitle;
    $fname = $obj->Hrcat->TITLE . $obj->Hrcat->BNAME . ' ' . $obj->Hrcat->SURNAME;
    $readcount = $obj->read_count;
    $likecount = $obj->like_count;
    $place = $obj->place;
    $doc_date = $obj->doc_date;
    $canedit = EDoc::can_edit($obj);
    $list_status = EDocStatus::getStatus($p);
    $list_read = EDoc::list_read($p);
    $list_like = EDoc::list_like($p);
    $list_apcept = EDoc::list_apcept($p);
    $list_agree = EDoc::list_agree($p);
} else {
    $etype = '';
    $eaction = '';
    $type_name = '';
}
if((($t==3)||($t==4))&&(!$canedit)){
   $t=1; 
}
$tab[$t] = 'active';
?>

@section('body')
<div class="row-fluid">
    <!-- main col left --> 
    <div class="span12">
        <div class="page-header">
            <h3 class="blue m0">
                <span class="{{$obj->doctype->color}}"><i class="{{$obj->doctype->icon}} "></i></span>
                {{$etitle}}
            </h3>
        </div>
        <div class="tabbable">
            <ul class="nav nav-tabs" id="myTab">
                <li class="{{isset($tab[1])?'active':''}}">
                    <a data-toggle="tab" href="#t1">
                        <i class="green icon-home bigger-110"></i>
                        อ่านแล้ว <span class="badge badge-warning">{{count($list_read)}}</span>
                    </a>
                </li>
                <li class="{{isset($tab[2])?'active':''}}">
                    <a data-toggle="tab" href="#t2">
                        <i class="icon-thumbs-up-alt"></i>
                        ชอบ <span class="badge badge-important">{{count($list_like)}}</span>
                    </a>
                </li>
                @if($canedit)
                <li class="{{isset($tab[3])?'active':''}}">
                    <a data-toggle="tab" href="#t3">
                        <i class="icon-ok"></i>
                        รับทราบ <span class="badge badge-success">{{count($list_apcept)}}</span>
                    </a>
                </li>
                <li class="{{isset($tab[4])?'active':''}}">
                    <a data-toggle="tab" href="#t4">
                        <i class="icon-lightbulb"></i>
                        คิดเห็น <span class="badge badge-yellow">{{count($list_agree)}}</span>
                    </a>
                </li>
                @endif

                <li class="{{isset($tab[5])?'active':''}}">
                    <a data-toggle="tab" href="#t5">
                        <i class="icon-time"></i>
                        time division <span class="badge badge-pink">{{count($list_status)}}</span>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="t1" class="tab-pane {{isset($tab[1])?'active':''}}">
                    <table class="table table-thin table-hover">
                        <thead>
                            <tr>
                                <th class="p0"></th>
                                <th class="p0">พนักงาน</th>
                                <th class="p0">อ่านล่าสุด</th>
                                <th class="center p0">อ่าน (ครั้ง)</th>
                            </tr>
                        </thead>
                        <?php
                        $i = 0;
                        ?>
                        @foreach($list_read as $d)
                        <tr>
                            <td class="center">{{++$i}}</td>
                            <td>{{$d->BNAME.' '.$d->SURNAME}}</td>
                            <td>{{timepost_shot($d->read_date)}}ก่อน</td>
                            <td class="center"><span class="badge badge-important smaller">{{$d->rcount}}</span></td>
                        </tr>
                        @endforeach
                    </table>
                </div>

                <div id="t2" class="tab-pane {{isset($tab[2])?'active':''}}">
                    <table class="table table-thin table-hover">
                        <thead>
                            <tr>
                                <th class="p0"></th>
                                <th class="p0">พนักงาน</th>
                                <th class="p0">ถูกใจ</th>
                            </tr>
                        </thead>
                        <?php
                        $i = 0;
                        ?>
                        @foreach($list_like as $d)
                        <tr>
                            <td class="center">{{++$i}}</td>
                            <td>{{$d->BNAME.' '.$d->SURNAME}}</td>
                            <td><i class="icon-thumbs-up-alt"></i> {{timepost_shot($d->like_date)}}ก่อน </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                @if($canedit)
                <div id="t3" class="tab-pane {{isset($tab[3])?'active':''}}">
                    <table class="table table-thin table-hover">
                        <thead>
                            <tr>
                                <th class="p0"></th>
                                <th class="p0">พนักงาน</th>
                                <th class="p0">รับทราบ</th>
                            </tr>
                        </thead>
                        <?php
                        $i = 0;
                        ?>
                        @foreach($list_apcept as $d)
                        <tr>
                            <td class="center">{{++$i}}</td>
                            <td>{{$d->BNAME.' '.$d->SURNAME}}</td>
                            <td><i class="icon-ok"></i> {{timepost_shot($d->apcept_date)}}ก่อน </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <div id="t4" class="tab-pane {{isset($tab[4])?'active':''}}">
                    <table class="table table-thin table-hover">
                        <thead>
                            <tr>
                                <th class="p0"></th>
                                <th class="p0">พนักงาน</th>
                                <th class="center p0">เห็นด้วย</th>
                                <th class="center p0">ไม่เห็นด้วย</th>
                                <th class="p0">เมื่อ</th>
                            </tr>
                        </thead>
                        <?php
                        $i = 0;
                        $c1 = 0;
                        $c2 = 0;
                        ?>
                        @foreach($list_agree as $d)
                        <?php
                        if ($d->agree == 1) {
                            $c1++;
                        } else if ($d->agree == -1) {
                            $c2++;
                        }
                        ?>
                        <tr>
                            <td class="center">{{++$i}}</td>
                            <td>{{$d->BNAME.' '.$d->SURNAME}}</td>
                            <td class="center">{{$d->agree==1?'<i class="icon-ok green"></i>':''}}</td>
                            <td class="center">{{$d->agree==-1?'<i class="icon-ok red"></i>':''}}</td>
                            <td>{{timepost_shot($d->agree_date)}}ก่อน </td>
                        </tr>
                        @endforeach
                        <tfoot>
                            <tr>
                                <th colspan="2"><div class="text-right">รวม</div></th>
                        <th class="center">{{$c1}}<br/>{{$i>0?number_format(($c1/$i)*100,2):'0'}} %</th>
                        <th class="center">{{$c2}}<br/>{{$i>0?number_format(($c2/$i)*100,2):'0'}} %</th>
                        <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                @endif
                <div id="t5" class="tab-pane {{isset($tab[5])?'active':''}}">
                    <div><i class="icon-pencil"></i> <span  class="p1 muted">บันทึกโดย </span> {{$fname}}
                        <span  class="p1 muted">เมื่อ </span>
                        {{dateth('d n ee เวลา H:i',strtotime($obj->date_create))}} น.
                    </div>
                    <table class="table table-hover">   
                        <thead>
                            <tr>
                                <th class="p0"></th>
                                <th class="p0">ใช้เวลา</th>
                                <th class="p0">สะสม</th>
                            </tr>
                        </thead>
                        <?php
                        $i = 0;
                        ?>
                        @foreach($list_status as $d)
                        <?php
                        $i++;
                        if ($i == 1) {
                            $dtb = $d->date_save;
                            $dts = $d->date_save;                            
                        }else{
                            if($i>6){$i=2;}
                        }
                        ?>
                        <tr class="{{$stid==$d->id?'success':''}}">
                            <td class="center p0">
                                <div class="pull-left" style="margin-left: {{($i-1)*30}}px;">                                                                    
                                    <img class="w30" src="{{asset('images/downright-a.png')}}"/>
                                </div>
                                <div class="pull-left mt1">
                                    <div class="">
                                        <span id="delid{{$d->id}}" class="pr0 m04 pull-left label label-large label-{{$d->st_color}} arrowed-right">
                                            <a class="white" href="javascript:" title="{{$d->st_dtl}}&#013;บันทึกโดย {{$d->BNAME.' '.$d->SURNAME}} "><i class="{{$d->st_icon}}"></i>  {{$d->st_name}} </a>                                    
                                            <small class="black">{{dateth('d n ee',strtotime($d->date_save))}}</small>
                                        </span>
                                        
                                    </div>
                                    @if($d->st_dtl)
                                    <div class="mt0 pl1 grey font12 text-left">{{$d->st_dtl}}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="w50">{{$i!=1?timepost_shot($dtb,$d->date_save):'-'}}</td>
                            <td class="w50">{{$i!=1?timepost_shot($dts,$d->date_save):'-'}}</td>
                        </tr>
                        <?php
                        $dtb = $d->date_save;
                        ?>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@stop
@section('foot')

@stop
@section('foot_meta')
{{ HTML::script(asset('js/newpost.js'))}}
@stop
@section('page_title'){{$page_title}} @stop


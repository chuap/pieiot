@extends('edoc.ace') 
@section('page_title')สร้างเอกสารใหม่ @stop
@section('head_meta')
<title>ฝ่ายพัฒนาทรัพยากรบุคคล</title> 
{{ HTML::style(asset('fileupload/css/jquery.fileupload.css'))}}
{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui.css'))}}
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-noscript.css'))}}</noscript>
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui-noscript.css'))}}</noscript>
{{ HTML::script(asset('ckeditor/ckeditor.js'))}}
{{ HTML::style(asset('css/autocomplete.css'))}}
<style>
    .list_sl{ background-color: #ccff99;}
</style>

@stop
<?php
$page_title = 'สร้างรายการใหม่';
$sw_assign = true;
$p = Input::get('p');
$etype = Input::get('etype');
$doc_date = date('d-m-Y');
$refid = Input::get('ref');
$eassignfrom = '';
if ($p) {
    $obj = EDoc::find($p);
    if (!EDoc::can_edit($obj)) {
        echo "<meta http-equiv='refresh' content='0;url=" . asset('edoc') . "'>";
        exit();
    }
    $etitle = $obj->etitle;
    $efrom = $obj->efrom;
    $eto = $obj->eto;
    $eno = $obj->eno;
    $place = $obj->place;
    $doc_date = $obj->doc_date? : $doc_date;
    $etype = $obj->etype;
    $epri = $obj->epri;
    $esecret = $obj->esecret;
    $sw_comment = $obj->sw_comment;
    $eaction = $obj->eaction;
    $dtl = $obj->content = str_replace('src="files', 'src="' . asset('') . 'files', $obj->dtl);
    $dtl = $obj->content = str_replace('src="ckeditor', 'src="' . asset('') . 'ckeditor', $dtl);
    $page_title = 'แก้ไขข้อมูล';
    Session::put('curr_p', $p);
    $tag_type = $obj->tag_type;
    $refid = $eno = $obj->from_doc;
    $ah = EDocAssign::assign_h($p, '1');
    if (count($ah) > 0) {
        $assign_dtl = $ah[0]->assign_dtl;
        $eassignfrom = $ah[0]->fromid;
    }
} else {
    //isset($etype)? : $etype = '';
    isset($eaction)? : $eaction = '';
    isset($tag_type)? : $tag_type = '';
    isset($etitle)? : $etitle = '';
    isset($epri)? : $epri = '';
    isset($esecret)? : $esecret = '';
    Session::put('curr_p', false);
    isset($sw_comment)? : $sw_comment = '1';
}
if ($refid) {
    $dref = EDoc::find($refid);
    $etitle = $etitle ? $etitle : '[F]' . $dref->etitle;
    //$efrom = $dref->efrom;
} else {
    $dref = 0;
}
//echo $etype;
?>
@section('breadcrumbs')
<i class="icon-pencil bigger-130"></i><li class="active">{{$etitle}}</li>
@stop

@section('body')
<div class="row-fluid">
    <!-- main col left --> 
    <div class="span8">
        @if($dref)
        <div class="widget-box mt0 mb1">
            <div class="widget-header widget-header-flat widget-header-small">
                <h5>
                    <i class="icon-file-text"></i>
                    ต้นเรื่อง
                </h5>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="font14"><a href="{{asset('edoc/view?p='.$dref->id)}}" target="_blank"><span class="{{$dref->doctype->color}}"><i class="{{$dref->doctype->icon}} "></i> </span>{{$dref->etitle;}}</a></div>
                    <div style="margin-left: 16px;">
                        <span>{{$dref->doctype->type_name}}<span  class="p1 muted"> เลขที่</span>
                            {{$dref->eno?$dref->eno:'-'}}
                            <span  class="p1 muted"> ลว.</span>
                            {{$dref->doc_date?dateth('d n ee',strtotime($dref->doc_date)):'-'}}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <p class="lead">{{$page_title}}</p>

        @if (Session::has('flash_error'))
        <div class="alert alert-danger">
            <div id="flash_error">{{ Session::get('flash_error') }}</div>
        </div>
        @endif
        {{ Form::open(array('action' => 'EDocController@addNew')) }}
        {{ Form::hidden('p',$p) }}
        {{ Form::hidden('refid',$refid) }}
        <div class="form-horizontal" />
        <div class="control-group mb1">
            <label class="control-label" for="form-field-1">ประเภท</label>
            <div class="controls">                

                <select id="etype" class="red" onchange="etype_click()" name="etype" onclick="">
                    @foreach (EDoc::edoc_type() as $n2)
                    <option <?php if ($etype == $n2->type_id) echo ' selected="selected" '; ?> value="{{$n2->type_id}}">{{$n2->type_name}}</option>
                    @endforeach
                </select> 
                &nbsp; &nbsp;                
                <label class="inline" for=""> เลขที่ </label>&nbsp; 
                {{ Form::text('eno', isset($eno)?$eno:'', array('placeholder'=>'auto', 'class' => 'span2 red')) }} 

            </div>
        </div>

        <div class="control-group mb1">
            <label class="control-label" for="form-field-2">ชื่อเรื่อง</label>
            <div class="controls">
                {{ Form::text('etitle', isset($etitle)?$etitle:'', array('placeholder'=>'ระบุเรื่อง', 'class' => 'span10 blue')) }}
                <span class="help-inline">*</span>
            </div>
        </div>

        <div class="control-group mb1">
            <label class="control-label" for="form-input-readonly">เจ้าของเรื่อง</label>
            <div class="controls">
                {{ Form::text('efrom', isset($efrom)?$efrom:'', array('placeholder'=>'เจ้าของเรื่อง', 'class' => 'span10 blue')) }}
            </div>
        </div>

        <div class="control-group mb1">
            <label class="control-label" for="form-field-4">เรียน</label>
            <div class="controls">
                {{ Form::text('eto', isset($eto)?$eto:'', array('placeholder'=>'เรียน', 'class' => 'span10 blue')) }}
            </div>
        </div>
        <div class="control-group mb1">
            <label class="control-label" for="form-field-1">ที่</label>
            <div class="controls">                
                {{ Form::text('place', isset($place)?$place:'', array('placeholder'=>'', 'class' => 'span5 blue')) }} 
                &nbsp; &nbsp;                
                <label class="inline" for=""> ลงวันที่ </label>&nbsp; 
                {{ Form::text('doc_date', isset($doc_date)?$doc_date:'', array('placeholder'=>'dd-mm-yyyy', 'class' => 'datepicker span3 blue')) }}

            </div>
        </div>
        <div class="control-group mb1">
            <label class="control-label" for="form-field-1">ความเร่งด่วน</label>
            <div class="controls">                
                <select id="epri" class=" w100" onchange="" name="epri" >
                    <option {{$epri=='0'?'selected':''}} value="0">ปกติ</option>
                    <option {{$epri=='1'?'selected':''}} value="1">ด่วน</option>
                    <option {{$epri=='2'?'selected':''}} value="2">ด่วนมาก</option>
                    <option {{$epri=='3'?'selected':''}} value="3">ด่วนที่สุด</option>
                </select>
                &nbsp; &nbsp;                
                <label class="inline" for=""> ชั้นความลับ </label>&nbsp; 
                <select id="esecret" class=" w100" onchange="" name="esecret" >
                    <option {{$esecret=='0'?'selected':''}} value="0">ปกติ</option>
                    <option {{$esecret=='1'?'selected':''}} value=1>ลับ</option>
                    <option {{$esecret=='2'?'selected':''}} value=2>ลับเฉพาะ</option>
                    <option {{$esecret=='3'?'selected':''}} value=3>ลับมาก</option>
                    <option {{$esecret=='4'?'selected':''}} value=4>ลับที่สุด</option>
                    <option {{$esecret=='5'?'selected':''}} value=5>ปกปิด</option>
                    <option {{$esecret=='6'?'selected':''}} value=6>เฉพาะ</option>
                </select>
            </div>
        </div>

        <div class="control-group  mb0">
            {{ Form::textarea('dtl', isset($dtl)?$dtl:'', array('id'=>'dtl','placeholder'=>'รายละเอียด', 'class' => 'form-control')) }}
        </div>


        <div id="accordion2" class="accordion">
            <div class="accordion-group">
                <div class="accordion-heading ">
                    <a href="#collapseOne" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle collapsed">
                        มอบหมายงาน
                        <input  onclick="assign_click()" {{$sw_assign?'checked':''}} id="sw_assign" name="sw_assign"  type="checkbox" class="ace-switch ace-switch-6" />
                        <span class="lbl"></span>

                    </a>
                </div>

                <div class="accordion-body collapse" id="collapseOne">
                    <div class="accordion-inner">
                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                    </div>
                </div>
            </div>

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a href="#collapseTwo" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle collapsed">
                        Collapsible Group Item #2
                    </a>
                </div>

                <div class="accordion-body collapse" id="collapseTwo">
                    <div class="accordion-inner">
                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                    </div>
                </div>
            </div>

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a href="#collapseThree" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle collapsed">
                        Collapsible Group Item #3
                    </a>
                </div>

                <div class="accordion-body collapse" id="collapseThree">
                    <div class="accordion-inner">
                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et.
                    </div>
                </div>
            </div>
        </div>







        <div>
            <div class="widget-box">
                <div class="widget-header widget-header-flat">
                    <label>
                        <h4 class="smaller">มอบหมายงาน
                            <input onclick="assign_click()" {{$sw_assign?'checked':''}} id="sw_assign" name="sw_assign"  type="checkbox" class="ace-switch ace-switch-6" />
                            <span class="lbl"></span></h4>
                    </label>                    
                </div>
                <div class="widget-body {{$sw_assign?'':'hidden'}}" id="div_assign">
                    <div class="widget-main">                        
                        <?php
                        $p1 = '';
                        $tx = '';
                        $assign_dtl = '';
                        $maxi = 0;
                        $assign_r = array();
                        if ($p) {

                            foreach (EDocAssign::assign_listlv1($p) as $d) {
                                $maxi++;
                                $lb1 = $d->BNAME . ' ' . $d->SURNAME . '(' . $d->POSITION_S . ')';
                                $t0 = '<input id="assignid' . $maxi . '" name="assignid' . $maxi . '" type="hidden" value="' . $d->catid . '">';
                                $t = '<div id="ac' . $d->catid . '" class="contact-itm bg_cat">' . $t0 . $lb1 . ' <a onclick="delassign(\'ac' . $d->catid . '\')" class="btn btn-xs btn-minier btn-danger pt0">x</a></div>';

                                $tx.= $t;
                                $assign_r[$d->catid] = true;
                            }
                            if ($maxi > 0) {
                                $p1 = 'well';
                            }
                        }
                        ?>
                        <div class="control-group">
                            <label class="control-label" for="form-field-4">ผู้มอบหมาย</label>
                            <div class="controls">
                                <select id="eassignfrom" class="form-control" name="eassignfrom">

                                    @foreach (Hrcat::boss_dep() as $n2)
                                    <?php
                                    $cs = '';
                                    if (in_array($n2->POSITION_S, array('ฝ.'))) {
                                        $t = $n2->POSITION_S . '' . $n2->BNAME . ' ' . $n2->SURNAME;
                                        $cs = 'blue';
                                    } else if (in_array($n2->POSITION_S, array('ชฝ.', 'ชอ.'))) {
                                        $t = $n2->POSITION_S . '' . $n2->BNAME . ' ' . $n2->SURNAME;
                                        $cs = 'red';
                                    } else if (in_array($n2->POSITION_S, array('ผส.', 'ผสค.'))) {
                                        $t = $n2->POSITION_S . '' . $n2->SECTION_N;
                                    } else if ($n2->POSITION_S == 'ฝ.') {
                                        $t = $n2->POSITION_S . $n2->DEPRTMENT_S;
                                    } else if ($n2->POSITION_S == 'ผอ.') {
                                        $t = $n2->DEPRTMENT_S;
                                    } else {
                                        $t = $n2->POSITION_S . '' . $n2->BNAME . ' ' . $n2->SURNAME;
                                    }
                                    ?>
                                    <option {{$eassignfrom==$n2->ID8?'selected':''}} id="sac{{$n2->ID8}}" class="{{$cs}} {{isset($assign_r[$n2->ID8])?'list_sl':''}}" value="{{$n2->ID8}}">{{$t}}</option>
                                    @endforeach
                                </select>  
                            </div>
                        </div>
                        <div class="control-group mb1">

                            <label class="control-label" for="form-field-4">สั่งการ/มอบหมายให้</label>
                            <div class="controls">
                                <select id="eassign" class="form-control" onchange="assign_select()" name="eassign">
                                    <option value=""><เลือก></option>
                                    @foreach (Hrcat::boss_dep() as $n2)
                                    <?php
                                    $cs = '';
                                    if (in_array($n2->POSITION_S, array('ฝ.'))) {
                                        $t = $n2->POSITION_S . '' . $n2->BNAME . ' ' . $n2->SURNAME;
                                        $cs = 'blue';
                                    } else if (in_array($n2->POSITION_S, array('ชฝ.', 'ชอ.'))) {
                                        $t = $n2->POSITION_S . '' . $n2->BNAME . ' ' . $n2->SURNAME;
                                        $cs = 'red';
                                    } else if (in_array($n2->POSITION_S, array('ผส.', 'ผสค.'))) {
                                        $t = $n2->POSITION_S . '' . $n2->SECTION_N;
                                    } else if ($n2->POSITION_S == 'ฝ.') {
                                        $t = $n2->POSITION_S . $n2->DEPRTMENT_S;
                                    } else if ($n2->POSITION_S == 'ผอ.') {
                                        $t = $n2->DEPRTMENT_S;
                                    } else {
                                        $t = $n2->POSITION_S . '' . $n2->BNAME . ' ' . $n2->SURNAME;
                                    }
                                    ?>
                                    <option id="sac{{$n2->ID8}}" class="{{$cs}} {{isset($assign_r[$n2->ID8])?'list_sl':''}}" value="{{$n2->ID8}}">{{$t}}</option>
                                    @endforeach
                                </select>  
                                {{ Form::text('txfind2', '', array('id'=>'txfind2','placeholder'=>'<ค้นหา>', 'class' => 'form-control')) }}  
                            </div>
                        </div>
                        <div id="divassign" class="clearfix mb0 p04 {{$p1}}">{{$tx}}</div> 
                        <input id="tmaxassign" name="tmaxassign" type="hidden" value="{{$maxi}}">
                        <div>
                            <textarea rows="3" class="span12 limited" placeholder="<รายละเอียด>" id="assign_dtl" name="assign_dtl" data-maxlength="">{{$assign_dtl}}</textarea>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="tabbable mt1">
            <ul class="nav nav-tabs" id="myTab">
                <li class="active">
                    <a data-toggle="tab" href="#t1">
                        <i class="green icon-info bigger-110"></i>
                        ผู้ติดตามเอกสาร <span class="badge badge-warning"></span>
                    </a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#t2">
                        <i class="icon-gear"></i>
                        ตั้งค่าเพิ่มเติม <span class="badge badge-important"></span>
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div id="t1" class="tab-pane active">
                    <span class="label label-success">กิจกรรมสำหรับผู้ติดตาม</span>
                    <div class="control-group mb1">
                        <label class="control-label" for="form-field-4">กิจกรรม</label>
                        <div class="controls">
                            <select id="eaction" class="form-control" onchange="" name="eaction">
                                @foreach (EDoc::edoc_actions() as $n2)
                                <option <?php if ($eaction == $n2->act_id) echo ' selected="selected" '; ?> value="{{$n2->act_id}}">{{$n2->act_name}}</option>
                                @endforeach
                            </select>  
                        </div>
                    </div>
                    <div class="control-group mb1 mt0">
                        <label class="control-label" for="form-field-4">เพิ่มผู้ติดตาม</label>
                        <div class="controls">
                            {{ Form::text('txfind', '', array('id'=>'txfind','placeholder'=>'ค้นหาชือ', 'class' => 'form-control')) }}   
                            <span  class="help-inline">*ผู้ทีจะสามารถดูเรื่องนี้ได้</span>
                        </div>

                    </div>

                    <?php
                    $p1 = '';
                    $tx = '';
                    $maxi = 0;
                    if ($p) {
                        foreach (EDoc::tag_list($p) as $d) {
                            $maxi++;
                            $lb1 = $d->BNAME . ' ' . $d->SURNAME . '(' . $d->POSITION_S . ')';
                            $t0 = '<input id="tbid' . $maxi . '" name="tbid' . $maxi . '" type="hidden" value="' . $d->catid . '">';
                            $t = '<div id="ct' . $d->catid . '" class="contact-itm">' . $t0 . $lb1 . ' <a onclick="dellist2(\'ct' . $d->catid . '\')" class="btn btn-xs btn-minier btn-danger pt0">x</a></div>';

                            $tx.= $t;
                        }
                        if ($maxi > 0) {
                            $p1 = 'well';
                        }
                    }
                    ?>
                    <div id="divto" class="clearfix mb0 p04 {{$p1}}">{{$tx}}</div> 
                    <div id="sentto "></div>
                    <span id="ltime"></span> 
                    <input id="tmaxi" name="tmaxi" type="hidden" value="{{$maxi}}">
                    <div class="alert alert-info">
                        <div class="text-left clearfix">
                            <label class="pull-left pl1">
                                <input {{($tag_type=="")?"checked":''}} name="tag_type" id="tax1" value="" type="radio" />
                                <span class="lbl">เฉพาะชื่อที่ถูกแท็ก</span>
                            </label>

                            <label  class="pull-left pl1">
                                <input {{($tag_type=="s")?"checked":''}} name="tag_type" id="tax2" value="s" type="radio" />
                                <span class="lbl">ทุกคนในส่วน</span>
                            </label>
                            <label class="pull-left pl1">
                                <input {{($tag_type=="d")?"checked":''}} name="tag_type" id="tax3" value="d" type="radio" />
                                <span class="lbl">ทุกคนในฝ่าย</span>
                            </label>
                            <label class="pull-left pl1">
                                <input {{($tag_type=="a")?"checked":''}} name="tag_type" id="tax4" value="a" type="radio" />
                                <span class="lbl">ทุกคนในองค์กร</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div id="t2" class="tab-pane">
                    <div class="span5">
                        <label>
                            <input name="sw_comment" value="1" {{$sw_comment==1?'checked':''}} class="ace-switch ace-switch-4" type="checkbox" />
                            <span class="lbl">แสดงความคิดเห็น </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions">
            <button  class="btn btn-info" type="submit" onclick="topdf();">
                <i class="icon-ok bigger-110"></i>
                Submit
            </button>

            &nbsp; &nbsp; &nbsp;
            <button class="btn" type="reset">
                <i class="icon-undo bigger-110"></i>
                Reset
            </button>
        </div>
    </div>

    {{ Form::close() }}
</div>
<div class="span4">
    <h4>แนบไฟล์</h4>
    <table role="presentation" class="table table-striped imgshadow">    <tbody class="files">
            @foreach (FilesBook::getall($p) as $f)
            <?php ?>
            <tr id="delid{{$f->id}}">
                <td>
                    @if ($f->thumb!=null) 
                    <a href="{{asset($f->url)}}" target="_blank"><img class="w40" src="{{asset($f->thumb)}}"></a>
                    @else
                    <a href="{{asset($f->url)}}"><img alt="150x150" class="w40" src="{{FilesBook::geticon($f->file_name)}}" /></a>
                    @endif
                </td>       
                <td><a href="javascript:" onclick="InsertText('{{asset($f->url)}}','{{asset($f->thumb)}}','{{$f->file_name}}')"><i class="icon-mail-reply-all"></i> {{mb_substr($f->file_name,0,30,'UTF-8')}}</a></td>
                <td><a href="javascript:" onclick="deleteimgbook({{$f->id}});">ลบ</a></td>
            </tr>
            @endforeach
        </tbody></table>
    <form id="fileupload" style="" class="well m0" action="uploadbook" method="POST" enctype="multipart/form-data">
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->

        <div class="fileupload-buttonbar">
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span>เพิ่มไฟล์ใหม่..</span>
                <input type="file" name="files[]" multiple>
            </span>   

        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped imgshadow"><tbody class="files"></tbody></table>
        <!-- The global progress state -->
        <div class="fileupload-progress fade">
            <!-- The global progress bar -->
            <div class="progress progress-striped active f12" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
            <!-- The extended global progress state -->
            <div class="progress-extended">&nbsp;</div>
        </div>
    </form>


</div>
</div>
@stop
@section('foot')

<script>
                    $(document).ready(function() {ractiveclick(); });
                    function ractiveclick(){
                    //alert($('#cert_st').is(':checked'));
                    if ($('#cert_st').is(':checked')){
                    $('.ractive1').removeClass("hidden");
                    } else{
                    $('.ractive1').addClass("hidden");
                    }
                    }
            function closepage(r) {
            if (r)
                    parent.window.location.reload();
                    parent.$.fn.colorbox.close();
            }
</script>

<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
    <td>
    <span class="preview"></span>
    </td>
    <td>
    <div class="name">{%=file.name%}</div>
    <strong class="error text-danger"></strong>
    </td>
    <td>    
    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:30px;"></div></div>
    </td>
    <td>
    {% if (!i && !o.options.autoUpload) { %}
    <button class="btn btn-minier btn-primary start" disabled>
    <i class="icon-circle-arrow-up f12"></i>
    <span>เริ่มอัปโหลด</span>
    </button>
    {% } %}
    {% if (!i) { %}
    <button class="btn btn-minier btn-warning cancel">
    <i class="icon-remove-sign f12"></i>  
    </button>
    {% } %}
    </td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
    <td>
    <span class="preview">
    {% if (file.thumbnailUrl) { %}
    <a href="{%=file.url%}"  title="{%=file.name%}" download="{%=file.name%}" data-gallery><img class="w50" src="{%=file.thumbnailUrl%}"></a>
    {% } %}
    </span>
    </td>
    <td>
    <p class="name">
    {% if (file.url) { %}
    <a onclick="InsertText('{%=file.url%}','{%=file.thumbnailUrl%}','{%=file.name%}');" href="javascript:" title="{%=file.name%}">{%=file.name%}</a>
    {% } else { %}
    <span>{%=file.name%}</span>
    {% } %}
    </p>
    {% if (file.error) { %}
    <div><span class="label label-danger">Error</span> {%=file.error%}</div>
    {% } %}
    </td>
    <td class='' style="width:60px">
    {% if (file.deleteUrl) { %}
    <button class="btn btn-danger btn-minier delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
    <i class="icon-trash f12"></i> 
    <span></span>
    </button>                
    {% } else { %}
    <button class="btn btn-minier btn-warning cancel">
    <i class="icon-remove-sign f12"></i>
    <span>ยกเลิก</span>
    </button>
    {% } %}
    </td>
    </tr>
    {% } %}
</script>

@stop

@section('foot_meta')

<script src="{{asset('/')}}fileupload/js/vendor/jquery.ui.widget.js"></script>
<script src="{{asset('/')}}fileupload/js/tmpl.min.js"></script>
<script src="{{asset('/')}}fileupload/js/load-image.min.js"></script>
<script src="{{asset('/')}}fileupload/js/canvas-to-blob.min.js"></script>
<script src="{{asset('/')}}fileupload/js/jquery.blueimp-gallery.min.js"></script>
<script src="{{asset('/')}}fileupload/js/jquery.iframe-transport.js"></script>
<script src="{{asset('/')}}fileupload/js/jquery.fileupload.js"></script>
<script src="{{asset('/')}}fileupload/js/jquery.fileupload-process.js"></script>
<script src="{{asset('/')}}fileupload/js/jquery.fileupload-image.js"></script>
<script src="{{asset('/')}}fileupload/js/jquery.fileupload-audio.js"></script>
<script src="{{asset('/')}}fileupload/js/jquery.fileupload-video.js"></script>
<script src="{{asset('/')}}fileupload/js/jquery.fileupload-validate.js"></script>
<script src="{{asset('/')}}fileupload/js/jquery.fileupload-ui.js"></script>
<script src="{{asset('/')}}fileupload/js/main_book.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="{{asset('/')}}fileupload/js/cors/jquery.xdr-transport.js"></script>
<![endif]-->


{{ HTML::script(asset('js/newpost.js'))}}


@stop



@extends('admin.ace') 
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
$sw_follow = false;
$p = Input::get('p');

if($p){
    $car=Car::find_car($p); 
    $dtl = str_replace('src="files', 'src="' . asset('') . 'files', $car->dtl);
    $dtl = str_replace('src="ckeditor', 'src="' . asset('') . 'ckeditor', $dtl);
    $dtl = str_replace('@root/', asset('/'), $dtl);
    $page_title='แก้ไขรายการ : '.Car::car_name($car);
    Session::put('curr_p', $p);
    $new_data = explode("|", $car->options);
    $i = 0;
    $dataop=array();
    foreach ($new_data as $id => $value) {
        $i++;
        if($value){
           $dataop[$value]=1; 
        }
    }
}
else{
    $car=null;
    Session::put('curr_p', false);
}
$y = date('Y');



?>


@section('breadcrumbs')
<i class="icon-pencil bigger-130"></i><li class="active">{{$page_title}}</li>
@stop

@section('body')
<div class="row-fluid">
    <div class="span8">
        <form id="f1">
            <div class="form-horizontal" >
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">ยี่ห้อ</label>
                    <div class="controls">   
                        <select id="txbrand" class=" span3" onchange="" name="txbrand" onclick="">
                            @foreach (Car::brand_list() as $d)
                            <option {{$car?$car->brand==$d->bid?'selected':'':''}} value="{{$d->bid}}">{{$d->brandname}}</option>
                            @endforeach
                        </select> 
                        <label class="inline ml2 " for=""> รุ่น </label>&nbsp; 
                        {{ Form::text('txmodel', $car?$car->car_model:'', array('placeholder'=>'', 'id' => 'txmodel', 'class' => 'span3')) }} 
                        <label class="inline ml2" for=""> ปี </label>&nbsp; 
                        <select id="txyear" class="span2" onchange="" name="txyear" onclick="">
                            <option value=""></option>
                            @for ($i=0;$i<40;$i++)
                            <option {{$car?$car->year==($y-$i)?'selected':'':''}} value="{{$y-$i}}">{{$y-$i}}</option>
                            @endfor
                        </select>
                    </div>

                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">ประเภท</label>
                    <div class="controls">   
                        <select id="txcartype" class="span6" onchange="" name="txcartype" onclick="">
                            @foreach (Car::type_list() as $d)
                            <option {{$car?$car->car_type==$d->tid?'selected':'':''}} value="{{$d->tid}}">{{$d->type_name}}</option>
                            @endforeach
                        </select> 
                        <label class="inline ml2" for=""> สีรถ </label>&nbsp; 
                        {{ Form::text('txcolor', $car?$car->color:'', array('placeholder'=>'', 'class' => 'w100')) }}
                    </div>
                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">รายละเอียดรุ่น</label>
                    <div class="controls">   
                        {{ Form::text('txmodeldesc', $car?$car->model_desc:'', array('placeholder'=>'', 'class' => 'span6')) }} 
                        <label class="inline ml2 " for=""> เกียร์ </label>&nbsp; 
                        <select id="txgear" class="span2" onchange="" name="txgear" onclick="">                            
                            <option {{$car?$car->gear=='auto'?'selected':'':''}} value="auto">Auto</option>  
                            <option {{$car?$car->gear=='manual'?'selected':'':''}} value="manual">Manual</option>
                        </select> 
                    </div>
                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">เชื้อเพลิง</label>
                    <div class="controls">   
                        {{ Form::text('txetype', $car?$car->etype:'', array('placeholder'=>'', 'class' => 'span4')) }} 
                        <label class="inline ml2 " for=""> ขนาดเครื่องยนต์ </label>&nbsp; 
                        {{ Form::text('txmtype', $car?$car->mtype:'', array('placeholder'=>'', 'class' => 'span2')) }}
                    </div>
                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">ราคา</label>
                    <div class="controls">   
                        <input value="{{$car?$car->price:'0'}}" type="text" class="black text-bold" name="txprice" id="txprice" /> 
                    
                        <label class="inline ml2 " for=""> สถานะ </label>&nbsp; 
                        <select id="txstatus" class="span3" onchange="" name="txstatus" onclick="">                            
                            <option {{$car?$car->car_status=='1'?'selected':'':''}} value="1">ปกติ</option>  
                            <option {{$car?$car->car_status=='2'?'selected':'':''}} value="2">จองแล้ว</option> 
                            <option {{$car?$car->car_status=='8'?'selected':'':''}} value="8">ขายแล้ว</option>  
                            <option {{$car?$car->car_status=='9'?'selected':'':''}} value="9">ยกเลิกข้อมูล</option>
                            
                        </select> 
                    </div>
                    
                </div>
                <div class="control-group  mb0">
                {{ Form::textarea('dtl', isset($dtl)?$dtl:'', array('id'=>'dtl','placeholder'=>'รายละเอียด', 'class' => 'form-control')) }}
                </div>
            </div>

            <div id="accordion2" class="accordion  inp  ">
                <div class="accordion-group ">
                    <div class="accordion-heading">
                        <a href="#collapseOne" data-parent="#accordion2" data-toggle="collapse" class="accordion-toggle ">
                            <i class="icon-gear"></i>
                            Options <span class="badge badge-important"></span>
                        </a>
                    </div>
                    <div class="accordion-body" id="collapseOne">
                        <div class="accordion-inner clearfix">
                            <?php $i = 1; ?>
                            @foreach (Car::option_list() as $d)
                            @if(($i % 4)==1)<div class="row-fluid ">@endif
                                <div class="span3 ">
                                    <label>
                                        <input {{isset($dataop[$d->oid])?'checked':''}} name="sw_{{$d->oid}}" value="1" class="" type="checkbox" />
                                        <span class="lbl f12"> {{$d->op_name}} </span>
                                    </label>
                                </div>
                                @if(($i % 4)==0)</div>@endif                            
                            <?php $i++; ?>
                            @endforeach
                            <?php
                            if (($i % 4) != 1) {
                                echo '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-actions text-right ">
                    <a id="btn_save" class="btn btn-info radius-4 " onclick="savecar()"  aria-hidden="true">
                        <i class="icon-save bigger-160"></i>
                        บันทึกข้อมูล
                    </a>

                    &nbsp; &nbsp; &nbsp;
                    <button class="btn" type="reset">
                        <i class="icon-undo bigger-110"></i>
                        Reset
                    </button>
                </div>
            </div>
            {{ Form::hidden('p',$p) }}
            {{ Form::hidden('at','savecar',array('id'=>'at')) }}
            {{ Form::hidden('rf',isset($rf)?$rf:'',array('id'=>'rf')) }}
        </form>
    </div>
    <div class="span4">
        <h4>แนบไฟล์รูปภาพ</h4>
        <table role="presentation" class="table table-striped imgshadow">    
            <tbody class="files">
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
            </tbody>
        </table>
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

<script language="javascript" type="text/javascript">
    $(document).ready(function () {
    $('#tnx1').ace_spinner({value: {{isset($tnx1)?$tnx1:0}}, min: 1, max: 100, step: 1, icon_up: 'icon-plus', icon_down: 'icon-minus', btn_up_class: 'btn-default', btn_down_class: 'btn-default'});
    $('#tnx2').ace_spinner({value: {{isset($tnx2)?$tnx2:0}}, min: 0, max: 10000, step: 10, icon_up: 'icon-plus', icon_down: 'icon-minus', btn_up_class: 'btn-default', btn_down_class: 'btn-default'});
    $('#txprice').ace_spinner({value: {{$car?$car->price:0}}, min: 0, max: 1000000000000, step: 100000, icon_up: 'icon-plus', icon_down: 'icon-minus', btn_up_class: 'btn-default', btn_down_class: 'btn-default'});
    });
    function savecar() {
    //    alert();return false;
    $('#dtl').val(CKEDITOR.instances.dtl.getData());    
    if (ckktext('#txmodel', 'กรุณาระบุรุ่น')){return false; }
    if (ckktext('#txyear', 'กรุณาระบุปีรถ')){return false; }
    $('#btn_save').addClass('hidden');
    $.ajax({
    url: rootContext + 'postaction',
        type: "POST",
        datatype: "json",
        data: $("#f1").serialize()
    }).success(function (result) {
    var obj = jQuery.parseJSON(result);
    if (obj.STATUS == true) {
    bootbox.dialog({
    title: "บันทึกข้อมูลเรียบร้อยแล้ว!!",
        message: '<span class="red">' + obj.MSG + '</span>',
        buttons: {main: {label: "ดำเนินการต่อ", className: "btn-success",
            callback: function (result) {
            //parent.$.colorbox.close();
            window.location.href = rootContext + 'admin/car_list';
            }}
        }

    });
    $('#btn_save').removeClass('hidden');
    } else {
    bootbox.dialog({
    title: "ล้มเหลว!!",
        message: 'msg: <span class="red font14">' + obj.MSG + '</span>',
        buttons: {main: {label: "OK !", className: "btn-danger"}
        }
    });
    $('#btn_save').removeClass('hidden');
    }
    });
    }
    function ckktext(tid, msg){
    if ($(tid).val().trim() == ''){
    bootbox.dialog({
    title: "แจ้งเตือน!!",
        message: 'msg: <span class="red font14">' + msg + '</span>',
        buttons: {main: {label: "OK !", className: "btn-danger"}
        }
    });
    $(tid).focus()
        return true;
    } else{return false; }
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
    <sp                        an class="preview">
    {% if (                            file.thumbnailUrl) { %}
    <a href="{%=file.url%}"  title="{%=file.name%}" down                                                                                                                    load="{%=file.name%}" data-gallery><img class="w50" src="{%=file.thumbnailUrl%}"></a>
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



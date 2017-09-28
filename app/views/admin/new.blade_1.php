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
                        <select id="etype" class=" span3" onchange="" name="etype" onclick="">
                            @foreach (Car::brand_list() as $d)  
                            <option value="{{$d->bid}}">{{$d->brand}}</option>
                            @endforeach
                        </select> 
                        <label class="inline ml2 " for=""> รุ่น </label>&nbsp; 
                        {{ Form::text('eno', isset($eno)?$eno:'', array('placeholder'=>'', 'class' => 'span3')) }} 
                        <label class="inline ml2" for=""> ปี </label>&nbsp; 
                        <select id="txyear" class="span2" onchange="" name="txyear" onclick="">
                            <option value=""></option>
                            @for ($i=0;$i<40;$i++)  
                            <option value="{{$y-$i}}">{{$y-$i}}</option>
                            @endfor
                        </select>
                    </div>

                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">ประเภท</label>
                    <div class="controls">   
                        <select id="etype" class="span6" onchange="" name="etype" onclick="">
                            @foreach (Car::type_list() as $d)  
                            <option value="{{$d->tid}}">{{$d->type_name}}</option>
                            @endforeach
                        </select> 
                    </div>
                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">รายละเอียดรุ่น</label>
                    <div class="controls">   
                        {{ Form::text('eno', isset($eno)?$eno:'', array('placeholder'=>'', 'class' => 'span6')) }} 
                        <label class="inline ml2 " for=""> เกียร์ </label>&nbsp; 
                        <select id="etype" class="span2" onchange="" name="etype" onclick="">                            
                            <option value="auto">auto</option>  
                            <option value="manual">manual</option>
                        </select> 
                    </div>
                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">เชื้อเพลิง</label>
                    <div class="controls">   
                        {{ Form::text('eno', isset($eno)?$eno:'', array('placeholder'=>'', 'class' => 'span4')) }} 
                        <label class="inline ml2 " for=""> ขนาดเครื่องยนต์ </label>&nbsp; 
                        {{ Form::text('eno', isset($eno)?$eno:'', array('placeholder'=>'', 'class' => 'span2')) }}
                    </div>
                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">ราคา</label>
                    <div class="controls">   
                        {{ Form::text('eno', isset($eno)?$eno:'', array('placeholder'=>'', 'class' => 'span4')) }} 
                    </div>
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
                            @if(($i % 4)==1)
                            <div class="row-fluid ">
                                @endif
                                <div class="span3 m0 p0">
                                    <label>
                                        <input name="sw_comment" value="1" class="" type="checkbox" />
                                        <span class="lbl f12"> {{$d->op_name}} </span>
                                    </label>
                                </div>
                                @if(($i % 4)==0)                            
                            </div>
                            @endif
                            <?php $i++; ?>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::hidden('p',$p) }}
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

<script>


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



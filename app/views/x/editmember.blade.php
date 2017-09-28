@extends('exam.master_nohead') 
@section('head_meta')
<title>HRD->อัปโหลดไฟล์</title> 
{{ HTML::style('fileupload/css/style.css')}}
{{ HTML::style('fileupload/css/blueimp-gallery.min.css')}}
{{ HTML::style('fileupload/css/jquery.fileupload.css')}}
{{ HTML::style('fileupload/css/jquery.fileupload-ui.css')}}
<noscript>{{ HTML::style('fileupload/css/jquery.fileupload-noscript.css')}}</noscript>
<noscript>{{ HTML::style('fileupload/css/jquery.fileupload-ui-noscript.css')}}</noscript>
{{ HTML::script('ckeditor/ckeditor.js')}}
<style>
    .w50{ width: 50px;}
</style>
@stop
@section('sidebar')
<h2 class="lead">อัปโหลดไฟล์</h2>
<!-- The file upload form used as target for the file upload widget -->
<form id="fileupload" class="well p1" action="uploadpost" method="POST" enctype="multipart/form-data">
    <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
    <div class="row fileupload-buttonbar">
        <div class="col-lg-7">
            <!-- The fileinput-button span is used to style the file input field as button -->
            <span class="btn btn-success fileinput-button">
                <i class="glyphicon glyphicon-plus"></i>
                <span>เลือกไฟล์...</span>
                <input type="file" name="files[]" multiple>
            </span>               
        </div>
        <!-- The global progress state -->
        <div class="col-lg-5 fileupload-progress fade">
            <!-- The global progress bar -->
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar progress-bar-success" style="width:0%;"></div>
            </div>
            <!-- The extended global progress state -->
            <div class="progress-extended">&nbsp;</div>
        </div>
    </div>
    <!-- The table listing the files available for upload/download -->
    <table role="presentation" class="table table-striped imgshadow"><tbody class="files"></tbody></table>
</form>
<h4 class="lead">ไฟล์ล่าสุดของคุณ</h4>
<table role="presentation" class="table table-striped imgshadow">    <tbody class="files">
        @foreach (Files::lastFile(30) as $f)
        <tr id="delid{{$f->id}}">
            <td>
                @if ($f->thumb!=null) 
                <a href="#"><img class="w50" src="{{asset($f->thumb)}}"></a>
                @endif
            </td>       
            <td><a href="javascript:" onclick="InsertText('{{$f->url}}','{{$f->thumb}}','{{$f->file_name}}')">{{$f->file_name}}</a></td>
            <td><a href="javascript:" onclick="deleteimg({{$f->id}});">ลบ</a></td>
        </tr>
        @endforeach
    </tbody></table>
<br>

@stop



@section('foot')
{{ HTML::script(asset('/').'js/action.js')}}
<script>
    $(document).ready(function() {ractiveclick();});
    function ractiveclick(){
    //alert($('#cert_st').is(':checked'));
    if($('#cert_st').is(':checked')){
    $('.ractive1').removeClass("hidden");
    }else{
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
    <button class="btn btn-sm btn-primary start" disabled>
    <i class="glyphicon glyphicon-upload"></i>
    <span>เริ่มอัปโหลด</span>
    </button>
    {% } %}
    {% if (!i) { %}
    <button class="btn btn-sm btn-warning cancel">
    <i class="glyphicon glyphicon-ban-circle"></i>
    <span>ยกเลิก</span>
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
    <button class="btn btn-danger btn-sm delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
    <i class="glyphicon glyphicon-trash"></i>
    <span></span>
    </button>                
    {% } else { %}
    <button class="btn btn-sm btn-warning cancel">
    <i class="glyphicon glyphicon-ban-circle"></i>
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
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="{{asset('/')}}fileupload/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="{{asset('/')}}fileupload/js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="{{asset('/')}}fileupload/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="{{asset('/')}}fileupload/js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="{{asset('/')}}fileupload/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="{{asset('/')}}fileupload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="{{asset('/')}}fileupload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="{{asset('/')}}fileupload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="{{asset('/')}}fileupload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="{{asset('/')}}fileupload/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="{{asset('/')}}fileupload/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="{{asset('/')}}fileupload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="{{asset('/')}}fileupload/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="{{asset('/')}}fileupload/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="{{asset('/')}}fileupload/js/cors/jquery.xdr-transport.js"></script>
<![endif]-->
<script>
    CKEDITOR.replace('dtl', {  customConfig : '<?php echo  asset('/') ?>ckeditor/customconfig_micro.js'    });
    $(".datepicker").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat:"dd-mm-yy"
    });</script>
<script>
    function InsertText(f, b, n) {
    var editor = CKEDITOR.instances.dtl;
    if (editor.mode == 'wysiwyg')
    {
    var x=n.substring(n.length-4);
    //alert(x);
    if (b){
    var t = '<img class="img-responsive" src="{{asset('/')}}' + f + '">';
    }
    else if(' .wav'.indexOf(x)>0){

    var t='<audio type="audio/x-wav" src="' + f + '" controls></audio>';
    }
    else if(' .wma'.indexOf(x)>0){

    var t='<audio type="audio/wma" src="' + f + '" controls></audio>';
    }
    else if(' .mp3'.indexOf(x)>0){        
    var t='<audio type="audio/mp3" src="' + f + '" controls></audio>';        
    }
    else{
    var t = '<a href="' + f + '" >' + n + '</a>';
    }
    editor.insertHtml(t);
    }
    else
    alert('You must be in WYSIWYG mode!');
    }


</script>  

@stop
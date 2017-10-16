@extends('admin.ace_blank') 
@section('head_meta')
<title></title> 
{{ HTML::style(asset('fileupload/css/jquery.fileupload.css'))}}
{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui.css'))}}
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-noscript.css'))}}</noscript>
<noscript>{{ HTML::style(asset('fileupload/css/jquery.fileupload-ui-noscript.css'))}}</noscript>
{{ HTML::script(asset('ckeditor/ckeditor.js'))}}
{{ HTML::style(asset('css/autocomplete.css'))}}
<link rel="stylesheet" href="{{asset('themes/ace/assets/css/colorpicker.css')}}" />

@stop
<?php
$page_title = 'Select project type.';
$page_icon = "icon-plus";
$rf = Input::get('r') ? Input::get('r') : 'refresh';
$mtype = Input::get('mtype');
$pro = Input::get('pro');
$step = Input::get('step');
//$step = 1;
$tid = '';

if (!$mtype) {
    $step = 1;
    
} else {
    $step = 2;
    $minfo = Projects::projectType($mtype);
    $page_title ='<i class="h30 '.$minfo->tyicon.'"></i> '. $minfo->tyname;
}
$pinfo = Projects::find($pro);
if ($pinfo) {

    $page_title = ' ' . $pro . ' : ' . $pinfo->proname;
    $page_icon = "icon-pencil";
    //$new_data = explode("|", $pinfo->portslist);
    $i = 0;
}


?>

@section('body')
<?php
//echo "$mtype $datasl tid: $tid";
?>

@if($step==1)
<div class="row-fluid ">
    <div class="span12">
        <p class="lead">{{$page_title}}</p>        
    </div>
    <div>
        
        <a href="{{asset('pie.newproject?step=2&mtype=fix')}}" class="btn btn-app btn-success  no-radius" style="width: 200px;">
            <i class="icon-calendar bigger-200"></i>Fix Project
        </a>
        <a href="{{asset('pie.newproject?step=2&mtype=flow')}}" class="btn btn-app btn-info  no-radius" style="width: 200px;">
            <i class="icon-sort-by-attributes bigger-200"></i>Flow project
        </a>
        <a href="{{asset('pie.newproject?step=2&mtype=theme')}}" class="btn btn-app btn-warning  no-radius" style="width: 200px;">
            <i class="icon-tags bigger-200"></i>Theme project
        </a>
    </div>
</div>
@elseif($step==2)
<div class="row-fluid">
    <div class="span12">
        <p class="lead">{{$page_title}}</p>        
    </div>
    <div>
        <form id="f1">
            <div class="form-horizontal" >
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">Project name</label>
                    <div class="controls">
                        <input class="span10" id="txproname" name="txproname" type="text"  value="{{$pinfo?$pinfo->proname:''}}">
                    </div>
                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">Description</label>
                    <div class="controls">
                        <textarea rows="2" class="span12 limited" placeholder="" id="txprodesc" name="txprodesc" data-maxlength="">{{$pinfo?$pinfo->prodesc:''}}</textarea>
                    </div>
                </div>
                
                <div class="control-group mb1">
                    @if(!$pro)
                    <label class="control-label" for="form-field-1">
                        <a href="{{asset('pie.newproject?step=1')}}"><i class="icon-backward"></i> Back</a>
                    </label>
                    @endif
                    <div class="controls">
                        <a onclick="proeditsave()" class="btn_save btn btn-info "><i class="icon-plus bigger-125"></i> {{$pinfo?'Save':'Create'}} project </a>
                        <div class="hidden text-center divload pull-left">
                            <img src="{{asset('images/loading.gif')}}" style="width: 30px;">    
                            Processing...             
                        </div>
                    </div>

                </div>
            </div>

            {{ Form::hidden('ac','pronewsave',array('id'=>'ac')) }}
            {{ Form::hidden('pieid','0',array('id'=>'pieid')) }}
            {{ Form::hidden('mtype',$mtype,array('id'=>'mtype')) }}
            {{ Form::hidden('pro',$pro,array('id'=>'pro')) }}
        </form>
    </div>
</div>
@endif
@stop
@section('foot')
<script language="javascript" type="text/javascript">
    function proeditsave() {
        //alertbox($("#f1").serialize());
        //return false;
        if (ckktext('#txproname', 'Please enter project name.')) {
            return false;
        }
        $('.btn_save').addClass('hidden');
        $('.divload').removeClass('hidden');
        $.ajax({
            url: rootContext + 'adminaction',
            type: "POST",
            datatype: "json",
            data: $("#f1").serialize()
        }).success(function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.STATUS == true) {
                parent.$.colorbox.close();
                parent.window.location.replace('{{asset("/")}}'+'pro-'+obj.MSG+'.info');

            } else {
                alertbox(obj.MSG + '');

            }
            $('.divload').addClass('hidden');
            $('.btn_save').removeClass('hidden');
        });
    }
    function ckktext(tid, msg) {
        if ($(tid).val().trim() == '') {
            alertbox(msg);
            return true;
        } else {
            return false;
        }
    }
     



    function closepage(r) {
        if (r)
            parent.window.location.reload();
        parent.$.fn.colorbox.close();
    }

    


</script>
@stop
@section('foot_meta')
<script src="{{asset('themes/ace/assets/js/bootstrap-colorpicker.min.js')}}"></script>

<script>

</script>
@stop
@section('page_title'){{$page_title}} @stop


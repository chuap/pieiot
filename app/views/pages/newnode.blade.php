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
$page_title = 'Select Model.';
$page_icon = "icon-plus";
$rf = Input::get('r') ? Input::get('r') : 'refresh';
$mtype = Input::get('mtype');
$pieid = Input::get('pieid');
$listmodel = Pies::pieModel();
$step = Input::get('step');
//$step = 1;
$tid = '';
$pieidtx='';
if (!$mtype) {
    $step = 1;
    
} else {
    $step = 2;
    $minfo = Pies::modelInfo($mtype);
    $piename=$minfo->modelname;
    $page_title ='<img class="h30" src="'.asset($minfo->img).'" /> '. $minfo->modelname;
}
if($pieid){
    $pieinfo=Pies::find($pieid);
    $step = 2;
    $mtype=$pieinfo->piemodel;
    $piename=$pieinfo->piename;
    $pieidtx=$pieinfo->pieid;
    $txdesc=$pieinfo->desc;
    $piecolor=$pieinfo->color;
    $minfo = Pies::modelInfo($mtype);
    $page_title='<i class="icon-edit"></i> '.$pieinfo->piename.'  <small></small>';
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
        @foreach($listmodel as $d)
        <a href="{{asset('pie.newnode?step=2&mtype='.$d->modelid)}}" class="btn btn-app {{$d->btn}} no-radius" style="width: 150px;">
            <img src="{{asset($d->img)}}" class="h50"><br />{{$d->modelname}}
        </a>
        @endforeach

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
                    <label class="control-label" for="form-field-1">Node name</label>
                    <div class="controls">
                        <input class="span10" id="piename" name="piename" type="text"  value="{{$piename}}">
                    </div>
                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">Node ID</label>
                    <div class="controls">
                        <input class="span10" id="pieidtx" name="pieidtx" type="text"  value="{{$pieidtx}}">
                    </div>
                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">Description</label>
                    <div class="controls">
                        <textarea rows="2" class="span12 limited" placeholder="" id="txdesc" name="txdesc" data-maxlength="">{{isset($txdesc)?$txdesc:''}}</textarea>
                    </div>
                </div>
                <div class="control-group mb1">
                    <label class="control-label" for="form-field-1">Color</label>
                    <div class="controls">
                        <input onchange="changecolor()" class="span2" id="piecolor" name="piecolor" type="text"  value="{{isset($piecolor)?$piecolor:''}}">
                    </div>
                </div>
                <div class="control-group mb1">
                    @if(!$pieid)
                    <label class="control-label" for="form-field-1">
                        <a href="{{asset('pie.newnode?step=1')}}"><i class="icon-backward"></i> Back</a>
                    </label>
                    @endif
                    <div class="controls">
                        <a onclick="newpie()" class="btn_save btn btn-info "><i class="icon-plus bigger-125"></i>  Save Pie Node </a>
                        <div class="hidden text-center divload pull-left">
                            <img src="{{asset('images/loading.gif')}}" style="width: 30px;">    
                            Processing...             
                        </div>
                    </div>

                </div>
            </div>

            {{ Form::hidden('ac','newpie',array('id'=>'ac')) }}
            {{ Form::hidden('mtype',$mtype,array('id'=>'mtype')) }}
            {{ Form::hidden('pieid',$pieid,array('id'=>'pieid')) }}
        </form>
    </div>
</div>
@endif
@stop
@section('foot')
<script language="javascript" type="text/javascript">
    $(function () {
        $('#piecolor').colorpicker();

        $("input[name='piecolor']").change(function () {
            alert('ss');
        });
    });


    function newpie() {
//        alertbox($("#f1").serialize());
//        return false;
        if (ckktext('#piename', 'Please enter "Node name"')) {
            return false;
        }
        if (ckktext('#pieidtx', 'Please enter "Node ID"')) {
            return false;
        }

        $('.btn_save').addClass('hidden');
        $('.divload').removeClass('hidden');

        $.ajax({
            url: rootContext + 'adminaction',
            type: "GET",
            datatype: "json",
            data: $("#f1").serialize()
        }).success(function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.STATUS == true) {
                parent.$.colorbox.close();
                //parent.location.reload();
                parent.window.location.href = rootContext + "pie-"+obj.LASTID+'.info';
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
    function assign_select() {
        var lb1 = $("#action_ports option:selected").text();
        var bid = $('#action_ports').val();
        addassign(bid, lb1);
    }
    function mode_select() {
        var m = $('#action_mode').val();
        $('.ports').addClass('hidden');
        $('.md_' + m).removeClass('hidden');
        $("#divaction_ports").removeClass('well');
        $("#divaction_ports").text('');
        $('#tmaxaction_ports').val(0);
    }
    function mode_begin() {
        var m = $('#action_mode').val();
        $('.ports').addClass('hidden');
        $('.md_' + m).removeClass('hidden');
    }

    function delassign(tr) {
        //var tr = $(this).closest('tr');
        //alert(tr);
        $('#' + tr).css("background-color", "#FF3700");
        $('#' + tr).fadeOut(400, function () {
            $('#' + tr).remove();
            $('#s' + tr).removeClass('list_sl');
            sumassign();
        });
        //return false;
    }



    function closepage(r) {
        if (r)
            parent.window.location.reload();
        parent.$.fn.colorbox.close();
    }

    function check_key(e) {
        if (e.keyCode == 13) {
            findmember();
            return false;
        }
    }
    function changecolor() {
        alert('aa');
        $('#piecolor').css("background-color", "yellow");
    }


</script>
@stop
@section('foot_meta')
<script src="{{asset('themes/ace/assets/js/bootstrap-colorpicker.min.js')}}"></script>

<script>

</script>
@stop
@section('page_title'){{$page_title}} @stop


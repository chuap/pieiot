@extends('edoc.ace') 
@section('head_meta')
<title>ฝ่ายพัฒนาทรัพยากรบุคคล</title> 
<?php
$tp = asset('theme/ace');
?>
{{ HTML::style(asset('css/autocomplete.css'))}}
{{ HTML::style(asset('theme/ace/assets/css/fullcalendar.css'))}}
<link rel="stylesheet" href="{{$tp}}/assets/css/select2.css" />
<link rel="stylesheet" href="{{$tp}}/assets/css/bootstrap-editable.css" />
<link rel="stylesheet" type="text/css" href="{{asset('js/picedit/css/picedit.css')}}" />
<style>
    .fc-event-title::before{
    }
</style>
@stop
<?php
$t = Input::get('t')? : 1;
$tab[$t] = 'active';
$hr = Hrcat::find(Session::get('cat.catid'));
?>
@section('breadcrumbs')
<li class="active hidden-phone">Profile</li>
@stop
@section('body')
<div class="page-header position-relative">
    <h1>
        Profile
        <small>
            <i class="icon-double-angle-right"></i>            
        </small>
    </h1>
</div><!--/.page-header-->

<div class="row-fluid">
    <div class="span12">
        <!--PAGE CONTENT BEGINS-->

        <div id="user-profile-2" class="user-profile row-fluid">
            <div class="tabbable">
                <ul class="nav nav-tabs padding-18">
                    <li class="{{isset($tab[1])?'active':''}}">
                        <a data-toggle="tab" href="#home">
                            <i class="green icon-user bigger-120"></i>
                            Profile
                        </a>
                    </li>
                    @if(0)
                    <li class="{{isset($tab[2])?'active':''}}">
                        <a data-toggle="tab" href="#feed">
                            <i class="orange icon-rss bigger-120"></i>
                            Activity Feed
                        </a>
                    </li>
                    @endif
                    <li class="{{isset($tab[3])?'active':''}}">
                        <a data-toggle="tab" href="#setting">
                            <i class="orange icon-gears bigger-120"></i>
                            Setting
                        </a>
                    </li>

                </ul>

                <div class="tab-content no-border padding-24">
                    <div id="home" class="tab-pane in {{isset($tab[1])?'active':''}}">
                        <div class="row-fluid">
                            <div class="span3 center">
                                <div>
                                    <span class="profile-picture">
                                        <a href="#postModal"  data-toggle="modal"><img title="คลิกเพื่อเปลี่ยนรูปใหม่" id="avatarx" class="editable myavatar" alt="" src="{{asset(Session::get('cat.avatar'))}}" /></a>
                                    </span>
                                </div>
                                <div class="loading hidden" ><img class="w30" src="{{asset('images/loading.gif')}}"/></div>

                                <a href="#" onclick="rand_avatar()" class="btn btn-small btn-block btn-success">
                                    <i class="icon-bug bigger-110"></i>
                                    สุ่มรูปประจำตัว
                                </a>
                            </div><!--/span-->

                            <div class="span9">
                                <h4 class="blue">
                                    <span class="middle">{{Session::get('cat.nickname')}}</span>

                                    <span class="label label-purple arrowed-in-right">
                                        <i class="icon-circle smaller-80"></i>
                                        {{Session::get('cat.position')}}
                                    </span>
                                </h4>

                                <div class="profile-user-info">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Username </div>

                                        <div class="profile-info-value">
                                            <span>{{Session::get('cat.catid')}}</span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> สังกัด </div>

                                        <div class="profile-info-value">
                                            <i class="icon-map-marker light-orange bigger-110"></i>
                                            <span>{{$hr->DEPARTMENT_S}}</span>
                                            <span>{{$hr->SECTION_N}}</span>
                                        </div>
                                    </div>

                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> ตำแหน่ง </div>

                                        <div class="profile-info-value">
                                            <span>{{$hr->POSITION_N}} ({{$hr->POSITION}})</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> เข้าระบบล่าสุด </div>

                                        <div class="profile-info-value">
                                            <span>{{dateth('d nn eeee เวลา H:i น.',strtotime($hr->profile->lastlogin))}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="hr hr-8 dotted"></div>

                            </div><!--/span-->

                        </div><!--/row-fluid-->
                        <div>
                            <div class="control-group m0 ">
                                <div class="">เลือก Skin </div>
                                <div class="controls clearfix">                                    
                                    <label class="pull-left pl1">
                                        <input class="skin_sl" {{Session::get('cat.skin')=='default'?'checked':''}} name="skin_sl" type="radio" value="default" />
                                        <span class="lbl p1" style="background-color:#438EB9"> C1</span>
                                    </label>
                                    <label class="pull-left pl1">
                                        <input class="skin_sl" {{Session::get('cat.skin')=='skin-1'?'checked':''}} name="skin_sl" type="radio" value="skin-1" />
                                        <span class="lbl p1" style="background-color:#222A2D"> C2</span>
                                    </label>
                                    <label class="pull-left pl1">
                                        <input class="skin_sl" {{Session::get('cat.skin')=='skin-2'?'checked':''}} name="skin_sl" type="radio" value="skin-2" />
                                        <span class="lbl p1" style="background-color:#C6487E"> C3</span>
                                    </label>
                                    <label class="pull-left pl1">
                                        <input class="skin_sl" {{Session::get('cat.skin')=='skin-3'?'checked':''}} name="skin_sl" type="radio" value="skin-3" />
                                        <span class="lbl p1" style="background-color:#D0D0D0"> C4</span>
                                    </label> 
                                </div>
                            </div>
                        </div>
                        <div class="space-20"></div>
                    </div><!--#home-->
                    @if(0)

                    <div id="feed" class="tab-pane {{isset($tab[2])?'active':''}}" >

                        <div class="profile-feed row-fluid">

                            <div class="span6">
                                <div class="profile-activity clearfix">
                                    <div>
                                        <i class="pull-left thumbicon icon-ok btn-success no-hover"></i>
                                        <a class="user" href="#"> Alex Doe </a>
                                        joined
                                        <a href="#">Country Music</a>

                                        group.
                                        <div class="time">
                                            <i class="icon-time bigger-110"></i>
                                            5 hours ago
                                        </div>
                                    </div>

                                    <div class="tools action-buttons">
                                        <a href="#" class="blue">
                                            <i class="icon-pencil bigger-125"></i>
                                        </a>

                                        <a href="#" class="red">
                                            <i class="icon-remove bigger-125"></i>
                                        </a>
                                    </div>
                                </div>

                            </div><!--/span-->

                            <div class="span6">
                                <div class="profile-activity clearfix">
                                    <div>
                                        <i class="pull-left thumbicon icon-edit btn-pink no-hover"></i>
                                        <a class="user" href="#"> Alex Doe </a>
                                        published a new blog post.
                                        <a href="#">Read now</a>
                                        <div class="time">
                                            <i class="icon-time bigger-110"></i>
                                            11 hours ago
                                        </div>
                                    </div>

                                    <div class="tools action-buttons">
                                        <a href="#" class="blue">
                                            <i class="icon-pencil bigger-125"></i>
                                        </a>

                                        <a href="#" class="red">
                                            <i class="icon-remove bigger-125"></i>
                                        </a>
                                    </div>
                                </div>


                            </div><!--/span-->                           

                        </div><!--/row-->

                        <div class="space-12"></div>

                        <div class="center">
                            <a href="#" class="btn btn-small btn-primary">
                                <i class="icon-rss bigger-150 middle"></i>

                                View more activities
                                <i class="icon-on-right icon-arrow-right"></i>
                            </a>
                        </div>

                        <div class="alert alert-info">กำลังพัฒนา!!</div>
                    </div><!--/#feed-->
                    @endif
                    <div id="setting" class="tab-pane {{isset($tab[3])?'active':''}}">     
                        <div class="span5">
                            <label>
                                <input onclick="setmenumin()" id="sw_menumin" name="sw_menumin"  value="1" {{$hr->profile->menumin==1?'checked':''}} class="ace-switch ace-switch-4" type="checkbox" />
                                <span class="lbl">Menu-min </span>
                            </label>
                        </div>


                    </div><!--/#feed-->
                </div>
            </div>
        </div>

        <!--PAGE CONTENT ENDS-->
    </div><!--/.span-->

</div><!--/.row-fluid-->

<div id="postModal" class="modal fade " tabindex="-1000" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="f1" action="{{asset('edoc/action')}}" method="post" enctype="multipart/form-data">
                {{ Form::hidden('at','change_avatar',array('id'=>'at')) }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    เปลี่ยนรูปโปรไฟล์
                </div>
                <div class="modal-body text-center">
                    <input type="file" name="thefile" id="thebox">
                </div>
                <div class="modal-footer  ">
                    <div class="row-fluid ">
                        <div class="span8 text-left">

                        </div>
                        <div class="span4">
                            <button type="submit" onclick="beginupload()" class="btsubmit btn btn-app btn-grey btn-mini radius-4 "  aria-hidden="true">
                                <i class="icon-save bigger-160"></i>
                                Save
                            </button>
                        </div>	
                    </div>
                </div>
            </form>    
        </div>
    </div>
</div>

@stop
@section('foot')


<!--ace scripts-->

<script type="text/javascript">
    function beginupload() {
        $(".btsubmit").addClass('hidden');
    }
    function submit_profile() {
        var rootContext = document.body.getAttribute("data-root");
        if (!rootContext)
            rootContext = '';
        console.log("submit event");
        var fd = new FormData(document.getElementById("f1"));
        fd.append("label", "WEBUPLOAD");
        $.ajax({
            url: rootContext + 'edoc/action',
            type: "POST",
            data: fd,
            enctype: 'multipart/form-data',
            processData: false, // tell jQuery not to process the data
            contentType: false   // tell jQuery not to set contentType
        }).done(function (data) {
            console.log("PHP Output:");
            console.log(data);
            var obj = jQuery.parseJSON(data);
            if (obj.STATUS == true) {

                //alert('delid'+pretxt + hid);
            } else {
                //$.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
                bootbox.dialog({
                    title: "ล้มเหลว!!",
                    message: '<h5>msg: <span class="red">' + obj.ERROR_MSG + '</span></h5>',
                    buttons: {main: {label: "OK!", className: "btn-danger"}}

                });
            }
        });
        return false;
    }
    function submit_profile2() {
        var rootContext = document.body.getAttribute("data-root");
        if (!rootContext)
            rootContext = '';
        $.ajax({
            url: rootContext + 'edoc/action',
            type: "post",
            datatype: "json",
            data: $("#f1").serialize()
        }).success(function (result) {
            var obj = jQuery.parseJSON(result);
            if (obj.STATUS == true) {
                removetag('delid' + pretxt + hid);
                //alert('delid'+pretxt + hid);
            } else {
                //$.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
                bootbox.dialog({
                    title: "ล้มเหลว!!",
                    message: '<h5>msg: <span class="red">' + obj.ERROR_MSG + '</span></h5>',
                    buttons: {main: {label: "OK!", className: "btn-danger"}}

                });
            }
        });


    }
    $(".skin_sl").on("click", function () {
        myskin_things();
    });

    $('#avatar2').on('click', function () {
        var modal =
                '<div class="modal hide fade">\
                                                <div class="modal-header">\
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>\
                                                        <h4 class="blue">Change Avatar</h4>\
                                                </div>\
                                                \
                                                <form class="no-margin id="fprofile">\
                                                <div class="modal-body">\
                                                        <div class="space-4"></div>\
                                                        <div style="width:75%;margin-left:12%;"><input type="file" name="file-input" /></div>\
                                                </div>\
                                                \
                                                <div class="modal-footer center">\
                                                        <button type="submit" class="btn btn-small btn-success"><i class="icon-ok"></i> Submit</button>\
                                                        <button type="button" class="btn btn-small" data-dismiss="modal"><i class="icon-remove"></i> Cancel</button>\
                                                </div>\
                                                </form>\
                                        </div>';


        var modal = $(modal);
        modal.modal("show").on("hidden", function () {
            modal.remove();
        });

        var working = false;

        var form = modal.find('form:eq(0)');
        var file = form.find('input[type=file]').eq(0);
        file.ace_file_input({
            style: 'well',
            btn_choose: 'Click to choose new avatar',
            btn_change: null,
            no_icon: 'icon-picture',
            thumbnail: 'small',
            before_remove: function () {
                //don't remove/reset files while being uploaded
                return !working;
            },
            before_change: function (files, dropped) {
                var file = files[0];
                if (typeof file === "string") {
                    //file is just a file name here (in browsers that don't support FileReader API)
                    if (!(/\.(jpe?g|png|gif)$/i).test(file))
                        return false;
                }
                else {//file is a File object
                    var type = $.trim(file.type);
                    if ((type.length > 0 && !(/^image\/(jpe?g|png|gif)$/i).test(type))
                            || (type.length == 0 && !(/\.(jpe?g|png|gif)$/i).test(file.name))//for android default browser!
                            )
                        return false;

                    if (file.size > 1100000) {//~100Kb
                        return false;
                    }
                }

                return true;
            }
        });

        form.on('submit', function () {
            //alert($('#fprofile')[0].name);
            if (!file.data('ace_input_files'))
                return false;

            file.ace_file_input('disable');
            form.find('button').attr('disabled', 'disabled');
            form.find('.modal-body').append("<div class='center'><i class='icon-spinner icon-spin bigger-150 orange'></i></div>");

            var deferred = new $.Deferred;
            working = true;
            deferred.done(function () {
                form.find('button').removeAttr('disabled');
                form.find('input[type=file]').ace_file_input('enable');
                form.find('.modal-body > :last-child').remove();

                modal.modal("hide");

                var thumb = file.next().find('img').data('thumb');
                if (thumb)
                    $('#avatar2').get(0).src = thumb;

                working = false;
            });


            setTimeout(function () {
                deferred.resolve();
            }, parseInt(Math.random() * 800 + 800));

            return false;
        });

    });



</script>
@stop
@section('foot_meta')
{{ HTML::script(asset('js/newpost.js'))}}
<script type="text/javascript" src="{{asset('js/picedit/js/picedit.js')}}"></script>
<script type="text/javascript">
    var img_tmp;        
    $(function () {
        $('#theboxXX').picEdit();
        $('#postModal').modal('toggle');
        $('#postModal').modal('toggle');
    });
    $('#thebox').picEdit({
        maxWidth: '100%',
        maxHeight: '280',
        imageUpdated: function (img) {
            //img_tmp= img.sr;
        },
        formSubmitted: function (result) {
            //console.log(result.responseText);
            var obj = jQuery.parseJSON(result.responseText);
            if (obj.STATUS == true) {
                $(".myavatar").attr("src", obj.MSG);
                //$(".myavatar").attr("src", img_tmp);
                $('#postModal').modal('toggle');
                //alert(obj.MSG);
                //location.reload();
            } else {
                //$.msgBox({title: "CAT-HRD System", content: obj.ERROR_MSG, type: "error"});
                bootbox.dialog({
                    title: "ล้มเหลว!!",
                    message: '<h5>msg: <span class="red">' + obj.ERROR_MSG + '</span></h5>',
                    buttons: {main: {label: "OK!", className: "btn-danger"}}

                });
            }
            $(".btsubmit").removeClass('hidden');
        },
        redirectUrl: false,
        defaultImage: false
    });
</script>
@stop
@section('page_title')Profile :: {{$hr->profile->nickname}} @stop


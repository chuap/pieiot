<!DOCTYPE html>
<?php
$tp = asset('themes/ace');
?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>
            @section('page_title')
            HRD e-Document 
            @show</title>
        <!--basic styles-->
        @yield('head_meta')
        <link href="{{$tp}}/assets/css/progress.css" rel="stylesheet" />
        <link href="{{$tp}}/assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="{{$tp}}/assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/font-awesome.min.css" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/jquery.gritter.css" />
        {{ HTML::style('js/jquery-ui/css/start/jquery-ui-1.10.4.custom.css')}}
        {{ HTML::style('css/mod.css')}} 
        {{ HTML::style('js/colorbox/colorbox.css')}} 
        {{ HTML::style('js/msg_box/Styles/msgBoxLight.css')}} 
        <!--[if IE 7]>
          <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
        <![endif]-->
        <!--page specific plugin styles-->
        <!--fonts-->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" />
        <!--ace styles-->
        <link rel="stylesheet" href="{{$tp}}/assets/css/ace.min.css" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/ace-responsive.min.css" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/ace-skins.min.css" />

        <link rel="stylesheet" href="{{$tp}}/assets/css/jquery-ui-1.10.3.custom.min.css" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/chosen.css" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/datepicker.css" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/bootstrap-timepicker.css" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/daterangepicker.css" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/colorpicker.css" />


        <!--[if lte IE 8]>
          <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
        <![endif]-->
        <!--inline styles related to this page-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
    <body  data-root="{{asset('/')}}">
        <div class="main-container container-fluid">                        

            <div class="page-content">                        
                @yield('body')
            </div><!--/.page-content-->
        </div><!--/.main-container-->
        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-small btn-inverse">
            <i class="icon-double-angle-up icon-only bigger-110"></i>
        </a>
        <!--basic scripts-->
        <!--[if !IE]>-->
        <script src="{{$tp}}/assets/js/jquery-2.0.3.min.js"></script>
        <!--<![endif]-->
        <!--[if IE]>
        <script src="{{$tp}}/assets/js/1.10.2/jquery.min.js"></script>
        <![endif]-->
        <!--[if !IE]>-->
        <!--<![endif]-->
        <!--[if IE]>
        <script type="text/javascript">
        window.jQuery || document.write("<script src='{{$tp}}/assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
        </script>
        <![endif]-->
        <script type="text/javascript">
if ("ontouchend" in document)
    document.write("<script src='{{$tp}}/assets/js/jquery.mobile.custom.min.js'>" + "<" + "/script>");
        </script>
        <script src="{{$tp}}/assets/js/bootstrap.min.js"></script>
        <!--page specific plugin scripts-->
        <!--[if lte IE 8]>
          <script src="{{$tp}}/assets/js/excanvas.min.js"></script>
        <![endif]-->
        {{ HTML::script(asset('js/jquery-ui/js/jquery-ui.js'))}}
        {{ HTML::script('js/colorbox/jquery.colorbox.js')}} 
        {{ HTML::script('js/msg_box/Scripts/jquery.msgBox.js')}}
        {{ HTML::script(asset('/').'js/action.js')}}
        {{ HTML::script('js/bootbox.min.js')}}
        
        @yield('foot') 
        <script src="{{asset('/')}}js/piescript.js" type="text/javascript"></script>
        <!--inline scripts related to this page-->
        <script src="{{$tp}}/assets/js/ace-elements.min.js"></script>
        <script src="{{$tp}}/assets/js/ace.min.js"></script>
<!--        <script src="{{$tp}}/assets/js/date-time/bootstrap-datepicker.min.js"></script>
        <script src="{{$tp}}/assets/js/date-time/bootstrap-timepicker.min.js"></script>-->
        
        <script type="text/javascript" src="{{asset('js/jquery-ui/js')}}/jquery-ui-timepicker-addon.js"></script>
        <script src="{{$tp}}/assets/js/date-time/bootstrap-timepicker.min.js"></script>
        <script src="{{$tp}}/assets/js/date-time/moment.min.js"></script>
        <script src="{{$tp}}/assets/js/date-time/daterangepicker.min.js"></script>


        <script type="text/javascript">
$(function () {
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "dd-mm-yy"
    });
    $('.timepicker').timepicker({
        minuteStep: 1,
        showSeconds: true,
        showMeridian: false
    });
//    $('.datetimepicker').datetimepicker({
//        timeFormat: 'HH:mm:ss',
//        dateFormat: 'dd-mm-yy',
//        stepHour: 2,
//        stepMinute: 10,
//        stepSecond: 10
//    });
    var colorbox_params = {
        reposition: true,
        scalePhotos: true,
        scrolling: false,
        previous: '<i class="icon-arrow-left"></i>',
        next: '<i class="icon-arrow-right"></i>',
        close: '&times;',
        current: '{current} of {total}',
        maxWidth: '100%',
        maxHeight: '100%',
        onOpen: function () {
            document.body.style.overflow = 'hidden';
        },
        onClosed: function () {
            document.body.style.overflow = 'auto';
        },
        onComplete: function () {
            $.colorbox.resize();
        }
    };

    $('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
    $("#cboxLoadingGraphic").append("<i class='icon-spinner orange'></i>");//let's add a custom loading icon

    /**$(window).on('resize.colorbox', function() {
     try {
     //this function has been changed in recent versions of colorbox, so it won't work
     $.fn.colorbox.load();//to redraw the current frame
     } catch(e){}
     });*/
})

        </script>

    </body>
    @yield('foot_meta')
</html>

<?php
$tp = asset('themes/lte');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>
            @section('page_title')
            PiIoT 
            @show
        </title>
        <link href="{{asset('images/pie.ico')}}" rel="shortcut icon">
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="{{$tp}}/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="{{$tp}}/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="{{$tp}}/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Morris chart -->
        <!--        <link href="{{$tp}}/css/morris/morris.css" rel="stylesheet" type="text/css" />-->
        <!-- jvectormap -->
        <!--        <link href="{{$tp}}/css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />-->
        <!-- fullCalendar -->
        <!--        <link href="{{$tp}}/css/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />-->
        <!-- Daterange picker -->
        <link href="{{$tp}}/css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <!--        <link href="{{$tp}}/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />-->
        <!-- Theme style -->
        <link href="{{$tp}}/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        {{ HTML::style('js/jquery-ui/css/start/jquery-ui-1.10.4.custom.css')}}
        {{ HTML::style('js/colorbox/colorbox.css')}} 

        {{ HTML::style('css/totop.css')}} 
        {{ HTML::style('css/mod.css')}} 

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-black" data-root="{{asset('/')}}">
        <!-- header logo: style can be found in header.less -->

        <section class="content">

            @yield('body')

            <a href="javascript:" id="return-to-top"><i class="ion ion-chevron-up"></i></a>

        </section>


        <!-- add new calendar event modal -->


        <!-- jQuery 2.0.2 -->
        <script src="{{$tp}}/js/jquery.min.js" type="text/javascript"></script>
        <!-- jQuery UI 1.10.3 -->
<!--        <script src="{{$tp}}/js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>-->
        {{ HTML::script(asset('js/jquery-ui/js/jquery-ui.js'))}}
        <!-- Bootstrap -->
        <script src="{{$tp}}/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- Morris.js charts -->
<!--        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>-->
<!--        <script src="{{$tp}}/js/plugins/morris/morris.min.js" type="text/javascript"></script>-->
        <!-- Sparkline -->
<!--        <script src="{{$tp}}/js/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>-->
        <!-- jvectormap -->
<!--        <script src="{{$tp}}/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>-->
<!--        <script src="{{$tp}}/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>-->
        <!-- fullCalendar -->
<!--        <script src="{{$tp}}/js/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>-->
        <!-- jQuery Knob Chart -->
<!--        <script src="{{$tp}}/js/plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script>-->
        <!-- daterangepicker -->
<!--        <script src="{{$tp}}/js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>-->
        <!-- Bootstrap WYSIHTML5 -->
<!--        <script src="{{$tp}}/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>-->
        <!-- iCheck -->
<!--        <script src="{{$tp}}/js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>-->

        <!-- AdminLTE App -->
        <script src="{{$tp}}/js/AdminLTE/app.js" type="text/javascript"></script>
        <script src="{{asset('/')}}js/piescript.js" type="text/javascript"></script>

        <script src="{{asset('/')}}js/totop.js"></script>  
        <script src="{{asset('/')}}js/colorbox/jquery.colorbox.js"></script>          
        <script src="{{asset('/')}}js/bootbox.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="{{asset('js/jquery-ui/js')}}/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript" src="{{asset('js/jquery-ui/js')}}/jquery-ui-timepicker-addon-i18n.min.js"></script>
        <script type="text/javascript">
$(".datepicker").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "dd-mm-yy"
});

var x = $(document).width();
if (x < 1000) {
    w1 = '98%';
} else {
    w1 = '990';
}

$(".newproject").colorbox({iframe: true, width: w1, height: "99%", overlayClose: true});
$(document).ready(function () {


});
        </script>



        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--        <script src="{{$tp}}/js/AdminLTE/dashboard.js" type="text/javascript"></script>        -->

        @yield('foot')
    </body>
    @yield('foot_meta')
</html>


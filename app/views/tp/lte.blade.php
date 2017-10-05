<?php
$tp = asset('themes/lte');
if(!isset($mn)){$mn='';}
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
        <link href="{{$tp}}/css/morris/morris.css" rel="stylesheet" type="text/css" />
        <!-- jvectormap -->
        <link href="{{$tp}}/css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
        <!-- fullCalendar -->
        <link href="{{$tp}}/css/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
        <!-- Daterange picker -->
        <link href="{{$tp}}/css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="{{$tp}}/css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="{{$tp}}/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{asset('themes/ace/assets/css/font-awesome.min.css')}}" />
        {{ HTML::style('js/jquery-ui/css/start/jquery-ui-1.10.4.custom.css')}}
        {{ HTML::style('js/colorbox/colorbox.css')}} 

        {{ HTML::style('css/totop.css')}} 
        {{ HTML::style('css/mod.css')}} 
        @yield('head_meta')

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="skin-black" data-root="{{asset('/')}}">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="{{asset('/')}}" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                <img class="w30 mt0" src="{{asset('images/pie.png')}}">
                PieIoT.com
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">

                        <!-- Notifications: style can be found in dropdown.less -->
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-warning"></i>
                                <span class="label label-warning">10</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 10 notifications</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <i class="ion ion-ios7-people info"></i> 5 new members joined today
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-warning danger"></i> Very long description here that may not fit into the page and may cause design problems
                                            </a>
                                        </li>

                                    </ul>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>                        
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>{{Session::get('cat.nickname')}} <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <img src="{{asset(Session::get('cat.avatar'))}}" class="img-circle" alt="User Image" />
                                    <p>
                                        {{Session::get('cat.nickname')}}
                                        <small>เข้าระบบเมื่อ {{dateth('d n ee H:i:s น.',strtotime(Session::get('cat.lastlogin')))}}</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="{{asset('logout?oldpath=login')}}" class="btn btn-default btn-flat btnlogout">Sign out</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="{{asset(Session::get('cat.avatar'))}}" class="img-circle" alt="User Image">
                        </div>
                        <div class="pull-left info">
                            <p>{{Session::get('cat.nickname')}}</p>

                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>

                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        
                        <li class="{{$mn=='pies'?'active':''}}">
                            <a href="{{asset('pies')}}">
                                <i class="fa fa-th"></i> <span>Pie Nodes</span> <small class="badge pull-right bg-green">{{Pies::countMyPie()}}</small>
                            </a>
                        </li>
                        <li class="{{$mn=='projects'?'active':''}}">
                            <a href="{{asset("projects")}}">
                                <i class="fa fa-dashboard"></i> <span>My Projects</span> <small class="badge pull-right bg-blue">{{Projects::countMyProject()}}</small>
                            </a>
                        </li>
                        <li class="{{$mn=='reports'?'active':''}}">
                            <a href="{{asset("reports")}}">
                                <i class="fa fa-bar-chart-o"></i> <span>Reports</span> <small class="badge pull-right bg-purple">{{Reports::countMyReport()}}</small>
                            </a>
                        </li>

                        
                        <li class="treeview {{$mn=='setup'?'active':''}}">
                            <a href="#">
                                <i class="fa fa-gears"></i>
                                <span>Setting</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{asset('admin/users')}}"><i class="fa fa-angle-double-right"></i> Users</a></li>
                            </ul>
                        </li>

                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    @section('page_header')
                    <h1>
                        Dashboard
                        <small>Control panel</small>
                    </h1>
                    @show
                    @section('breadcrumb')
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                    @show
                </section>

                <!-- Main content -->
                <section class="content">

                    @yield('body')

                    <a href="javascript:" id="return-to-top"><i class="ion ion-chevron-up"></i></a>

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->


        <!-- add new calendar event modal -->


        <!-- jQuery 2.0.2 -->
        <script src="{{$tp}}/js/jquery.min.js" type="text/javascript"></script>
        <!-- jQuery UI 1.10.3 -->
        <script src="{{$tp}}/js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <!-- Bootstrap -->
        <script src="{{$tp}}/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- Morris.js charts -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="{{$tp}}/js/plugins/morris/morris.min.js" type="text/javascript"></script>
        <!-- Sparkline -->
        <script src="{{$tp}}/js/plugins/sparkline/jquery.sparkline.min.js" type="text/javascript"></script>
        <!-- jvectormap -->
        <script src="{{$tp}}/js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js" type="text/javascript"></script>
        <script src="{{$tp}}/js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js" type="text/javascript"></script>
        <!-- fullCalendar -->
        <script src="{{$tp}}/js/plugins/fullcalendar/fullcalendar.min.js" type="text/javascript"></script>
        <!-- jQuery Knob Chart -->
        <script src="{{$tp}}/js/plugins/jqueryKnob/jquery.knob.js" type="text/javascript"></script>
        <!-- daterangepicker -->
        <script src="{{$tp}}/js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
<!--         Bootstrap WYSIHTML5 
        <script src="{{$tp}}/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>
         iCheck 
        <script src="{{$tp}}/js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>-->

        <!-- AdminLTE App -->
        <script src="{{$tp}}/js/AdminLTE/app.js" type="text/javascript"></script>
        <script src="{{asset('/')}}js/piescript.js" type="text/javascript"></script>

        <script src="{{asset('/')}}js/totop.js"></script>  
        <script src="{{asset('/')}}js/colorbox/jquery.colorbox.js"></script>  
        
        <script src="{{asset('/')}}js/bootbox.min.js" type="text/javascript"></script>
        <script type="text/javascript">
        $('.imcolorbox').colorbox();


var x = $(document).width();
if (x < 1000) {
    w1 = '98%';
} else {
    w1 = '820';
}
$(".newproject").colorbox({iframe: true, width: w1, height: "90%", overlayClose: true});
$(document).ready(function () {


});
        </script>



        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--        <script src="{{$tp}}/js/AdminLTE/dashboard.js" type="text/javascript"></script>        -->
        @yield('foot')
    </body>
    @yield('foot_meta')
</html>


<!DOCTYPE html>
<?php
$tp = asset('themes/ace');
$p = Input::get('p');
$mn = isset($mn) ? $mn : '';
?>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>
            @section('page_title')
            Admin Tools 
            @show</title>
        <!--basic styles-->
        @yield('head_meta')
        <link rel="shortcut icon" href="{{asset('images/home.ico')}}" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="{{$tp}}/assets/css/progress.css" rel="stylesheet" />
        <link href="{{$tp}}/assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="{{$tp}}/assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/jquery.gritter.css" />

        <link rel="stylesheet" href="{{$tp}}/assets/css/font-awesome.min.css" />
        {{ HTML::style('js/jquery-ui/css/start/jquery-ui-1.10.4.custom.css')}}
        {{ HTML::style('css/mod.css')}} 
        {{ HTML::style('js/colorbox/colorbox.css')}} 
        {{ HTML::style('js/msg_box/Styles/msgBoxLight.css')}} 
        <!--[if IE 7]>
          <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
        <![endif]-->
        <!--page specific plugin styles-->
        <!--fonts-->
        <link rel="stylesheet" href="{{$tp}}/assets/css/ace.min.css" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/ace-responsive.min.css" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/ace-skins.min.css" />
        <!--[if lte IE 8]>
          <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
        <![endif]-->
        <!--inline styles related to this page-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
    <body  data-root="{{asset('/')}}">
        <?php
        $hrd = HRDProfile::find(Session::get('cat.uid'));
        ?>
        <input name="myskin" id="myskin" type="hidden" value="{{Session::get('cat.skin')}}"> 
        <div class="navbar hidden-print">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a href="#" class="brand">
                        <img style="height: 20px; margin-top: -4px;" src="{{asset('images/andy.png')}}"/>
                        <small>
                            Admin Tools
                        </small>
                    </a><!--/.brand-->
                    <ul class="nav ace-nav pull-right">
                        <li class="light-blue">
                            <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                                <img class="nav-user-photo myavatar" src="{{asset(Session::get('cat.avatar'))}}" alt="" />
                                <span class="user-info">
                                    <small>Welcome,</small>
                                    {{Session::get('cat.nickname')}}
                                </span>
                                <i class="icon-caret-down"></i>
                            </a>
                            <ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">

                                <li><a href="{{asset('admin/profile')}}"> แก้ไขรูปส่วนตัว </a</li>

                                <li class="divider"></li>
                                <li>
                                    <a href="{{asset('logout')}}" onclick="">
                                        <i class="icon-off"></i>
                                        ออกจากระบบ
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul><!--/.ace-nav-->
                </div><!--/.container-fluid-->
            </div><!--/.navbar-inner-->
        </div>

        <div class="main-container container-fluid">
            <a class="menu-toggler" id="menu-toggler" href="#">
                <span class="menu-text"></span>
            </a>

            <div class="sidebar {{$hrd->menumin?'menu-min':''}}  hidden-print" id="sidebar">
                <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                    <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                        <span class="btn btn-success"></span>
                        <span class="btn btn-info"></span>
                        <span class="btn btn-warning"></span>
                        <span class="btn btn-danger"></span>
                    </div>
                </div><!--#sidebar-shortcuts-->

                <ul class="nav nav-list">
                    <li class="{{$p=='me'?'active':''}}">
                        <a href="{{asset('/')}}" class="">
                            <i class="icon-double-angle-left"></i>
                            <span class="menu-text ">
                                หน้าเว็บหลัก
                            </span>
                        </a>
                    </li>
                    @if(0)
                    <li class="{{$mn==''?'active':''}}">
                        <a href="{{asset('admin/car_list')}}" class="">
                            <i class="icon-home"></i>
                            <span class="menu-text ">
                                หน้าแรก
                            </span>
                        </a>
                    </li>
                    @endif
                    <li class="{{ in_array($mn,array('c','car_list'))?'active':''}}">
                        <a href="{{asset('admin/car_list')}}" class="">
                            <i class="icon-qrcode"></i>
                            <span class="menu-text">
                                รายการรถยนต์
                            </span>
                        </a>
                    </li>
                    <li class="{{ in_array($mn,array('c','users'))?'active':''}}">
                        <a href="{{asset('admin/users')}}" class="">
                            <i class="icon-user"></i>
                            <span class="menu-text">
                                สมาชิก
                            </span>
                        </a>
                    </li>

                </ul><!--/.nav-list-->
                <div class="sidebar-collapse" id="sidebar-collapseX" onclick="setmenumin()">                     
                    <i class="iconsidebar icon-double-angle-{{$hrd->menumin?'right':'left'}}"></i>
                </div>
            </div>
            <div class="main-content">
                <div class="breadcrumbs" id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li>
                            <i class="icon-home home-icon"></i>
                            <a href="{{asset('admin/car_list')}}">Home</a>
                            <span class="divider">
                                <i class="icon-angle-right arrow-icon"></i>
                            </span>
                        </li>
                        @yield('breadcrumbs')                        
                    </ul><!--.breadcrumb-->


                </div>
                <div class="page-content clearfix">                        
                    @yield('body')
                    <hr class="hr-dotted">
                    <div class="pull-right clearfix grey pr1 text-right">
                        <span class="pink">นครรถบ้าน</span><br/>
                        <span class="text-muted">@</span> nakhonrodbaan.com<br/> <span class="lighter">tel.</span>
                    </div>
                </div><!--/.page-content-->
                @if(0)
                <div class="ace-settings-container" id="ace-settings-container">
                    <div class="btn btn-app btn-mini btn-warning ace-settings-btn" id="ace-settings-btn">
                        <i class="icon-cog bigger-150"></i>
                    </div>

                    <div class="ace-settings-box" id="ace-settings-box">
                        <div>
                            <div class="pull-left">
                                <select id="skin-colorpicker" class="hide">
                                    <option data-class="default" value="#438EB9" />#438EB9
                                    <option data-class="skin-1" value="#222A2D" />#222A2D
                                    <option data-class="skin-2" value="#C6487E" />#C6487E
                                    <option data-class="skin-3" value="#D0D0D0" />#D0D0D0
                                </select>
                            </div>
                            <span>&nbsp; Choose Skin</span>
                        </div>

                        <div>
                            <input type="checkbox" class="ace-checkbox-2" id="ace-settings-header" />
                            <label class="lbl" for="ace-settings-header"> Fixed Header</label>
                        </div>

                        <div>
                            <input type="checkbox" class="ace-checkbox-2" id="ace-settings-sidebar" />
                            <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                        </div>

                        <div>
                            <input type="checkbox" class="ace-checkbox-2" id="ace-settings-breadcrumbs" />
                            <label class="lbl" for="ace-settings-breadcrumbs"> Fixed Breadcrumbs</label>
                        </div>

                        <div>
                            <input type="checkbox" class="ace-checkbox-2" id="ace-settings-rtl" />
                            <label class="lbl" for="ace-settings-rtl"> Right To Left (rtl)</label>
                        </div>
                    </div>
                </div><!--/#ace-settings-container-->
                @endif
            </div><!--/.main-content-->
        </div><!--/.main-container-->
        <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-small btn-inverse">
            <i class="icon-double-angle-up icon-only bigger-110"></i>
        </a>
        <!--/alert-->
        <div id="alertModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="p_title">HRD System</h3>
            </div>
            <div class="modal-body">
                <p id="p_msg">Are you sure you wish to delete?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">OK</button>
            </div>
        </div> 
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
        {{ HTML::script(asset('/').'js/newpost.js')}}
        {{ HTML::script('js/bootbox.min.js')}}
        <script src="{{$tp}}/assets/js/jquery.gritter.min.js"></script>
        <script src="{{$tp}}/assets/js/fuelux/fuelux.spinner.min.js"></script>

        <!--inline scripts related to this page-->
        <script src="{{$tp}}/assets/js/ace-elements.min.js"></script>
        <script src="{{$tp}}/assets/js/ace.min.js"></script>
        <script type="text/javascript" src="{{asset('js/jquery-ui/js')}}/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript" src="{{asset('js/jquery-ui/js')}}/jquery-ui-timepicker-addon-i18n.min.js"></script>
        @yield('foot') 
        <script type="text/javascript">
                    $(function () {
                      if ($(".datatables")[0]) {
                        $('.datatables').dataTable({
                          "iDisplayLength": 100,
                          "oLanguage": {
                            "sLengthMenu": "แสดง _MENU_ ต่อหน้า",
                            "sZeroRecords": "ไม่เจอข้อมูลที่ค้นหา",
                            "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                            "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 รายการ",
                            "sInfoFiltered": "(จากทั้งหมด _MAX_ รายการ)",
                            "sSearch": "ค้นหา :"
                          }
                        });
                      }
                      $(".datepicker").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: "dd-mm-yy"
                      });
                      $('.datetimepicker').datetimepicker({
                        timeFormat: 'HH:mm:ss',
                        dateFormat: 'dd-mm-yy',
                        stepHour: 2,
                        stepMinute: 10,
                        stepSecond: 10
                      });
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
                    $(document).ready(function () {
                      setskin($('#myskin').val());
                    });
        </script>
    </body>
    @yield('foot_meta')
</html>

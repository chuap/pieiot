<!DOCTYPE html>
<?php
$tp = asset('theme/ace');
$p = Input::get('p');
$hrd = HRDProfile::find(Session::get('cat.catid'));
if (Input::get('p') * 1 > 0) {
    $fix_sidebar = 'menu-min';
    $fix_sidebar_icon = 'right';
} else {
    $fix_sidebar = $hrd->menumin ? 'menu-min' : '';
    $fix_sidebar_icon = $hrd->menumin ? 'right' : 'left';
}
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
        <link rel="shortcut icon" href="{{asset('images/doc.ico')}}" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
        <link rel="stylesheet" href="{{asset('css/fonts.googleapis.css')}}" />
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
        $tp_count = EDoc::count_topic();
        $unread = EDoc::unread();
        $notification = EDoc::notification();
        ?>
        <input name="myskin" id="myskin" type="hidden" value="{{Session::get('cat.skin')}}"> 
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a href="#" class="brand">
                        <img style="height: 20px; margin-top: -4px;" src="{{asset('images/CAT-50.png')}}"/>
                        <small>
                            e-Document Plus
                        </small>
                    </a><!--/.brand-->

                    <ul class="nav ace-nav pull-right">
                        <li class="grey">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#" title="งานเข้า">
                                <i class="icon-cloud-download icon-animated-wrench"></i>
                                @if($tp_count['in']>0)
                                <span class="badge badge-grey">{{$tp_count['in']}}</span>
                                @endif
                            </a>

                            <ul style="width: 320px;" class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-closer">                                
                                @if($tp_count['in_all']>0)
                                <li class="nav-header">
                                    <i class="icon-ok"></i>
                                    {{$tp_count['in_all']}} งานเข้า 
                                </li>
                                @foreach(EDoc::assign_me('',10)as $d)                                
                                <li >
                                    <a href="{{asset('edoc/view?p='.$d->id)}}">
                                        <div class="clearfix">
                                            <span class="pull-left overflow-hidden" style="width: 85%">
                                                <i title="{{$d->type_name}}" class="{{$d->icon}} {{$d->color}} icon-large"></i>
                                                {{$d->etitle}}
                                            </span>
                                            <span class="pull-right font10">{{timepost_shot($d->date_create)}}</span>
                                        </div>
                                    </a>
                                </li>
                                @endforeach
                                <li>
                                    <a href="{{asset('edoc/assign_me')}}">
                                        ดูงานเข้าทั้งหมด
                                        <i class="icon-arrow-right"></i>
                                    </a>
                                </li>
                                @else
                                <li class="nav-header clearfix">
                                    ไม่มีงานเข้า
                                </li>
                                <li class="clearfix">
                                    <a href="{{asset('edoc/new')}}"><i class="icon-plus-sign"></i> สร้างใหม่ </a>                                    
                                </li>
                                @endif


                            </ul>
                        </li>

                        <li class="purple">
                            <a data-toggle="dropdown" class="dropdown-toggle" onclick="setlastnotification()"  href="#" title="การแจ้งเตือน">
                                <i class="icon-bell-alt icon-animated-bell"></i>
                                @if(count(EDoc::notification('unread'))>0)
                                <span class="badge badge-important">{{count(EDoc::notification('unread'))}}</span>
                                @endif
                            </a>
                            <ul style="width: 320px;" class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-closer">
                                <li class="nav-header">
                                    <i class="icon-bell-alt"></i>
                                    {{count($notification)}} Notifications
                                </li>
                                <?php $ii = 0; ?>
                                @foreach($notification as $d)
                                @if(++$ii<11)
                                <li >
                                    <a href="{{asset('edoc/view?p='.$d->id)}}" >
                                        <div class="clearfix">
                                            <div class="pull-left" style="">
                                                <img class="w20" src="{{asset(HRDProfile::gen_profile($d->cat)->avatar)}}" />

                                            </div>
                                            <div class="pull-left overflow-hidden pl1" style="width: 230px;">
                                                <span class="text-info">{{$d->nickname}}</span>
                                                @if($d->like=='like')
                                                <span class="pink"><i class="icon-thumbs-up-alt bigger-110"></i> ชอบ</span>
                                                @elseif($d->like=='agree')
                                                <span class="green"><i class="icon-check bigger-110"></i> เห็นด้วย</span>                                                
                                                @elseif($d->like=='disagree')
                                                <span class="red"><i class="icon-remove-circle bigger-110"></i> ไม่เห็นด้วย</span>                                                
                                                @elseif($d->like=='apcept')
                                                <span class="text-info"><i class="icon-ok bigger-110"></i> รับทราบ</span>                                                
                                                @elseif($d->like=='msg')
                                                <span class="text-primary"><i class="icon-comments bigger-110"></i> แสดงความคิดเห็น</span>                                                
                                                @endif
                                                <div><span class="lighter">{{$d->etitle}}</span></div>
                                            </div>
                                            <span class="pull-right font10">{{timepost_shot($d->like_date)}}</span>
                                        </div>
                                    </a>
                                </li>
                                @endif
                                @endforeach
                                <li>
                                </li>
                            </ul>
                        </li>

                        <li class="green">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#" title="เรื่องใหม่">
                                <i class="icon-file-text-alt icon-animated-vertical"></i>
                                @if(count($unread)>0)
                                <span class="badge badge-success">{{count($unread)}}</span>
                                @endif
                            </a>

                            <ul style="width: 320px;" class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-closer">
                                <li class="nav-header">
                                    <i class="icon-file-text-alt"></i>
                                    {{count($unread)}} เรื่องใหม่ <small>(ย้อยหลัง 1 เดือน)</small>
                                </li>
                                <?php $ii = 0; ?>
                                @foreach($unread as $d)
                                @if(++$ii<11)
                                <li >
                                    <a href="{{asset('edoc/view?p='.$d->id)}}">
                                        <div class="clearfix">
                                            <span class="pull-left overflow-hidden" style="width: 85%">
                                                <i title="{{$d->type_name}}" class="{{$d->icon}} {{$d->color}} icon-large"></i>
                                                {{$d->etitle}}
                                            </span>
                                            <span class="pull-right font10">{{timepost_shot($d->date_create)}}</span>
                                        </div>
                                    </a>
                                </li>
                                @endif
                                @endforeach
                                <li>
                                    <a href="{{asset('edoc/all')}}">
                                        ดูเรื่องทั้งหมด
                                        <i class="icon-arrow-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </li>

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

                                <li>
                                    <a href="{{asset('edoc/profile')}}">
                                        <i class="icon-user"></i>
                                        แก้ไขรูปส่วนตัว
                                    </a>
                                </li>

                                <li class="divider"></li>

                                <li>
                                    <a href="javascript:" onclick="hrdlogout('edoc/login');">
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

            <div class="sidebar {{$fix_sidebar}}" id="sidebar">
                <div class="sidebar-shortcuts" id="sidebar-shortcuts">
                    <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
                        <a href="{{asset('edoc')}}" class="btn btn-small btn-success" title="หน้าหลัก">
                            <i class="icon-home icon-large"></i>
                        </a>
                        <a href="{{asset('edoc/new')}}" class="btn btn-small btn-info" title="สร้างเอกสารใหม่">
                            <i class="icon-pencil"></i>
                        </a>
                        <a href="{{asset('edoc/calendar')}}" class="btn btn-small btn-warning" title="ปฏิทิน">
                            <i class="icon-calendar"></i>
                        </a>

                        <a href="{{asset('edoc/profile')}}" class="btn btn-small btn-danger" title="ตั้งค่าระบบ">
                            <i class="icon-gears"></i>
                        </a>
                    </div>

                    <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
                        <span class="btn btn-success"></span>
                        <span class="btn btn-info"></span>
                        <span class="btn btn-warning"></span>
                        <span class="btn btn-danger"></span>
                    </div>
                </div><!--#sidebar-shortcuts-->

                <ul class="nav nav-list">   
                    <li class="{{!$mn||$mn=='timeline'?'active':''}}">
                        <a href="{{asset('edoc/timeline')}}" class="">
                            <i class="icon-time purple"></i>
                            <span class="menu-text ">
                                e-Doc Timeline
                            </span>
                        </a>
                    </li> 
                    <li class="{{$p=='me'?'active':''}}">
                        <a href="{{asset('edoc/all?p=me')}}" class="">
                            <i class="icon-user orange"></i>
                            <span class="menu-text ">
                                My Document
                                @if(isset($tp_count['me']))
                                <span class="badge badge-light">{{$tp_count['me']}}</span>
                                @endif
                            </span>
                        </a>
                    </li>              
                    <li class="{{$mn=='assign_me'?'active':''}}">
                        <a href="{{asset('edoc/assign_me')}}" class="">

                            <i class="icon-cloud-download red"></i>

                            <span class="menu-text ">
                                งานเข้า
                                @if(($tp_count['in'])>0)
                                <span class="badge badge-important">{{$tp_count['in']}}</span>
                                @else
                                <span class="badge badge-light">{{$tp_count['in_all']}}</span>
                                @endif
                            </span>
                        </a>
                    </li>
                    <li class="{{$mn=='assign_to'?'active':''}}">
                        <a href="{{asset('edoc/assign_to')}}" class="">

                            <i class="icon-cloud-upload green"></i>

                            <span class="menu-text ">
                                มอบหมายงาน
                                @if(isset($tp_count['out']))
                                <span class="badge badge-light">{{$tp_count['out']}}</span>
                                @endif
                            </span>
                        </a>
                    </li>
                    <li class="{{$mn=='tag_me'?'active':''}}">
                        <a href="{{asset('edoc/tag_me')}}" class="">

                            <i class="icon-tag purple"></i>

                            <span class="menu-text ">
                                Tag Me
                                @if($tp_count['mail_new']>0)
                                <span class="badge badge-purple">{{$tp_count['mail_new']}}</span>
                                @elseif(isset($tp_count['mail']))
                                <span class="badge badge-light">{{$tp_count['mail']}}</span>
                                @endif
                            </span>
                        </a>
                    </li>
                    <li class="{{$p&&$p!='me'?'active':''}} default ">
                        <a href="{{asset('edoc/all')}}" class="dropdown-toggle">
                            <i class="icon-sitemap"></i>
                            <span class="menu-text ">
                                เอกสารทั้งหมด
                            </span>
                            <b class="arrow icon-angle-down"></b>
                        </a>
                        @if(0)
                        <span class="badge badge-light">{{$tp_count['all']}}</span>
                        @endif
                        <ul class="submenu" style="display: block;">
                            @foreach(EDocType::getType()as $d)
                            <li class="{{$p==$d->type_id?'active':''}}">
                                <a href="{{asset('edoc/all?p='.$d->type_id)}}" class="">
                                    <i class="{{$d->icon}} {{$d->color}}"></i>
                                    <span class="menu-text ">
                                        {{$d->type_name}}
                                        @if(isset($tp_count[$d->type_id]))
                                        <span class="badge badge-{{$d->css}}">{{$tp_count[$d->type_id]}}</span>
                                        @endif
                                    </span>
                                </a>
                            </li>
                            @endforeach  
                        </ul>
                    </li>


                    <li>
                        <a href="#" class="dropdown-toggle">
                            <i class="icon-bar-chart"></i>
                            <span class="menu-text">
                                Reports
                            </span>
                            <b class="arrow icon-angle-down"></b>
                        </a>
                        <ul class="submenu">
                            <li>
                                <a href="error-404.html">
                                    <i class="icon-double-angle-right"></i>
                                    Error 404
                                </a>
                            </li>
                        </ul>
                    </li>


                </ul><!--/.nav-list-->

                <div class="sidebar-collapse" id="sidebar-collapseX" onclick="setmenumin()">                     
                    <i class="iconsidebar icon-double-angle-{{$fix_sidebar_icon}}"></i>
                </div>
            </div>

            <div class="main-content">
                <div class="breadcrumbs" id="breadcrumbs">
                    <ul class="breadcrumb">
                        <li>
                            <i class="icon-home home-icon"></i>
                            <a href="{{asset('edoc')}}">Home</a>
                            <span class="divider">
                                <i class="icon-angle-right arrow-icon"></i>
                            </span>
                        </li>
                        @yield('breadcrumbs')                        
                    </ul><!--.breadcrumb-->

                    <div class="nav-search" id="nav-search">
                        <form class="form-search" action="{{asset('edoc/all')}}" method="get" >
                            <span class="input-icon">
                                <input type="text" placeholder="Search ..." class="input-small nav-search-input" name="find" id="find" autocomplete="off" />
                                <i class="icon-search nav-search-icon"></i>
                            </span>
                        </form>
                    </div><!--#nav-search-->
                </div>
                <div class="page-content clearfix">                        
                    @yield('body')
                    <hr class="hr-dotted">
                    <div class="pull-right clearfix grey pr1 text-right">
                        <span class="pink">ฝ่ายพัฒนาทรัพยากรบุคคล(พบ.) โทรสาร 7420</span><br/>
                        <span class="text-muted">พัฒนาโดย</span> ส่วนศูนย์การเรียนรู้และสื่อบริการ<br/> <span class="lighter">prachuap.k@cattelecom.com</span>
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
        {{ HTML::script('js/bootbox.min.js')}}
        
        <script src="{{$tp}}/assets/js/jquery.gritter.min.js"></script>
        <script src="{{$tp}}/assets/js/fuelux/fuelux.spinner.min.js"></script>
        @yield('foot') 
        <!--inline scripts related to this page-->
        <script src="{{$tp}}/assets/js/ace-elements.min.js"></script>
        <script src="{{$tp}}/assets/js/ace.min.js"></script>
        <script type="text/javascript" src="{{asset('js/jquery-ui/js')}}/jquery-ui-timepicker-addon.js"></script>
        <script type="text/javascript" src="{{asset('js/jquery-ui/js')}}/jquery-ui-timepicker-addon-i18n.min.js"></script>
        <script type="text/javascript">
                    $(function () {
//                        $('.datatables').dataTable({
//                            "iDisplayLength": 100
//                        });
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

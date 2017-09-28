<!DOCTYPE html>
<?php
$tp = asset('themes/ace');
?>
<html lang="en">
    <head>
        <!--##login##-->
        <meta charset="utf-8" />
        <title>Login Page - HRD</title>
        <meta name="description" content="User login page" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <!--basic styles-->
        <link href="{{$tp}}/assets/css/bootstrap.min.css" rel="stylesheet" />
        <link href="{{$tp}}/assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/font-awesome.min.css" />
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
        {{ HTML::style('js/colorbox/colorbox.css')}} 
        {{ HTML::style('js/msg_box/Styles/msgBoxLight.css')}} 

        <!--[if lte IE 8]>
          <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
        <![endif]-->

        <!--inline styles related to this page-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

    <body class="login-layout" data-root="{{asset('/')}}">
        <div class="main-container container-fluid">
            <div class="main-content">
                <div class="row-fluid">
                    <div class="span12">
                        <div class="login-container">
                            <div class="row-fluid">
                                <div class="center">
                                    <h1>
                                        
                                        <span class="white">Nakhon</span>
                                        <span class="red">RodBann</span>
                                    </h1>
                                    <h4 class="blue">&copy; นครรถบ้าน</h4>
                                </div>
                            </div>
                            <div class="space-6"></div>
                            <div class="row-fluid">
                                <div class="position-relative">
                                    <div id="login-box" class="login-box visible widget-box no-border">
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                
                                                <h4 class="header blue lighter bigger">
                                                    <i class="icon-coffee green"></i>
                                                    ระบุรหัสผู้ใช้งานและรหัสผ่าน
                                                </h4>

                                                <div class="space-6"></div>

                                                <form class="" onsubmit="hrdloginedoc();" id="f1" action="javascript:" method="post" role="form">
                                                    <?php
                                                    $k = md5(date('YmddmY'));
                                                    ?>
                                                    <fieldset>
                                                        <label>
                                                            <span class="block input-icon input-icon-right">
                                                                <input  name="tuname"  id="tuname" type="text" class="span12" placeholder="รหัสพนักงาน" />
                                                                <i class="icon-user"></i>
                                                            </span>
                                                        </label>

                                                        <label>
                                                            <span class="block input-icon input-icon-right">
                                                                <input name="tpwd" id="tpwd" type="password" class="span12" placeholder="รหัสผ่าน" />
                                                                <i class="icon-lock"></i>
                                                            </span>
                                                        </label>
                                                        <i class="icon-star blue"></i>
                                                        Admin Tools
                                                        <div class="space"></div>

                                                        <div class="clearfix">
                                                            @if(0)
                                                            <label class="inline">
                                                                <input type="checkbox" />
                                                                <span class="lbl"> Remember Me</span>
                                                            </label>
                                                            @endif
                                                            <div id="divload" class="hidden text-center">
                                                                <img src="{{asset('images/loading.gif')}}" style="width: 30px;">    
                                                                กำลังดำเนินการ...             
                                                            </div>

                                                            <button onclick="" type="submit" class="width-35 pull-right btn btn-small btn-primary">
                                                                <i class="icon-key"></i>
                                                                Login
                                                            </button>
                                                            <input name='k' type='hidden' id='k' value='{{$k}}' />
                                                            <input name='oldpath' type='hidden' id='oldpath' value='edoc' />
                                                        </div>

                                                        <div class="space-4"></div>
                                                    </fieldset>
                                                </form>


                                            </div><!--/widget-main-->

                                            <div class="toolbar clearfix">
                                                @if(0)
                                                <div>
                                                    <a href="{{asset('/')}}" class="forgot-password-link">
                                                        <i class="icon-arrow-left"></i>
                                                        เว็บไซต์ พบ.
                                                    </a>
                                                </div>
                                                <div>

                                                    <a href="http://hrd.cattelecom.com/script/forms/regist.php?callurl={{asset('edoc/login')}}&action=close" class="user-signup-link">
                                                        ลงทะเบียนเข้าใช้งาน
                                                        <i class="icon-arrow-right"></i>
                                                    </a>
                                                </div>
                                                
                                                <div>
                                                    <a href="#" onclick="show_box('forgot-box');
                                                            return false;" class="forgot-password-link">
                                                        <i class="icon-arrow-left"></i>
                                                        I forgot my password
                                                    </a>
                                                </div>

                                                <div>
                                                    <a href="#" onclick="show_box('signup-box');
                                                            return false;" class="user-signup-link">
                                                        I want to register
                                                        <i class="icon-arrow-right"></i>
                                                    </a>
                                                </div>
                                                @endif
                                            </div>
                                        </div><!--/widget-body-->
                                    </div><!--/login-box-->


                                </div><!--/position-relative-->
                            </div>
                        </div>
                    </div><!--/.span-->
                </div><!--/.row-fluid-->
            </div>
        </div><!--/.main-container-->
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
        <script type="text/javascript">
                    window.jQuery || document.write("<script src='assets/js/jquery-2.0.3.min.js'>" + "<" + "/script>");
        </script>
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

        <!--ace scripts-->

        <script src="{{$tp}}/assets/js/ace-elements.min.js"></script>
        <script src="{{$tp}}/assets/js/ace.min.js"></script>
        {{ HTML::script('js/colorbox/jquery.colorbox.js')}} 
        {{ HTML::script('js/msg_box/Scripts/jquery.msgBox.js')}}
        {{ HTML::script(asset('/').'js/action.js')}}

        <!--inline scripts related to this page-->

        <script type="text/javascript">
            $(".regist").colorbox({iframe: true, width: "800px", height: "540px", overlayClose: false});
            function show_box(id) {
                $('.widget-box.visible').removeClass('visible');
                $('#' + id).addClass('visible');
            }
            
        </script>
    </body>
</html>

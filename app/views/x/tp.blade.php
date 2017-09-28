<?php
$tp = asset('theme/admin');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Core CSS - Include with every page -->
        <link href="{{$tp}}/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{$tp}}/font-awesome/css/font-awesome.css" rel="stylesheet">
        <!-- Page-Level Plugin CSS - Blank -->
        <!-- SB Admin CSS - Include with every page -->
        <link href="{{$tp}}/css/sb-admin.css" rel="stylesheet">
        {{ HTML::style('css/mod.css')}}    
        {{ HTML::style('js/colorbox/colorbox.css')}} 
        {{ HTML::style('js/msg_box/Styles/msgBoxLight.css')}} 	
        {{ HTML::style('js/jquery-ui/css/start/jquery-ui-1.10.4.custom.css')}} 

        @yield('head_meta')

        <title>HRD Admin</title>
    </head>    
    <body  data-root="{{asset('/')}}">       
        <?php
        if (!isset($p)) {
            $p = 0;
        }
        if (!isset($mn) || ($mn == 'mysection')) {
            $mn = 'courseinfo';
        }
        $y_list = excsql("select y from followup_h where section='" . Session::get('cat.section') . "' group by y order by y desc");
        $yc = 0;
        ?>
        <a id="gtotop" class="btn btn-warning" href="javascript:"><span class="glyphicon glyphicon-arrow-up"></span>Top</a>  
        <div id="wrapper" class="">
            <nav class="navbar navbar-default navbar-fixed-top hidden-print " role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="/" class="navbar-brand logo">e-Doc</a>



                </div>
                <nav class="collapse navbar-collapse" role="navigation">

                    <ul class="nav navbar-nav">
                        <li>
                            <a href="#"><i class="glyphicon glyphicon-home"></i> Home</a>
                        </li>
                        <li>
                            <a href="#postModal" role="button" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Post</a>
                        </li>
                        <li>
                            <a href="#"><span class="badge">badge</span></a>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-user"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="">More</a></li>
                                <li><a href="">More</a></li>
                                <li><a href="">More</a></li>
                                <li><a href="">More</a></li>
                                <li><a href="">More</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- /.navbar-top-links -->

                <div class="navbar-default navbar-static-side" role="navigation">
                    <div class="sidebar-collapse">
                        <ul class="nav" id="side-menu">
                            <li class="sidebar-search">
                                <div class="input-group custom-search-form">
                                    <input id="tfind" type="text" onkeypress="return check_key(event)" class="form-control" placeholder="Search...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button" onclick="findst()">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </div>
                                <!-- /input-group -->
                            </li> 
                            <li class="">
                                <a class="" href="{{asset('followup')}}"><i class="fa fa-home fa-fw"></i> หน้าหลัก Training Followup</a>
                            </li>
                            @foreach($y_list as $d1)
                            <?php
                            $yc++;
                            ?>
                            <li class="<?php
                            if ($d1->y == Followup::getconf('y') || 1) {
                                echo 'active';
                            }
                            ?>">
                                <a href="{{asset('followup/mysection')}}" ><i class="fa fa-folder-open fa-fw "></i> หลักสูตรติดตามผล {{$d1->y+543}}<span class="fa arrow"></span></a>
                                <ul class="nav nav-second-level ">
                                    @foreach (Followup::mysection_y(Session::get('cat.section'),$d1->y) as $nn)   
                                    <li class="">
                                        <a class="f12 <?php
                            if ($p == $nn->id) {
                                echo ' textuline green';
                            }
                            ?>" href="{{asset('followup/'.$mn.'?p='.$nn->id)}}"><i style="margin-left: -14px;" class="fa fa-arrow-circle-o-right"></i> {{$nn->cname}} <small class="red">{{$nn->classname?'รุ่น '.$nn->classname:''}}</small></a>
                                    </li>
                                    @endforeach   
                                </ul>
                                <!-- /.nav-second-level -->
                            </li>  
                            @endforeach

                            @if((in_array(Session::get('cat.uclass'), array("admin")))||(Obj::isAdmin('followup','logs')))
                            <li class="">
                                <a class="" href="{{asset('followup/logs')}}"><i class="fa fa-exclamation-triangle"></i> Log(s)</a>
                            </li>
                            @endif
                        </ul>
                        <!-- /#side-menu -->
                    </div>
                    <!-- /.sidebar-collapse -->
                </div>
                <!-- /.navbar-static-side -->
            </nav>

            <div id="page-wrapper">                
                <div class="row">
                    <div class="col-lg-12">                    
                        @yield('body') 
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /#page-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Core Scripts - Include with every page -->
        <script src="{{$tp}}/js/jquery-1.10.2.js"></script>
        <script src="{{$tp}}/js/bootstrap.min.js"></script>
        <script src="{{$tp}}/js/plugins/metisMenu/jquery.metisMenu.js"></script>        

        <!-- SB Admin Scripts - Include with every page -->
        <script src="{{$tp}}/js/sb-admin.js"></script>
        <script src="{{$tp}}/js/plugins/dataTables/jquery.dataTables.js"></script>
        <script src="{{$tp}}/js/plugins/dataTables/dataTables.bootstrap.js"></script>


        {{ HTML::script('js/colorbox/jquery.colorbox.js')}} 
        {{ HTML::script('js/msg_box/Scripts/jquery.msgBox.js')}}

        <!-- Page-Level Demo Scripts - Blank - Use for reference -->
        <script>
                                            $(document).ready(function () {

                                                $(".popup").colorbox({iframe: true, width: "90%", height: "95%", overlayClose: false});
                                                $(".popup_w").colorbox({iframe: true, width: "900", height: "95%", overlayClose: false});
                                                $(".popup_95").colorbox({iframe: true, width: "95%", height: "100%", overlayClose: false});
                                                $(".popup_full").colorbox({iframe: true, width: "100%", height: "100%", overlayClose: false});
                                                $(".popup_confirm").colorbox({iframe: true, width: "550", height: "480", overlayClose: false});

                                                $(".popup_700").colorbox({iframe: true, width: "700", height: "500", overlayClose: false});
                                                $(".popup_add").colorbox({iframe: true, width: "900", height: "95%", overlayClose: false});
                                                $('.datatables').dataTable({
                                                    "iDisplayLength": 50
                                                });
                                            });
                                            function findst() {
                                                exit();
                                                if ($("#tfind").val()) {
                                                    window.location.href = "{{asset('/cert/find')}}/" + $("#tfind").val();
                                                }
                                                else {
                                                    $("#tfind").focus()
                                                }
                                            }
                                            function check_key(e) {
                                                if (e.keyCode == 13) {
                                                    findst();
                                                    return false;
                                                }
                                            }
                                            function findexp() {
                                                if ($("#date_exp").val()) {
                                                    window.location.href = "{{asset('/cert/exp')}}?d=" + $("#date_exp").val();
                                                }
                                                else {
                                                    $("#date_exp").focus();
                                                }
                                            }
                                            function keyfindexp(e) {
                                                if (e.keyCode == 13) {
                                                    findexp();
                                                    return false;
                                                }
                                            }
        </script>
        {{ HTML::script('js/jquery-ui/js/jquery-ui-1.10.4.custom.js')}} 
        {{ HTML::script('js/bootscript.js')}}
        @yield('foot') 

    </body>
    @yield('foot_meta')

</html>


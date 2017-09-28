<!-- === BEGIN HEADER === -->
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<?php
$tp = asset('themes/enl');
?>
<html lang="en">
    <!--<![endif]-->
    <head>
        <!-- Title -->
        <title>
            @section('page_title')
            Test
            @show</title>
        <!--basic styles-->
        @yield('head_meta')
        <!-- Meta -->
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <!-- Favicon -->
        <link href="{{asset('images/car-2.ico')}}" rel="shortcut icon">
        <!-- Bootstrap Core CSS -->
        <link rel="stylesheet" href="{{$tp}}/assets/css/ace.min.css" />
        <link rel="stylesheet" href="{{$tp}}/assets/css/bootstrap.css" rel="stylesheet">
        <!-- Template CSS -->
        <link rel="stylesheet" href="{{$tp}}/assets/css/animate.css" rel="stylesheet">
        <link rel="stylesheet" href="{{$tp}}/assets/css/font-awesome.css" rel="stylesheet">
        <link rel="stylesheet" href="{{$tp}}/assets/css/nexus.css" rel="stylesheet">
        <link rel="stylesheet" href="{{$tp}}/assets/css/responsive.css" rel="stylesheet">
        <link rel="stylesheet" href="{{$tp}}/assets/css/custom.css" rel="stylesheet">
        {{ HTML::style('css/mod.css')}} 
        <!-- Google Fonts-->
        <link href="https://fonts.googleapis.com/css?family=Maitree|Taviraj|Trirong" rel="stylesheet">
        <link href="http://fonts.googleapis.com/css?family=Roboto:400,300" rel="stylesheet" type="text/css">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div id="body_bg">
            <div id="pre_header" class="container">
                <div class="row margin-top-10 visible-lg">
                    <div class="col-md-6">
                        <p>
                            <strong>Phone:</strong>&nbsp;xxx</p>
                    </div>
                    <div class="col-md-6 text-right">
                        <p>
                            <strong>Email:</strong>info@example.com</p>
                    </div>
                </div>
            </div>
            <div class="primary-container-group">
                <!-- Background -->
                <div class="primary-container-background">
                    <div class="primary-container"></div>
                    <div class="clearfix"></div>
                </div>
                <!--End Background -->
                <div class="primary-container">
                    <div id="header" class="container">
                        <div class="row">
                            <!-- Logo -->
                            <div class="logo">
                                <a href="{{asset('/')}}" title="">
                                    <img src="{{$tp}}/assets/img/logo.png" alt="Logo" />
                                </a>
                            </div>
                            <!-- End Logo -->
                            <ul class="social-icons pull-right hidden-xs">                                
                                <li class="social-facebook">
                                    <a href="#" target="_blank" title="Facebook"></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Top Menu -->
                    <div id="hornav" class="container no-padding">
                        <div class="row">
                            <div class="col-md-12 no-padding">
                                <div class="pull-right visible-lg">
                                    <ul id="hornavmenu" class="nav navbar-nav">
                                        <li>
                                            <a href="{{asset('/')}}" class="fa-home">หน้าหลัก</a>
                                        </li>

                                        <li>
                                            <a  class="fa-car">รายการรถ</a>
                                            <ul>
                                                @foreach (Car::brand_list_eb() as $d)
                                                <li class="parent">
                                                    <a href="{{asset('brands/'.$d->brandname)}}">{{$d->brandname}}</a>
                                                    <ul>
                                                        @foreach (Car::model_by_brand($d->bid) as $d2)
                                                        <li>
                                                            <a href="{{asset($d->brandname.'/'.$d2->car_model)}}">{{$d2->car_model}}</a>
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </li>


                                        <li>
                                            <a href="{{asset('pages/about')}}" class="fa-phone">ติดต่อเรา</a>
                                        </li>

                                        @if(Session::get('cat.islogin'))
                                        <li>
                                            <a class="fa-gears">Admin</a>
                                            <ul>
                                                <li>
                                                    <a href="{{asset('admin/car_list')}}">รายการรถยนต์</a>
                                                </li>
                                                <li>
                                                    <a href="{{asset('logout')}}">Sign Out</a>
                                                </li>
                                            </ul>
                                        </li>
                                        @else
                                        <li>
                                            <a href="{{asset('login')}}" class="fa-key">Sign in</a>
                                        </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Top Menu -->
                    <!-- === END HEADER === -->
                    <!-- === BEGIN CONTENT === -->
                    
                    @yield('beforbody')
                    <div id="content">
                        <div class="container">
                            @yield('body')
                        </div>
                    </div>
                    @yield('afterbody')
                </div>
            </div>

            <!-- === END CONTENT === -->
            <!-- === BEGIN FOOTER === -->
            <div id="base">
                <div class="container padding-vert-30 margin-top-40">
                    <div class="row">
                        <!-- Sample Menu -->
                        <div class="col-md-6 margin-bottom-20">
                            <h3 class="margin-bottom-10">Sample Menu</h3>
                            <ul class="menu">
                                <li>
                                    <a class="fa-tasks" href="#">Placerat facer possim</a>
                                </li>
                                <li>
                                    <a class="fa-users" href="#">Quam nunc putamus</a>
                                </li>
                                <li>
                                    <a class="fa-signal" href="#">Velit esse molestie</a>
                                </li>
                                <li>
                                    <a class="fa-coffee" href="#">Nam liber tempor</a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <!-- End Sample Menu -->
                        <!-- Contact Details -->
                        <div class="col-md-6 margin-bottom-20">
                            <h3 class="margin-bottom-10">Contact Details</h3>
                            <p>
                                <span class="fa-phone">Telephone:</span>(212)888-77-88
                                <br>
                                <span class="fa-envelope">Email:</span>
                                <a href="mailto:info@joomla51.com">info@joomla51.com</a>
                                <br>
                                <span class="fa-link">Website:</span>
                                <a href="http://www.joomla51.com">www.joomla51.com</a>
                            </p>
                            <p>The Dunes, Top Road,
                                <br>Strandhill,
                                <br>Co. Sligo,
                                <br>Ireland</p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
            <!-- Footer Menu -->
            <div id="footer">
                <div class="container">
                    <div class="row">
                        <div id="copyright" class="col-md-4">
                            <p>Your Copyright Info</p>
                        </div>
                        <div id="footermenu" class="col-md-8">
                            <ul class="list-unstyled list-inline pull-right">
                                <li>
                                    <a href="#" target="_blank">Sample Link</a>
                                </li>
                                <li>
                                    <a href="#" target="_blank">Sample Link</a>
                                </li>
                                <li>
                                    <a href="#" target="_blank">Sample Link</a>
                                </li>
                                <li>
                                    <a href="#" target="_blank">Sample Link</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Footer Menu -->
            <!-- JS -->
            <script type="text/javascript" src="{{$tp}}/assets/js/jquery.min.js" type="text/javascript"></script>

            @yield('foot')

            <script type="text/javascript" src="{{$tp}}/assets/js/bootstrap.min.js" type="text/javascript"></script>
            <script type="text/javascript" src="{{$tp}}/assets/js/scripts.js"></script>
            <!-- Isotope - Portfolio Sorting -->
            <script type="text/javascript" src="{{$tp}}/assets/js/jquery.isotope.js" type="text/javascript"></script>
            <!-- Mobile Menu - Slicknav -->
            <script type="text/javascript" src="{{$tp}}/assets/js/jquery.slicknav.js" type="text/javascript"></script>
            <!-- Animate on Scroll-->
            <script type="text/javascript" src="{{$tp}}/assets/js/jquery.visible.js" charset="utf-8"></script>
            <!-- Sticky Div -->
            <script type="text/javascript" src="{{$tp}}/assets/js/jquery.sticky.js" charset="utf-8"></script>
            <!-- Slimbox2-->
            <script type="text/javascript" src="{{$tp}}/assets/js/slimbox2.js" charset="utf-8"></script>
            <!-- Modernizr -->
            <script src="{{$tp}}/assets/js/modernizr.custom.js" type="text/javascript"></script>
            <!-- End JS -->

    </body>

</html>
<!-- === END FOOTER === -->



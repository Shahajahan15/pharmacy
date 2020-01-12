<!doctype html>
<html lang="en">
<head>
    @include('layout/_header')
    @yield('header')
</head>
<body class="skin-blue">
<header class="header hidden-print">
    <input type="hidden" name="site-url-hidden-field" id="site-url-hidden-field" value="{{site_url()}}'/admin/'">
    <input type="hidden" name="env_var" id="env_var" value="{{ENVIRONMENT}}">
    <a href="{{site_url()}}/admin/content" class="logo-link">
        <img id="logo-img" class="logo img-responsive" src="{!! base_url('logo-side.png')  !!}" />
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
                <!-- Messages: style can be found in dropdown.less-->

                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="glyphicon glyphicon-user"></i>
                        <span>{{current_user()->username}}<i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            @if($empImg = current_session()->userdata('image_profile'))
                                <img src="{{img_path()}}employee_img/{{$empImg}}" class="img-circle" alt="User Image" />
                            @else
                                <img src="{{img_path()}}profile/empty-people.png" class="img-circle" alt="User Image" />
                            @endif
                            <p>
                            <div>{{current_user()->display_name}}</div>
                            <small>{{current_user()->email}}</small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{!! site_url(SITE_AREA . '/settings/users/edit') !!}" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{!! site_url('logout') !!}" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

    <div class="wrapper row-offcanvas row-offcanvas-left hidden-print">
        <!-- Left side column. contains the logo and sidebar -->
        @include('layout/_left_nav')

        <!-- Right side column. Contains the navbar and content of the page -->
        <aside class="right-side">
            @include('layout/_content_header')

            <section class="content container-min-height">
                <div id="messages" style="display: none;">
                    <div class='alert alert-block fade in notification'><a data-dismiss='alert' class='close' href='#'>×</a><div id="text"></div></div>
                </div>
                {!! Template::message() !!}

                @yield('content')

                @if(isset($content))
                    {!! $content !!}
                @else
                    {!! Template::content() !!}
                @endif
            </section>
        </aside>
    </div><!-- ./wrapper -->

    @include('layout/_footer')
</body>
</html>
<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar hidden-print">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                @if ($empImg = current_session()->userdata('image_profile'))
                    <img src="{{img_path()}}employee_img/{{$empImg}}" class="img-circle" alt="User Image" />
                @else
                    <img src="{{img_path()}}profile/empty-people.png" class="img-circle" alt="User Image" />
                @endif
            </div>
            <div class="pull-left info">
                <p>Hello, {{current_user()->username}}</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        @if (current_session()->userdata('role_id') == 10)
           <div class="user-panel">
                <p class="info">Collected: {{current_session()->userdata('collection')}} Tk</p>
           </div>
        @endif
        
        <ul class="sidebar-menu">
            <li class="active">
                <a href="{{site_url()}}/admin/content">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>

            {{--left menu bar here --}}
            {{--@include('layout/_menu')--}}
            {!! theme_view('partials/_menu')  !!}
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
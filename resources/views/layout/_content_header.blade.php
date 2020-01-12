<section class="content-header ">
    @if(!$monolith && isset($toolbar_title))
        <h1>{{$toolbar_title}}</h1>
    @endif
    @yield('content-header')

    @if(strpos(uri()->uri_string(), 'settings') !== false)
        <div class="breadcrumb">
            {!! Template::block('sub_nav', '') !!}
        </div>
    @else
        {!! Template::block('sub_nav', '') !!}
    @endif

    @include('layout/_confirm_alert')
</section>
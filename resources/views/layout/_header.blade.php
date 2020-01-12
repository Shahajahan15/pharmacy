@php
    Assets::add_css(array(
        'bootstrap.css',
        'font-awesome.min.css',
        //'ionicons.min.css',
        //'datepicker/datepicker3.css',
        'timepicker/bootstrap-timepicker.min.css',
        //'daterangepicker/daterangepicker-bs3.css',
        //'bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
        'select2.min.css',
        'datatables/dataTables.bootstrap.css',
        'AdminLTE.css',
        'jquery-ui.min.css',
        'rahat-hospital.css'
    ));
@endphp

<meta charset="utf-8">
<title>
    @if(isset($toolbar_title))
        {!! $toolbar_title !!} :
    @endif
    Online Hospital Management System
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="noindex" />
{!! Assets::css(null, true)  !!}
<script>window.jQuery || document.write('<script src="{{  js_path() }}jquery.min.js"><\/script>')</script>
<script type="text/javascript">
    var base_url = "{{ base_url() }}";
</script>
{{--<style type="text/css">--}}
    {{--.remove:hover{--}}
        {{--color:red;--}}
        {{--border: 2px solid black;--}}
        {{--cursor: pointer;--}}
    {{--}--}}
    {{--.bootstrap-timepicker-widget table td input {--}}
        {{--width:50px;--}}
    {{--}--}}
{{--</style>--}}
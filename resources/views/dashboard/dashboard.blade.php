@extends('app')

@section('content-header')
    <h1>Dashboard</h1>
@endsection

@section('content')

    <div class="row">


    </div>

<script>
    var cashDailyChartData = {!! $cashDailyChartData !!};
    var cashMonthlyChartData = {!! $cashMonthlyChartData !!};
    var ticketDailyChartData = {!! $ticketDailyChartData !!};
    var ticketMonthlyChartData = {!! $ticketMonthlyChartData !!};
    var patientDailyChartData = {!! $patientDailyChartData !!};
    var patientMonthlyChartData = {!! $patientMonthlyChartData !!};
    var bedStatus = {!! json_encode($bedStatus) !!};
    var cashCollection = {!! $cashCollection !!};
</script>

@endsection
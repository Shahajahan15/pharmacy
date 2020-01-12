@extends('app')

@section('content-header')
    <h1>Pharmacy Dashboard</h1>
@endsection

@section('content')

    <div class="row">
        <section class="content">
            <legend style="width: 115px">Main Pharmacy</legend>
            <div class="row">

                <div class="col-lg-4 col-xs-6 no-padding-right">
                    <div class="white-box">
                        <div class="r-icon-stats"> <i class="fa fa-plus bg-megna"></i>
                            <div class="bodystate">
                                <h4><small class="badge bg-green">TK: {{$mcashIn}}</small></h4> <span class="tile-label-dash">Today Cash Receive </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-6 no-padding-right">
                    <div class="white-box">
                        <div class="r-icon-stats"> <i class="fa fa-minus bg-megna"></i>
                            <div class="bodystate">
                                <h4><small class="badge bg-aqua">TK:{{$mcashRn}}</small></h4> <span class="tile-label-dash">Today Cash Return</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-6 no-padding-right">
                    <div class="white-box">
                        <div class="r-icon-stats"> <i class="fa fa-star-half-o bg-megna"></i>
                            <div class="bodystate">
                                <h4><small class="badge bg-aqua">TK:{{$mcashIn - $mcashRn}}</small></h4> <span class="tile-label-dash">Nit Cash Receive</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <legend style="width: 115px">Sub Pharmacy</legend>
            <div class="row">

                <div class="col-lg-4 col-xs-6 no-padding-right">
                    <div class="white-box">
                        <div class="r-icon-stats"> <i class="fa fa-plus bg-megna"></i>
                            <div class="bodystate">
                                <h4><small class="badge bg-green">TK: {{$scashIn}}</small></h4> <span class="tile-label-dash">Today Cash Receive </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-6 no-padding-right">
                    <div class="white-box">
                        <div class="r-icon-stats"> <i class="fa fa-minus bg-megna"></i>
                            <div class="bodystate">
                                <h4><small class="badge bg-aqua">TK:{{$scashRn}}</small></h4> <span class="tile-label-dash">Today Cash Return</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xs-6 no-padding-right">
                    <div class="white-box">
                        <div class="r-icon-stats"> <i class="fa fa-star-half-o bg-megna"></i>
                            <div class="bodystate">
                                <h4><small class="badge bg-aqua">TK:{{$scashIn - $scashRn}}</small></h4> <span class="tile-label-dash">Nit Cash Receive</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <legend style="width: 115px">Total Collection</legend>
            <div class="row">
               
              <div class="col-lg-12 col-xs-6 no-padding-right">
                    <div class="white-box">
                        <div class="r-icon-stats" style="text-align: center;"> <i class="fa fa-money bg-megna"></i>
                            <div class="bodystate">
                                <h4><small class="badge bg-aqua">TK:{{$mcashIn - $mcashRn + $scashIn - $scashRn}}</small></h4> <span class="tile-label-dash">Total Cash Collection</span>
                            </div>
                        </div>
                    </div>
                </div>
                  
            </div>

        </section>
    </div>
    <hr>

    <div class="row panel">
        
        

        <!-- Cash collection bar chart in days & monthly -->
        <div class="col-lg-6">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-bar-chart-o"></i>Main Pharmacy  Cash collection</li>
                </ul>
                <canvas id="cash_chart_days_main" ></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-bar-chart-o"></i>Sub Pharmacy  Cash collection</li>
                </ul>
                <canvas id="cash_chart_days_sub" ></canvas>
            </div>
        </div>
                <div class="col-lg-6">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-bar-chart-o"></i>Monthly Cash collection(Main Pharmacy)</li>
                </ul>
                <canvas id="main_cash_chart_months" ></canvas>
            </div>
        </div>
                <div class="col-lg-6">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs pull-right">
                    <li class="pull-left header"><i class="fa fa-bar-chart-o"></i>Monthly Cash collection(Sub Pharmacy)</li>
                </ul>
                <canvas id="sub_cash_chart_months" ></canvas>
            </div>
        </div>

        <!--div id="container" style="width:100%; height:400px;"></div-->
 </div>

<script>
    var cashMainDailyChartData = {!! $cashMainDailyChartData !!};     
    var cashSubDailyChartData = {!! $cashSubDailyChartData !!};
    var cashMainMonthlyChartData = {!! $cashMainMonthlyChartData !!};
    var cashSubMonthlyChartData = {!! $cashSubMonthlyChartData !!};

$(document).ready(function () {
    
    var cash_chart_days_main = new Chart($("#cash_chart_days_main"), {
        type: 'bar',
        data: {
            labels: cashMainDailyChartData.label,
            datasets: [{
                fill: false,
                borderColor: "#26B99A",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                label: "Cash Collection",
                backgroundColor: "#26B99A",
                data: cashMainDailyChartData.value,
            }]
        },
        options: {
            legend: false,
            scales: {
                yAxes: [
                    {
                        ticks: {
                            beginAtZero: true
                        }
                    }
                ]
            }
        }
    });

    var cash_chart_days_sub = new Chart($("#cash_chart_days_sub"), {
        type: 'bar',
        data: {
            labels: cashSubDailyChartData.label,
            datasets: [{
                fill: false,
                borderColor: "#26B99A",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                label: "Cash Collection",
                backgroundColor: "#26B99A",
                data: cashSubDailyChartData.value,
            }]
        },
        options: {
            legend: false,
            scales: {
                yAxes: [
                    {
                        ticks: {
                            beginAtZero: true
                        }
                    }
                ]
            }
        }
    });

    var main_cash_chart_months = new Chart($("#main_cash_chart_months"), {
        type: 'bar',
        data: {
            labels: cashMainMonthlyChartData.label,
            datasets: [{
                fill: false,
                borderColor: "#26B99A",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                label: "Cash Collection",
                backgroundColor: "#26B99A",
                data: cashMainMonthlyChartData.value,
            }]
        },
        options: {
            legend: false,
            scales: {
                yAxes: [
                    {
                        ticks: {
                            beginAtZero: true
                        }
                    }
                ]
            }
        }
    });

        var sub_cash_chart_months = new Chart($("#sub_cash_chart_months"), {
        type: 'bar',
        data: {
            labels: cashSubMonthlyChartData.label,
            datasets: [{
                fill: false,
                borderColor: "#26B99A",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                label: "Cash Collection",
                backgroundColor: "#26B99A",
                data: cashSubMonthlyChartData.value,
            }]
        },
        options: {
            legend: false,
            scales: {
                yAxes: [
                    {
                        ticks: {
                            beginAtZero: true
                        }
                    }
                ]
            }
        }
    });
});
</script>


@endsection
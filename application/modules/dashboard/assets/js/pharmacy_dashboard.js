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


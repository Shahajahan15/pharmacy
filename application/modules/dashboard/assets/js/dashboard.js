$(document).ready(function () {
        
        var cash_chart_days = new Chart($("#cash_chart_days"), {
        type: 'bar',
        data: {
            labels: cashDailyChartData.label,
            datasets: [{
                fill: false,
                borderColor: "#26B99A",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                label: "Cash Collection",
                backgroundColor: "#26B99A",
                data: cashDailyChartData.value,
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



    var cash_chart_months = new Chart($("#cash_chart_months"), {
        type: 'bar',
        data: {
            labels: cashMonthlyChartData.label,
            datasets: [{
                fill: false,
                borderColor: "#26B99A",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                label: "Cash Collection",
                backgroundColor: "#26B99A",
                data: cashMonthlyChartData.value,
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

    var ticket_chart_days = new Chart($("#ticket_chart_days"), {
        type: 'line',
        data: {
            labels: ticketDailyChartData.label,
            datasets: [{
                fill: false,
                borderColor: "#26B99A",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                label: "Tickets",
                backgroundColor: "#26B99A",
                data: ticketDailyChartData.value,
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

    var ticket_chart_months = new Chart($("#ticket_chart_months"), {
        type: 'line',
        data: {
            labels: ticketMonthlyChartData.label,
            datasets: [{
                fill: false,
                borderColor: "#26B99A",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                label: "Tickets",
                backgroundColor: "#26B99A",
                data: ticketMonthlyChartData.value,
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

    var patient_chart_days = new Chart($("#patient_chart_days"), {
        type: 'line',
        data: {
            labels: patientDailyChartData.label,
            datasets: [{
                fill: false,
                borderColor: "#26B99A",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                label: "Patients",
                backgroundColor: "#26B99A",
                data: patientDailyChartData.value,
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

    var patient_chart_months = new Chart($("#patient_chart_months"), {
        type: 'line',
        data: {
            labels: patientMonthlyChartData.label,
            datasets: [{
                fill: false,
                borderColor: "#26B99A",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                label: "Tickets",
                backgroundColor: "#26B99A",
                data: patientMonthlyChartData.value,
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

    var manPowerPieChart = new Chart($("#cash_collection_chart"), {
        type: 'horizontalBar',
        data: {
            labels: cashCollection.label,
            datasets: [{
                label: "Cash Collection",
                backgroundColor: "#26B99A",
                data: cashCollection.value,
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    ticks: {
                        min: 0
                    }
                }],
                yAxes: [{
                    stacked: true
                }]
            }
        }
    });

 

    var bedPieChart = new Chart($("#bed_chart"), {
        type: 'pie',
        data: {
            labels: bedStatus.reduce(function(acc, cur) {
                return acc.concat(cur.label);
            }, []),
            datasets: [{
                data: bedStatus.reduce(function(acc, cur) {
                    return acc.concat(cur.value);
                }, []),
                backgroundColor : ["#26B99A", "#FB1414"]
            }],
        }
    });
});


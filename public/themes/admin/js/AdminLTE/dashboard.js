/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/

$(function() {
    "use strict";

    //Make the dashboard widgets sortable Using jquery UI
    $(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection();

    $(".connectedSortable .box-header, .connectedSortable .nav-tabs-custom").css("cursor", "move");

    //jQuery UI sortable for the todo list
    $(".todo-list").sortable({
        placeholder: "sort-highlight",
        handle: ".handle",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection();

    // Ticket bar chart in days
    var ticket_chart_days = c3.generate({
        bindto: '#ticket_chart_days',
        data: {
            type: 'bar',
            json: [
                { 'indicator': '2015-02-18', 'total': 100 },
                { 'indicator': '2015-02-19', 'total': 200 },
                { 'indicator': '2015-02-20', 'total': 300 },
                { 'indicator': '2015-02-21', 'total': 110 },
                { 'indicator': '2015-02-22', 'total': 80 },
                { 'indicator': '2015-02-23', 'total': 190 },
                { 'indicator': '2015-02-24', 'total': 200 },
                { 'indicator': '2015-02-25', 'total': 150 },
                { 'indicator': '2015-02-26', 'total': 140 },
                { 'indicator': '2015-02-27', 'total': 140 },
                { 'indicator': '2015-02-28', 'total': 140 },
                { 'indicator': '2015-03-01', 'total': 140 },
                { 'indicator': '2015-03-02', 'total': 140 },
                { 'indicator': '2015-03-03', 'total': 140 },
                { 'indicator': '2015-03-04', 'total': 140 }
            ],
            keys: {
                x: 'indicator',
                value: ['total']
            }
        },
        axis: {
            x: {
                type: 'timeseries',
                tick : {
                    format : "%b,%d"
                }
            },
            y: {
                label: {
                    text: '-Tickets-',
                    position: 'outer-middle'
                }
            }
        },
        bar: {
            width: {
                ratio: 0.5
            }
        }
    });

    // Ticket bar chart in monthly
    var ticket_chart_months = c3.generate({
        bindto: '#ticket_chart_months',
        data: {
            type: 'bar',
            json: [
                { 'indicator': '2015-02-18', 'total': 800 },
                { 'indicator': '2015-02-19', 'total': 1200 },
                { 'indicator': '2015-02-20', 'total': 1300 },
                { 'indicator': '2015-02-21', 'total': 1110 },
                { 'indicator': '2015-02-22', 'total': 980 },
                { 'indicator': '2015-02-23', 'total': 1190 },
                { 'indicator': '2015-02-24', 'total': 1200 },
                { 'indicator': '2015-02-25', 'total': 1150 },
                { 'indicator': '2015-02-26', 'total': 1140 },
                { 'indicator': '2015-02-27', 'total': 1140 },
                { 'indicator': '2015-02-28', 'total': 1240 },
                { 'indicator': '2015-03-01', 'total': 1240 },
                { 'indicator': '2015-03-02', 'total': 1340 },
                { 'indicator': '2015-03-03', 'total': 1440 },
                { 'indicator': '2015-03-04', 'total': 1140 }
            ],
            keys: {
                x: 'indicator',
                value: ['total']
            }
        },
        axis: {
            x: {
                type: 'timeseries',
                tick : {
                    format : "%b,%d"
                }
            },
            y: {
                label: {
                    text: '-Tickets-',
                    position: 'outer-middle'
                }
            }
        },
        bar: {
            width: {
                ratio: 0.5
            }
        }
    });


    // Cash collection bar chart in days
    var chart3 = c3.generate({
        bindto: '#cash-chart-days',
        data: {
            type: 'bar',
            json: [
                { 'indicator': '2015-02-18', 'total': 100 },
                { 'indicator': '2015-02-19', 'total': 200 },
                { 'indicator': '2015-02-20', 'total': 300 },
                { 'indicator': '2015-02-21', 'total': 110 },
                { 'indicator': '2015-02-22', 'total': 80 },
                { 'indicator': '2015-02-23', 'total': 190 },
                { 'indicator': '2015-02-24', 'total': 200 },
                { 'indicator': '2015-02-25', 'total': 150 },
                { 'indicator': '2015-02-26', 'total': 140 },
                { 'indicator': '2015-02-27', 'total': 140 },
                { 'indicator': '2015-02-28', 'total': 140 },
                { 'indicator': '2015-03-01', 'total': 140 },
                { 'indicator': '2015-03-02', 'total': 140 },
                { 'indicator': '2015-03-03', 'total': 140 },
                { 'indicator': '2015-03-04', 'total': 140 }
            ],
            keys: {
                x: 'indicator',
                value: ['total']
            }
        },
        axis: {
            x: {
                type: 'timeseries',
                tick : {
                    format : "%b,%d"
                }
            },
            y: {
                label: {
                    text: '-Tickets-',
                    position: 'outer-middle'
                }
            }
        },
        bar: {
            width: {
                ratio: 0.5
            }
        }
    });

    // Cash collection bar chart in monthly
    var chart4 = c3.generate({
        bindto: '#cash-chart-months',
        data: {
            type: 'bar',
            json: [
                { 'indicator': '2015-02-18', 'total': 800 },
                { 'indicator': '2015-02-19', 'total': 1200 },
                { 'indicator': '2015-02-20', 'total': 1300 },
                { 'indicator': '2015-02-21', 'total': 1110 },
                { 'indicator': '2015-02-22', 'total': 980 },
                { 'indicator': '2015-02-23', 'total': 1190 },
                { 'indicator': '2015-02-24', 'total': 1200 },
                { 'indicator': '2015-02-25', 'total': 1150 },
                { 'indicator': '2015-02-26', 'total': 1140 },
                { 'indicator': '2015-02-27', 'total': 1140 },
                { 'indicator': '2015-02-28', 'total': 1240 },
                { 'indicator': '2015-03-01', 'total': 1240 },
                { 'indicator': '2015-03-02', 'total': 1340 },
                { 'indicator': '2015-03-03', 'total': 1440 },
                { 'indicator': '2015-03-04', 'total': 1140 }
            ],
            keys: {
                x: 'indicator',
                value: ['total']
            }
        },
        axis: {
            x: {
                type: 'timeseries',
                tick : {
                    format : "%b,%d"
                }
            },
            y: {
                label: {
                    text: '-Taka-',
                    position: 'outer-middle'
                }
            }
        },
        bar: {
            width: {
                ratio: 0.5
            }
        }
    });

    var patient_chart = c3.generate({
        bindto: '#patient_admitted',
        data: {
            type: 'line',
            json: [
                { 'indicator': '2015-02-18', 'total': 800 },
                { 'indicator': '2015-02-19', 'total': 1200 },
                { 'indicator': '2015-02-20', 'total': 1300 },
                { 'indicator': '2015-02-21', 'total': 1110 },
                { 'indicator': '2015-02-22', 'total': 980 },
                { 'indicator': '2015-02-23', 'total': 1190 },
                { 'indicator': '2015-02-24', 'total': 1200 },
                { 'indicator': '2015-02-25', 'total': 1150 },
                { 'indicator': '2015-02-26', 'total': 1140 },
                { 'indicator': '2015-02-27', 'total': 1140 },
                { 'indicator': '2015-02-28', 'total': 1240 },
                { 'indicator': '2015-03-01', 'total': 1240 },
                { 'indicator': '2015-03-02', 'total': 1340 },
                { 'indicator': '2015-03-03', 'total': 1440 },
                { 'indicator': '2015-03-04', 'total': 1140 }
            ],
            keys: {
                x: 'indicator',
                value: ['total']
            }
        },
        axis: {
            x: {
                type: 'timeseries',
                tick : {
                    format : "%b,%d"
                }
            },
            y: {
                label: {
                    text: '-Taka-',
                    position: 'outer-middle'
                }
            }
        },
        bar: {
            width: {
                ratio: 0.5
            }
        }
    });


    function barChart(div, data, label){
        var chart1 = c3.generate({
            bindto: div,
            data: {
                type: 'line',
                json: data,
                keys: {
                    x: 'indicator',
                    value: ['total']
                }
            },
            axis: {
                x: {
                    type: 'timeseries',
                    tick : {
                        format : "%b,%d"
                    }
                },
                y: {
                    label: {
                        text: '-'+label+'-',
                        position: 'outer-middle'
                    }
                }
            },
            bar: {
                width: {
                    ratio: 0.5
                }
            }
        });
    }


    //pie chart
    var bed_chart = c3.generate({
        bindto:'#bed_chart',
        data: {
            columns: [
                ['Bed Free', 30],
                ['Bed Reserved', 50]
            ],
            type: 'pie'
        },
        pie: {
            label: {
                format: function (value, ratio, id) {
                    return value;
                }
            }
        }
    });

    var manpower_chart = c3.generate({
        bindto:'#manpower_chart',
        data: {
            columns: [
                ['Doctor', 30],
                ['Nurse', 20],
                ['Staff', 10],
                ['Tecnician', 10],
                ['Clark', 12]
            ],
            type: 'pie'
        },
        pie: {
            label: {
                format: function (value, ratio, id) {
                    return value;
                }
            }
        }
    });

});


//call chart
//getDataByAjax
//barChart = function("#ticket_chart_days", data, label);

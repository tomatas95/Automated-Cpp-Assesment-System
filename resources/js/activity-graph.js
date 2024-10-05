document.addEventListener('DOMContentLoaded', function() {
    let graphXAxis = [];
    let createdExercises = [];
    let submittedExercises = [];

    createdData.forEach(function(item) {
        if (!graphXAxis.includes(item.date)) {
            graphXAxis.push(item.date);
        }
    });

    submittedData.forEach(function(item) {
        if (!graphXAxis.includes(item.date)) {
            graphXAxis.push(item.date);
        }
    });

    graphXAxis.sort();

    graphXAxis.forEach(date => {
        createdExercises.push(0);
        submittedExercises.push(0);
    });

    createdData.forEach(function(item) {
        let index = graphXAxis.indexOf(item.date);
        createdExercises[index] = item.count;
    });

    submittedData.forEach(function(item) {
        let index = graphXAxis.indexOf(item.date);
        submittedExercises[index] = item.count;
    });

    let chart;

    function renderChart(graphXAxis, createdExercises, submittedExercises, chartType = 'area') {
        if (createdExercises.length === 0 && submittedExercises.length === 0) {
            document.querySelector("#exerciseChart").innerHTML = `<p class="no-data-message">${jsvalidi18.no_data_available}</p>`;
            return;
        }

        let options = {
            chart: {
                type: chartType,
                fontFamily: 'Helvetica, Arial, sans-serif',
                height: 350,
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 350
                    }
                },
                dropShadow: {
                    enabled: true,
                    top: 2,
                    left: 2,
                    blur: 1,
                    color: '#000',
                    opacity: 0.25
                },
                toolbar: {
                    show: true
                }
            },
            series: [{
                    name: jsvalidi18.created_ex,
                    data: createdExercises
                },
                {
                    name: jsvalidi18.submitted_ex,
                    data: submittedExercises
                }
            ],
            xaxis: {
                categories: graphXAxis
            },
            colors: ['#1DA09C', '#3467eb'],
            stroke: {
                curve: 'smooth'
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                position: 'top'
            },
            tooltip: {
                shared: true,
                intersect: false
            },
            responsive: [{
                    breakpoint: 1000,
                    options: {
                        chart: {
                            height: 200,
                            toolbar: {
                                show: false
                            }
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                },
                {
                    breakpoint: 768,
                    options: {
                        chart: {
                            height: 400,
                            width: '100%',
                        },
                        xaxis: {
                            labels: {
                                show: true,
                                style: {
                                    fontSize: '12px'
                                }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    fontSize: '12px'
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            fontSize: '14px'
                        }
                    }
                },
                {
                    breakpoint: 360,
                    options: {
                        chart: {
                            height: 300,
                            width: '100%',
                            toolbar: {
                                show: false
                            }
                        },
                        xaxis: {
                            labels: {
                                show: true,
                                style: {
                                    fontSize: '10px'
                                },
                                rotate: -45
                            }
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    fontSize: '10px'
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            fontSize: '12px',
                            horizontalAlign: 'center',
                            offsetX: 0,
                            offsetY: -5
                        },
                        grid: {
                            padding: {
                                left: 5,
                                right: 5,
                                bottom: 5
                            }
                        }
                    }
                }
            ]
        };

        if (chart) {
            chart.destroy();
        }

        chart = new ApexCharts(document.querySelector("#exerciseChart"), options);
        chart.render();
    }

    renderChart(graphXAxis, createdExercises, submittedExercises);

    function filterLegends() {
        var allLegends = document.querySelectorAll(".legend input[type='checkbox']");

        for (var i = 0; i < allLegends.length; i++) {
            if (!allLegends[i].checked) {
                chart.toggleSeries(allLegends[i].value);
            }
            allLegends[i].addEventListener('change', function() {
                chart.toggleSeries(this.value);
            });
        }
    }

    filterLegends();

    document.querySelectorAll('input[name="chartType"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            renderChart(graphXAxis, createdExercises, submittedExercises, this.value);
        });
    });
});
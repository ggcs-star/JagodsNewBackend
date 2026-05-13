$(function () {
    let chart;
    let month = getMonth();
    let start = month.startDate;
    let end = month.endDate;
    
    function getMonth() {
        const now = new Date();
        
        const start = new Date(now.getFullYear(), now.getMonth(), 1);
        const startDate = start.toISOString().split('T')[0];
        
        const end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        const endDate = end.toISOString().split('T')[0];
        
        return {
            startDate,
            endDate
        };
    }
    
    fetchOrderSummary(start, end);

    $('#orderSummaryDateRange').on('apply.daterangepicker', function (ev, picker) {
        fetchOrderSummary(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
    });
    
    
    function fetchOrderSummary(startDate, endDate) {
        let url = window.location.origin + '/admin/day-wise-order-summery';
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'), // Ensure CSRF protection
                start_date: startDate,
                end_date: endDate,
            },
            success: function (response) {
                let object = JSON.parse(response);
                
                if (!chart) {
                    chart = new ApexCharts(document.querySelector("#orderSummaryChart"), {
                        chart: {
                            height: 320,
                            type: 'radialBar',
                        },
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    size: '25%'
                                },
                                track: {
                                    margin: 10
                                },
                                dataLabels: {
                                    name: {
                                        fontSize: '14',
                                        fontFamily: 'inherit',
                                    },
                                    value: {
                                        fontSize: '14',
                                        fontWeight: 'bold',
                                        fontFamily: 'inherit',
                                        color: '#1F1F39',
                                        offsetY: 5,
                                    },
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        formatter: function (w) {
                                            return w.config.series.reduce((a, b) => a + b, 0).toFixed(2);
                                        }
                                    }
                                },
                            },
                        },
                        stroke: {
                            lineCap: 'round'
                        },
                        series: object.summary.values,
                        labels: object.summary.keys,
                        colors: ['#FF4F99','#A953FF','#FB4E4E'],
                    });
                    chart.render();
                } else {
                    chart.updateSeries( object.summary.values );
                }
    
                $('#deliveredIndicator').html(object.summary.values[0] + '%' );
                $('#canceledIndicator').html(object.summary.values[1] + '%' );
                $('#rejectedIndicator').html(object.summary.values[2] + '%' );
                // $('#avgSales').html(object.avgSales);
    
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    };
});
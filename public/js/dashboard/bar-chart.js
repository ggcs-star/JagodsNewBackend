/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

$(function () {
    let chart;
    
    $('#revenueDateRange').on('apply.daterangepicker', function (ev, picker) {
        fetchRevenueStatistics(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
    });
    
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
    
    fetchRevenueStatistics(start, end);
    
    
    
    function fetchRevenueStatistics(startDate, endDate) {
        let url = window.location.origin + '/admin/day-wise-revenue-statistics';
        
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
                    chart = new ApexCharts(document.querySelector("#revenueChart"), {
                            series: [{
                                name: Boolean(object.revenue) ? 'Sale Amount' : 'Order Activity' ,
                                data : object.data,
                            }],
                            chart: {
                                type: 'bar',
                                height: 276,
                                parentHeightOffset: 0,
                                toolbar: { show: false },
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 2,
                                    columnWidth: '40%',
                                    dataLabels: { position: 'top' },
                                }
                            },
                            xaxis: {
                                categories: object.scale,
                                position  : 'bottom',
                                tooltip   : { enabled: false },
                                labels    : {
                                    style: {
                                        fontSize: '12px',
                                        fontFamily: 'inherit',
                                    }
                                }
                            },
                            tooltip: {
                                style: {
                                    fontSize: '14px',
                                    fontFamily: 'inherit',
                                }
                            },
                            colors:['#567DFF', '#7fd5c3'],
                            grid: { show: false, },
                            yaxis: { show: false },
                            dataLabels: { enabled: false },
                        });
                        
                        chart.render();
                } else {
                    chart.updateSeries([{
                        data: object.data,
                    }]);
                    chart.updateOptions({
                        xaxis: {
                            categories: object.scale,
                        }
                    });
                }
    
                // $('#totalSales').html(object.totalSales);
                // $('#avgSales').html(object.avgSales);
    
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    };
});



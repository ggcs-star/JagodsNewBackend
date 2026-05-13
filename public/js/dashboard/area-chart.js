/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

$(function () {
    let chart;
    
    $('#salesDateRange').on('apply.daterangepicker', function (ev, picker) {
        fetchSalesStatistics(picker.startDate.format('YYYY-MM-DD'), picker.endDate.format('YYYY-MM-DD'));
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
    
    fetchSalesStatistics(start, end);
    
    
    
    function fetchSalesStatistics(startDate, endDate) {
        let url = window.location.origin + '/admin/day-wise-sales-statistics';
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
                    chart = new ApexCharts(document.querySelector("#salesChart"), {
                        series: [{
                            name: "Sales",
                            data: object.sales,
                        }],
                        chart: {
                            type: 'area',
                            height: 250,
                            fontFamily: 'inherit',
                            parentHeightOffset: 0,
                            zoom: {
                                enabled: false
                            },
                            toolbar: {
                                show: false,
                            },
                        },
                        xaxis: {
                            type: 'Day',
                            categories: object.dates,
                            tooltip: {
                                enabled: false
                            },
                            axisBorder: {
                                show: false
                            },
                        },
                        stroke: {
                            width: 3,
                            lineCap: "round",
                            curve: "smooth",
                        },
                        colors: ["#EE7F30"],
                        grid: {
                            show: false
                        },
                        yaxis: {
                            show: false
                        },
                        dataLabels: {
                            enabled: false,
                        },
                    });
                    chart.render();
                } else {
                    chart.updateSeries([{
                        data: object.sales,
                    }]);
                    chart.updateOptions({
                        xaxis: {
                            categories: object.dates,
                        }
                    });
                }
    
                $('#totalSales').html(object.totalSales);
                $('#avgSales').html(object.avgSales);
    
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    };
});

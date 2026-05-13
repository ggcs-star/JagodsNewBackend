$(function () {
    let start = moment().startOf('month');
    let end = moment().endOf('month');
    function cb(start, end) {
        $('#orderDateRange span').html(start.format('DD MMM') + ' - ' + end.format('DD MMM'));
    }
    $('#orderDateRange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    cb(start, end);
});


$(function () {
    let start = moment().startOf('month');
    let end = moment().endOf('month');
    function cb(start, end) {
        $('#salesDateRange span').html(start.format('DD MMM') + ' - ' + end.format('DD MMM'));
    }
    $('#salesDateRange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    cb(start, end);
});


$(function () {
    let start = moment().startOf('month');
    let end = moment().endOf('month');
    function cb(start, end) {
        $('#orderSummaryDateRange span').html(start.format('DD MMM') + ' - ' + end.format('DD MMM'));
    }
    $('#orderSummaryDateRange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);
    cb(start, end);
});


$(function () {
    let start = moment().startOf('month');
    let end = moment().endOf('month');
    function cb(start, end) {
        $('#revenueDateRange span').html(start.format('DD MMM, YYYY') + ' - ' + end.format('DD MMM, YYYY'));
    }
    $('#revenueDateRange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today'       : [moment(), moment()],
            'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month'  : [moment().startOf('month'), moment().endOf('month')],
            'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'This Year'   : [moment().startOf('year'), moment().endOf('year')],
            'Last Year'   : [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
        }
    }, cb);
    cb(start, end);
});

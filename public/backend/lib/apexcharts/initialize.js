//================================
//      AREA CHART INIT
//================================
var options = {
    series: [{
        name: "Month",
        data: [23, 34, 12, 54, 32, 43, 60, 55, 50, 65],
    }],
    chart: {
        type: 'area',
        height: 250,
        fontFamily: 'inherit',
        parentHeightOffset: 0,
        zoom: { enabled: false },
        toolbar: { show: false, },
    },
    xaxis: {
        tooltip: { enabled: false },
        axisBorder: { show: false },
    },
    stroke: {
        width: 3,
        lineCap: "round",
        curve: "smooth",
    },
    colors: [ "#FF4F99"],
    grid: { show: false },
    yaxis: { show: false },
    dataLabels: { enabled: false, },
};
var chart = new ApexCharts(document.querySelector("#area-chart"), options);
chart.render();


//===============================
//      RADIAL CHART INIT
//===============================
var options = {
    chart: {
        height: 320,
        type: 'radialBar',
    },
    plotOptions: {
        radialBar: {
            hollow: { size: '25%' },
            track: { margin: 10 },
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
            },
        },
    },
    stroke: { lineCap: 'round' },
    series: [70, 52, 34, 16],
    colors: ['#FF4F99', '#567DFF', '#A953FF', '#FB4E4E'],
    labels: ['Delivered', 'Returened', 'Canceled', 'Rejected'],
};

var chart = new ApexCharts(document.querySelector("#radial-chart"), options);
chart.render();



//===============================
//      COLUMN CHART INIT
//===============================   
var options = {
    series: [{
        name: 'Customer',
        data: [29, 39, 30, 57, 46, 89, 46, 76, 15, 80],
    },{
        name: 'Data',
        data : [55, 30, 77, 105, 58, 2, 68, 9, 12, 23],
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
        categories: ["11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00"],
        position: 'bottom',
        tooltip: { enabled: false },
        labels: {
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
};

var chart = new ApexCharts(document.querySelector("#column-chart"), options);
chart.render();

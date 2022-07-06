var options = {
    chart: {
        height: 350,
        type: 'area',
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth',
        width: 3,
    },
    series: [],
    colors: [],
    xaxis: {
        type: 'datetime',
        categories: [],
    },
    grid: {
        borderColor: '#f1f1f1',
        padding: {
            bottom: 15
        }
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy HH:mm'
        },
    },
    legend: {
        offsetY: 7
    }
}


const Options = {
    set_series: function (data) {
        data.forEach(function (currentValue) {
            options.series.push(currentValue);
            var randomColor = Math.floor(Math.random()*16775215).toString(16);
            console.log(randomColor);
            Options.set_colors(randomColor);
        });
    },

    set_colors: function(color){
        options.colors.push("#"+color);
    },

    set_xaxis: function (days) {
        var _arr = Object.values(days);
        options.xaxis.categories = _arr;
    },

    start: function () {
        var chart = new ApexCharts(
            document.querySelector("#spline_area"),
            options
        );
        chart.render();
    }
}

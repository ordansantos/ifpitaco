/**
 * 
 */

CHART = (function () {

    var chart = {};

    chart.drawChart = function (opts, qtd_opts, qtd_opt) {

        var colors = ['#F7464A', '#46BFBD', '#FDB45C', '#949FB1', '#4D5360'];
        var highlights = ['#FF5A5E', '#5AD3D1', '#FFC870', '#A8B3C5', '#616774'];
        var data = [];
        for (var i = 0; i < qtd_opt; i++) {

            data.push({
                value: parseInt(qtd_opts[i]),
                color: colors[i],
                highlight: highlights[i],
                label: opts[i]
            });
        }

        var ctx = $("#myChart").get(0).getContext("2d");
        var myPieChart = new Chart(ctx).Pie(data, {animationSteps: 50});
    };


    //Customizando o tooltip padrão para não limitar a quantidade de caracteres
    Chart.defaults.global.customTooltips = function (tooltip) {

        // Tooltip Element
        var tooltipEl = $('#chartjs-tooltip');
        $('#chartjs-tooltip').show();

        // Hide if no tooltip
        if (!tooltip) {
            tooltipEl.css({
                opacity: 0
            });
            return;
        }

        // Set caret Position
        tooltipEl.removeClass('above below');
        tooltipEl.addClass(tooltip.yAlign);

        // Set Text
        tooltipEl.html(tooltip.text);

        // Find Y Location on page
        var top;
        if (tooltip.yAlign == 'above') {
            top = tooltip.y - tooltip.caretHeight - tooltip.caretPadding;
        } else {
            top = tooltip.y + tooltip.caretHeight + tooltip.caretPadding;
        }

        // Display, position, and set styles for font
        tooltipEl.css({
            opacity: 1,
            left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
            top: tooltip.chart.canvas.offsetTop + top + 'px',
            fontFamily: tooltip.fontFamily,
            fontSize: tooltip.fontSize,
            fontStyle: tooltip.fontStyle
        });
    };


    return chart;

})();
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>TGXQ2015 Net Value</title>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript"
	src="js/jquery-ui/js/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="js/highstocks.js"></script>
<!--
<script type="text/javascript" src="js/highchart_dark_green.js"></script>
<script type="text/javascript" src="js/highchart_dark_blue.js"></script>
<script type="text/javascript" src="js/highchart_gray.js"></script>
<script type="text/javascript" src="js/highchart_grid.js"></script>
-->
<script type="text/javascript" src="js/exporting.js"></script>
</head>
<body>
	<div id="container" style="height: 650px; min-width: 500px"></div>
	<script>$(function () {
	    $.getJSON('data.php?d=tgxq2015&callback=?', function (data) {
	        if (data.length <= 0) {
	            return;
	        }
	        data.sort(function(a,b) {return (a[0]<b[0])?-1:1;});
	        assetBase = data[0][1];
	        shBase = data[0][3];
	        if (assetBase == 0 || shBase == 0) {
	            return;
	        }
	        var assetD = [],
	            shD = [],
	            vol = [];
	        for (var i = 0; i < data.length; i++) {
	            if (data[i][0] == "" || data[i][1] == "" || data[i][2] == "" || data[i][3] == "") continue;
	            var d = $.datepicker.parseDate('yymmdd', data[i][0]);
	            var dut = d.getTime() + 8 * 3600000;
	            // assetD
	            assetD[i] = {};
	            assetD[i].x = dut;
	            assetD[i].y = (data[i][1] - assetBase) / assetBase;
	            assetD[i].name = 'nv';
	            assetD[i].absraw = data[i][1] / assetBase;
				assetD[i].abs = Highcharts.numberFormat(assetD[i].absraw, 3);
	            assetD[i].percent = Highcharts.numberFormat(assetD[i].y * 100, 2);
	            if (i == 0) {
	                assetD[i].percentToPrev = 0;
	            } else {
	                assetD[i].percentToPrev = Highcharts.numberFormat((assetD[i].absraw - assetD[i - 1].absraw) * 100 / assetD[i - 1].absraw, 2);
				}
	            // vol
	            vol[i] = {};
	            vol[i].x = dut;
	            vol[i].y = Math.round(data[i][2] * 100 / data[i][1]) / 10;
	            vol[i].name = 'vol';
	            // shD
	            shD[i] = {};
	            shD[i].x = dut;
	            shD[i].y = (data[i][3] - shBase) / shBase;
	            shD[i].name = 'idx';
	            shD[i].abs = data[i][3];
	            shD[i].percent = Highcharts.numberFormat(shD[i].y * 100, 2);
	            if (i == 0) {
	                shD[i].percentToPrev = 0;
	            } else {
	                shD[i].percentToPrev = Highcharts.numberFormat((data[i][3] - data[i - 1][3]) * 100 / data[i - 1][3], 2);
	            }
	        }

	        // Create the chart
	        $('#container').highcharts('StockChart', {
	            rangeSelector: {
	                selected: 1
	            },
	            colors: [
	                '#910000',
	                '#2f7ed8',
	                '#0d233a',
	                '#8bbc21',
	                '#1aadce',
	                '#492970',
	                '#f28f43',
	                '#77a1e5',
	                '#c42525',
	                '#a6c96a'],

	            title: {
	                text: 'TGXQ2015 Net Value'
	            },

	            yAxis: [{
	                title: {
	                    text: 'Net Value'
	                },
	                height: 300,
	                lineWidth: 2,
	                labels: {
	                    formatter: function () {
	                        return this.value * 100 + '%';
	                    }
	                }
	            }, {
	                title: {
	                    text: 'Volume'
	                },
	                top: 400,
	                height: 100,
	                offset: 0,
	                lineWidth: 2,
	                min: 0,
	                max: 10.001,
	                labels: {
	                    formatter: function () {
	                        return this.value;
	                    }
	                }
	            }],

	            series: [{
	                name: '净　　值',
	                data: assetD,
	                marker: {
	                    enabled: true,
	                    radius: 3
	                },
	                tooltip: {
	                    valueDecimals: 3
	                }
	            }, {
	                name: '上证指数',
	                data: shD,
	                marker: {
	                    enabled: true,
	                    radius: 3
	                },
	                tooltip: {
	                    valueDecimals: 3
	                }
	            }, {
	                type: 'column',
	                name: 'Volume',
	                data: vol,
	                yAxis: 1
	            }],

	            tooltip: {
	                formatter: function () {
	                    var s = '<b>' + Highcharts.dateFormat("%Y-%m-%d", this.x) + '</b><br/>';
	                    s += '<br/>------------------------';
	                    for (var i = 0; i < this.points.length; i++) {
	                        var pointType = this.points[i].point.options.name;
	                        if (pointType == 'nv' || pointType == 'idx') {
	                            if (pointType == 'nv') {
	                                s += '<br/>净　　值：';
	                            } else if (pointType == 'idx') {
	                                s += '<br/>上证指数：';
	                            }

	                            var abs = this.points[i].point.options.abs;
	                            var percent = this.points[i].point.options.percentToPrev;
	                            s += abs + ' (<span style="color:';
	                            if (percent < 0) {
	                                s += 'green';
	                            } else {
	                                s += 'red';
	                            }
	                            s += '">' + percent + '%</span>)';
	                        } else if (pointType == 'vol') {
	                            s += '<br/>------------------------';
	                            s += '<br/>仓　　位：';
	                            s += this.points[i].point.y + '成';
	                        } else {
	                            continue;
	                        }
	                    }
	                    s += '<br/>------------------------';
	                    return s;
	                }
	            },

	            legend: {
	                enabled: true
	            },
	        });
	    });
	});
	</script>
</body>
</html>
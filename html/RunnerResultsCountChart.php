<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>

jQuery(document).ready(function($) {

  $.ajax('<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/statistics/groupedrunnerresultscount?groupSize=50&minimumResultCount=25')
    .done(function(data) {
      createRunnerResultsCountChart(data);
    });	

  function createRunnerResultsCountChart(data) {

    am5.ready(function() {
      // Create root element
      // https://www.amcharts.com/docs/v5/getting-started/#Root_element
      var root = am5.Root.new("chartdiv");

      // Set themes
      // https://www.amcharts.com/docs/v5/concepts/themes/
      root.setThemes([
        am5themes_Animated.new(root)
      ]);

      // Create chart
      // https://www.amcharts.com/docs/v5/charts/xy-chart/
      var chart = root.container.children.push(am5xy.XYChart.new(root, {
        panX: false,
        panY: false,
        layout: root.verticalLayout
      }));


      // Add legend
      // https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
      var legend = chart.children.push(
        am5.Legend.new(root, {
          centerX: am5.p50,
          x: am5.p50
        })
      );

      // Create axes
      // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
      var xAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
        categoryField: "range",
        renderer: am5xy.AxisRendererX.new(root, {
          cellStartLocation: 0.1,
          cellEndLocation: 0.9,
		  minGridDistance: 20
        }),
        tooltip: am5.Tooltip.new(root, {})
      }));

      xAxis.data.setAll(data);

      var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
        //logarithmic: true,
        renderer: am5xy.AxisRendererY.new(root, {})
      }));
		
	  // Modify chart's colors
	chart.get("colors").set("colors", [
	  am5.color(0xe88112),
	  am5.color(0x585656)
	]);


      // Add series
      // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
      function makeSeries(name, fieldName) {
        var series = chart.series.push(am5xy.ColumnSeries.new(root, {
          name: name,
          xAxis: xAxis,
          yAxis: yAxis,
          valueYField: fieldName,
          categoryXField: "range"
        }));

        series.columns.template.setAll({
          tooltipText: "{name}, {categoryX}:{valueY}",
          width: am5.percent(90),
          tooltipY: 0
        });

        series.data.processor = am5.DataProcessor.new(root, {
          numericFields: [fieldName]
        });

        series.data.setAll(data);

        // Make stuff animate on load
        // https://www.amcharts.com/docs/v5/concepts/animations/
        series.appear();

        series.bullets.push(function () {
          return am5.Bullet.new(root, {
            locationY: 0,
            sprite: am5.Label.new(root, {
              text: "{valueY}",
              fill: root.interfaceColors.get("alternativeText"),
              centerY: 0,
              centerX: am5.p50,
              populateText: true
            })
          });
        });

        legend.data.push(series);
      }

      makeSeries("Female", "female");
      makeSeries("Male", "male");

      // Make stuff animate on load
      // https://www.amcharts.com/docs/v5/concepts/animations/
      chart.appear(1000, 100);
    
    }); // end am5.ready()
  } // end function createRunnerResultsCountChart()
});
</script>
<div> 
	<div id="chartdiv" style="width: 100%; height: 700px; max-width:100%; clear: both;"></div>
</div>
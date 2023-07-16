<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>
  jQuery(document).ready(function($) {

    $.getJSON(
      '<?php echo esc_url(home_url()); ?>/wp-json/ipswich-jaffa-api/v2/categories',
      function(data) {
        var select = $('#ageCategory')[0];

        select.options.length = 0;
        select.options.add(new Option('Please select...', 0));

        for (var i = 0; i < data.length; i++) {
          select.options.add(new Option(data[i].description + ' (' + data[i].code + ')', data[i].id));
        }
      }
    );

    $.getJSON(
      '<?php echo esc_url(home_url()); ?>/wp-json/ipswich-jaffa-api/v2/distances',
      function(data) {
        var select = $('#distance')[0];

        select.options.length = 0;
        select.options.add(new Option('Please select...', 0));

        for (var i = 0; i < data.length; i++) {
            // Only show fixed distance events.
            if (data[i].resultUnitTypeId == "2" && data[i].name != "Ultra") {
          select.options.add(new Option(data[i].name, data[i].id));
            }
        }
      }
    );

   var root;

    $('#ageCategory').change(function() {
      var ageCategoryId = $('#ageCategory').val();
      if (ageCategoryId == 0)
        return;

      $('#recordTitle').html("Club record for age category " + $("#ageCategory option:selected").text());
      $('#distance').prop('selectedIndex', 0);
      $.ajax('<?php echo esc_url(home_url()); ?>/wp-json/ipswich-jaffa-api/v2/results/historicrecords/category/' + ageCategoryId)
        .done(function(data) {
          createTimelineChart(data, "Category");
        });
    });

    $('#distance').change(function() {
      var distanceId = $('#distance').val();
      if (distanceId == 0)
        return;

      $('#recordTitle').html("Club records for distance " + $("#distance option:selected").text());
      $('#ageCategory').prop('selectedIndex', 0);
      $.ajax('<?php echo esc_url(home_url()); ?>/wp-json/ipswich-jaffa-api/v2/results/historicrecords/distance/' + distanceId)
        .done(function(data) {
          createTimelineChart(data, "Distance");
        });
    });

    function createTimelineChart(data, titlePrefix) {

      am5.ready(function() {
         
          if (root) {
          root.dispose();
          }
                  // Create root element
        // https://www.amcharts.com/docs/v5/getting-started/#Root_element 
         root = am5.Root.new("chartdiv");

        // Set themes
        // https://www.amcharts.com/docs/v5/concepts/themes/ 
        root.setThemes([
          am5themes_Animated.new(root)
        ]);

        // Create chart
        // https://www.amcharts.com/docs/v5/charts/xy-chart/
        var chart = root.container.children.push(am5xy.XYChart.new(root, {
          panX: true,
          panY: true,
          wheelX: "panX",
          wheelY: "zoomX",
          pinchZoomX: true
        }));

        chart.leftAxesContainer.set("layout", root.verticalLayout);
        

        // Create axes
        // https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
        var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
          maxDeviation: 0.1,
          groupData: false,
          baseInterval: {
            timeUnit: "day",
            count: 1
          },
          extraMax: 0.05,
          extraMin: 0.01,
          renderer: am5xy.AxisRendererX.new(root, {
            minGridDistance: 100
          }),
          tooltip: am5.Tooltip.new(root, {})
        }));

xAxis.children.push(
  am5.Label.new(root, {
    text: "Year",
    x: am5.p50,
    centerX:am5.p50
  })
);
        
        // Add series
        // https://www.amcharts.com/docs/v5/charts/xy-chart/series/
        //for (var i = 0; i < data.length; i++) {
        $.each(data, function(i, item) {
            
          var yRenderer = am5xy.AxisRendererY.new(root, {});
          yRenderer.labels.template.set('visible', false);
          
          var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
            maxDeviation: 0.1,
            renderer: yRenderer
          }));

          yAxis.axisHeader.children.push(am5.Label.new(root, {
            text: titlePrefix + ": " + item.code,
            fontWeight: "300"
          }));

          yAxis.axisHeader.set("paddingTop", 20);

          var series = chart.series.push(am5xy.LineSeries.new(root, {
            minBulletDistance: 10,
            name: titlePrefix + ": " + item.code,
            xAxis: xAxis,
            yAxis: yAxis,
            valueYField: "performance",
            valueXField: "date",
            tooltip: am5.Tooltip.new(root, {
              pointerOrientation: "horizontal",
              labelText: "{runnerName}, {time}, {eventName}"
            })

          }));

          series.data.processor = am5.DataProcessor.new(root, {
            dateFormat: "yyyy-MM-dd",
            dateFields: ["date"],
            numericFields: ["performance"]
          });

          // Make stuff animate on load
          // https://www.amcharts.com/docs/v5/concepts/animations/
          series.data.setAll(item.records);

          var currentDataItem;
          series.on("tooltipDataItem", function(dataItem) {
            if (currentDataItem) {
              am5.array.each(currentDataItem.bullets, function(bullet) {
                bullet.get("sprite").unhover();
              });
            }
            currentDataItem = dataItem;
            if (currentDataItem) {
              am5.array.each(currentDataItem.bullets, function(bullet) {
                bullet.get("sprite").hover();
              });
            }
          });

          series.bullets.push(function() {
            var sprite = am5.Container.new(root, {
              interactive: true,
              setStateOnChildren: true
            });

            sprite.states.create("hover", {});

            var outer = sprite.children.push(am5.Circle.new(root, {
              radius: 7,
              fillOpacity: 0,
              stroke: series.get("fill"),
              strokeWidth: 2,
              strokeOpacity: 0
            }));

            outer.states.create("hover", {
              strokeOpacity: 1
            });

            var inner = sprite.children.push(am5.Circle.new(root, {
              radius: 5,
              fill: series.get("fill"),
              stroke: root.interfaceColors.get("background"),
              strokeWidth: 2
            }));

            return am5.Bullet.new(root, {
              sprite: sprite
            });
          });
          series.appear(1000, 100);

          // Add cursor
          // https://www.amcharts.com/docs/v5/charts/xy-chart/cursor/
          var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
            xAxis: xAxis,
            snapToSeries: chart.series.values
          }));
          cursor.lineY.set("visible", false);

          // Add scrollbar
          // https://www.amcharts.com/docs/v5/charts/xy-chart/scrollbars/
          chart.set("scrollbarX", am5.Scrollbar.new(root, {
            orientation: "horizontal"
          }));

          // Make stuff animate on load
          // https://www.amcharts.com/docs/v5/concepts/animations/
          chart.appear(1000, 100);
        });
      }); // end am5.ready()
    }
  });
</script>
<style>
  #chartdiv {
    width: 100%;
    height: 4000px;
  }
</style>
<div class="section">
  <div class="formRankCriteria">
    <p>Here you can view a graphical timeline representation of our club records and when they were broken. Either select the age category to view the history of club records for all distances and disaplines or a distance to view the records across all age categories.</p>
    <p>Charts will be displayed below after selection.</p>
    <label for="ageCategory">Age Category</label>
    <select id="ageCategory" name="ageCategory" size="1" title="Select age category">
    </select>
    <label for="distance">Distance</label>
    <select id="distance" name="distance" size="1" title="Select distance">
    </select>
  </div>
  <h3 id="recordTitle"></h3>
</div>
<div id="chartdiv"></div>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>

jQuery(document).ready(function($) {

  var chart, series, year;

$.ajax('<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/events/<?php echo $_GET['eventId']; ?>/topAttendees')
	.done(function(data) {
		createEventTopAttendeeChart(data);
	});	

function createEventTopAttendeeChart(allData) {
  var startingYear = Object.keys(allData)[0];
  var lastYear = Object.keys(allData).reverse()[0];

am5.ready(function() {

  // Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");

var stepDuration = 500;

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([am5themes_Animated.new(root)]);

// Create chart
// https://www.amcharts.com/docs/v5/charts/xy-chart/
chart = root.container.children.push(am5xy.XYChart.new(root, {
  panX: true,
  panY: true,
  wheelX: "none",
  wheelY: "none"
}));

// We don't want zoom-out button to appear while animating, so we hide it at all
chart.zoomOutButton.set("forceHidden", true);

// Create axes
// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
var yRenderer = am5xy.AxisRendererY.new(root, {
  minGridDistance: 20,
  inversed: true
});
// hide grid
yRenderer.grid.template.set("visible", false);

var yAxis = chart.yAxes.push(am5xy.CategoryAxis.new(root, {
  maxDeviation: 0,
  categoryField: "runner",
  renderer: yRenderer
}));

var xAxis = chart.xAxes.push(am5xy.ValueAxis.new(root, {
  maxDeviation: 0,
  min: 0,
  strictMinMax: true,
  extraMax: 0.1,
  renderer: am5xy.AxisRendererX.new(root, {})
}));

xAxis.set("interpolationDuration", stepDuration / 10);
xAxis.set("interpolationEasing", am5.ease.linear);

// Add series
// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
series = chart.series.push(am5xy.ColumnSeries.new(root, {
  xAxis: xAxis,
  yAxis: yAxis,
  valueXField: "value",
  categoryYField: "runner"
}));

// Rounded corners for columns
series.columns.template.setAll({ cornerRadiusBR: 5, cornerRadiusTR: 5 });

// Make each column to be of a different color
series.columns.template.adapters.add("fill", function (fill, target) {
  return chart.get("colors").getIndex(series.columns.indexOf(target));
});

series.columns.template.adapters.add("stroke", function (stroke, target) {
  return chart.get("colors").getIndex(series.columns.indexOf(target));
});

// Add label bullet
series.bullets.push(function () {
  return am5.Bullet.new(root, {
    locationX: 1,
    sprite: am5.Label.new(root, {
      text: "{valueXWorking.formatNumber('#.# a')}",
      fill: root.interfaceColors.get("alternativeText"),
      centerX: am5.p100,
      centerY: am5.p50,
      populateText: true
    })
  });
});

var label = chart.plotContainer.children.push(am5.Label.new(root, {
  text: startingYear,
  fontSize: "8em",
  opacity: 0.2,
  x: am5.p100,
  y: am5.p100,
  centerY: am5.p100,
  centerX: am5.p100
}));

// Get series item by category
function getSeriesItem(category) {
  for (var i = 0; i < series.dataItems.length; i++) {
    var dataItem = series.dataItems[i];
    if (dataItem.get("categoryY") == category) {
      return dataItem;
    }
  }
}

// Axis sorting
function sortCategoryAxis() {
  // sort by value
  series.dataItems.sort(function (x, y) {
    return y.get("valueX") - x.get("valueX"); // descending
    //return x.get("valueX") - y.get("valueX"); // ascending
  });

  // go through each axis item
  am5.array.each(yAxis.dataItems, function (dataItem) {
    // get corresponding series item
    var seriesDataItem = getSeriesItem(dataItem.get("category"));

    if (seriesDataItem) {
      // get index of series data item
      var index = series.dataItems.indexOf(seriesDataItem);
      // calculate delta position
      var deltaPosition =
        (index - dataItem.get("index", 0)) / series.dataItems.length;
      // set index to be the same as series data item index
      if (dataItem.get("index") != index) {
        dataItem.set("index", index);
        // set deltaPosition instanlty
        dataItem.set("deltaPosition", -deltaPosition);
        // animate delta position to 0
        dataItem.animate({
          key: "deltaPosition",
          to: 0,
          duration: stepDuration / 2,
          easing: am5.ease.out(am5.ease.cubic)
        });
      }
    }
  });
  // sort axis items by index.
  // This changes the order instantly, but as deltaPosition is set, they keep in the same places and then animate to true positions.
  yAxis.dataItems.sort(function (x, y) {
    return x.get("index") - y.get("index");
  });
}

year = startingYear;
const d = new Date();
let currentYear = d.getFullYear();

// update data with values each 1.5 sec
var interval = setInterval(function () {
  year++;

  if (year > currentYear) {
    clearInterval(interval);
    clearInterval(sortInterval);
  }

  updateData();
}, stepDuration);

var sortInterval = setInterval(function () {
  sortCategoryAxis();
}, 100);

function setInitialData() {
  var d = allData[lastYear];

  for (var n in d) {
    series.data.push({ runner: n, value: Number(d[n]) });
    yAxis.data.push({ runner: n });
  }
}

function updateData() {
  var itemsWithNonZero = 0;

  if (allData[year]) {
    label.set("text", year.toString());

    am5.array.each(series.dataItems, function (dataItem) {
      var category = dataItem.get("categoryY");
      var value = allData[year][category];

      if (value > 4) { // TODO This affects the view
        itemsWithNonZero++;
      }

      dataItem.animate({
        key: "valueX",
        to: value,
        duration: stepDuration,
        easing: am5.ease.linear
      });
      dataItem.animate({
        key: "valueXWorking",
        to: value,
        duration: stepDuration,
        easing: am5.ease.linear
      });
    });

    yAxis.zoom(0, itemsWithNonZero / yAxis.dataItems.length);
  }
}

setInitialData();
setTimeout(function () {
  year++;
  updateData();
}, 50);

$('#replay-top-attendees-chart-animation').click(function () {		
  year = 1983;		
  setInitialData();
setTimeout(function () {
  year++;
  updateData();
}, 50);

	series.appear(1000);
  chart.appear(1000, 100);
});	

// Make stuff animate on load
// https://www.amcharts.com/docs/v5/concepts/animations/
series.appear(1000);
chart.appear(1000, 100);

}); // end am5.ready()
} // end function createEventTopAttendeeChart()
});
</script>
<div> 
  <div id="topEventAttendeeReplay">
		<input type="button" value="Replay animation" id="replay-top-attendees-chart-animation" style="font-size: 10px; float: right;"/>
	</div>
	<div id="chartdiv" style="width: 100%; height: 700px; max-width:100%; clear: both;"></div>
</div>
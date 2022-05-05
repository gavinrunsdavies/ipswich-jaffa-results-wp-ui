<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>

jQuery(document).ready(function($) {

$.ajax('<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/statistics/clubresults')
	.done(function(data) {
		createResultsRadar(data);
	});	

var resultsYearTable = $('#results-year-table');	
			
resultsYearTable.DataTable({			
  columns: [
      { data: "year" },
      { data: "count" }           
  ],
  order: [[ 0, "desc" ]],			
  paging: true,
  displayLength : 10,
  lengthChange : false,
  processing : true,	
  searching : false,
  ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/statistics/type/3')					
});

function getAjaxRequest(url) {
  return {
    //"async": false,
    "url" : '<?php echo esc_url( home_url() ); ?>' + url,
    "method": "GET",
    "headers": {
    "cache-control": "no-cache"				
    },
    "dataSrc" : ""
  }
}

function createResultsRadar(data) {

am5.ready(function() {

// Create root element
// https://www.amcharts.com/docs/v5/getting-started/#Root_element
var root = am5.Root.new("chartdiv");

// Create custom theme
// https://www.amcharts.com/docs/v5/concepts/themes/#Quick_custom_theme
const myTheme = am5.Theme.new(root);
myTheme.rule("Label").set("fontSize", 10);
myTheme.rule("Grid").set("strokeOpacity", 0.06);

// Set themes
// https://www.amcharts.com/docs/v5/concepts/themes/
root.setThemes([am5themes_Animated.new(root), myTheme]);

// tell that valueX should be formatted as a date (show week number)
root.dateFormatter.setAll({  
  dateFormat: "y",
  dateFields: ["valueX"]
});

root.locale.firstDayOfWeek = 0;

var yearlyData = [];
var monthlyData = []; 

var firstDay = am5.time.round(new Date(data[0]["monthYear"]), "year", 1);
var lastDay = new Date(Number(data[data.length - 1]["year"]) + 1, 0, 1);
var total = 0;
var dateFormatter = am5.DateFormatter.new(root, {});
var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
var monthAxisData = [
	{ month: "Jan" },
	{ month: "Feb" },
	{ month: "Mar" },
	{ month: "Apr" },
	{ month: "May" },
	{ month: "Jun" },
	{ month: "Jul" },
	{ month: "Aug" },
	{ month: "Sep" },
	{ month: "Oct" },
	{ month: "Nov" },
	{ month: "Dec" }
];

var colorSet = am5.ColorSet.new(root, {});

// PREPARE DATA
function prepareData(data) {

  var firstYear = firstDay.getFullYear();
  for (var year = firstYear, i = 0; year <= lastDay.getFullYear(); year++, i++) {
    yearlyData[i] = {};
    yearlyData[i].count = 0;
    yearlyData[i].year = year;
    
    var date = new Date(year, 0, 1);
    var endDate = new Date(year, 11, 31);

    yearlyData[i].date = date.getTime(); // are these used?
    yearlyData[i].endDate = endDate.getTime();
  }

  am5.array.each(data, function(di) {
    var date = new Date(di["monthYear"]);
    var month = date.getMonth();
    var year = date.getFullYear();

    var count = Number(di["count"]);

    yearlyData[year - firstYear].count += count;
    total += count;

    monthlyData.push(
      { 
        date: date.getTime(),
        month: months[month],
        year: year,
        count: count, 
        xc: Number(di["xc"]),
        road: Number(di["road"]),
        mt: Number(di["multi-terrain"]),
        virtual: Number(di["virtual"]),
        track: Number(di["track"]),
        other: Number(di["other"]),
        unknown: Number(di["unknown"]),
        title: months[month] + " " + year 
      }
    );
  });
}

function createBubbleSeries(name, valueField, colour) {
  // bubble series is a line series with strokes hiddden
  // https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_series
  var bubbleSeries = chart.series.push(
    am5radar.RadarLineSeries.new(root, {
      name: name,
      calculateAggregates: true,
      xAxis: dateAxis,
      yAxis: yearAxis,
      baseAxis: dateAxis,
      categoryYField: "month",
      valueXField: "date",
      valueField: valueField,
      maskBullets: false
    })
  );

  bubbleSeries.strokes.template.set("forceHidden", true);  

  // add bullet
  var circleTemplate = am5.Template.new({});
  bubbleSeries.bullets.push(function () {
    var graphics = am5.Circle.new(root, {
      fill: am5.color(colour),
      tooltipText: "{title}: {value}"
    }, circleTemplate);
    return am5.Bullet.new(root, {
      sprite: graphics
    });
  });

  // Add heat rule (makes bubbles to be of a various size, depending on a value)
  // https://www.amcharts.com/docs/v5/concepts/settings/heat-rules/
  bubbleSeries.set("heatRules", [{
    target: circleTemplate,
    min: 0,
    max: 20,
    dataField: "value",
    key: "radius"
  }]);

  return bubbleSeries;
}

function toggleSeries(visible, target) {
  if (visible) {
  
    chart.series.each(function(series){
        if(series != target && series.className != "RadarColumnSeries"){
            series.hide()
        }
    }) ;
  }
}

prepareData(data);

// Create chart
// https://www.amcharts.com/docs/v5/charts/radar-chart/
var chart = root.container.children.push(
  am5radar.RadarChart.new(root, {
    panX: false,
    panY: false,
    wheelX: "panX",
    wheelY: "zoomX",
    innerRadius: am5.percent(20),
    radius: am5.percent(90),
    startAngle: 270 - 170,
    endAngle: 270 + 170,
    layout: root.verticalLayout
  })
);

// add label in the center
chart.radarContainer.children.push(
  am5.Label.new(root, { 
    text:
      "Total Ipswich JAFFA RC results\n[color:#FF8C00;fontSize:2em;]" + Math.round(total) + 
      "[/]\n[fontSize:0.8em;]Each circle represents\nmonthly result count.\nSize represents number of JAFFA finishers.[/]",
    textAlign: "center",
    centerX: am5.percent(50),
    centerY: am5.percent(50)
  })
);

// Add cursor
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Cursor
var cursor = chart.set(
  "cursor",
  am5radar.RadarCursor.new(root, {
    behavior: "zoomX"
  })
);
cursor.lineY.set("visible", false);

// Create axes and their renderers
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_axes

// date axis - years
var dateAxisRenderer = am5radar.AxisRendererCircular.new(root, {
  minGridDistance: 20
});

dateAxisRenderer.labels.template.setAll({
  radius: 30,
  textType: "radial",
  centerY: am5.p50
});

var dateAxis = chart.xAxes.push(
  am5xy.DateAxis.new(root, {
    baseInterval: { timeUnit: "year", count: 1 },
    renderer: dateAxisRenderer,
    min: firstDay.getTime(),
    max: lastDay.getTime()
  })
);

// count axis
var countAxisRenderer = am5radar.AxisRendererRadial.new(root, {
  axisAngle: 90,
  radius: am5.percent(50),
  innerRadius: am5.percent(20),
  inversed: true,
  minGridDistance: 20
});

countAxisRenderer.labels.template.setAll({
  centerX: am5.p50,
  minPosition: 0.05,
  maxPosition: 0.95
});

var countAxis = chart.yAxes.push(
  am5xy.ValueAxis.new(root, {
    renderer: countAxisRenderer
  })
);

countAxis.set("numberFormat", "#.");

// year axis
var yearAxisRenderer = am5radar.AxisRendererRadial.new(root, {
  axisAngle: 90,
  innerRadius: am5.percent(50),
  radius: am5.percent(100),
  minGridDistance: 20
});

yearAxisRenderer.labels.template.setAll({
  centerX: am5.p50
});

var yearAxis = chart.yAxes.push(
  am5xy.CategoryAxis.new(root, {
    categoryField: "month",
    renderer: yearAxisRenderer
  })
);

// Create series
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Adding_series
var countSeries = chart.series.push(
  am5radar.RadarColumnSeries.new(root, {
    calculateAggregates: true,
    xAxis: dateAxis,
    yAxis: countAxis,
    valueYField: "count",
    valueXField: "date",
    tooltip: am5.Tooltip.new(root, {
      labelText: "[bold]{year}\n[font-size:13px]Total {valueY} finishers"
    })
  })
);

countSeries.columns.template.set("strokeOpacity", 0);

// Set up heat rules
// https://www.amcharts.com/docs/v5/concepts/settings/heat-rules/
countSeries.set("heatRules", [{
  target: countSeries.columns.template,
  key: "fill",
  min: am5.color(0x673ab7),
  max: am5.color(0xf44336),
  dataField: "valueY"
}]);

// Add legend
// https://www.amcharts.com/docs/v5/charts/xy-chart/legend-xy-series/
var legend = chart.children.push(am5.Legend.new(root, {
  x: am5.p50,
  centerX: am5.p50,
  marginTop: 50,
  layout: am5.GridLayout.new(root, {
    maxColumns: 4,
    fixedWidthGrid: true
  })
}));

var series = [
  { name: "Road", field: "road", colour: 0xe88112},
  { name: "Cross-Country", field: "xc", colour: 0xe88112},
  { name: "Track", field: "track", colour: 0xe88112},
  { name: "Other", field: "other", colour: 0xe88112},
  { name: "Unknown", field: "unknown", colour: 0xe88112},
  { name: "Multi-terrain", field: "mt", colour: 0xe88112},
  { name: "Virtual", field: "virtual", colour: 0xe88112}
];

// Add series for each course type. Hide them initially
am5.array.each(series, function(courseType) {
  var bubbleSeries = createBubbleSeries(courseType.name, courseType.field, courseType.colour);  
  
  bubbleSeries.data.setAll(monthlyData);

  // Hide them all until toggled "on"
  bubbleSeries.hide();

  legend.data.push(bubbleSeries);

  bubbleSeries.on("visible", toggleSeries);
});

// Add the total series
var totalBubbleSeries = createBubbleSeries("Total", "count", 0xe88112);    
totalBubbleSeries.data.setAll(monthlyData);
totalBubbleSeries.appear(1000);
legend.data.push(totalBubbleSeries);

totalBubbleSeries.on("visible", toggleSeries);

// set data
// https://www.amcharts.com/docs/v5/charts/radar-chart/#Setting_data
yearAxis.data.setAll(monthAxisData);
countSeries.data.setAll(yearlyData);

countSeries.appear(1000);
chart.appear(1000, 100);

var fromTime = new Date(firstDay.getFullYear(), 0, 1).getTime();
var toTime = new Date(1979, 11, 31).getTime();
createRange("Seventies", 0, fromTime, toTime);

fromTime = new Date(1980, 0, 1).getTime();
toTime = new Date(1989, 11, 31).getTime();
createRange("Eighties", 1, fromTime, toTime);

fromTime = new Date(1990, 0, 1).getTime();
toTime = new Date(1999, 11, 31).getTime();
createRange("Nighties", 2, fromTime, toTime);
	
fromTime = new Date(2000, 0, 1).getTime();
toTime = new Date(2009, 11, 31).getTime();
createRange("Naugties", 3, fromTime, toTime);

fromTime = new Date(2010, 0, 1).getTime();
toTime = new Date(2019, 11, 31).getTime();
createRange("Tens", 4, fromTime, toTime);
	
fromTime = new Date(2020, 0, 1).getTime();
toTime = new Date(2029, 11, 31).getTime();
createRange("Twenties", 5, fromTime, toTime);	

function createRange(name, index, fromTime, toTime) {
  var axisRange = dateAxis.createAxisRange(
    dateAxis.makeDataItem({ above: true })
  );
  axisRange.get("label").setAll({ text: name });

  axisRange.set("value", fromTime);
  axisRange.set("endValue", toTime);

  // every 2nd color for a bigger contrast
  var fill = axisRange.get("axisFill");
  fill.setAll({
    toggleKey: "active",
    cursorOverStyle: "pointer",
    fill: colorSet.getIndex(index * 2),
    visible: true,
    dRadius: 25,
    innerRadius: -25
  });
  axisRange.get("grid").set("visible", false);

  var label = axisRange.get("label");
  label.setAll({
    fill: am5.color(0xffffff),
    textType: "circular",
    radius: 8,
    text: name
  });

  // clicking on a range zooms in
  fill.events.on("click", function (event) {
    var dataItem = event.target.dataItem;
    if (event.target.get("active")) {
      dateAxis.zoom(0, 1);
    } else {
      dateAxis.zoomToValues(dataItem.get("value"), dataItem.get("endValue"));
    }
  });
}

}); // end am5.ready()
} // end function createResultsRadar()
});
</script>
<div> 
	<div id="chartdiv" style="width: 100%; height: 700px; max-width:100%; clear: both;"></div>
</div>
<div>		
		<table class="display" id="results-year-table" style="width:100%">	
			<caption>Number of Results By Year</caption>				
			<thead>
				<tr>					
					<th>Year</th>
					<th>Number of Results</th>
				</tr>
			</thead>			
			<tbody>				
			</tbody>
		</table>
</div>
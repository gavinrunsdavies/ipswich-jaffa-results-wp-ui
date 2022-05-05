<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/plugins/timeline.js"></script>
<script src="https://cdn.amcharts.com/lib/4/plugins/bullets.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script>

jQuery(document).ready(function($) {	
	
  $.getJSON(
    '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/categories',
    function(data) {
      var select, option;

      select = $('#ageCategory')[0];

      select.options.length = 0;
      select.options.add(new Option('Please select...', 0));
      
      for (var i = 0; i < data.length; i++) {
        select.options.add(new Option(data[i].description + ' (' + data[i].code + ')', data[i].id));
      }
    }
  );

  $('#ageCategory').change(function () {
			var ageCategoryId = $('#ageCategory').val();
			if (ageCategoryId == 0)
				return;

      $('#ageCategoryTitle').html($("#ageCategory option:selected").text());
      $.ajax('<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/historicrecords/category/' + ageCategoryId)
	      .done(function(data) {
		      createTimelineChart(data);
	    });	
  });

  function createTimelineChart(data) {    

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    var chart = am4core.create("chartdiv", am4plugins_timeline.SerpentineChart);
    chart.curveContainer.padding(50, 20, 200, 200);
    chart.levelCount = 15;
    chart.yAxisRadius = am4core.percent(-10);
    chart.yAxisInnerRadius = am4core.percent(-45);
    chart.maskBullets = false;

    // var colorSet = new am4core.ColorSet();

    // colorSet.saturation = 0.5;
    chart.colors.list = [
      am4core.color("#845EC2"),
      am4core.color("#D65DB1"),
      am4core.color("#FF6F91"),
      am4core.color("#FF9671"),
      am4core.color("#FFC75F"),
      am4core.color("#F9F871")
    ];

    chart.data = data;

    chart.dateFormatter.dateFormat = "yyyy-MM-dd";
    chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";
    chart.fontSize = 15;

    var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "distance";
    categoryAxis.renderer.grid.template.disabled = true;
    categoryAxis.renderer.labels.template.paddingRight = 25;
    categoryAxis.renderer.minGridDistance = 10;
    categoryAxis.renderer.innerRadius = -60;
    categoryAxis.renderer.radius = 60;

    var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
    dateAxis.renderer.minGridDistance = 70;
    dateAxis.baseInterval = { count: 1, timeUnit: "day" };
    dateAxis.renderer.tooltipLocation = 0;
    dateAxis.startLocation = -0.5;
    dateAxis.renderer.line.strokeDasharray = "1,4";
    dateAxis.renderer.line.strokeOpacity = 0.6;
    dateAxis.tooltip.background.fillOpacity = 0.2;
    dateAxis.tooltip.background.cornerRadius = 5;
    dateAxis.tooltip.label.fill = new am4core.InterfaceColorSet().getFor("alternativeBackground");
    dateAxis.tooltip.label.paddingTop = 7;

    var labelTemplate = dateAxis.renderer.labels.template;
    labelTemplate.verticalCenter = "middle";
    labelTemplate.fillOpacity = 0.7;
    labelTemplate.background.fill = new am4core.InterfaceColorSet().getFor("background");
    labelTemplate.background.fillOpacity = 1;
    labelTemplate.padding(7, 7, 7, 7);

    var series = chart.series.push(new am4plugins_timeline.CurveColumnSeries());
    series.columns.template.height = am4core.percent(20);
    //series.columns.template.tooltipText = "[bold]{runnerName} - {eventName}, {time}[/]: {openDateX} - {dateX}";

    series.dataFields.openDateX = "startDate";
    series.dataFields.dateX = "endDate";
    series.dataFields.categoryY = "distance";
    //series.columns.template.propertyFields.fill = "color"; // get color from data
    //series.columns.template.propertyFields.stroke = "color";
    series.columns.template.strokeOpacity = 0;

    // as by default columns of the same series are of the same color, we add adapter which takes colors from chart.colors color set
    series.columns.template.adapter.add("fill", (fill, target) => {
      return target.dataItem ? chart.colors.getIndex(target.dataItem.index) : fill;
    });
        
      series.bulletsContainer.parent = chart.seriesContainer;		

    var bullet2 = series.bullets.push(new am4charts.CircleBullet());
    bullet2.circle.radius = 10;
    bullet2.circle.strokeOpacity = 0;
    bullet2.propertyFields.fill = "color";
    bullet2.locationX = 1;

          // Creating a bullet
      var bullet = series.bullets.push(new am4plugins_bullets.FlagBullet());

      // Setting label to display values from data
      bullet.label.text = "{categoryY}\n[bold]{runnerName} - {eventName}[/]\n{time}\n{openDateX} - {dateX}";
      bullet.label.textAlign = "middle";
      bullet.locationX = 1;
      bullet.poleHeight = 50;
      bullet.pole.propertyFields.stroke = "bulletColor"; // TODO
        bullet.fillOpacity = 0.5;
        bullet.strokeOpacity = 0.5;
      bullet.scale = 0.2;		

        bullet.setStateOnChildren = true;
    bullet.states.create("hover").properties.scale = 1.2;
    bullet.label.states.create("hover").properties.fillOpacity = 1.0;
        bullet.label.states.create("hover").properties.zIndex = 1000;
        
      // Background is a WavedRectangle, which we configure, as well as instruct
      // it to get its fill and border color from data field "bulletColor"
      bullet.background.waveLength = 15;
      bullet.background.fillOpacity = 1;
      //bullet.background.propertyFields.fill = "bulletColor";
      //bullet.background.propertyFields.stroke = "bulletColor";

    chart.scrollbarX = new am4core.Scrollbar();
    chart.scrollbarX.align = "center"
    chart.scrollbarX.width = am4core.percent(85);

    var cursor = new am4plugins_timeline.CurveCursor();
    chart.cursor = cursor;
    cursor.xAxis = dateAxis;
    cursor.yAxis = categoryAxis;
    cursor.lineY.disabled = true;
    cursor.lineX.strokeDasharray = "1,4";
    cursor.lineX.strokeOpacity = 1;

    categoryAxis.cursorTooltipEnabled = true;
		
  } // end function createTimelineChart()
});
</script>
<style>

#chartdiv {
  width: 100%;
  height: 6000px;
}

</style>
<div class="section"> 
	<div class="formRankCriteria">
		<p>Here you can select and then view a graphical timeline representation of our club records and when they were broken. Select the age category and the history of club records will be displayed below.</p>
		
		<label for="ageCategory">Age Category</label>
		<select id="ageCategory" name="ageCategory" size="1" title="Select category">
		</select>			 						     				
	</div>
  <h1 id="ageCategoryTitle"></h1>
</div>
<div id="chartdiv"></div>
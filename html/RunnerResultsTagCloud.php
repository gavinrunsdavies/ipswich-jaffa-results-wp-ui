<div> 
	<div id="chartYearSelector">
		<select id="runner-results-tag-cloud-year-selection" title="Select year" style="font-size: 10px; float: right;">
			<option value="0" selected="selected">All Time</option>  
			<?php
			for ($y = date("Y"); $y >= 1977; $y--) 
			{
				printf('<option value="%d">%d</option>', $y, $y);              
			}
			?>							
		</select>	
	</div>
	<div id="chartdiv" style="width: 100%; height: 300px; clear: both;"></div>
</div>

<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/plugins/wordCloud.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<script type="text/javascript">
	jQuery(document).ready(function($) {

		var chart = createTagCloud();

		var yearSelectList = $('#runner-results-tag-cloud-year-selection');
		yearSelectList.change(function () {
			var year = yearSelectList.val();				

			setTagCloudChartData(chart, year);			
		});
		
		setTagCloudChartData(chart, 0);

		function setTagCloudChartData(chart, year) {
			if (year == 0) {
				url = '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/statistics/results/runner';
			} else {
				url = '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/statistics/results/runner/year/' + year;
			}

			$.getJSON(url,				
				function(data) {
					chart.data = data;	
				}
			);	
		}		

		function createTagCloud() {
			am4core.useTheme(am4themes_animated);

			var chart = am4core.create("chartdiv", am4plugins_wordCloud.WordCloud);
			var series = chart.series.push(new am4plugins_wordCloud.WordCloudSeries());
			series.randomness = 0.5;
			series.rotationThreshold = 0.5;						
			
			series.dataFields.word = "name";
			series.dataFields.value = "count";
			series.dataFields.id = "runnerId";
			
			series.heatRules.push({
				"target": series.labels.template,
				"property": "fill",
				"min": am4core.color("#999596"),
				"max": am4core.color("#E88112"),
				"dataField": "value"
			});
			
			series.labels.template.url = "<?php echo $memberResultsPageUrl; ?>?runner_id={id}";
			series.labels.template.tooltipText = "{word}: {value} results";
			
			var hoverState = series.labels.template.states.create("hover");
			hoverState.properties.fill = am4core.color("#E88112");
			
			// Return series rather than the chart
			return series;
		}
	});
</script>
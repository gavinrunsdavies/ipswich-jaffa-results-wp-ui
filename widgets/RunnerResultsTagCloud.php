<div> 
	<div id="chartdiv"></div>
</div>

<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/plugins/wordCloud.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>

<script type="text/javascript">
	jQuery(document).ready(function($) {

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

		$.ajax(getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/statistics/type/7'))
			.done(function(data) {
				createTagCloud(data);
			});	

		function createTagCloud(data) {
				
			am4core.ready(function() {
			
				// Themes begin
				am4core.useTheme(am4themes_animated);
				// Themes end
				
				var chart = am4core.create("chartdiv", am4plugins_wordCloud.WordCloud);
				chart.fontFamily = "Courier New";
				var series = chart.series.push(new am4plugins_wordCloud.WordCloudSeries());
				series.randomness = 0.1;
				series.rotationThreshold = 0.5;
				
				series.data = data;
				
				series.dataFields.word = "name";
				series.dataFields.value = "count";
				series.dataFields.id = "runnerId"; // Added.
				
				series.heatRules.push({
				"target": series.labels.template,
				"property": "fill",
				"min": am4core.color("#0000CC"),
				"max": am4core.color("#CC00CC"),
				"dataField": "value"
				});
				
				series.labels.template.url = "<?php echo $memberResultsPageUrl; ?>?runner_id={id}";
				series.labels.template.tooltipText = "{word}: {value} results";
				
				var hoverState = series.labels.template.states.create("hover");
				hoverState.properties.fill = am4core.color("#FF0000");
				
				var subtitle = chart.titles.create();
				subtitle.text = "(click to open)";
				
				var title = chart.titles.create();
				title.text = "Members with the most race results";
				title.fontSize = 20;
				title.fontWeight = "800";
				
			}); // end am4core.ready()
		}

	});
</script>
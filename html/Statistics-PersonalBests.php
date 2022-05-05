<div class="section"> 
	<style>
		div.center-panel { margin-bottom:1em}
	</style>
	<div style="display:block" class="center-panel">		
		<table class="display" id="personal-best-year-table" style="width:100%">	
			<caption>Personal Bests By Year</caption>				
			<thead>
				<tr>	
					<th>Year</th>
					<th>Number of Personal Bests</th>
				</tr>
			</thead>			
			<tbody>				
			</tbody>
		</table>
	</div>
	<div id="chartdiv" style="width: 100%; height: 700px; max-width:100%; clear: both;"></div>
	<div style="display:block" class="center-panel">		
		<table class="display" id="personal-best-total-table" style="width:100%">	
			<caption>Personal Best Total</caption>				
			<thead>
				<tr>										
					<th>Name</th>								
					<th>Number of Personal Bests</th>
					<th>First PB</th>
					<th>Last PB</th>
				</tr>
			</thead>			
			<tbody>				
			</tbody>
		</table>
	</div>	
</div>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {	

		$.getJSON(
			'<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/statistics/type/4',
			function(data) {	
				createPersonalBestTotalTable(data);
				createPersonalBestChart(data);
			}
		);
		
		$('#personal-best-year-table').DataTable({			
			  "columns": [
             { data: "year" },
             { data: "count" }           
         ],
		    order: [[ 1, "desc" ]],			
			paging: true,
			displayLength : 10,
			lengthChange : false,
			processing    : true,				
			searching : false,
			ajax    	  : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/statistics/type/5')					
		});
			
		function createPersonalBestTotalTable(data) {
			$('#personal-best-total-table').DataTable({			
					"columns": [
					{ data: "name" },
					{ data: "count" },
					{ data: "firstPB" },
					{ data: "lastPB" }            
				],
				order: [[ 1, "desc" ]],
				columnDefs   : [
					{
						targets: [ 0 ], 
						"render": function ( data, type, row, meta ) {				
							var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
							var anchor = '<a href="' + resultsUrl;
							if (resultsUrl.indexOf("?") >= 0) {
								anchor += '&runner_id=' + row.runnerId;
							} else {
								anchor += '?runner_id=' + row.runnerId;
							}
							anchor += '">' + data + '</a>';								

							return anchor;
						}
					}				
				],
				displayLength : 10,
				lengthChange : false,
				processing : true,				
				searching : false,
				data: data				
			});		
		}

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

	function createPersonalBestChart(data) {
		am5.ready(function() {

			// Create root element
			// https://www.amcharts.com/docs/v5/getting-started/#Root_element
			var root = am5.Root.new("chartdiv");
			root.dateFormatter.setAll({
			dateFormat: "yyyy-MM-dd",
			dateFields: ["valueX", "openValueX"]
			});


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
			var legend = chart.children.push(am5.Legend.new(root, {
			centerX: am5.p50,
			x: am5.p50
			}))

			var colors = chart.get("colors");

			// Create axes
			// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
			var yAxis = chart.yAxes.push(
			am5xy.CategoryAxis.new(root, {
				categoryField: "name",
				renderer: am5xy.AxisRendererY.new(root, {}),
				tooltip: am5.Tooltip.new(root, {})
			})
			);

			// TODO set name for top X
			for (let index = 0; index < data.length && index < 10; index++) {
				yAxis.data.push({
					name: data[index].name
				});							
			}

			var xAxis = chart.xAxes.push(
			am5xy.DateAxis.new(root, {
				baseInterval: { timeUnit: "day", count: 1 },
				renderer: am5xy.AxisRendererX.new(root, {})
			})
			);


			// Add series
			// https://www.amcharts.com/docs/v5/charts/xy-chart/series/
			var series = chart.series.push(am5xy.ColumnSeries.new(root, {
			xAxis: xAxis,
			yAxis: yAxis,
			openValueXField: "firstPB",
			valueXField: "lastPB",
			categoryYField: "name",
			sequencedInterpolation: true
			}));

			series.columns.template.setAll({
			templateField: "columnSettings",
			strokeOpacity: 0,
			tooltipText: "{name}:\n[bold]{openValueX}[/] - [bold]{valueX}[/]"
			});

			// Set up data processor to parse string dates
			// https://www.amcharts.com/docs/v5/concepts/data/#Pre_processing_data
			series.data.processor = am5.DataProcessor.new(root, {
				dateFormat: "yyyy-MM-dd",
				dateFields: ["firstPB", "lastPB"]
			});

			series.data.setAll(data);

			// Make stuff animate on load
			// https://www.amcharts.com/docs/v5/concepts/animations/
			series.appear();
			chart.appear(1000, 100);

		}); // end am5.ready()
	}
});
</script>

<div class="section"> 
	<style>
		div.center-panel { margin-top:3em}
		#chartdiv {
	width		: 100%;
	height		: 500px;
	font-size	: 11px;
}

#chartdata {
  width: 900px;
  max-width: 100%;
  border: 2px solid #eee;
}

#chartdata table {
  width: 100%;
}

#chartdata table th, #chartdata table td {
  text-align: center;
  padding: 5px 7px;
}

#chartdata table th {
  background: #999;
  color: #fff;
}

#chartdata table td {
  border-bottom: 1px solid #eee;
}

#chartdata table td.row-title {
  font-weight: bold;
}
	</style>
	<div class="center-panel">
		<label for="distance">Distance</label>
		<select id="distance" name="distance" size="1" title="Select distance">
		</select>			 						     				
	</div>
	<div style="display:block" class="center-panel">		
		
		<div id="chartdiv"></div>
		<div id="legend" class="chartdiv"></div>
		<div id="chartdata"></div>   
		<div id="test"></div>
	</div>	
</div>
<!-- amCharts javascript sources -->
<script type="text/javascript" src="http://www.amcharts.com/lib/3/amcharts.js"></script>
<script type="text/javascript" src="http://www.amcharts.com/lib/3/serial.js"></script>
<script type="text/javascript" src="http://www.amcharts.com/lib/3/themes/none.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {						
			// https://www.amcharts.com/tips/simulate-multiple-series-weekly-chart/
			//https://www.amcharts.com/tips/automatically-create-a-table-of-chart-data-on-load/
			
		$.getJSON(
		  '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/distances',
		  function(data) {
			var name, select, option;

			// Get the raw DOM object for the select box
			select = document.getElementById('distance');

			// Clear the old options
			select.options.length = 0;
			select.options.add(new Option('Please select...', 0));
			
			// Load the new options
			for (var i = 0; i < data.length; i++) {
				select.options.add(new Option(data[i].text, data[i].id));
			}
		  }
		);
		
		function createClubRecordsTimeLine() {
			var lineChart = AmCharts.makeChart("chartdiv", {
				"type": "serial",
				"theme": "none",
				//"dataProvider": dataSet,
				//"dataTableId": "chartdata",
				"valueAxes": [{
					"gridColor":"#FFFFFF",
					"gridAlpha": 0.2,
					"dashLength": 0,
					"labelFunction": function (totalSec) {
						var hours = parseInt( totalSec / 3600 ) % 24;
						var minutes = parseInt( totalSec / 60 ) % 60;
						var seconds = totalSec % 60;

						return (hours < 10 ? "0" + hours : hours) + "-" + (minutes < 10 ? "0" + minutes : minutes) + "-" + (seconds  < 10 ? "0" + seconds : seconds);
					}
				}],
				"gridAboveGraphs": true,
				"startDuration": 0.2,
				/*"graphs": [{
					"title": "Male Open",
					"balloonText": "<b>[[runnerName]]</b>: [[time]],<br/>[[eventName]], [[date]]",
					"bullet": "round",
					"bulletSize": 10,
					"bulletBorderColor": "#ffffff",
					"bulletBorderAlpha": 1,
					"bulletBorderThickness": 2,
					"valueField": "M"
				}, 
				{
					"title": "Male V40",
					"balloonText": "<b>[[runnerName]]</b>: [[time]],<br/>[[eventName]], [[date]]",
					"bullet": "round",
					"bulletSize": 10,
					"bulletBorderColor": "#ffffff",
					"bulletBorderAlpha": 1,
					"bulletBorderThickness": 2,
					"valueField": "M40"
				},
				{
					"title": "Male V45",
					"balloonText": "<b>[[runnerName]]</b>: [[time]],<br/>[[eventName]], [[date]]",
					"bullet": "round",
					"bulletSize": 10,
					"bulletBorderColor": "#ffffff",
					"bulletBorderAlpha": 1,
					"bulletBorderThickness": 2,
					"valueField": "M45"
				},
				{
					"title": "Male V50",
					"balloonText": "<b>[[runnerName]]</b>: [[time]],<br/>[[eventName]], [[date]]",
					"bullet": "round",
					"bulletSize": 10,
					"bulletBorderColor": "#ffffff",
					"bulletBorderAlpha": 1,
					"bulletBorderThickness": 2,
					"valueField": "M50"
				},
				{
					"title": "Male V55",
					"balloonText": "<b>[[runnerName]]</b>: [[time]],<br/>[[eventName]], [[date]]",
					"bullet": "round",
					"bulletSize": 10,
					"bulletBorderColor": "#ffffff",
					"bulletBorderAlpha": 1,
					"bulletBorderThickness": 2,
					"valueField": "MV55"
				},
				{
					"title": "Male V60",
					"balloonText": "<b>[[runnerName]]</b>: [[time]],<br/>[[eventName]], [[date]]",
					"bullet": "round",
					"bulletSize": 10,
					"bulletBorderColor": "#ffffff",
					"bulletBorderAlpha": 1,
					"bulletBorderThickness": 2,
					"valueField": "M60"
				},
				{
					"title": "Male V65",
					"balloonText": "<b>[[runnerName]]</b>: [[time]],<br/>[[eventName]], [[date]]",
					"bullet": "round",
					"bulletSize": 10,
					"bulletBorderColor": "#ffffff",
					"bulletBorderAlpha": 1,
					"bulletBorderThickness": 2,
					"valueField": "M65"
				},
				{
					"title": "Male V70",
					"balloonText": "<b>[[runnerName]]</b>: [[time]],<br/>[[eventName]], [[date]]",
					"bullet": "round",
					"bulletSize": 10,
					"bulletBorderColor": "#ffffff",
					"bulletBorderAlpha": 1,
					"bulletBorderThickness": 2,
					"valueField": "M70"
				},
				{
					"title": "Ladies Open",
					"balloonText": "<b>[[runnerName]]</b>: [[time]],<br/>[[eventName]], [[date]]",
					"bullet": "round",
					"bulletSize": 10,
					"bulletBorderColor": "#ffffff",
					"bulletBorderAlpha": 1,
					"bulletBorderThickness": 2,
					"valueField": "L"
				}],*/
				"chartCursor": {
					"categoryBalloonEnabled": false,
					"cursorAlpha": 0,
					"zoomable": false
				},
				"categoryField": "date",
				"categoryAxis": {
					"gridPosition": "start",
					"gridAlpha": 0
				},
				legend: {
					divId: "legend",
					listeners: [{
					  event: "hideItem",
					  method: function(item) {
						//toggleAllGraphs(item, 'hide');
					  }
					}, {
					  event: "showItem",
					  method: function(item) {
						//toggleAllGraphs(item, 'show');
					  }
					}]
				}
			});
			
			return lineChart;
			//chart.validateData();
		}
		
		function toggleAllGraphs(item, action) {
		  for(var i = 0; i < AmCharts.charts.length; i++) {
			var chart = AmCharts.charts[i];
			if (chart == item.chart)
			  continue;
			if (action == 'hide')
			  chart.hideGraph(chart.graphs[item.dataItem.index]);
			else
			  chart.showGraph(chart.graphs[item.dataItem.index]);
		  }
		}
		
		$('#distance').change(function () {
			var distanceId = $('#distance').val();
			if (distanceId == 0)
				return;
		
			$.ajax(getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/historicrecords/distance/' + distanceId))
				.done(function(data) {
					var graphData = [];
					$.each(data, function(i, item){
						var category = item.code;
						
						$.each(item.records, function(j, record){							
							var comps = record.time.split(':');
							var seconds = parseInt(comps[0] * 3600) + parseInt(comps[1] * 60) + parseInt(comps[2]);		
							//record[category] = seconds;
							record['category'] = category;
							record['seconds'] = seconds;
							
							if (!(category in graphData)) 
								graphData[category] = [];
							graphData[category].push(record);
						});
                    });
					
					graphData.sort(function(a, b){
					  return a.date == b.date ? 0 : +(a.date > b.date) || -1;
					});

					document.getElementById("test").innerHTML =JSON.stringify(graphData['M']);
					var chart = createClubRecordsTimeLine();
					//$.each(graphData, function(i, dataSet){	
					for (var i in graphData) {
						var dataSet = graphData[i];
						var graph = new AmCharts.AmGraph();
						graph.title = dataSet.category;
						graph.balloonText = "<b>[[runnerName]]</b>: [[time]],<br/>[[eventName]], [[date]]";
						graph.bullet = "round";
						graph.bulletSize = 10;
						graph.bulletBorderColor = "#ffffff";
						graph.bulletBorderAlpha = 1;
						graph.bulletBorderThickness = 2;
						graph.valueField = dataSet.seconds;
						chart.addGraph(graph);				
					};
				});	
		});
			

		function getAjaxRequest(url) {
			return {
			  "url": '<?php echo get_site_url(); ?>' + url,
			  "method": "GET",
			  "headers": {
				"cache-control": "no-cache"				
			  },
			  "dataSrc" : ""
			}
		}
		
		/**
		 * A plugin to automatically creata a data table for the chart
		 * The plugin will check if the chart config has the following setting set: "dataTableId"
		 */
		AmCharts.addInitHandler(function(chart) {

		  // check if export to table is enabled
		  if (chart.dataTableId === undefined)
			return;

		  // get chart data
		  var data = chart.dataProvider;

		  // create a table
		  var holder = document.getElementById(chart.dataTableId);
		  var table = document.getElementById("chartDataTable");
		  if (table != null)		  
			holder.removeChild(table);
		  table = document.createElement("chartDataTable");
		  table.id = "dataTable";
		  holder.appendChild(table);
		  var tr, td;

		  // add first row
		  for (var x = 0; x < chart.dataProvider.length; x++) {
			// first row
			if (x == 0) {
			  tr = document.createElement("tr");
			  table.appendChild(tr);
			  td = document.createElement("th");
			  td.innerHTML = chart.categoryAxis.title;
			  tr.appendChild(td);
			  for (var i = 0; i < chart.graphs.length; i++) {
				td = document.createElement('th');
				td.innerHTML = chart.graphs[i].title;
				tr.appendChild(td);
			  }
			}

			// add rows
			tr = document.createElement("tr");
			table.appendChild(tr);
			td = document.createElement("td");
			td.className = "row-title";
			td.innerHTML = chart.dataProvider[x][chart.categoryField];
			tr.appendChild(td);
			for (var i = 0; i < chart.graphs.length; i++) {
			  td = document.createElement('td');
			  td.innerHTML = chart.dataProvider[x][chart.graphs[i].valueField];
			  tr.appendChild(td);
			}
		  }

		}, ["serial"]);
	});
</script>
<style>
.page-header {
	display: none;
}
#jaffa-event-title {
	text-align: center;
}
.site-content {
	padding-top: 0;
}

div.race-insights-chart {
	height: 350px;	
	margin-bottom: 5em;
}
</style>
<div class="section">
	<h2 id="jaffa-event-title"></h2>
	<div id="jaffa-race-results">
	</div>
	<div id="race-insights-panel" style="margin: 3em 0; display: none">
		<h3>Race Insights: Yearly comparison</h3>		
		<div id="race-insights-chart" style="clear: both;"></div>
	</div>
	<div class="formRankCriteria">
		<label for="jaffa-other-race-results">Other race results</label>
		<select id="jaffa-other-race-results">
		</select>
	</div>
</div>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script type="text/javascript">
	<?php if (isset($_GET['raceId']) || (isset($_GET['eventId']) && isset($_GET['date']))): ?>
	jQuery(document).ready(function ($) {

		$('#jaffa-other-race-results').change(function () {
			var raceId = $('#jaffa-other-race-results').val();
			if (raceId == 0)
				return;

			getRace(raceId);
		});

		// 1. Get race
		// 2. If meetingId is > 0, get meetingId
		// 2.1 Get associated races
		// 3 Get race results (for each race)
		// 4. If no race Id (backwards compatibility) get event races then match on date field (array in response)

		<?php if (isset($_GET['raceId'])) {?>
			getRace(<?php echo $_GET['raceId']; ?>);
		<?php } else {?>
			// Temporary. For backwards compatibility only.
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events/<?php echo $_GET['eventId']; ?>/races'))
				.done(function(raceData) {
					for (var i = 0; i < raceData.length; i++) {
						if (raceData[i].date == '<?php echo $_GET['date']; ?>') {
							getRace(raceData[i].id);
							break;
						}
					}
				});
		<?php }?>

		function setEventName(name) {
			$('#jaffa-event-title').html(name);
		}

		var selectedRaceCourseTypeId = 0;

		function getRace(raceId) {
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/races/' + raceId))
				.done(function(raceData) {
					setEventName(raceData.eventName);
					selectedRaceCourseTypeId = raceData.courseTypeId;
					if (raceData.meetingId > 0) {
						getMeeting(raceData.meetingId);
					} else {
						getRaceResult(raceData, raceData.eventName, raceData.date, raceData.resultMeasurementUnitTypeId);
					}
					getEventRaces(raceData.eventId);
				});
		}

		function getMeeting(meetingId) {
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/meetings/' + meetingId))
				.done(function(response) {
					if (response.meeting != null) {
						var meetingDates = response.meeting.fromDate;
						if (response.meeting.fromDate != response.meeting.toDate) {
							meetingDates += ' - ' + response.meeting.toDate;
						}

						var meetingTitle = '<h3>Meeting: '+response.meeting.name+' ('+meetingDates+')</h3>';
						$('#jaffa-race-results').prepend(meetingTitle);
					}

					if (response.races != null) {
						for (var i = 0; i < response.races.length; i++) {
							getRaceResult(response.races[i], response.races[i].description, response.races[i].date, response.races[i].resultMeasurementUnitTypeId);
						}
					}

					if (response.teams.length > 0) {
						setTeamResults(response.teams);
					}
			});
		}

		function setTeamResults(teams) {

			var maxResults = 0;
			for (var i = 0; i < teams.length; i++) {
				if (teams[i].results.length > maxResults) {
					maxResults = teams[i].results.length;
				}
			}
			var dataSet = [];
			var tableHeaderRow = '<tr><th>Team</th><th>Category</th><th>Position</th><th>Result</th>';
			for (var i = 0; i < maxResults; i++) {
				tableHeaderRow += '<th></th>';
			}
			tableHeaderRow += '</tr>';

			var tableHtml = '<table class="display" id="jaffa-team-results-table">';
			tableHtml += '<caption>Team Results</caption>';

			tableHtml += '<thead>';
			tableHtml += tableHeaderRow;
			tableHtml += '</thead>';
			tableHtml += '<tbody>';
			for (var i = 0; i < teams.length; i++) {
				var dataRow = [];
				dataRow.push(teams[i].teamName);
				dataRow.push(teams[i].teamCategory);
				dataRow.push(teams[i].teamPosition);
				dataRow.push(teams[i].teamResult);
				var runnerCount = 1;
				for (var j = 0; j < teams[i].results.length; j++, runnerCount++) {
					if (runnerCount != teams[i].results[j].teamOrder) {
						dataRow.push('-');
						runnerCount++;
					}
					
					var runnerTime = '<a href="<?php echo $memberResultsPageUrl; ?>?runner_id=' + teams[i].results[j].runnerId + '">';
					runnerTime += teams[i].results[j].runnerName;
					if (teams[i].results[j].runnerResult != '00:00:00') {
						runnerTime += ' (' + teams[i].results[j].runnerResult + ')';
					}
					runnerTime += '</a>';
					dataRow.push(runnerTime);					
				}
				// cells for missing runners
				for (; runnerCount <= maxResults; runnerCount++) {
					dataRow.push('-');
				}

				dataSet.push(dataRow);				
			}
			tableHtml += '</tbody>';
			tableHtml += '</table>';

			$('#jaffa-race-results').append(tableHtml);

			$('#jaffa-team-results-table').DataTable({
				paging : false,
				searching: false,
				order: [[ 3, "asc" ]],
				data: dataSet
			});
		}

		function getEventRaces(eventId) {
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events/'+ eventId + '/races'))
				.done(function(raceData) {
					var dateOptions = '<option>Please select...</option>';
					var meetingId = 0;
					for (var i = 0; i < raceData.length; i++) {
						if (raceData[i].meetingId > 0) {
							if (meetingId != raceData[i].meetingId) {
								meetingId = raceData[i].meetingId;
								dateOptions += '<option value="' + raceData[i].id + '">' + raceData[i].date + ' (meeting) </option>';
							}
						} else {
							dateOptions += '<option value="' + raceData[i].id + '">' + raceData[i].date + ' (' + raceData[i].count + ' results) </option>';
						}
					}

					$('#jaffa-other-race-results').append(dateOptions);

					getRaceInsightsData(eventId);
			});
		}

		function getRaceResult(race, description, date, measurementUnitType) {
			var courseTypeIdsToDisplayImprovements = ["1", "3", "6"];
			var resultColumnTitle;
			var tableName = 'jaffa-race-results-table-';
			switch (Number(measurementUnitType)) {
				case 2:
					resultColumnTitle = 'Seconds';
					break;
				case 3:
					resultColumnTitle = 'Metres';
				break;
				case 4:
					resultColumnTitle = 'Kilometres';
				break;
				case 5:
					resultColumnTitle = 'Miles';
				break;
				default:
					resultColumnTitle = 'Time';
					break;
			}
			
			var title = description != null ? description + ', ' : '';
			title += ipswichjaffarc.formatDate(date);
			//var tableCaption = '<h3 style="text-align:center;font-weight:bold;font-size:1.5em">' + title + '</h3>';
			//$('#jaffa-race-results').append(tableCaption);
			if (race.report != null) {
				var raceReport = '<p>' + race.report + '</p>';
				$('#jaffa-race-results').append(raceReport);
			}
			var tableRow = '<tr><th data-priority="2">Position</th><th data-priority="1">Name</th><th data-priority="3">' + resultColumnTitle + '</th><th>Personal Best</th><th>Season Best</th><th>Category</th><th>Standard</th><th  data-priority="5">Info</th><th data-priority="4">Age Grading</th></tr>';
			var tableHtml = '<table class="display" id="' + tableName + race.id + '">';
			tableHtml += '<caption>' + title + '</caption>';
			tableHtml += '<thead>';
			tableHtml += tableRow;
			tableHtml += '</thead>';
			tableHtml += '</table>';
			$('#jaffa-race-results').append(tableHtml);

			var table = $('#'+tableName + race.id).DataTable({
				responsive: {
					details: {
						renderer: function ( api, rowIdx, columns ) {
							var data = $.map( columns, function ( col, i ) {
								return col.hidden ?
									'<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
										'<td>'+col.title+':'+'</td> '+
										'<td>'+col.data+'</td>'+
									'</tr>' :
									'';
							} ).join('');

							return data ?
								$('<table/>').append( data ) :
								false;
						}
					}
				},
				paging : false,
				searching: false,
				serverSide : false,
				columns : [{
						data : "position"
					}, {
						data : "runnerName",
						render : function (data, type, row, meta) {
							var html = '<a href="<?php echo $memberResultsPageUrl; ?>?runner_id=' + row.runnerId + '">' + data + '</a>';
							if (row.team > 0) {
								var tooltip = '';
								if (row.team == 1)
									tooltip = "Part of the winning team";
								else
									tooltip = "Part of the scoring team finishing in " + row.team;

								html += ' <i class="fa fa-certificate" aria-hidden="true" title="' + tooltip + '"></i>'
							}
							return html;
						}
					}, {
						data : "result",
						render : function(data, type, row, meta) {
							if (measurementUnitType != 1 && measurementUnitType != "1" && measurementUnitType != undefined) {
								return Number(data).toLocaleString();
							}

							return ipswichjaffarc.secondsToTime(row.performance);
						},
						className : 'text-right'
					}, {
						data : "isPersonalBest",
						visible: courseTypeIdsToDisplayImprovements.includes(race.courseTypeId) ? true : false,
						render : function (data, type, row, meta) {
							if (data == 1) {
								var improvementHtml = '';
								if (row.previousPersonalBestPerformance != undefined) {
									var improvement = getResultImprovement(row.previousPersonalBestPerformance, row.performance);
									improvementHtml = '<span style="font-size:smaller; vertical-align: middle; font-family: Courier New; font-style: italic;"> -';
									if (improvement.length > 1)
										improvementHtml += improvement[0] + '\'' + improvement[1] + '\'\'';
									else if (improvement.length > 0)
										improvementHtml += improvement[0] + '\'\'';
									improvementHtml += '</span>';
								}

								return '<i class="fa fa-check" aria-hidden="true"></i>' + improvementHtml;
							}
							return '';
						},
						className : 'text-center'
					}, {
						data : "isSeasonBest",
						visible: courseTypeIdsToDisplayImprovements.includes(race.courseTypeId) ? true : false,
						render : function (data, type, row, meta) {
							if (data == 1) {
								return '<i class="fa fa-check" aria-hidden="true"></i>';
							}
							return '';
						},
						className : 'text-center'
					}, {
						data : "categoryCode"
					}, {
						data : "standardType",
						visible: courseTypeIdsToDisplayImprovements.includes(race.courseTypeId) ? true : false
					}, {
						data : "info"
					}, {
						data : "percentageGrading",
						visible: courseTypeIdsToDisplayImprovements.includes(race.courseTypeId) ? true : false,
						render : function (data, type, row, meta) {
							var html = data > 0 ? data + '%' : '';
							if (row.percentageGradingBest == 1) {
								html += ' <i style="color: #e88112;" class="fa fa-star" aria-hidden="true" title="New percenatge grading personal best"></i>'
							}
							return html;
						}
					}
				],
				processing : true,
				autoWidth : false,
				scrollX : true,
				order : [[0, "asc"], [2, "asc"]],
				ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/race/' + race.id)
			});
		}

		function getResultImprovement(previousTimeInSeconds, newTimeInSeconds) {

			var secondsImprovment = parseFloat(previousTimeInSeconds) - parseFloat(newTimeInSeconds);
			
			var result = [];
			if (secondsImprovment > 60) {
				result.push(Math.floor(secondsImprovment / 60));
				result.push(Math.round(((secondsImprovment % 60) + Number.EPSILON) * 100) / 100);
			} else {
				result.push(Math.round((secondsImprovment + Number.EPSILON) * 100) / 100);
			}

			return result;
		}

		function getAjaxRequest(url) {
			return {
				"url" : '<?php echo esc_url(home_url()); ?>' + url,
				"method" : "GET",
				"headers" : {
					"cache-control" : "no-cache"
				},
				"dataSrc" : ""
			}
		}

		function getRaceInsightsData(eventId) {
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events/' + eventId + '/insights'))
					.done(function(data) {
						showRaceInsightsData(data.years);
						showEventInsightsData(data.distance);
					});
		}

		function showRaceInsightsData(raceData) {
			
			var showPanel = false;
			var distanceData = getDistinctRaceDistanceData(raceData);
			distanceData.forEach(distance => {
				if (distance.data.length > 4 && distance.distance != null) {
					createRaceInsightsChart(distance.data, distance.distance);
					showPanel = true;
				}		
			});

			if (showPanel) {
				$('#race-insights-panel').show();			
			}
		}

		function getDistinctRaceDistanceData(data) {
			var distinct = [];
			for (var i = 0; i < data.length; i++) {
				var index = distinct.findIndex(o => o.distance === data[i].distance);
				if (index < 0) {
					distinct.push({distance : data[i].distance, data: new Array(data[i])});
				} else {
					distinct[index].data.push(data[i]);
				}
			}

			return distinct;
		}

		function showEventInsightsData(insights) {
			$('#race-insights-panel').append('<h4>Event Records</h5>');
			$('#race-insights-panel').append('<p><small>Please note these are the Ipswich JAFFA Event records and are indepedent of course which may have changed over the duration of our event results.</small></p>');
			insights.forEach(distance => {
				$('#race-insights-panel').append('<h5> Distance: ' + distance.distance + '</h5>');
				$('#race-insights-panel').append('<p>Total results: <strong>' + distance.count +
					'</strong>, Average time: <strong>' + distance.mean +
					'</strong>, Fastest time: <strong>' + distance.min +
					'</strong>, Slowest time: <strong>' + distance.max + 
					'</strong></p>');
			});
		}

		function createRaceInsightsChart(data, distance) {
			$('#race-insights-panel').show();

			var containerDiv = document.createElement('div');    
			containerDiv.id = "distance-" + distance;
			containerDiv.className = "race-insights-chart";
			document.getElementById('race-insights-chart').appendChild(containerDiv);

			am5.ready(function() {

				var root = am5.Root.new(containerDiv.id);

				root.setThemes([
					am5themes_Animated.new(root)
				]);

				// Create chart
				var chart = root.container.children.push(am5xy.XYChart.new(root, {	
					panX: false,
					panY: false,
					wheelY: "none",
					pinchZoomX:true
				}));	
				chart.zoomOutButton.set("forceHidden", true);		

				// Create axes
				// https://www.amcharts.com/docs/v5/charts/xy-chart/axes/
				var xRenderer = am5xy.AxisRendererX.new(root, { minGridDistance: 30 });
				xRenderer.labels.template.setAll({
					rotation: -90,
					centerY: am5.p50,
					centerX: am5.p100,
					paddingRight: 15
				});

				var yearXAxis = chart.xAxes.push(am5xy.CategoryAxis.new(root, {
					maxDeviation: 0.3,
					categoryField: "year",
					renderer: xRenderer
				}));

				var countAxisRenderer = am5xy.AxisRendererY.new(root, {});
				countAxisRenderer.grid.template.set("forceHidden", true);
				var countYAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
					maxDeviation: 0.3,
					renderer: countAxisRenderer,
					tooltip: am5.Tooltip.new(root, {})
				}));

				// Line series (date) axis 				
				var timeYAxisRenderer = am5xy.AxisRendererY.new(root, {opposite: true});
				timeYAxisRenderer.grid.template.set("forceHidden", true);
				var timeYAxis = chart.yAxes.push(am5xy.DateAxis.new(root, {
					groupData: false,
					tooltipDateFormat: "HH:mm:ss",
    				baseInterval: { timeUnit: "minute", count: 1 },
					extraMax: 0.02,
					extraMin: 0.02,
					renderer: timeYAxisRenderer
				}));

				timeYAxisRenderer.grid.template.set("forceHidden", true);				

				// Create series for count				
				var countSeries = chart.series.push(am5xy.ColumnSeries.new(root, {
					xAxis: yearXAxis,
					yAxis: countYAxis,
					valueYField: "count",
					sequencedInterpolation: true,
					categoryXField: "year"
				}));

				var tooltip = countSeries.set("tooltip", am5.Tooltip.new(root, {
					pointerOrientation: "horizontal"
				}));

				if (selectedRaceCourseTypeId == 1 || selectedRaceCourseTypeId == 2 || selectedRaceCourseTypeId == 3) {
					tooltipText = "[bold]{categoryX}:[/]\n[width: 140px]Finishers[/] {count}\n[width: 140px]Mean time[/] {mean}\n[width: 140px]Fastest time[/] {min}\n[width: 140px]Last finisher time[/] {max}"
				} else {
					tooltipText = "[bold]{categoryX}:[/]\n[width: 140px]Finishers[/] {count}"
				}

				tooltip.label.setAll({
					text: tooltipText
				});

				countSeries.data.processor = am5.DataProcessor.new(root, {
					numericFields: ["count"]
				});

				countSeries.columns.template.setAll({ cornerRadiusTL: 5, cornerRadiusTR: 5 });
				countSeries.columns.template.adapters.add("fill", function(fill, target) {
					return chart.get("colors").getIndex(countSeries.columns.indexOf(target));
				});

				countSeries.columns.template.adapters.add("stroke", function(stroke, target) {
					return chart.get("colors").getIndex(countSeries.columns.indexOf(target));
				});

				// Line series for min, mean, max
				function createTimeSeries(field) {
					var lineSeries = chart.series.push(am5xy.LineSeries.new(root, {
						name: field,
						connect: true,
						xAxis: yearXAxis,
						yAxis: timeYAxis,
						valueYField: field,
						categoryXField: "year"
					}));

					lineSeries.strokes.template.setAll({ strokeWidth: 2 });

					lineSeries.bullets.push(function() {
						var graphics = am5.Circle.new(root, {
							strokeWidth: 2,
							radius: 5,
							stroke: lineSeries.get("stroke"),
							fill: root.interfaceColors.get("background"),
						});
						
						return am5.Bullet.new(root, {
							sprite: graphics
						});	
					});

					lineSeries.data.setAll(data);
					lineSeries.appear(1000);
				}				
				
				var date = new Date();
  				for (var i = 0; i < data.length; i++) {
					data[i].meanTime = date.setHours(getHours(data[i].mean), getMinutes(data[i].mean), getSeconds(data[i].mean));
    				data[i].minTime = date.setHours(getHours(data[i].min), getMinutes(data[i].min), getSeconds(data[i].min));
    				data[i].maxTime = date.setHours(getHours(data[i].max), getMinutes(data[i].max), getSeconds(data[i].max));
  				}

				// Only display times for road (1),  MT (2) and track races (3)
				if (selectedRaceCourseTypeId == 1 || selectedRaceCourseTypeId == 2 || selectedRaceCourseTypeId == 3) {
					createTimeSeries("meanTime");
					createTimeSeries("minTime");
					createTimeSeries("maxTime");
				}

				// Add cursor
				var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
					xAxis: yearXAxis,
  					yAxis: countYAxis
				}));
				
				cursor.lineX.set("visible", false);

				chart.children.unshift(am5.Label.new(root, {
					text: "Race distance "+ distance,
					fontSize: 18,
					fontWeight: "500",
					textAlign: "center",
					x: am5.percent(50),
					centerX: am5.percent(50),
					paddingTop: 0,
  					paddingBottom: 0
				}));

				yearXAxis.data.setAll(data);
				countSeries.data.setAll(data);

				// Make stuff animate on load
				// https://www.amcharts.com/docs/v5/concepts/animations/
				countSeries.appear(1000);
				chart.appear(1000, 100);

				}); // end am5.ready()
		}

		function getHours(time) {
			return time ? time.substring(0, 2) : 0;
		}

		function getMinutes(time) {
			return time ? time.substring(3, 5) : 0;
		}

		function getSeconds(time) {
			return time ? time.substring(6, 8) : 0;
		}
	});
	<?php endif;?>
</script>
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
	
	jQuery(document).ready(function ($) {

		getMeetingDetails(<?php echo $_GET['raceId']; ?>, true);

		// Get race - get meeting details for event and date
		// Returns - races, teams/team results, event, meeting (report, dates)
		// Get event insights

		// Leagues? Are races part of a league

		$('#jaffa-other-race-results').change(function () {
			var raceId = $('#jaffa-other-race-results').val();
			if (raceId == 0)
				return;

			$('#jaffa-race-results').empty();
			getMeetingDetails(raceId, false);
			document.getElementById("jaffa-race-results").scrollIntoView();
		});

		function setEventName(name) {
			$('#jaffa-event-title').html(name);
		}

		function getMeetingDetails(raceId, isEventMetaDataRequired) {
			$.ajax(getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/meetingdetails/races/'+raceId))
				.done(function(response) {
					setEventName(response.event.name);
					processMeeting(response.meeting);
					if (response.teams?.length > 0) {
						setTeamResults(response.teams);
					}
					for (var i = 0; i < response.races.length; i++) {
						getRaceResult(response.races[i]);
					}

					if (isEventMetaDataRequired) {
						getEventRaces(response.event.id);
						getRaceInsightsData(response.event.id);
					}
				}
			);
		}

		function processMeeting(meeting) {
			if (meeting.id != 0) { // Ignore virtual meetings
				var meetingDates = meeting.fromDate;
				if (meeting.fromDate != meeting.toDate) {
					meetingDates += ' - ' + meeting.toDate;
				}

				var meetingTitle = '<h3>Meeting: '+meeting.name+' ('+meetingDates+')</h3>';
				$('#jaffa-race-results').prepend(meetingTitle);
			}
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
				.done(function(races) {
					var options = '<option>Please select...</option>';
					var date = 0;
					var raceId = 0;
					var resultsCount = 0;
					
					for (var i = 0; i < races.length; i++) {
						if (races[i].date != date) {
							if (date != 0) {
								options += '<option value="' + raceId + '">' + date + ' (' + resultsCount + ' results)</option>';
							}
							resultsCount = Number(races[i].count);
							date = races[i].date;
							raceId = races[i].id;
						} else {
							resultsCount += Number(races[i].count);
						}
					}
					options += '<option value="' + raceId + '">' + date + ' (' + resultsCount + ' results)</option>';

					$('#jaffa-other-race-results').append(options);
			});
		}

		function getResultsTitle(race) {
			var title = '';

			if (race.description) {
				title += race.description + ', ';
			}

			title += ipswichjaffarc.formatDate(race.date);

			if (race.distance) {
				title += " | " + race.distance;
			}

			if (race.courseType) {
				title += " | " + race.courseType;
			}

			if (race.conditions) {
				title += " | " + race.conditions;
			}

			if (race.venue) {
				title += " | " + race.venue;
			}

			if (race.county) {
				title += " | " + race.county;
			}

			if (race.area) {
				title += " | " + race.area;
			}

			if (race.countryCode) {
				title += " | " + race.countryCode;
			}

			return title;
		}

		function getRaceResult(race) {
			var courseTypeIdsToDisplayImprovements = ["1", "3", "6"];
			var resultColumnTitle;
			var tableName = 'jaffa-race-results-table-';
			if  (race.resultUnitTypeId == "3") {
				resultColumnTitle = 'Distance';
			} else {
				resultColumnTitle = 'Time';
			}
			
			var title = getResultsTitle(race);
			
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
						data : "position",
						name : "position"
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
						data : "performance",
						render : function(data, type, row, meta) {
							if (race.resultUnitTypeId == "3") {
								return Number(data).toLocaleString();
							}

							return ipswichjaffarc.secondsToTime(row.performance);
						},
						name: 'performance',
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
						//visible: courseTypeIdsToDisplayImprovements.includes(race.courseTypeId) ? true : false,
						name : "standardType"
					}, {
						data : "info"
					}, {
						data : "percentageGrading",
						//visible: courseTypeIdsToDisplayImprovements.includes(race.courseTypeId) ? true : false,
						render : function (data, type, row, meta) {
							var html = data > 0 ? data + '%' : '';
							if (row.percentageGradingBest == 1) {
								html += ' <i style="color: #e88112;" class="fa fa-star" aria-hidden="true" title="New percenatge grading personal best"></i>'
							}
							return html;
						},
						name : "percentageGrading"
					}
				],
				footerCallback: function ( row, data, start, end, display ) {
					// Hide column if value is all zeroes / empty
					var api = this.api();
					var nonEmpty = (x) => x != '' && x != undefined;
					var nonZero = (x) => x > 0;
					showHideColumn(api, 'performance:name', nonZero);
					showHideColumn(api, 'percentageGrading:name', nonZero);
					showHideColumn(api, 'position:name', nonZero);
					showHideColumn(api, 'standardType:name', nonEmpty);
				},
				processing : true,
				autoWidth : false,
				scrollX : true,
				order : [[0, "asc"], [2, "asc"]],
				ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/race/' + race.id)
			});
		}

		function showHideColumn(api, columnName, expression) {
			var visible = api
				.column( columnName, { page: 'current'} )
				.data()
				.toArray()
				.some(expression);
					
			$(api.column(columnName).visible(visible));
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
				if (distance.data.length > 4) {
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
			$('#race-insights-panel').append('<p><small>Please note these are the Ipswich JAFFA Event records and are independent of course which may have changed over the duration of our event results.</small></p>');
			insights.forEach(distance => {
				$('#race-insights-panel').append('<h5>Distance: ' + distance.distance + '</h5>');
				$('#race-insights-panel').append('<p>Total results: <strong>' + distance.count +
					'</strong>, average time: <strong>' + ipswichjaffarc.secondsToTime(distance.meanPerformance) +
					'</strong>, fastest time: <strong>' + ipswichjaffarc.secondsToTime(distance.minPerformance) +
					'</strong> was achieved by <strong><a href="<?php echo $memberResultsPageUrl; ?>?runner_id=' + distance.fastestRunnerId + '">' + distance.fastestRunnerName +
					'</a></strong> at the ' + ipswichjaffarc.formatDate(distance.fastestRaceDate) +
					' race, slowest time: <strong>' + ipswichjaffarc.secondsToTime(distance.maxPerformance) + 
					'</strong>.</p>');
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

				root.durationFormatter.setAll({
					baseUnit: "second",
					durationFormat: "mm:ss"
				});

				// Create chart
				var chart = root.container.children.push(am5xy.XYChart.new(root, {
					panX: false,
					panY: false,
					wheelY: "none",
					pinchZoomX: true
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

				// Duration series (seconds) axis
				var timeYAxisRenderer = am5xy.AxisRendererY.new(root, {opposite: true});
				timeYAxisRenderer.grid.template.set("forceHidden", true);
				var timeYAxis = chart.yAxes.push(am5xy.DurationAxis.new(root, {
					baseUnit: "second",
					extraMax: 0.02,
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

				if (distance) {
					tooltipText = "[bold]{categoryX}:[/]\n[width: 140px]Finishers[/] {count}\n"
					+"[width: 140px]Mean time[/] {mean}\n[width: 140px]Fastest time[/] {min}\n"
					+"[width: 140px]Last finisher time[/] {max}"
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

					lineSeries.data.processor = am5.DataProcessor.new(root, {
						numericFields: [field]
					});

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

				// Only display times for defined distance races.
				if (distance) {
					createTimeSeries("meanPerformance");
					createTimeSeries("minPerformance");
					createTimeSeries("maxPerformance");
				}

				// Add cursor
				var cursor = chart.set("cursor", am5xy.XYCursor.new(root, {
					xAxis: yearXAxis,
  					yAxis: countYAxis
				}));
				
				cursor.lineX.set("visible", false);

				var chartTitle = '';
				if (distance) {
					chartTitle = "Race distance " + distance;
				}
				chart.children.unshift(am5.Label.new(root, {
					text: chartTitle,
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
	});
</script>
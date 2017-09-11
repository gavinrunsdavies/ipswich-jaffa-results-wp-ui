<div class="section">
	<div class="jumbotron center-panel">
		<div class="row">
			<div class="col-md-3 col-md-offset-3"><strong>Name:</strong></div>
			<div class="col-md-3 runnerName"></div>
		</div>
		<div class="row">
			<div class="col-md-3 col-md-offset-3"><strong>Gender:</strong></div>
			<div class="col-md-3 runnerGender"></div>
		</div>
		<div class="row">
			<div class="col-md-3 col-md-offset-3"><strong>Age Group:</strong></div>
			<div class="col-md-3 runnerAgeCategory"></div>
		</div>
		<div class="row">
			<div class="col-md-3 col-md-offset-3"><strong>Standard Certificates:</strong></div>
			<div class="col-md-3"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#standardCertificatesModal">
  Show certificates
</button></div>
		</div>
	</div>	
	<div class="center-panel">
		<table class="table table-striped table-bordered" id="member-ranking-table">
		<caption style="font-weight:bold">Club Rankings for <span class="runnerName"></span></caption>
			<thead>
				<tr>
					<th></th>
					<th>5k</th>
					<th>5m</th>
					<th>10k</th>
					<th>10m</th>
					<th>HM</th>
					<th>20m</th>
					<th>M</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		
		<p style="font-size: smaller">The above Ipswich JAFFA Running Club rankings show where <span class="runnerName"></span> ranks amongst other Ipswich JAFFA members (past and present). Ranking category: <span class="runnerGender"></span>.</p>
		<p style="font-size: smaller">Click on an above ranking to find out more information about the result.</p>
	</div>
	<div class="center-panel">
		<table class="table table-striped table-bordered" id="member-race-count-table">
		<caption style="font-weight:bold">Race distance breakdown for <span class="runnerName"></span></caption>
			<thead>
				<tr>			
					<th></th>
					<th>5k</th>
					<th>5m</th>
					<th>10k</th>
					<th>10m</th>
					<th>HM</th>
					<th>20m</th>
					<th>M</th>
					<th>Other</th>
					<th>Not Measured</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<p style="font-size: smaller">The above totals are only valid for measured race distances (e.g. those that can count towards a personal best).</p>
		<p style="font-size: smaller">The total distance covered calculation is only approximate and the accuracy of older results is not guaranteed.</p>
	</div>
	<div class="center-panel">
		<div class="row">
			<div class="col-md-6">
				<h3>Race distance summary</h3>
				<div id="race-distance-chart" style="height: 250px;"></div>
			</div>
			<div class="col-md-6">
				<h3>Course type summary</h3>
				<div id="course-type-chart" style="height: 250px;"></div>
			</div>			
		</div>
	</div>
	<div class="center-panel">
		<div id="member-race-predictions-current">
			<table class="table table-striped table-bordered" id="member-race-predictions-current-table">			
			<caption style="font-weight:bold">Race predictions based on best known performances for last year of competition (<span class="lastYearOfCompetition"></span>)</caption>
				<thead>
					<tr>
						<th></th>
						<th>5k</th>
						<th>5m</th>
						<th>10k</th>
						<th>10m</th>
						<th>HM</th>
						<th>20m</th>
						<th>M</th>
					</tr>
				</thead>			
				<tbody>		
				</tbody>
			</table>
		</div>
		<div id="member-race-predictions-best">
			<table class="table table-striped table-bordered" id="member-race-predictions-best-table">
			<caption style="font-weight:bold">Race predictions based on best known performances.</caption>
				<thead>
					<tr>
						<th></th>
						<th>5k</th>
						<th>5m</th>
						<th>10k</th>
						<th>10m</th>
						<th>HM</th>
						<th>20m</th>
						<th>M</th>
					</tr>
				</thead>				
				<tbody>
				</tbody>
			</table>
		</div>
		<p style="font-size: smaller">The above race predictions are based on the known performances and calculated using the formula: T2 = T1 x (D2/D1)^1.06, where D1 is known distance, D2 is target distance, T1 is result for distance D1 and T2 is predicted time for target distance D2. Read predictions in columns not rows. Entries in bold show the achieved time (T1).</p>
		<br />
	</div>
	<div class="center-panel">
		<table class="table table-striped table-bordered" id="member-seasonal-best-results-table">
		<caption style="font-weight:bold">The Best Known Performances for <span class="runnerName"></span></caption>
			<thead>
				<tr>
					<th>Year</th>
					<th>5k</th>
					<th>5m</th>
					<th>10k</th>
					<th>10m</th>
					<th>HM</th>
					<th>20m</th>
					<th>M</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Year</th>
					<th>5k</th>
					<th>5m</th>
					<th>10k</th>
					<th>10m</th>
					<th>HM</th>
					<th>20m</th>
					<th>M</th>
				</tr>
			</tfoot>
			<tbody>
			</tbody>
		</table>
	</div>	
	<div class="center-panel">
		<table class="table table-striped table-bordered" id="member-results-table">
			<thead>
				<tr>
					<th data-hide="always">Race Id</th>	
					<th>Race</th>
					<th>Date</th>
					<th>Position</th>
					<th>Result</th>
					<th>Personal Best</th>
					<th>Standard</th>
					<th>Info</th>
					<th>Age Grading</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th data-hide="always">Race Id</th>	
					<th>Race</th>
					<th>Date</th>
					<th>Position</th>
					<th>Result</th>
					<th>Personal Best</th>
					<th>Standard</th>
					<th>Info</th>
					<th>Age Grading</th>
				</tr>
			</tfoot>
			<tbody>			
			</tbody>
		</table>
	</div>
</div>
<div class="modal fade" id="standardCertificatesModal" tabindex="-1" role="dialog" aria-labelledby="Standard Certificates">
  <div class="modal-dialog modal-lg" style="z-index:10000" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Standard Certificates achieved by <span class="runnerName"></span></h4>
	  </div>
	  <div class="modal-body">		
		<table class="table table-bordered" id="member-certificates-table">
			<thead>
				<tr>
					<th>Standard</th>
					<th>Details</th>
					<th>Certificate</th>
				</tr>
			</thead>
			<tbody style="width: 100%; height: 500px;">			
			</tbody>
		</table>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- amCharts javascript sources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/pie.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {	
		
		var rankings;
		$.getJSON(
			'<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/runners/<?php echo $_GET['runner_id']; ?>',
			function(data) {	
				// {"id":"116","name":"Gavin Davies","sexId":"2","dateOfBirth":"1980-05-16","isCurrentMember":"1","sex":"Male","certificates": array[], "rankings" : array[]}
				$('.runnerName').text(data.name);
				$('.runnerAgeCategory').text(data.ageCategory);
				$('.runnerGender').text(data.sex);
				rankings = data.rankings;
				populateCertificatesTable(data.name, data.certificates);
				populateRankingsTable(data.rankings);
			}
		);			
					
		var supportedDistanceIds = [1,2,3,4,5,7,8];
		var results;
		var allDistances;
		$.when(
			$.getJSON(
				'<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/distances',
				function(data) {	
					allDistances = data;
				}
			),
			$.getJSON(
				// {"eventId":"89","eventName":"Jaffa Handicap 5","distanceId":"2","id":"82722","date":"2016-07-25","raceName":"","raceId":"3965","position":"22","time":"00:28:07","result":"00:28:07","isPersonalBest":"0","isSeasonBest":"0","standard":"3 Star","info":"","percentageGrading":"77.99"}
				'<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/runner/<?php echo $_GET['runner_id']; ?>',
				function(data) {
					results = data;
					createResultsDataTable(data);
				}
			)
		).then( function(){			 
			 processResults(results, allDistances);
		 });
				
		function processResults(data, distances) {	
			
			// Get PBs by year. Data returned sorted by date (descending)
			var seasonalBest = [];
			var personalBest = [];
			var raceDistanceCount = [];
			var courseTypeCount = [];
			var otherRaceDistanceCount = 0;
			
			$.each(data, function(i, result){		
											
				var resultYear = result.date.substring(0, 4);
				if (seasonalBest[resultYear] === undefined) {
					seasonalBest[resultYear] = [];					
					seasonalBest[resultYear][result.distanceId] = result;											
				} else {
					if (seasonalBest[resultYear][result.distanceId] === undefined || (result.result < seasonalBest[resultYear][result.distanceId].result || 
							(result.result == seasonalBest[resultYear][result.distanceId].result && result.date < seasonalBest[resultYear][result.distanceId].date))) {
						seasonalBest[resultYear][result.distanceId] = result;					
					}
				}
									
				if (result.isPersonalBest == 1) {
					if (personalBest[result.distanceId] === undefined || personalBest[result.distanceId].result > result.result) {
						personalBest[result.distanceId] = result;	
					}
				}
				
				var distanceId = result.distanceId;
				if (distanceId == null)
					distanceId = 0;
				else 
					distanceId = parseInt(distanceId);
					
				if (supportedDistanceIds.indexOf(distanceId) == -1 && distanceId != 0) {
					otherRaceDistanceCount++;
				}
				
				if (raceDistanceCount[distanceId] === undefined)
					raceDistanceCount[distanceId] = 0;
				raceDistanceCount[distanceId] += 1;
				
				var courseTypeId = result.courseTypeId;
				if (courseTypeId == null)
					courseTypeId = 0;
				if (courseTypeCount[courseTypeId] === undefined)
					courseTypeCount[courseTypeId] = 0;
				courseTypeCount[courseTypeId] += 1;
			});
							
			populateRaceCountTable(raceDistanceCount, otherRaceDistanceCount);
			populateSeaonalBestTable(seasonalBest);
			populateLatestRacePredictorTable(seasonalBest, data[0].date.substring(0, 4));
			populateAllTimeRacePredictorTable(personalBest);
			createRaceDistancePieChart(raceDistanceCount, otherRaceDistanceCount);
			createCourseTypePieChart(courseTypeCount);
		}
		
		function getDistance(distanceId) {
			for (var i = 0; i < allDistances.length; i++) {
				if (parseInt(allDistances[i].id) === distanceId) {
				  return allDistances[i];
				}
			}
			
			return null;
		}
		
				
		function populateRankingsTable(rankings) {
			var tableBody = $('#member-ranking-table tbody');
				
			var rows = '';				
			rows += '<tr>';						
			rows += '<td>All Time</td>';
			$.each(supportedDistanceIds, function(i, distanceId) {										
				$.each(rankings, function(j, rank) {		
					if (distanceId == rank.distanceId) {
						rows += '<td ><a tabindex="0" role="button" class="rank-result" data-contenttitle="All time ranking" data-overallrankid="' + rank.resultId + '">' + rank.rank + '</a></td>';		
						return;
					}
				});
			});
			rows += '</tr>';	
			
			tableBody.append(rows);		
		}
		
		function populateLatestRacePredictorTable(data, year) {
			var tableId = '#member-race-predictions-current-table';
			var tableBody = $(tableId + ' tbody');
				
			var rows = '';
					
			$.each(supportedDistanceIds, function(k, distanceId) {
				rows += '<tr>';	
				var distance = getDistance(distanceId);
				if (distance != null) {
					rows += '<td>' +distance.text+ '</td>';				
					$.each(supportedDistanceIds, function(k2, distanceId2) {							
						if (data[year][distanceId] !== undefined) {
							if (distanceId == distanceId2) {
								rows += '<td class="success"><strong>' + data[year][distanceId].result + '</strong></td>';
							} else {
								rows += '<td>'+ getPredictedTime(distanceId, data[year][distanceId].result, distanceId2) +'</td>';
							}
						} else {
							rows += '<td></td>';
						}					
					});	
					rows += '</tr>';	
				}
			});			
			
			tableBody.append(rows);
			$('.lastYearOfCompetition').text(year);
		}
		
		function populateAllTimeRacePredictorTable(data) {
			var tableId = '#member-race-predictions-best-table';
			var tableBody = $(tableId + ' tbody');
			
			var rows = '';
						
			$.each(supportedDistanceIds, function(k, distanceId) {
				var distance = getDistance(distanceId);
				if (distance != null) {
					rows += '<tr>';	
					rows += '<td>' +distance.text+ '</td>';				
					$.each(supportedDistanceIds, function(k2, distanceId2) {							
						if (data[distanceId] !== undefined) {
							if (distanceId == distanceId2) {
								rows += '<td class="success"><strong>' + data[distanceId].result + '</strong></td>';
							} else {
								rows += '<td>'+ getPredictedTime(distanceId, data[distanceId].result, distanceId2) +'</td>';
							}
						} else {
							rows += '<td></td>';
						}					
					});	
					rows += '</tr>';
				}
			});			
			
			tableBody.append(rows);
		}
		
		function populateSeaonalBestTable(data) {
			var tableId = '#member-seasonal-best-results-table'
			var tableBody = $(tableId + ' tbody');
				
			var rows = '';
						
			for (var i = data.length - 1; i > 0; i--) {
				var year = i;	
				if (data[year] === undefined)
					continue;
				
				rows += '<tr>';
				rows += '<td>' + year + '</td>';
				
				$.each(supportedDistanceIds, function(k, distanceId) {
					if (data[year][distanceId] !== undefined) {
						rows += '<td>' + data[year][distanceId].time + '</td>';
					} else {
						rows += '<td></td>';
					}
				});
					
				rows += '</tr>';
			}
			
			tableBody.append(rows);
		}
		
		function populateCertificatesTable(name, data) {
			if (data === null)
				return;
			var tableBody = $( '#member-certificates-table tbody');
			
			var rows = '';
					
			$.each(data, function(i, cert) {
				rows += '<tr>';	
				rows += '<td>' + cert.name+ '</td>';
				rows += '<td>' + cert.event + ', on ' + cert.date +'. Time ' + cert.result + '</td>';
				rows += '<td><a href="' + getStandardCertificatesUrl(name, cert) +'" target="_blank">View</a></td>';				
				rows += '</tr>';	
			});
			
			tableBody.append(rows);
		}
		
		function populateRaceCountTable(data, otherRaceDistanceCount) {
			var tableId = '#member-race-count-table';
			var tableBody = $(tableId + ' tbody');
						
			var sum  = data.reduce(function(a, b) { return a + b; }, 0);
			var rows = '<tr>';				
			rows += '<td>Count</td>';
			for(var i = 0; i < supportedDistanceIds.length; i++) {
				var count = data[supportedDistanceIds[i]] === undefined ? 0 : data[supportedDistanceIds[i]];
				rows += '<td>' + count + '</td>';
			}
						
			rows += '<td>' + otherRaceDistanceCount + '</td>';
			// Not measured			
			rows += '<td>' + (data[0] === undefined ? 0 : data[0]) + '</td>';
			rows += '<td>' + sum + '</td>';					
			rows += '</tr>';	
						
			rows += '<tr>';	
			rows += '<td>Distance (miles)</td>';
			for(var i = 0; i < supportedDistanceIds.length; i++) {
				var miles;
				if (data[supportedDistanceIds[i]] === undefined)
					miles = 0;
				else
					miles = getTotalRaceDistanceInMiles(supportedDistanceIds[i], data[supportedDistanceIds[i]]).toFixed(2);
				rows += '<td>' + miles + '</td>';
			}		

			var otherDistanceMilesSum = 0;
			for (var distanceId in data) {
				distanceId = parseInt(distanceId);
				if (supportedDistanceIds.indexOf(distanceId) == -1 && distanceId != 0) {
					otherDistanceMilesSum += getTotalRaceDistanceInMiles(distanceId, data[distanceId]);
				}
			}
			
			rows += '<td>' + otherDistanceMilesSum.toFixed(2) + '</td>';
			rows += '<td></td>';
			var totalDistance = data.reduce(function(total, currentValue, currentIndex) { return getTotalRaceDistanceInMiles(currentIndex, currentValue) + total; }, 0);
			rows += '<td>' + totalDistance.toFixed(2) + '</td>';					
			rows += '</tr>';
			tableBody.append(rows);
		}
		
		function getTotalRaceDistanceInMiles(distanceId, number) {
			var distance = getDistance(distanceId);
			if (distance != null) {
				return (distance.miles * number);
			}
						
			return 0;					
		}
		
		function getStandardCertificatesUrl(name, cert) {
			 		
			return '<?php echo plugins_url('php/standards/printcertificate.php', dirname(__FILE__)); ?>' +
			'?name=' + name +
			'&standard=' + cert.name +
			'&event=' + cert.event +
			'&date=' + cert.date +
			'&time=' + cert.result +
			'&filepath=<? echo plugin_dir_path(dirname(__FILE__)); ?>php/standards/';
		}
    
    function addPadding(number, size) {
      var s = String(number);
      while (s.length < (size || 2)) {s = "0" + s;}
      return s;
    }
		
		function getPredictedTime(actualDistanceId, actualTime, targetDistanceId) {

			var actualDistance = getDistance(actualDistanceId);
			var targetDistance = getDistance(targetDistanceId);
			var timeComponents = actualTime.split(':');
			var actualTotalMinutes = (parseInt(timeComponents[0]) * 60) + parseInt(timeComponents[1]) + (parseInt(timeComponents[2]) / 60);
			
			var targetTotalMinutes = actualTotalMinutes * (Math.pow((targetDistance.miles / actualDistance.miles), 1.06));
			
			var hours = Math.floor(targetTotalMinutes / 60);
		
			var minutes = Math.floor(targetTotalMinutes % 60);
			
			var seconds =  Math.floor(((targetTotalMinutes % 60) - minutes) * 60).toString();
		
			var targetTime = hours + ':' + minutes + ':' + addPadding(seconds, 2);
		
			return targetTime;
		}

		function createResultsDataTable(data) {
			$('#member-results-table').DataTable({
				pageLength : 20,
				serverSide : false,
				processing : true,
				autoWidth : false,
				order: [[ 2, "desc" ]],
				scrollX: true,
				data: data,
				columns: [
					{ 
						data: "raceId",
						visible: false  
					},
					{
						data: "raceName",
						render: function ( data, type, row, meta ) {	
							var resultsUrl = '<?php echo $raceResultsPageUrl; ?>';
							var anchor = '<a href="' + resultsUrl;						
							anchor += '?raceId=' + row.raceId;						
							anchor += '">' + row.eventName + nullToEmptyString(data) + '</a>';								
							return anchor;
						}
					},
					{
						data: "date"					
					},
					{
						data: "position",
						render : function (data, type, row, meta) {
							return data > 0 ? data : '';
						}
					},
					{
						data: "result",
						render : function (data, type, row, meta) {
							return data != '00:00:00' ? data : '';
						}
					},
					{
						data: "isPersonalBest",
						render : function (data, type, row, meta) {							
							if (data == 1) {
								return '<span class="glyphicon glyphicon-ok" aria-hidden="true"><span class="hidden">Yes</span></span>';
							} 
							return '';
						},
						className : 'text-center'
					},
					{
						data: "standard"
					},
					{
						data: "info"
					},
					{
						data: "percentageGrading",
						render : function (data, type, row, meta) {
							return data > 0 ? data + '%' : '';
						}
					}
					]
			});
		}
		
		function nullToEmptyString(value) {
			return (value == null || value == "") ? "" : " - " + value;
		}

		$('.rank-result').live( 'click', function () {
			var popover = $(this).popover({
				html: true,
				placement: 'top',
				container: 'body',
				title: function(){
					return $(this).data('contenttitle');
				},
				content: function(){
					var resultId = $(this).data('overallrankid');
					var ranking = getRanking(resultId);
					var html = '';
					if (ranking != null) {
						html = '<div><div class="row"><div class="col-md-6">Event</div><div class="col-md-6">'+ranking.event+'</div>';
						html += '<div class="col-md-6">Date</div><div class="col-md-6">'+ranking.date+'</div>';
						html += '<div class="col-md-6">Result</div><div class="col-md-6">'+ranking.result+'</div>';
						html += '<div class="col-md-6">Position in Event</div><div class="col-md-6">'+ranking.position+'</div>';
						html += '<div class="col-md-6">Rank</div><div class="col-md-6">'+ranking.rank+'</div></div></div>';			
					}
					return html;
				}
			}).popover('show');
		});
		
		function getRanking(resultId) {
			for (var i = 0; i < rankings.length; i++) {
				if (parseInt(rankings[i].resultId) === resultId) {
				  return rankings[i];
				}
			}
			
			return null;
		}
		
		function createRaceDistancePieChart(distances, otherRaceDistanceCount) {
				
			var chart = AmCharts.makeChart("race-distance-chart", {
				"type": "pie",
				"theme": "light",    
				"innerRadius": "40%",
				"gradientRatio": [-0.4, -0.4, -0.4, -0.4, -0.4, -0.4, 0, 0.1, 0.2, 0.1, 0, -0.2, -0.5],
				"dataProvider": [{
					"distance": "5km",
					"count": distances[1]
				}, {
					"distance": "5mi",
					"count": distances[2]
				}, {
					"distance": "10km",
					"count": distances[3]
				}, {
					"distance": "10mi",
					"count": distances[4]
				}, {
					"distance": "Half Marathon",
					"count": distances[5]
				}, {
					"distance": "20mi",
					"count": distances[7]
				}, {
					"distance": "Marathon",
					"count": distances[8]
				}, {
					"distance": "Other",
					"count": otherRaceDistanceCount
				}, {
					"distance": "Unclassified",
					"count": distances[0]
				}],
				"balloonText": "[[value]]",
				"valueField": "count",
				"titleField": "distance",
				"balloon": {
					"drop": true,
					"adjustBorderColor": false,
					"color": "#FFFFFF",
					"fontSize": 16
				}
			});
		}
		
		function createCourseTypePieChart(courses) {
				
			var chart = AmCharts.makeChart("course-type-chart", {
				"type": "pie",
				"theme": "light",    
				"innerRadius": "40%",
				"gradientRatio": [-0.4, -0.4, -0.4, -0.4, -0.4, -0.4, 0, 0.1, 0.2, 0.1, 0, -0.2, -0.5],
				"dataProvider": [{
					"course": "Road",
					"count": courses[1]
				}, {
					"course": "Multi-Terrain",
					"count": courses[2]
				}, {
					"course": "Track",
					"count": courses[3]
				}, {
					"course": "Fell",
					"count": courses[4]
				}, {
					"course": "Cross Country",
					"count": courses[5]
				}, {
					"course": "Indoor",
					"count": courses[6]
				}, {
					"course": "Park",
					"count": courses[7]
				}, {
					"course": "Field",
					"count": courses[8]
				}, {
					"course": "Unclassified",
					"count": courses[0]
				}],
				"balloonText": "[[value]]",
				"valueField": "count",
				"titleField": "course",
				"balloon": {
					"drop": true,
					"adjustBorderColor": false,
					"color": "#FFFFFF",
					"fontSize": 16
				}
			});
		}

	});
</script>
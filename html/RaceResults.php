<div class="section"> 
	<h2 id="jaffa-event-title"></h2>
	<div class="center-panel" id="jaffa-race-results">
	</div>
	<div class="center-panel">
	    <br />
		<form class="form-inline">
		  <div class="form-group">
			<label for="jaffa-other-race-results">Other race results</label>			
			  <select onchange="if (this.value) window.location.href=this.value" class="form-control" id="jaffa-other-race-results">
			  </select>
		  </div>
		</form>
	</div>
</div>
<script type="text/javascript">
	<?php if (isset($_GET['raceId']) || (isset($_GET['eventId']) && isset($_GET['date']))): ?>
	jQuery(document).ready(function ($) {

		// 1. Get race
		// 2. If meetingId is > 0, get meetingId
		// 2.1 Get associated races
		// 3 Get race results (for each race)
		// 4. If no race Id (backwards compatibility) get event races then match on date field (array in response)
		
		<?php if (isset($_GET['raceId'])) { ?> 
			getRace(<?php echo $_GET['raceId']; ?>);
		<?php } else { ?>
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
		<?php } ?>
		
		function setEventName(name) {			
			$('#jaffa-event-title').html(name);
		}
		
		function getRace(raceId) {
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/races/' + raceId))			
				.done(function(raceData) {	
					setEventName(raceData.eventName);
					if (raceData.meetingId > 0) {
						getMeeting(raceData.meetingId);
					} else {
						getRaceResult(raceData, raceData.eventName, raceData.date);
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
							getRaceResult(response.races[i], response.races[i].description, response.races[i].date);
						}		
					}

					if (response.teams != null) {
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

			var tableHeaderRow = '<tr><th>Team</th><th>Category</th><th>Position</th><th>Result</th>';
			tableHeaderRow += '<th colspan="'+maxResults+'">Runners</th>';
			tableHeaderRow += '</tr>';
			
			var tableHtml = '<table class="table table-striped table-bordered no-wrap" id="jaffa-team-results-table">';
			tableHtml += '<caption style="text-align:center;font-weight:bold;font-size:1.5em">Team Results</caption>';
			
			tableHtml += '<thead>';
			tableHtml += tableHeaderRow;
			tableHtml += '</thead>';
			tableHtml += '<tbody>';
			for (var i = 0; i < teams.length; i++) {
				tableHtml += '<tr>';
				tableHtml += '<th>';
				tableHtml += teams[i].teamName;
				tableHtml += '</th>';
				tableHtml += '<th>';
				tableHtml += teams[i].teamCategory;
				tableHtml += '</th>';
				tableHtml += '<td>';
				tableHtml += teams[i].teamPosition;
				tableHtml += '</td>';
				tableHtml += '<td>';
				tableHtml += teams[i].teamResult;
				tableHtml += '</td>';
				for (var j = 0, runnerCount = 1; j < teams[i].results.length; j++, runnerCount++) {
					if (runnerCount != teams[i].results[j].teamOrder) {
						tableHtml += '<td></td>';
						runnerCount++;
					}
					tableHtml += '<td><a href="<?php echo $memberResultsPageUrl; ?>?runner_id=' + teams[i].results[j].runnerId + '">';
					tableHtml += teams[i].results[j].runnerName;
					if (teams[i].results[j].runnerResult != '00:00:00') {
						tableHtml += ' (' + teams[i].results[j].runnerResult + ')';	
					}
					tableHtml += '</a></td>';
				}
				tableHtml += '</tr>';
			}
			tableHtml += '</tbody>';
			tableHtml += '</table>';

			$('#jaffa-race-results').append(tableHtml);
		}
		
		function getEventRaces(eventId) {
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events/'+ eventId + '/races'))					
				.done(function(raceData) {	
					var raceResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
					var url = raceResultsUrl + '?raceId=';						
					var dateOptions = '<option>Please select...</option>';
					var meetingId = 0;
					for (var i = 0; i < raceData.length; i++) {	
						if (raceData[i].meetingId > 0) {
							if (meetingId != raceData[i].meetingId) {
								meetingId = raceData[i].meetingId;
								dateOptions += '<option value="' + url + raceData[i].id + '">' + raceData[i].date + ' (meeting) </option>';
							}
						} else {
							dateOptions += '<option value="' + url + raceData[i].id + '">' + raceData[i].date + ' (' + raceData[i].count + ' results) </option>';
						}
					}					
					
					$('#jaffa-other-race-results').append(dateOptions);
			});
		}
		
		function getRaceResult(race, description, date) {
			var courseTypeIdsToDisplayImprovements = ["1", "3", "6"];
			var tableName = 'jaffa-race-results-table-';
			var tableRow = '<tr><th>Position</th><th>Name</th><th>Time</th><th>Personal Best</th><th>Season Best</th><th>Category</th><th>Standard</th><th>Info</th><th>Age Grading</th></tr>';
			var tableHtml = '<table class="table table-striped table-bordered no-wrap" id="' + tableName + race.id + '">';
			tableHtml += '<caption style="text-align:center;font-weight:bold;font-size:1.5em">' + description + ', ' + formatDate(date) + '</caption>';
			tableHtml += '<thead>';
			tableHtml += tableRow;
			tableHtml += '</thead>';
			tableHtml += '</table>';
			$('#jaffa-race-results').append(tableHtml);
			
			var table = $('#'+tableName + race.id).DataTable({				
				dom: 'tBip',
				buttons: {
					buttons: [{
					  extend: 'print',
					  text: '<i class="fa fa-print"></i> Print',
					  title: $('#jaffa-event-title').text() + ': ' + $('#' +tableName + race.id + ' caption').text(),
					  footer: true					  
					}]
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

								html += ' <span class="glyphicon glyphicon glyphicon-certificate" aria-hidden="true" title="' + tooltip + '"></span>'
							}
							return html;
						}
					}, {
						data : "time"
					}, {
						data : "isPersonalBest",
						visible: courseTypeIdsToDisplayImprovements.includes(race.courseTypeId) ? true : false,
						render : function (data, type, row, meta) {
							if (data == 1) {
                var improvementHtml = '';                
                if (row.previousPersonalBestResult != undefined) {
                  var improvement = getResultImprovement(row.previousPersonalBestResult, row.time);
                  improvementHtml = '<span style="font-size:smaller; vertical-align: middle; font-family: Courier New; font-style: italic;"> -';
                  if (improvement.length > 1)
                    improvementHtml += improvement[0] + '\'' + improvement[1] + '\'\'';
                  else if (improvement.length > 0)
                    improvementHtml += improvement[0] + '\'\'';
                  improvementHtml += '</span>';
                }                
                
								return '<span class="glyphicon glyphicon-ok" aria-hidden="true"><span class="hidden">Yes</span>' + improvementHtml + '</span>';                
							} 
							return '';
						},
						className : 'text-center'
					}, {
						data : "isSeasonBest",
						visible: courseTypeIdsToDisplayImprovements.includes(race.courseTypeId) ? true : false,
						render : function (data, type, row, meta) {
							if (data == 1) {
								return '<span class="glyphicon glyphicon-ok" aria-hidden="true"><span class="hidden">Yes</span></span>';
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
							return data > 0 ? data + '%' : '';
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

		function formatDate(date) {
			return (new Date(date)).toDateString();
		}
    
    function getResultImprovement(previousTime, newTime) {
      var previousTimeUnits = previousTime.split(":");
      var newTimeUnits = newTime.split(":");
      
      var previousTotalSeconds = 0;
      var newTotalSeconds = 0;
      
      if (previousTimeUnits.length > 2) {
        previousTotalSeconds = (3600 * parseInt(previousTimeUnits[0], 10)) + (60 * parseInt(previousTimeUnits[1])) + parseInt(previousTimeUnits[2]);
      } else if (previousTimeUnits.length > 1) {
        previousTotalSeconds = (60 * parseInt(previousTimeUnits[0], 10)) + parseInt(previousTimeUnits[1]);  
      } else {
        previousTotalSeconds = previousTimeUnits[0]; 
      }
      
      if (newTimeUnits.length > 2) {
        newTotalSeconds = (3600 * parseInt(newTimeUnits[0], 10)) + (60 * parseInt(newTimeUnits[1])) + parseInt(newTimeUnits[2]);
      } else if (newTimeUnits.length > 1) {
        newTotalSeconds = (60 * parseInt(newTimeUnits[0], 10)) + parseInt(newTimeUnits[1]);  
      } else {
        newTotalSeconds = newTimeUnits[0]; 
      }
      
      var secondsImprovment = previousTotalSeconds - newTotalSeconds;
      
      var result = [];
      if (secondsImprovment > 60) {
        result.push(Math.floor(secondsImprovment / 60));
        result.push(secondsImprovment % 60);
      } else {
        result.push(secondsImprovment);
      }
      
      return result;
    }
		
		function getAjaxRequest(url) {
			return {				
				"url" : '<?php echo esc_url( home_url() ); ?>' + url,
				"method" : "GET",
				"headers" : {
					"cache-control" : "no-cache"
				},
				"dataSrc" : ""
			}
		}
	});
	<?php endif; ?>
</script>
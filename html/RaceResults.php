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
						getMeeting(raceData.eventId, raceData.meetingId);
						getMeetingRaces(raceData.eventId, raceData.meetingId);
					} else {
						getRaceResult(raceData.id, raceData.eventName, raceData.date);
					}
					getEventRaces(raceData.eventId);
				});
		}
			
		function getMeeting(eventId, meetingId) {
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events/'+ eventId + '/meetings/' + meetingId))					
				.done(function(meetingData) {
					if (meetingData.length > 0) {
						var meetingDates = meetingData[0].fromDate;
						if (meetingData[0].fromDate != meetingData[0].toDate) {
							meetingDates += ' - ' + meetingData[0].toDate;
						}																							
						var meetingTitle = '<h3>Meeting: '+meetingData[0].name+' ('+meetingDates+')</h3>';
						$('#jaffa-race-results').prepend(meetingTitle);						
					}
			});
		}
			
		function getMeetingRaces(eventId, meetingId) {
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events/'+ eventId + '/meetings/' + meetingId + '/races'))					
				.done(function(meetingData) {					
					for (var i = 0; i < meetingData.length; i++) {	
						getRaceResult(meetingData[i].id, meetingData[i].description, meetingData[i].date);
					}					
			});
		}
		
		function getEventRaces(eventId) {
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events/'+ eventId + '/races'))					
				.done(function(raceData) {	
					var raceResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
					var url = raceResultsUrl + '?raceId=';						
					var dateOptions = '<option>Please select...</option>';
					for (var i = 0; i < raceData.length; i++) {							
						dateOptions += '<option value="' + url + raceData[i].id + '">' + raceData[i].date + ' (' + raceData[i].count + ' results) </option>';
					}					
					
					$('#jaffa-other-race-results').append(dateOptions);
			});
		}
		
		function getRaceResult(raceId, description, date) {
			var tableName = 'jaffa-race-results-table-';
			var tableHtml = '';
			var tableRow = '<tr><th>Position</th><th>Name</th><th>Time</th><th>Personal Best</th><th>Season Best</th><th>Category</th><th>Standard</th><th>Info</th><th>Age Grading</th></tr>';
			tableHtml += '<table class="table table-striped table-bordered no-wrap" id="' + tableName + raceId + '">';
			tableHtml += '<caption style="text-align:center;font-weight:bold;font-size:1.5em">' + description + ', ' + formatDate(date) + '</caption>';
			tableHtml += '<thead>';
			tableHtml += tableRow;
			tableHtml += '</thead>';
			tableHtml += '</table>';
			$('#jaffa-race-results').append(tableHtml);
			
			var table = $('#'+tableName + raceId).DataTable({				
				dom: 'tBip',
				buttons: {
					buttons: [{
					  extend: 'print',
					  text: '<i class="fa fa-print"></i> Print',
					  title: $('#jaffa-event-title').text() + ': ' + $('#' +tableName + raceId + ' caption').text(),
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
						data : "standardType"
					}, {
						data : "info"
					}, {
						data : "percentageGrading",
						render : function (data, type, row, meta) {
							return data > 0 ? data + '%' : '';
						}
					}
				],
				processing : true,
				autoWidth : false,
				scrollX : true,
				order : [[0, "asc"], [2, "asc"]],
				ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/race/' + raceId)
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
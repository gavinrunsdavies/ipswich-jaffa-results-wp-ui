<div class="section"> 
	<h2 id="jaffa-event-title"></h2>
	<div class="center-panel" id="jaffa-race-results">
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
							return '<input type="checkbox" value="1" disabled="disabled"' + (data == 1 ? ' checked="checked"' : '') + '/>';
						},
						className : 'text-center'
					}, {
						data : "isSeasonBest",
						render : function (data, type, row, meta) {
							return '<input type="checkbox" value="1" disabled="disabled"' + (data == 1 ? ' checked="checked"' : '') + '/>';
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
		
		function getAjaxRequest(url) {
			return {
				//"async": false,
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

<div class="section"> 
	<div class="center-panel" id="jaffa-race-results">
	</div>
</div>
<script type="text/javascript">
	<?php if (isset($_GET['raceId'])): ?>
	jQuery(document).ready(function ($) {

		// 1. Get race
		// 2. If meetingId is > 0, get meetingId
		// 2.1 Get associated races
		// 3 Get race results (for each race)
		
		// 1.
		$.ajax(
			getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/races/<?php echo $_GET['raceId']; ?>'))			
			.done(function(raceData) {	
				// 2.
				if (raceData.meetingId > 0) {
					getMeeting(raceData.eventId, raceData.meetingId);
					getMeetingRaces(raceData.eventId, raceData.meetingId);
				} else {
					getRaceResult(raceData.id, raceData.eventName, raceData.date);
				}
			});
			
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
					if (meetingData.length > 0) {
						for (var i = 0; i < meetingData.length; i++) {	
							getRaceResult(meetingData[i].id, meetingData[i].description, meetingData[i].date);
						}
					}
			});
		}
		
		function getRaceResult(raceId, description, date) {
			var tableName = 'jaffa-race-results-table-';
			var tableHtml = '';
			var tableRow = '<tr><th>Position</th><th>Name</th><th>Time</th><th>Personal Best</th><th>Season Best</th><th>Category</th><th>Standard</th><th>Info</th><th>Age Grading</th></tr>';
			tableHtml += '<table class="table table-striped table-bordered no-wrap" id="' + tableName + raceId + '">';
			tableHtml += '<caption>' + description + ', ' + date + '</caption>';
			tableHtml += '<thead>';
			tableHtml += tableRow;
			tableHtml += '</thead>';
			//tableHtml += '<tfoot>';
			//tableHtml += tableRow;
			//tableHtml += '</tfoot>';
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
							var html = '<a href="<?php echo $this->memberProfilePageUrl; ?>?runner_id=' + row.runnerId + '">' + data + '</a>';
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
				order : [[1, "asc"], [4, "asc"]],
				ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/race/' + raceId)
			});
		}

		function getAjaxRequest(url) {
			return {
				//"async": false,
				"url" : '<?php echo get_site_url(); ?>' + url,
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

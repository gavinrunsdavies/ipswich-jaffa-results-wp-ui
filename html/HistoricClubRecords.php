<div class="section"> 
	<style>
		div.center-panel { margin-top:1em}
	</style>
	<div class="center-panel">
		<p>Here you can find the full history of Ipswich JAFFA club records for various race distances. Select the distance the history of club records will be shown for each race age category.</p>
		
		<label for="distance">Distance</label>
		<select id="distance" name="distance" size="1" title="Select distance">
		</select>			 						     				
	</div>
	<div style="display:block" class="center-panel">				
		<div id="chartData"></div>   
	</div>	
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {						
	
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
				
		$('#distance').change(function () {
			var distanceId = $('#distance').val();
			if (distanceId == 0)
				return;
		
			$.ajax(getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/historicrecords/distance/' + distanceId))
				.done(function(data) {
					$('#chartData').empty();
					
					
						
					$.each(data, function(i, item){
						item.records.sort(function(a, b){
						   return ((a.date < b.date) ? -1 : ((a.date > b.date) ? 1 : 0));
						});
					});
					
					$.each(data, function(i, item){
						var sOut = '<h4>Category: ' + item.code + '</h4>';
						sOut += '<table class="table table-condensed" id="category_' + item.code + '">';
						sOut += '<thead>';
						sOut += '<tr><th>Name</th><th>Event</th><th>Description</th><th>Date</th><th>Time</th><th>Position</th</tr>';
						sOut += '</thead>';
						sOut += '<tbody>';						
						
						$.each(item.records, function(j, record){							
							
							sOut += '<tr>';
							sOut += '<td>' + getRunnerLink(record) + '</td>';
							sOut += '<td>' + getRaceLink(record) + '</td>';
							sOut += '<td>' + ((record.raceDescription == null) ? "" : record.raceDescription) + '</td>';
							sOut += '<td>' + record.date + '</td>';
							sOut += '<td>' + record.time + '</td>';
							sOut += '<td>' + record.position + '</td>';
							sOut += '</tr>';
						});
						sOut += '</tbody>';	
						sOut += '</table>';	
												
						$('#chartData').append(sOut);						
                    });								
				});	
		});
		
		function getRunnerLink(record) {
			var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
			var anchor = '<a href="' + resultsUrl;						
			anchor += '?runner_id=' + record.runnerId;						
			anchor += '">' + record.runnerName + '</a>';	
			
			return anchor;
		}
			
		function getRaceLink(record) {
			var eventResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
			var raceAnchor = '<a href="' + eventResultsUrl;
			if (eventResultsUrl.indexOf("?") >= 0) {
				raceAnchor += '&raceId=' + record.raceId;
			} else {
				raceAnchor += '?raceId=' + record.raceId;
			}
			raceAnchor += '">' + record.eventName + '</a>';
			
			return raceAnchor;
		}

		function getAjaxRequest(url) {
			return {
			  "url" : '<?php echo esc_url( home_url() ); ?>' + url,
			  "method": "GET",
			  "headers": {
				"cache-control": "no-cache"				
			  },
			  "dataSrc" : ""
			}
		}			
	});
</script>
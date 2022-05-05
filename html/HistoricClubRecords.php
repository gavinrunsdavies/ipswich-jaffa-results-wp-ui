<div class="section"> 
	<div class="formRankCriteria">
		<p>Here you can find the full history of Ipswich JAFFA club records for various race distances. Select the distance the history of club records will be shown for each race age category.</p>
		
		<label for="distance">Distance</label>
		<select id="distance" name="distance" size="1" title="Select distance">
		</select>			 						     				
	</div>
	<div style="display:block;margin-top:1em">			
		<h4 id="categoryLinks" style="display:none;text-align:center"></h4>	
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
					$('#categoryLinks').empty().append('| ');
																
					$.each(data, function(i, item){
						item.records.sort(function(a, b){
						   return ((a.date < b.date) ? -1 : ((a.date > b.date) ? 1 : 0));
						});
					});
					
					$.each(data, function(i, item){						
						var tableHtml = '<table class="display" id="category_' + item.code + '">';
						tableHtml += '<caption>Category: ' + item.code + '</caption>';
						tableHtml += '<thead>';
						tableHtml += '<tr><th>Name</th><th>Event</th><th>Description</th><th>Date</th><th>Time</th><th>Position</th</tr>';
						tableHtml += '</thead>';
						tableHtml += '<tbody>';	
						tableHtml += '</tbody>';	
						tableHtml += '</table>';
						tableHtml += '<a style="float:right" href="#top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>';

						var dataSet = [];
						$.each(item.records, function(j, record){	
							var dataRow = [];													
							dataRow.push(getRunnerLink(record));
							dataRow.push(getRaceLink(record));
							dataRow.push(record.raceDescription == null ? "" : record.raceDescription);
							dataRow.push(record.date);
							dataRow.push(record.time);
							dataRow.push(record.position);
							dataSet.push(dataRow);
						});
					
						$('#categoryLinks').append('<a href="#category_' + item.code +'">' + item.code +'</a> | ');
						$('#chartData').append(tableHtml);	
						$('#category_' + item.code).DataTable({
							paging : false,
							searching: false,							
							data: dataSet,
							order: [[ 3, "desc" ]]
						});					
                    });	
					$('#categoryLinks').show();							
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
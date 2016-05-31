<div class="section"> 
	<div class="center-panel">
		<form id="formRankCriteria" action="#" title="Select ranking criteria">
			<label for="distance">Distance</label>
			<select id="distance" name="distance" size="1" title="Select distance">			
			</select>			 
			<label for="sex">Gender</label>
			<select id="sex" name="sex" size="1" title="Select gender">
				<option value="0" selected="selected">Optional: Please select...</option>           
				<option value="2">Men's</option>              
				<option value="3">Ladies</option>              
			</select>		
			<label for="year">Year</label>
			<select id="year" name="year" size="1" title="Select year">
				<option value="0" selected="selected">Optional: Please select...</option>  
				<?php
				for ($y = date("Y"); $y >= 1977; $y--) 
				{
					printf('<option value="%d">%d</option>', $y, $y);              
				}
				?>							
			</select>	
			<label for="distinct">Distinct runners?</label>
			<input id="distinct" type="checkbox" name="distinct" value="1"/>			     
			<input id="wma-rank-submit" type="button" name="submit" value="Get Rankings"/>			     
		</form>
	</div>
	<div id="wma-ranking-results" style="display:none" class="center-panel">		
		<table class="table table-striped table-bordered" id="wma-ranking-results-table" style="width:100%">	
			<caption style="font-weight:bold; padding: 0.5em">Member Age Grading</caption>				
			<thead>
				<tr>
					<th>Rank</th>
					<th data-hide="always">Runner Id</th>
					<th>Name</th>
					<th data-hide="always">Event Id</th>
					<th data-hide="phone">Event</th>
					<th data-hide="phone,tablet">Date</th>
					<th data-hide="phone">Time</th>					
					<th>Age Grading</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Rank</th>
					<th>Runner Id</th>
					<th>Name</th>
					<th>Event Id</th>
					<th>Event</th>
					<th>Date</th>
					<th>Time</th>						
					<th>Age Grading</th>
				</tr>
			</tfoot>
			<tbody>				
			</tbody>
		</table>
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
			select.options.add(new Option('Optional. Please select...', 0));
			// Load the new options
			for (var i = 0; i < data.length; i++) {
				select.options.add(new Option(data[i].text, data[i].id));
			}
		  }
		);
			
		var wmaDt = null;
		$('#wma-rank-submit').click(function () {
		
			$('#wma-ranking-results').hide();

			var tableElement = $('#wma-ranking-results-table');
			
			if (wmaDt == null) {
				wmaDt = tableElement.DataTable({
					pageLength : 50,
					columns : [
					{ 
						data: "rank" 
					},
					{
						data: "runnerId",
						visible: false  
					},		
					{
					data: "name",
					render: function ( data, type, row, meta ) {	
						var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
						var anchor = '<a href="' + resultsUrl;						
						anchor += '?runner_id=' + row.runnerId;						
						anchor += '">' + data + '</a>';								

						return anchor;
					}
				},
				{
					data: "eventId",
					visible: false  
				},
				{
					data: "event",
					render: function ( data, type, row, meta ) {	
						var resultsUrl = '<?php echo $raceResultsPageUrl; ?>';
						var anchor = '<a href="' + resultsUrl;						
						anchor += '?raceId=' + row.raceId;						
						anchor += '">' + data + '</a>';								

						return anchor;
					}
				},
				{
					data: "date"
				},
				{
					data: "result"
				},
				{
					data: "percentageGrading"
				},
				],
				ajax    	  : {
					url : '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/ranking/wma/',			
					data : {
						"distanceId" : $('#distance').val(),
						"sexId": $('#sex').val(),
						"year":  $('#year').val(),
						"distinct":  $('#distinct').val()
					},
					dataSrc : ""
				}			
				});
			} else {
				wmaDt.ajax.reload();				
			}
			
			$('#wma-ranking-results').show();
		});
	});
</script>
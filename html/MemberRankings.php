<div class="section"> 
	<div class="center-panel">
		<p>Here you can find out where Ipswich JAFFA club members rank against other members in the club.</p>
		<form id="formRankCriteria" action="#" title="Select ranking year">	
			<label for="year">Year</label>
			<select id="year" name="year" size="1" title="Select year">
				<option value="0" selected="selected">All Time</option>  
				<?php
				for ($y = date("Y"); $y >= 1977; $y--) 
				{
					printf('<option value="%d">%d</option>', $y, $y);              
				}
				?>							
			</select>	
			<label for="distance">Distance</label>
			<select id="distance" name="distance" size="1" title="Select distance">						
			</select>				
			<input id="member-rank-submit" type="button" name="submit" value="Get Rankings"/>				
		</form>
	</div>
	<div id="mens-ranking-results" style="display:none" class="center-panel">		
		<table class="table table-striped table-bordered" id="mens-ranking-results-table">	
			<caption style="font-weight:bold; padding: 0.5em">Mens Ranking</caption>				
			<thead>
				<tr>
					<th>Rank</th>
					<th data-hide="always">Runner Id</th>
					<th>Name</th>								
					<th data-hide="always">Race Id</th>	
					<th>Event</th>	
					<th>Date</th>	
					<th>Time</th>	
				</tr>
			</thead>
			<tfoot>
				<tr>
				<th>Rank</th>
					<th data-hide="always">Runner Id</th>
					<th>Name</th>								
					<th data-hide="always">Race Id</th>	
					<th>Event</th>	
					<th>Date</th>	
					<th>Time</th>
				</tr>
			</tfoot>
			<tbody>				
			</tbody>
		</table>
	</div>
	<div id="ladies-ranking-results" style="display:none" class="center-panel">		
		<table class="table table-striped table-bordered" id="ladies-ranking-results-table">	
			<caption style="font-weight:bold; padding: 0.5em">Ladies Ranking</caption>				
			<thead>
				<tr>
					<th>Rank</th>
					<th data-hide="always">Runner Id</th>
					<th>Name</th>								
					<th data-hide="always">Race Id</th>	
					<th>Event</th>	
					<th>Date</th>	
					<th>Time</th>	
				</tr>
			</thead>
			<tfoot>
				<tr>
				<th>Rank</th>
					<th data-hide="always">Runner Id</th>
					<th>Name</th>								
					<th data-hide="always">Race Id</th>	
					<th>Event</th>	
					<th>Date</th>	
					<th>Time</th>
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

			// Load the new options
			for (var i = 0; i < data.length; i++) {
				select.options.add(new Option(data[i].text, data[i].id));
			}
		  }
		);
			
		$('#member-rank-submit').click(function () {
		
			$('#ladies-ranking-results', '#mens-ranking-results').hide();

			var ladiesTableElement = $('#ladies-ranking-results-table');			
			ladiesTableElement.DataTable({				
				pageLength : 20,
				paging : true,
				destroy	   : true,	
				processing    : true,
				searching: true,
				autoWidth     : false,
				scrollX: true,
				columns: [
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
					data: "raceId",
					visible: false  
				},
				{
					data: "event",
					render: function ( data, type, row, meta ) {	
						var resultsUrl = '<?php echo $eventResultsPageUrl; ?>';
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
				],
				ajax    	  : {
					url : '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/ranking/distance/' + $('#distance').val(),			
					data : {
						"sexId": '3',
						"year":  $('#year').val()
					},
					dataSrc : ""
				}				
			});
			$('#ladies-ranking-results').show();
			
			var mensTableElement = $('#mens-ranking-results-table');			
			mensTableElement.DataTable({
				pageLength : 20,
				paging : true,
				destroy	   : true,	
				processing    : true,
				searching: true,
				autoWidth     : false,
				scrollX: true,
				columns: [
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
					data: "raceId",
					visible: false  
				},
				{
					data: "event",
					render: function ( data, type, row, meta ) {	
						var resultsUrl = '<?php echo $eventResultsPageUrl; ?>';
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
				],
				ajax    	  : {
					url : '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/ranking/distance/' + $('#distance').val(),			
					data : {
						"sexId": '2',
						"year":  $('#year').val()
					},
					dataSrc : ""
				}					
			});
						
			$('#mens-ranking-results').show();
		});
	});
</script>
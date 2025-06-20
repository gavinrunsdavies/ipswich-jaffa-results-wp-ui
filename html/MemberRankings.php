<style>
.site-content {
	padding-top: 0;
}

@media only screen and (max-width: 768px) {
	.page-header {
		padding: 0;
	}
	.page-content, .entry-content, .entry-summary {
		margin: 0;
	}
}
</style>
<div class="section"> 
	<div class="formRankCriteria">
		<p>Here you can find out where Ipswich JAFFA club members rank against other members in the club.</p>
		<form action="#" title="Select ranking year">	
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
			<label for="category">Category (optional)</label>
			<select id="category" name="category" size="1" title="Select category">		
				<option value="0" selected="selected">All</option> 				
			</select>				
			<input id="member-rank-submit" type="button" value="Get Rankings" disabled="disabled"/>				
		</form>
	</div>
	<div id="mens-ranking-results" style="display:none;margin-bottom: 1em;">		
		<table class="display" id="mens-ranking-results-table">	
			<caption>Mens Ranking</caption>				
			<thead>
				<tr>
					<th data-priority="2">Rank</th>
					<th>Runner Id</th>
					<th data-priority="1">Name</th>								
					<th>Race Id</th>	
					<th data-priority="4">Event</th>	
					<th data-priority="5">Date</th>	
					<th data-priority="3">Performance</th>	
				</tr>
			</thead>			
			<tbody>				
			</tbody>
		</table>
	</div>
	<div id="ladies-ranking-results" style="display:none">		
		<table class="display" id="ladies-ranking-results-table">	
			<caption>Ladies Ranking</caption>				
			<thead>
			<tr>
					<th data-priority="2">Rank</th>
					<th>Runner Id</th>
					<th data-priority="1">Name</th>								
					<th>Race Id</th>	
					<th data-priority="4">Event</th>	
					<th data-priority="5">Date</th>	
					<th data-priority="3">Performance</th>	
				</tr>
			</thead>			
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

			// Load the new options
			for (var i = 0; i < data.length; i++) {
				select.options.add(new Option(data[i].text, data[i].id));
			}

			$('#member-rank-submit').prop('disabled', false);
		  }
		);

		$.getJSON(
		  '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/categories',
		  function(data) {
			var name, select, option;

			// Get the raw DOM object for the select box
			select = document.getElementById('category');

			// Load the new options for non default categories
			for (var i = 0; i < data.length; i++) {
				if (data[i].isDefault == 0 ) {
					select.options.add(new Option(data[i].code, data[i].id));
				}
			}
		  }
		);
			
		$('#member-rank-submit').click(function () {
		
			$('#ladies-ranking-results', '#mens-ranking-results').hide();

			var ladiesTableElement = $('#ladies-ranking-results-table');			
			ladiesTableElement.DataTable({	
				responsive: true,			
				pageLength : 20,
				paging : true,
				destroy : true,	
				processing : true,
				searching: true,
				autoWidth : false,
				scrollX: false,
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
					data: "performance",
					render : function (data, type, row, meta) {
						if (row.resultUnitTypeId == "3") {
								return Number(data).toLocaleString();
						}

						return ipswichjaffarc.secondsToTime(row.performance);						
					},
					className : 'text-right'
				}
				],
				ajax : {
					url : '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/ranking/distance/' + $('#distance').val(),			
					data : {
						"sexId": '3',
						"year":  $('#year').val(),
						"categoryId" : $('#category').val()
					},
					dataSrc : ""
				}				
			});
			$('#ladies-ranking-results').show();
			
			var mensTableElement = $('#mens-ranking-results-table');			
			mensTableElement.DataTable({
				responsive: true,
				pageLength : 20,
				paging : true,
				destroy : true,	
				processing : true,
				searching: true,
				autoWidth : false,
				scrollX: false,
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
					data: "performance",
					render : function (data, type, row, meta) {
						if (row.resultUnitTypeId == "3") {
								return Number(data).toLocaleString();
						}

						return ipswichjaffarc.secondsToTime(row.performance);						
					},
					className : 'text-right'
				}
				],
				ajax    	  : {
					url : '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/ranking/distance/' + $('#distance').val(),			
					data : {
						"sexId": '2',
						"year":  $('#year').val(),
						"categoryId" : $('#category').val()
					},
					dataSrc : ""
				}					
			});
						
			$('#mens-ranking-results').show();
		});
	});
</script>

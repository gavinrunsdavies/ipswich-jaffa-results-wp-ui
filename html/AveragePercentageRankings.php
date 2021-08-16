<div class="section"> 
	<div class="center-panel">
		<p>This form shows the average percentage age grading for male and female members for a given membership year and sample size. Only individual races where a WMA percentage grading has been calculated have been taken in to consideraton.</p>
		<ul>
			<li>If no year specificed the query is across all years.</li>
          	<li>Prior to 2015 it is for calendar year results</li>
        	<li>In 2016 the membership year changed to be from 1st March, so the 2015 year shows 14 months worth of race results</li>
         	<li>In 2021 the membership year changed to be from 1st April, so the 2020 year shows 13 months worth of race results</li>
			<li>From 2021 results will be 12 months from 1st April</li>
		</ul>
		<form id="formRankCriteria" action="#" title="Select ranking year">	
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
			<label for="populationSize">Number of Races</label>
			<select id="populationSize" name="populationSize" size="1" title="Select sample size">				
				<?php
				for ($y = 1; $y <= 12; $y++) 
				{
					printf('<option value="%d" %s>%d</option>', $y, ($y == 5 ? "selected=\"selected\"" : ""), $y);              
				}
				?>							
			</select>				
			<input id="average-age-rank-submit" type="button" name="submit" value="Get Average Rankings"/>	
			<br/>
			<small>If you omit the year from the selection criteria the average rankings for all results are taken in to calculation</small>			
		</form>
	</div>
	<div id="mens-average-age-ranking-results" style="display:none" class="center-panel">		
		<table class="table table-striped table-bordered" id="mens-average-age-ranking-results-table">	
			<caption style="font-weight:bold; padding: 0.5em">Mens Average Age Grading</caption>				
			<thead>
				<tr>
					<th>Rank</th>
					<th data-hide="always">Runner Id</th>
					<th>Name</th>								
					<th>Average Age Grading</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Rank</th>
					<th>Runner Id</th>
					<th>Name</th>				
					<th>Average Age Grading</th>
				</tr>
			</tfoot>
			<tbody>				
			</tbody>
		</table>
	</div>
	<div id="ladies-average-age-ranking-results" style="display:none" class="center-panel">		
		<table class="table table-striped table-bordered" id="ladies-average-age-ranking-results-table">	
			<caption style="font-weight:bold; padding: 0.5em">Ladies Average Age Grading</caption>				
			<thead>
				<tr>
					<th>Rank</th>
					<th data-hide="always">Runner Id</th>
					<th>Name</th>								
					<th>Average Age Grading</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Rank</th>
					<th>Runner Id</th>
					<th>Name</th>				
					<th>Average Age Grading</th>
				</tr>
			</tfoot>
			<tbody>				
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {	
			
		var ladiesDt, mensDt = null;
		$('#average-age-rank-submit').click(function () {
		
			$('#ladies-average-age-ranking-results', '#mens-average-age-ranking-results').hide();

			var ladiesTableElement = $('#ladies-average-age-ranking-results-table');			
			
			if (ladiesTableElement.DataTable() != null)
				ladiesTableElement.DataTable().destroy();
			
			ladiesTableElement.DataTable({				
				pageLength : 20,
				destory	   : true,	
				processing    : true,
				columns: [
				{ 
					data: "rank",
					width: "2em"
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
						if (resultsUrl.indexOf("?") >= 0) {
							anchor += '&runner_id=' + row.runnerId;
						} else {
							anchor += '?runner_id=' + row.runnerId;
						}
						anchor += '">' + data + '</a>';								

						return anchor;
					}
				},
				{
					data: "topXAvg",
					render: function ( data, type, row, meta ) {
							return (data + '%');
					}
				}
				],
				ajax    	  : {
					url : '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/ranking/averageWMA',			
					data : {
						"sexId": '3',
						"year":  $('#year').val(),
						"numberOfRaces": $('#populationSize').val()
					},
					dataSrc : ""
				}				
			});
			$('#ladies-average-age-ranking-results').show();					
			
			var mensTableElement = $('#mens-average-age-ranking-results-table');
			if (mensTableElement.DataTable() != null)
				mensTableElement.DataTable().destroy();
			
			mensTableElement.DataTable({
				pageLength : 20,
				destory	   : true,	
				processing    : true,
				columns: [
				{ 
					data: "rank",
					width: "2em"
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
						if (resultsUrl.indexOf("?") >= 0) {
							anchor += '&runner_id=' + row.runnerId;
						} else {
							anchor += '?runner_id=' + row.runnerId;
						}
						anchor += '">' + data + '</a>';								

						return anchor;
					}
				},
				{
					data: "topXAvg",
					render: function ( data, type, row, meta ) {
							return (data + '%');
					}
				}
				],
				ajax    	  : {
					url : '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/ranking/averageWMA',			
					data : {
						"sexId": '2',
						"year":  $('#year').val(),
						"numberOfRaces": $('#populationSize').val()
					},
					dataSrc : ""
				}					
			});			
						
			$('#mens-average-age-ranking-results').show();
		});
	});
</script>
<div class="section"> 
	<div class="center-panel">
		<form id="formGrandPrixCriteria" action="#" title="Select ranking criteria">		
			<label for="year">Year</label>
			<select id="year" name="year" size="1" title="Select year">
				<option value="0" selected="selected">Please select...</option>  
				<?php
				for ($y = date("Y"); $y >= 2015; $y--) 
				{
					printf('<option value="%d">%d</option>', $y, $y);              
				}
				?>							
			</select>		     			
			<input id="grand-prix-submit" type="button" name="submit" value="Get Scores"/>			     
		</form>
	</div>
	<div id="men-grand-prix-results" style="display:none" class="center-panel">		
		<div class="row">
			<div class="col-md-12">
				<table class="table table-striped table-bordered" id="men-grand-prix-races">
					<caption style="font-weight:bold; padding: 0.5em">Men's Grand Prix Races</caption>	
					<thead>
						<tr>
							<th>Event</th>
							<th>Date</th>
							<th>Description</th>
							<th>Course Type</th>
							<th>County</th>
							<th>Conditions</th>
							<th>Venue</th>
							<th>Distance</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-11">
				<table class="table table-striped table-bordered grandprix-table" id="men-grand-prix-results-table" style="width:100%">	
					<caption style="font-weight:bold; padding: 0.5em">Men's Grand Prix Current Standings</caption>				
					<thead>
						<tr>
							<th>Name</th>
							<th>Category</th>
							<th>Total Points</th>
							<th>Best 8 Points</th>		
							<th>Details</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<th>Name</th>
							<th>Category</th>
							<th>Total Points</th>
							<th>Best 8 Points</th>	
							<th>Details</th>
						</tr>
					</tfoot>					
				</table>
			</div>			
		</div>			
	</div>
	<div id="ladies-grand-prix-results" style="display:none" class="center-panel">		
		<div class="row">
			<div class="col-md-12">
				<table class="table table-striped table-bordered" id="ladies-grand-prix-races">
					<caption style="font-weight:bold; padding: 0.5em">Ladies Grand Prix Races</caption>	
					<thead>
						<tr>
							<th>Event</th>
							<th>Date</th>
							<th>Description</th>
							<th>Course Type</th>
							<th>County</th>
							<th>Conditions</th>
							<th>Venue</th>
							<th>Distance</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<div class="col-md-11">
				<table class="table table-striped table-bordered grandprix-table" id="ladies-grand-prix-results-table" style="width:100%">	
					<caption style="font-weight:bold; padding: 0.5em">ladies Grand Prix Current Standings</caption>				
					<thead>
						<tr>
							<th>Name</th>
							<th>Category</th>
							<th>Total Points</th>
							<th>Best 8 Points</th>		
							<th>Details</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
					<tfoot>
						<tr>
							<th>Name</th>
							<th>Category</th>
							<th>Total Points</th>
							<th>Best 8 Points</th>	
							<th>Details</th>
						</tr>
					</tfoot>					
				</table>
			</div>	
		</div>			
	</div>	
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {					
		$(function () {
		  $('[data-toggle="popover"]').popover();
		});	
		 
		function createDataTable(tableId, data, sexId) {
			var tableBody = $('#' + tableId + ' tbody');
			tableBody.empty();
				
			var rows = '';
			$.each(data, function(i, runner){
				rows += '<tr id = "' + runner.id + '" data-sex-id="' + sexId + '">';
				var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
				var anchor = '<a href="' + resultsUrl;						
				anchor += '?runner_id=' + runner.id;						
				anchor += '">' + runner.name + '</a>';		
				rows += '<td>' + anchor + '</td>';
				rows += '<td>' + runner.categoryCode + '</td>';
				rows += '<td>' + runner.totalPoints + '</td>';
				rows += '<td>' + runner.best8Score+ '</td>';
				rows += '<td class="text-center"><a tabindex="0" role="button"><span class="glyphicon glyphicon-option-horizontal grand-prix-detail" aria-hidden="true"></span></a></td>';				
				rows += '</tr>';
			});				
	
			var table = $('#' + tableId);
			if (table.DataTable() != null) {
				table.DataTable().clear();
				table.DataTable().destroy();				
			}
				
			
			tableBody.append(rows);
			
			table.DataTable( {
				pageLength : 10,
				paging : true,
				destory	   : true,	
				searching: 	true,
				order: 		[[ 3, "desc" ], [ 2, "desc" ]]
			} );
		}
		
		function populateRacesTable(tableId, data) {
			var tableBody = $('#' + tableId + ' tbody');
			tableBody.empty();
				
			var rows = '';
			$.each(data, function(i, race){
				rows += '<tr>';				
				var eventResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
				var anchor = '<a href="' + eventResultsUrl;
				if (eventResultsUrl.indexOf("?") >= 0) {
					anchor += '&raceId=' + race.id;
				} else {
					anchor += '?raceId=' + race.id;
				}
				anchor += '">' + race.eventName + '</a>';	
				rows += '<td>'+ anchor + '</td>';
				rows += '<td>' + race.date + '</td>';
				rows += '<td>' + nullToEmptyString(race.description)+ '</td>';
				rows += '<td>' + nullToEmptyString(race.courseType) + '</td>';
				rows += '<td>' + nullToEmptyString(race.county) + '</td>';					
				rows += '<td>' + nullToEmptyString(race.conditions) + '</td>';
				rows += '<td>' + nullToEmptyString(race.venue) + '</td>';
				rows += '<td>' + nullToEmptyString(race.distance) + '</td>';							
				rows += '</tr>';
			});
			
			tableBody.append(rows);
		}
		
		function nullToEmptyString(value) {
			return (value == null) ? "" : value;
		}
		
		function getAjaxRequest(url) {
			return {
				"url" : '<?php echo esc_url( home_url() ); ?>' + url,
				"method" : "GET",
				"headers" : {
					"cache-control" : "no-cache"
				},
				"dataSrc" : ""
			};
		}
		
		var mensGPData;
		var ladiesGPData;
		var mensOrderedRacesIds;
		var ladiesOrderedRacesIds;
		
		$('#grand-prix-submit').click(function () {
		
			var year = $('#year').val();
			$.getJSON(
			  '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/grandprix/' + year + '/' + 2,
			  function(data) {		
				mensGPData = data.results;
				
				mensOrderedRacesIds = data.races;
				populateRacesTable('men-grand-prix-races', data.races);
				createDataTable('men-grand-prix-results-table', data.results, 2);
				
				$('#men-grand-prix-results').show();
			  }
			);	

			$.getJSON(
			  '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/grandprix/' + year + '/' + 3,
			  function(data) {		
				ladiesGPData = data.results;
				
				ladiesOrderedRacesIds = data.races;
				populateRacesTable('ladies-grand-prix-races', data.races);
				createDataTable('ladies-grand-prix-results-table', data.results, 3);
				
				$('#ladies-grand-prix-results').show();
			  }
			);				
		});
		
		function getResultDetailHtml(runner, orderedRacesIds) {
						
			var table = '<table class="table table-striped table-bordered"><thead><tr><th>Race</th><th>Points</th></tr></thead><tbody>';
					
			var rows = '';
			
			var eventResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
			$.each(orderedRacesIds, function(j, raceDetail){
				$.each(runner.races, function(k, race){
					if (raceDetail.id == race.id) {
						
						var anchor = eventResultsUrl;
						if (eventResultsUrl.indexOf("?") >= 0) {
							anchor += '&raceId=' + raceDetail.id;
						} else {
							anchor += '?raceId=' + raceDetail.id;
						}
						rows += '<tr>';
						rows += '<td><a href="' + anchor + '">' + raceDetail.eventName + '</a></td>';
						rows += '<td>' + race.points + '</td>';
						rows += '</tr>';
						return;
					}
				});
			});
			
			table += rows;
			table += '</tbody></table>';
			
			return table;
		}
		
		$('.grandprix-table tbody td a span.grand-prix-detail').live( 'click', function () {
			var nTr = this.parentNode.parentNode.parentNode;
			var sexId = $(nTr).data('sex-id');			
				
			var runnerId = nTr.id;
			var data, orderedRacesIds;
		
			if (sexId == 2) {
				data = mensGPData;
				orderedRacesIds = mensOrderedRacesIds;				
			} else {
				data = ladiesGPData;
				orderedRacesIds = ladiesOrderedRacesIds;
			}
		
			var runner = findById(data, runnerId);
			if (runner == null)
				return;
		
			var html = getResultDetailHtml(runner, orderedRacesIds);			
		
			// Hide any exitsing
			$('.grandprix-table tbody td span.grand-prix-detail').popover('destroy');
			
			var popover = $(this).popover({
				title : runner.name,
				content : html,
				placement : 'right',
				container: 'body',
				trigger: 'click',
				html: 'true'					
			}).popover('show');		
		} );
		
		function findById(source, id) {
		  for (var i = 0; i < source.length; i++) {
			if (source[i].id === id) {
			  return source[i];
			}
		  }
		  return null;
		}		
	});
</script>
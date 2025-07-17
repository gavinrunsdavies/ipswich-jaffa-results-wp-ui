<div class="section"> 
	<div class="formRankCriteria">
		<form action="#" title="Select ranking criteria">		
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
	<div id="men-grand-prix-results" style="display:none">		
		<table class="display grandprix-table" id="men-grand-prix-results-table" style="width:100%" data-raceIds="mensOrderedRacesIds">	
			<caption>Men's Grand Prix Current Standings</caption>				
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
                    <th>Completed Races</th>
					<th>Category</th>
					<th>Total Points</th>
					<th>Best 8 Points</th>		
				</tr>
			</thead>
			<tbody>
			</tbody>					
		</table>		
	</div>
	<div id="ladies-grand-prix-results" style="display:none" class="center-panel">
		<table class="display grandprix-table" id="ladies-grand-prix-results-table" style="width:100%" data-raceIds="ladiesOrderedRacesIds">	
			<caption>Ladies Grand Prix Current Standings</caption>				
			<thead>
				<tr>
					<th></th>
					<th>Name</th>
                    <th>Completed Races</th>
					<th>Category</th>
					<th>Total Points</th>
					<th>Best 8 Points</th>
				</tr>
			</thead>
			<tbody>
			</tbody>				
		</table>		
	</div>	
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {				
		 
		function createDataTable(tableId, data, sexId) {
				
			$(tableId).DataTable({
				paging: true,
				destroy : true,
				searching: true,
				processing: true,
				autoWidth: false,
				scrollX: false,
				pageLength: 10,
				order: [[ 3, "desc" ], [ 2, "desc" ]],
				columns: [
					{
						className: 'dt-control',
						orderable: false,
						data: null,
						defaultContent: ''
					},
                    {
						data: "id",
                        visible: false
					},
					{
						data: "name",
						searchable: true,
						sortable: true,
						render: function(data, type, row, meta) {
							var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
							var anchor = '<a href="' + resultsUrl;
							anchor += '?runner_id=' + row.id;
							anchor += '">' + data + '</a>';

							return anchor;
						}
					},
                    {
						data: "numberOfRaces"
					},
					{
						data: "categoryCode"
					},
					{
						data: "totalPoints"
					},
					{
						data: "best8Score"
					},                    
                    {
						data: "averageScore",
                        visible: false
					},                    
                    {
						data: "races",
                        visible: false
					}
				],
				ajax: data
			});
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
				createDataTable('#men-grand-prix-results-table', data.results, 2);
				
				$('#men-grand-prix-results').show();
			  }
			);	

			$.getJSON(
			  '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/grandprix/' + year + '/' + 3,
			  function(data) {		
				ladiesGPData = data.results;
				ladiesOrderedRacesIds = data.races;
				createDataTable('#ladies-grand-prix-results-table', data.results, 3);
				
				$('#ladies-grand-prix-results').show();
			  }
			);				
		});
		
		function getRunnerResultDetails(data, raceData) {
						
			var table = '<table class="display"><thead><tr><th>Race</th><th>Points</th></tr></thead><tbody>';
					
			var rows = '';
			
			var eventResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
			$.each(raceData, function(j, raceDetail){
				$.each(data.races, function(k, race){
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

		$('.grandprix-table').on('click', 'td.dt-control', function (e) {
			let tr = e.target.closest('tr');			
			let table = $(tr).closest('table').DataTable(); 
			let row = table.row(tr);
		
			if (row.child.isShown()) {
				// This row is already open - close it
				row.child.hide();
			}
			else {
				// Open this row
				let raceIds = $(table.node()).data('raceids');
				row.child(getRunnerResultDetails(row.data(), raceIds)).show();
			}
		});
		
		// $('.grandprix-table tbody td a .grand-prix-detail').click(function () {
		// 	var nTr = this.parentNode.parentNode.parentNode;
		// 	var sexId = $(nTr).data('sex-id');			
				
		// 	var runnerId = nTr.id;
		// 	var data, orderedRacesIds;
		
		// 	if (sexId == 2) {
		// 		data = mensGPData;
		// 		orderedRacesIds = mensOrderedRacesIds;				
		// 	} else {
		// 		data = ladiesGPData;
		// 		orderedRacesIds = ladiesOrderedRacesIds;
		// 	}
		
		// 	var runner = findById(data, runnerId);
		// 	if (runner == null)
		// 		return;
		
		// 	var html = getResultDetailHtml(runner, orderedRacesIds);			
			
		// } );
		
		// function findById(source, id) {
		//   for (var i = 0; i < source.length; i++) {
		// 	if (source[i].id === id) {
		// 	  return source[i];
		// 	}
		//   }
		//   return null;
		// }		
	});
</script>

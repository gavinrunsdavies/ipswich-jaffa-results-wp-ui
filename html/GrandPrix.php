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
		<table class="display grandprix-table" id="men-grand-prix-results-table" style="width:100%" data-raceids="mensOrderedRacesIds">	
			<caption>Men's Grand Prix Current Standings</caption>				
			<thead>
				<tr>
					<th></th>
                    <th>Id</th>
					<th>Name</th>
                    <th>Completed Races</th>
					<th>Category</th>
					<th>Total Points</th>
					<th>Best 8 Points</th>	
                    <th></th>
                    <th></th>
				</tr>
			</thead>
			<tbody>
			</tbody>					
		</table>		
	</div>
	<div id="ladies-grand-prix-results" style="display:none" class="center-panel">
		<table class="display grandprix-table" id="ladies-grand-prix-results-table" style="width:100%" data-raceids="ladiesOrderedRacesIds">	
			<caption>Ladies Grand Prix Current Standings</caption>				
			<thead>
				<tr>
					<th></th>
                    <th>Id</th>
					<th>Name</th>
                    <th>Completed Races</th>
					<th>Category</th>
					<th>Total Points</th>
					<th>Best 8 Points</th>
                    <th></th>
                    <th></th>
				</tr>
			</thead>
			<tbody>
			</tbody>				
		</table>		
	</div>	
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {	

        let raceDataMap = {};
		 
		function createDataTable(tableId, data) {
				
			$(tableId).DataTable({
				paging: true,
				destroy : true,
				searching: true,
				processing: true,
				autoWidth: false,
				scrollX: false,
				pageLength: 10,
				order: [[ 5, "desc" ], [ 4, "desc" ]],
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
				data: data
			});
		}
		
		$('#grand-prix-submit').click(function () {
		
			var year = $('#year').val();
			$.getJSON(
			  '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/grandprix/' + year + '/' + 2,
			  function(data) {		
				raceDataMap['mensOrderedRacesIds'] = data.races;
				createDataTable('#men-grand-prix-results-table', data.results);
				
				$('#men-grand-prix-results').show();
			  }
			);	

			$.getJSON(
			  '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/grandprix/' + year + '/' + 3,
			  function(data) {		
                raceDataMap['ladiesOrderedRacesIds'] = data.races;
				createDataTable('#ladies-grand-prix-results-table', data.results);
				
				$('#ladies-grand-prix-results').show();
			  }
			);				
		});
		
		function getRunnerResultDetails(runnerData, raceData) {										
			var list = '<ul>';			
			var eventResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
			$.each(raceData, function(j, raceDetail){
				$.each(runnerData.races, function(k, race){
					if (raceDetail.id == race.id) {
						
						var anchor = eventResultsUrl;
						if (eventResultsUrl.indexOf("?") >= 0) {
							anchor += '&raceId=' + raceDetail.id;
						} else {
							anchor += '?raceId=' + raceDetail.id;
						}
						list += '<li>';
						list += '<a href="' + anchor + '">' + raceDetail.eventName + '</a>: ' + race.points + 'pts';
						list += '</li>';
						return;
					}
				});
			});
			
			list += '</ul>';
			
			return list;
		}

		$('.grandprix-table').on('click', 'td.dt-control', function (e) {
			let tr = e.target.closest('tr');			
            let table = $(tr).closest('table'); 
			let dataTableRow = table.DataTable().row(tr);
		
			if (dataTableRow.child.isShown()) {
				// This row is already open - close it
				dataTableRow.child.hide();
			}
			else {
				// Open this row
				let raceIds = table.data('raceids');
				dataTableRow.child(getRunnerResultDetails(dataTableRow.data(), raceDataMap[raceIds])).show();
			}
		});	
	});
</script>

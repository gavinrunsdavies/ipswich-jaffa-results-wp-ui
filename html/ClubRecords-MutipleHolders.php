<div class="section"> 
	<div style="display:block" class="center-panel" id="multiple-club-record-holders">				
	</div>
</div>
<style>
	#multiple-club-record-holders table.display th {
		background-color: var(--primary-color);
		background-repeat: no-repeat;
		background-position: center right;
	}
</style>
<script type="text/javascript">
	jQuery(document).ready(function($) {	

		$.getJSON(
			  '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/records/holders?limit=10',
			  function(data) {		
				createRecordHolderTables(data);
			  }
			);
			
		function createRecordHolderTables(data) {
			var tableHead = '<thead><th>Category</th><th>Event</th><th>Date</th><th>Distance</th><th>Result</th></thead>';
			var clubRecordHolders = $('#multiple-club-record-holders');	
			
			$.each(data, function(i, recordHolder) {
				var html = '';
				//html += '<h2>' + recordHolder.runner.name + '</h2>';
				html += '<table class="display" style="width:100%" id="runner' + recordHolder.runner.id +'">';
				html += '<caption>'+recordHolder.runner.name+'</caption>';
				html += tableHead;
				html += '<tbody>';
				var rows = addClubRecordsToTable(recordHolder.records);
				html += rows;
				html += '</tbody>';
				html += '</table>';	
				clubRecordHolders.append(html);

				var table = $('#runner' + recordHolder.runner.id);
												
				table.DataTable( {
					searching: 	false,					
					paging : false,
					order: [2, 'asc']
				} );
			});
		}

		function addClubRecordsToTable(records) {
			var rows = '';
			$.each(records, function(i, record) {
				rows += '<tr>';
				rows += '<td>'+ record.categoryCode + '</td>';
				rows += '<td><a href="<?php echo $raceResultsPageUrl; ?>?raceId='+ record.raceId + '">'+ record.eventName + '</a></td>';
				rows += '<td>'+ record.date + '</td>';
				rows += '<td>'+ record.distance + '</td>';
				rows += '<td>'+ record.result + '</td>';
				rows += '</tr>';
			});
			return rows;
		}
	});
</script>
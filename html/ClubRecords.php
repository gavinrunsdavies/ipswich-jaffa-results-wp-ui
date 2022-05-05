<div class="section" id="club-records-top"> 
	<div>
		<table class="display" id="overall-club-records">	
			<caption>Overall Club Records</caption>
			<thead>
				<tr>
					<th>Distance</th>
					<th>Record Holder</th>					
					<th>Event</th>
					<th>Record</th>
				</tr>
			</thead>							
		</table>	
	</div>
	<?php
	$distances = array('5 km' => 1, '5 m' => 2, '10 km' => 3, '10 m' => 4, 'Half marathon' => 5, '20 m' => 7, 'Marathon' => 8);
	?>	
	<h4 style="text-align:center">
		<?php
		echo '| ';
		foreach ($distances as $text => $distanceId)
		{		
			$tabId = "$distanceId";
			echo '<a href="#'.$distanceId.'">'.$text.'</a> | ';
		}
		?>			
	</h4>
	<div>
		<?php			
		foreach ($distances as $text => $distanceId)
		{		
		?>	
		<div id="<?php echo $distanceId; ?>">
			<table class="display club-records">	
				<caption><?php echo $text; ?></caption>
				<thead>
					<tr>
						<th>Category</th>
						<th>Record Holder</th>					
						<th>Event</th>
						<th>Record</th>
					</tr>
				</thead>							
			</table>	
			<a style="float:right" href="#club-records-top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
		</div>		
		<?php
		}
		?>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$('#overall-club-records').DataTable({
			serverSide : false,
			paging : false,
			searching: false,
			processing : true,
			ordering: false,
			autoWidth : false,
			scrollX: true,
			columns:[
				{
				data: "distance"
				},
				{
				data: "runnerName",
				searchable: true,
				sortable: true,
				render: function ( data, type, row, meta ) {	
					var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
					var anchor = '<a href="' + resultsUrl;
					anchor += '?runner_id=' + row.runnerId;					
					anchor += '">' + data + '</a>';								

					return anchor;
				}
				},
				{
				data: "eventName",
				render: function ( data, type, row, meta ) {	
					var resultsUrl = '<?php echo $raceResultsPageUrl; ?>';
					var anchor = '<a href="' + resultsUrl;
					anchor += '?raceId=' + row.raceId;					
					anchor += '">' + data + ' (' + row.date + ') </a>';								

					return anchor;
				}
				},
				{
				data: "result"
				}
			],
			ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/records')		
		});

		$('.club-records').each(function (index, value){
			var table = $(value);
			table.DataTable({
				pageLength : 50,
				serverSide : false,
				paging : false,
				searching: false,
				processing : true,
				autoWidth : false,
				scrollX: true,
				columns:[
				 {
					data: "categoryCode"
				 },
				 {
					data: "runnerName",
					searchable: true,
					sortable: true,
					render: function ( data, type, row, meta ) {	
						var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
						var anchor = '<a href="' + resultsUrl;
						anchor += '?runner_id=' + row.runnerId;					
						anchor += '">' + data + '</a>';								

						return anchor;
					}
				 },
				 {
					data: "eventName",
					render: function ( data, type, row, meta ) {	
						var resultsUrl = '<?php echo $raceResultsPageUrl; ?>';
						var anchor = '<a href="' + resultsUrl;
						anchor += '?raceId=' + row.raceId;					
						anchor += '">' + data + ' (' + row.date + ') </a>';								

						return anchor;
					}
				 },
				 {
					data: "result"
				 }
				],
				ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/records/distance/' + table.parent().attr('id'))		
			});
		});
		
		function getAjaxRequest(url) {
			return {
			  //"async": false,
			  "url" : url,
			  "method": "GET",
			  "headers": {
				"cache-control": "no-cache"				
			  },
			  "dataSrc" : ""
			}
		}
	});
</script>
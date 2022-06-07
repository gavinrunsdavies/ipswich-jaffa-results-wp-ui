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
<div class="section" id="club-records-top"> 
	<div>
		<table class="display" id="overall-club-records">	
			<caption>Overall Club Records</caption>
			<thead>
				<tr>
					<th data-priority="3">Distance</th>
					<th data-priority="1">Record Holder</th>					
					<th data-priority="4">Event</th>
					<th data-priority="2">Record</th>
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
						<th data-priority="3">Category</th>
						<th data-priority="1">Record Holder</th>					
						<th data-priority="4">Event</th>
						<th data-priority="2">Record</th>
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
			responsive: {
				details: {
					renderer: function ( api, rowIdx, columns ) {
						var data = $.map( columns, function ( col, i ) {
							return col.hidden ?
								'<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
									'<td>'+col.title+':'+'</td> '+
									'<td>'+col.data+'</td>'+
								'</tr>' :
								'';
						} ).join('');

						return data ?
							$('<table/>').append( data ) :
							false;
					}
				}
			},
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
					data: "result",
					render : function (data, type, row, meta) {
						return ipswichjaffarc.formatTime(data);
					},
					className : 'text-right'
				}
			],
			ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/records')		
		});

		$('.club-records').each(function (index, value){
			var table = $(value);
			table.DataTable({
				responsive: {
					details: {
						renderer: function ( api, rowIdx, columns ) {
							var data = $.map( columns, function ( col, i ) {
								return col.hidden ?
									'<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
										'<td>'+col.title+':'+'</td> '+
										'<td>'+col.data+'</td>'+
									'</tr>' :
									'';
							} ).join('');

							return data ?
								$('<table/>').append( data ) :
								false;
						}
					}
				},
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
					data: "result",
					render : function (data, type, row, meta) {
						return ipswichjaffarc.formatTime(data);
					},
					className : 'text-right'
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
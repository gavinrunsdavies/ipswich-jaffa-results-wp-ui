<div class="section"> 
	<h2 id="jaffa-event-title"></h2>
	<div id="jaffa-race-results">
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		
		getCountyChampionResults();
		
		function getCountyChampionResults() {
      		var groupColumn = 0;
			var tableName = 'jaffa-race-county-results-table';
			var tableHtml = '';
			var tableRow = '<tr><th data-priority="3">Year</th><th data-priority="2">Distance</th><th data-priority="1">Name</th><th data-priority="4">Category</th><th data-priority="5">Result</th><th data-priority="6">Event</th></tr>';
			tableHtml += '<table class="display" id="' + tableName + '">';
			tableHtml += '<thead>';
			tableHtml += tableRow;
			tableHtml += '</thead>';
			tableHtml += '</table>';
			$('#jaffa-race-results').append(tableHtml);
			
			var table = $('#'+tableName).DataTable({
				responsive: true,							
				paging : false,
				searching: true,
				serverSide : false,
				columnDefs: [
					{ "visible": false, "targets": groupColumn }
				],
				order: [[ groupColumn, 'desc' ]],
				columns : [
					{
						data : "date",
						render : function (data, type, row, meta) {
							return data.substring(0, 4);
						}
					},  
					{
						data : "distance",
            			searchable: true
					}, 
					{
						data : "runnerName",
            			searchable: true,
						render : function (data, type, row, meta) {
							var html = '<a href="<?php echo $memberResultsPageUrl; ?>?runner_id=' + row.runnerId + '">' + data + '</a>';
							if (row.team > 0) {
								var tooltip = '';
								if (row.team == 1)
									tooltip = "Part of the winning team";
								else
									tooltip = "Part of the scoring team finishing in " + row.team;

								html += ' <span class="glyphicon glyphicon glyphicon-certificate" aria-hidden="true" title="' + tooltip + '"></span>'
							}
							return html;
						}
					}, 
					{
						data : "categoryCode",
            			searchable: true
					}, 
					{
						data: "result",
						render : function (data, type, row, meta) {
							return ipswichjaffarc.formatTime(data);
						},
						className : 'text-right',
						searchable: false
					}, 
					{
						data: "eventName",
          				searchable: true,
						render: function ( data, type, row, meta ) {	
							var resultsUrl = '<?php echo $raceResultsPageUrl; ?>';
							var anchor = '<a href="' + resultsUrl;
							anchor += '?raceId=' + row.raceId;					
							anchor += '">' + data + ' (' + row.date + ') </a>';								

							return anchor;
						}
					}
				],
				processing : true,
				autoWidth : false,
				scrollX : false,				
				ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/county'),
				drawCallback: function ( settings ) {
				var api = this.api();
				var rows = api.rows( {page:'current'} ).nodes();
				var last=null;

				api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
					var year = group.substring(0, 4);
					if ( last !== year ) {
						$(rows).eq( i ).before(
							'<tr class="group"><th colspan="5">'+year+'</th></tr>'
						);

						last = year;
					}
				} );
				}
			});
		}
    
    function getAjaxRequest(url) {
			return {				
				"url" : '<?php echo esc_url( home_url() ); ?>' + url,
				"method" : "GET",
				"headers" : {
					"cache-control" : "no-cache"
				},
				"dataSrc" : ""
			}
		}
	});
</script>

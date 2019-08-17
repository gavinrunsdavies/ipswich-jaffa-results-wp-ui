<div class="section"> 
	<h2 id="jaffa-event-title"></h2>
	<div class="center-panel" id="jaffa-race-results">
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		
		getCountyChampionResults();
		
		function getCountyChampionResults() {
			var tableName = 'jaffa-race-county-results-table';
			var tableHtml = '';
			var tableRow = '<tr><th>Position</th><th>Name</th><th>Time</th><th>Personal Best</th><th>Season Best</th><th>Category</th><th>Standard</th><th>Info</th><th>Age Grading</th></tr>';
			tableHtml += '<table class="table table-striped table-bordered no-wrap" id="' + tableName + '">';
			tableHtml += '<caption style="text-align:center;font-weight:bold;font-size:1.5em">' + description + ', ' + formatDate(date) + '</caption>';
			tableHtml += '<thead>';
			tableHtml += tableRow;
			tableHtml += '</thead>';
			tableHtml += '</table>';
			$('#jaffa-race-results').append(tableHtml);
			
			var table = $('#'+tableName).DataTable({				
				dom: 'tBip',
				buttons: {
					buttons: [{
					  extend: 'print',
					  text: '<i class="fa fa-print"></i> Print',
					  title: $('#jaffa-event-title').text() + ': ' + $('#' +tableName + ' caption').text(),
					  footer: true					  
					}]
				},
				paging : false,
				searching: false,
				serverSide : false,
				columns : [{
						data : "date",
						render : function (data, type, row, meta) {
							return data.substring(0, 4);
						}
					}, {
						data : "runnerName",
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
					}, {
						data : "categoryCode"
					}, {
						data : "result"
					}
				],
				processing : true,
				autoWidth : false,
				scrollX : true,
				order : [[0, "asc"], [2, "asc"]],
				ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/county')
			});
		}

		function formatDate(date) {
			return (new Date(date)).toDateString();
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
	<?php endif; ?>
</script>
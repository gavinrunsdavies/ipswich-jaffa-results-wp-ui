<div class="section"> 
	<style>
		div.center-panel { margin-bottom:1em}
	</style>	
	<div style="display:block" class="center-panel">		
		<table class="display" id="top-member-results-table" style="width:100%">	
			<caption>Members with most results</caption>				
			<thead>
				<tr>
					<th>Name</th>						
					<th>Total Member Results</th>
				</tr>
			</thead>			
			<tbody>				
			</tbody>
		</table>
	</div>	
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {			
		
		var topMemberResultsTable = $('#top-member-results-table');	
			
		topMemberResultsTable.DataTable({			
			  "columns": [
             { data: "name" },
             { data: "count" }
         ],
		    order: [[ 1, "desc" ]],
			columnDefs   : [
				{
					targets: [ 0 ], 
					"render": function ( data, type, row, meta ) {				
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
				}				
			],
			displayLength : 10,
			lengthChange : false,
			processing    : true,				
			searching : false,
			ajax    	  : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/statistics/type/7')					
		});				

		function getAjaxRequest(url) {
			return {
			  //"async": false,
			  "url" : '<?php echo esc_url( home_url() ); ?>' + url,
			  "method": "GET",
			  "headers": {
				"cache-control": "no-cache"				
			  },
			  "dataSrc" : ""
			}
		}
	});
</script>

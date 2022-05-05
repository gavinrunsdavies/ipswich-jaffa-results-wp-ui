<div class="section"> 
	<style>
		div.center-panel { margin-bottom:1em}
	</style>

	<div style="display:block" class="center-panel">		
		<table class="display" id="top-jaffa-attended-races-table" style="width:100%">	
			<caption>Races with most JAFFA results</caption>				
			<thead>
				<tr>				
					<th>Name</th>						
					<th>Date</th>
					<th>Total Member Results</th>
				</tr>
			</thead>			
			<tbody>				
			</tbody>
		</table>
	</div>
	<div style="display:none" class="center-panel">		
		<table class="display" id="top-jaffa-attended-races-year-table" style="width:100%">	
			<caption>Races with most JAFFA results by year</caption>				
			<thead>
				<tr>
					<th>Year</th>					
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
		
		var topJaffaRaceTable = $('#top-jaffa-attended-races-table');	
			
		topJaffaRaceTable.DataTable({			
			  "columns": [
             { data: "name" },
             { data: "date" },
             { data: "count" }          
         ],
		    order: [[ 2, "desc" ]],
			columnDefs   : [
				{
					targets: [ 0 ], 
					"render": function ( data, type, row, meta ) {				
						var eventResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
						var anchor = '<a href="' + eventResultsUrl;
						if (eventResultsUrl.indexOf("?") >= 0) {
							anchor += '&eventId=' + row.eventId + '&date=' + row.date + '&event=' + data;
						} else {
							anchor += '?eventId=' + row.eventId + '&date=' + row.date+ '&event=' + data;
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
			ajax    	  : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/statistics/type/6')					
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

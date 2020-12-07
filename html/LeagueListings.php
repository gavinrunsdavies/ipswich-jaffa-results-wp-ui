<div class="section"> 
	<div class="center-panel">
		<table class="table table-striped table-bordered" id="league-listings-table">
			<thead>
				<tr>
					<th data-hide="always">cc</th>
					<th></th>
					<th>League</th>
					<th>Starting Year</th>
					<th>Total Number of Races</th>
					<th>Final Position</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>cc</th>
					<th></th>
					<th>League</th>
					<th>Starting Year</th>
					<th>Total Number of Races</th>
					<th>Final Position</th>
				</tr>
			</tfoot>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<style>
.close {
  color: red;
}
.open {
  color: green;
}
</style>
<script type="text/javascript">
	jQuery(document).ready(function($) {	

		var tableElement = $('#league-listings-table');
		
		var leagueTable = tableElement.dataTable({
			pageLength : 25,
			columns:[
			 {
				data: "id",
				visible : false,
				searchable: false,
				sortable: false 
			 },
			 {
				data: "name",
				class: "center",
				searchable: false,
				sortable: false,	
				render: function ( data, type, row, meta ) {				
					return '<span class="glyphicon glyphicon-chevron-down open"></span>';
				}	
			 },
             {
				data: "name"
			 },
			 {
				data: "startingYear",		
				searchable: false,
				sortable: true 
			 },
			 {
				data: "numberOfRaces",				 
				searchable: false,
				sortable: false 
			 },
			 {
				data: "finalPosition",				 
				searchable: false,
				sortable: true 
			 }
			],
			processing    : true,
			autoWidth     : false,	
			order: [[ 3, "desc" ]],
			scrollX: true,
			ajax    : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/leagues?courseTypeId=5')
		});
		
		function getAjaxRequest(url) {
			return {
				url : '<?php echo esc_url( home_url() ); ?>' + url,
				method : "GET",
				headers : {
					"cache-control" : "no-cache"
				},
				dataSrc : ""
			}
		}

		$(document).on("click", '#league-listings-table tbody td span.glyphicon-chevron-down', function () {
			$(this).addClass("glyphicon-chevron-up");
			$(this).addClass("close");
			$(this).removeClass("glyphicon-chevron-down");
			$(this).removeClass("open");
			var nTr = this.parentNode.parentNode;
			var newTr = leagueTable.fnOpen( nTr, 'Loading data...', 'details' );
			var aData = leagueTable.fnGetData( nTr );
			fnSetEventDetails(leagueTable, newTr, aData.id, aData.eventName);
		});

		$(document).on("click", '#league-listings-table tbody td span.glyphicon-chevron-up', function () {
			$(this).addClass("glyphicon-chevron-down");
			$(this).addClass("open");
			$(this).removeClass("glyphicon-chevron-up");
			$(this).removeClass("close");
			var nTr = this.parentNode.parentNode;
			leagueTable.fnClose( nTr );
		});
		
		/* Formating function for row details */
		function fnSetEventDetails ( oTable, nTr, iLeagueId, sLeagueName)
		{		
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/leagues/'+ iLeagueId))			
				.done(function(data) {	
					var tableName = 'leagueTable'+iLeagueId;					
					sOut = '<table class="table table-condensed" id="' + tableName + '">';
					sOut += '<thead>';
					sOut += '<tr><th>Event</th><th>Race</th><th>Date</th><th>Venue</th><th>Number of Results</th></tr>';
					sOut += '</thead>';
					sOut += '<tbody>';
					for (var i = 0; i < data.length; i++) {					
						sOut += '<tr>';
					
						var eventResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
						var anchor = '<a href="' + eventResultsUrl;
						if (eventResultsUrl.indexOf("?") >= 0) {
							anchor += '&raceId=' + data[i].raceId;
						} else {
							anchor += '?raceId=' + data[i].raceId;
						}
						anchor += '">' + data[i].raceName + '</a>';	
						sOut += '<td>'+ anchor + '</td>';
                        sOut += '<td>' + nullToEmptyString(data[i].eventName) + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].raceDate) + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].raceVenue) + '</td>';
						sOut += '<td>' + data[i].numberOfResults + '</td>';
						sOut += '</tr>';
					}
					sOut += '</tbody>';
					sOut += '</table>';
					
					$('td', nTr).html(sOut);					
					$('#' + tableName).DataTable({
						paging : false,
						searching: false,
                        order: [[ 2, "desc" ]],
                        columnDefs: [
							{
								targets: [1],
								visible: false
							}
						],
                        // add meeting Id as grouped rows
						drawCallback: function ( settings ) {
							var api = this.api();
							var rows = api.rows( {page:'current'} ).nodes();
							var last = null;
							var meetingIdColumnIndex = 1;
							api.column(meetingIdColumnIndex, {page:'current'} ).data().each( function ( eventName, i ) {
                                if ( last !== eventName ) {
                                    last = eventName; // Assume success!
                                    $(rows).eq( i ).before(
                                        '<tr class="group"><th colspan="4">'+eventName+'</th></tr>'
                                    );
                                }
							} );
						}
					});				
				}				
			);	
		}
		
		function nullToEmptyString(value) {
			return (value == null) ? "" : value;
		}
	});
</script>
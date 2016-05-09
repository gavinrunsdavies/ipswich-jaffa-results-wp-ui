<div class="section"> 
	<div class="center-panel">
		<table class="table table-striped table-bordered" id="event-listings-table">
			<thead>
				<tr>
					<th data-hide="always">cc</th>
					<th></th>
					<th>Event Name</th>
					<th data-hide="phone,tablet">Website</th>
					<th>Last Race Date</th>
					<th>Total Number of Results</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>cc</th>
					<th></th>
					<th>Event Name</th>
					<th>Website</th>
					<th>Last Race Date</th>
					<th>Total Number of Results</th>
				</tr>
			</tfoot>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {	

		var tableElement = $('#event-listings-table');
		
		var eventTable = tableElement.dataTable({
			pageLength : 25,
			columns:[
			 {
				data: "id",
				"visible" : false,
				"searchable": false,
				"sortable": false 
			 },
			 {
				// Open / Close image
				data: "name",
				"class": "center",
				"searchable": false,
				"sortable": false,
				// Member name, add hyperlink to profile
				"render": function ( data, type, row, meta ) {				
					return '<img src="<?php echo plugins_url('images/details_open.png', dirname(__FILE__)); ?>" />';
				}		
			 },
			 {
				data: "name"
			 },
			 {
				data: "website",
				"class": "left",
				"searchable": false,
				"render": function ( data, type, row, meta ) {		
					var sLink = "";
					var sAddress = row.website;
					if (sAddress != "" && sAddress != null && sAddress != "null")
					{
						if (!sAddress.startsWith('http')) {
							sLink = 'http://'+sAddress;
						} else {
							sLink = sAddress;
						}
						
						sLink = '<a href="' + sLink + '" target="_blank">'+sAddress+'</a>';
					}
					
					return sLink;
				}
			 },
			 {
				data: "lastRaceDate",		
				searchable: false,
				sortable: true 
			 },
			 {
				data: "count",				 
				searchable: false,
				sortable: false 
			 }
			],
			processing    : true,
			autoWidth     : false,	
			order: [[ 4, "desc" ]],
			scrollX: true,
			ajax    : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events')
		});
		
		function getAjaxRequest(url) {
			return {
			  //"async": false,
			  "url": '<?php echo get_site_url(); ?>' + url,
			  "method": "GET",
			  "headers": {				
				"cache-control": "no-cache"				
			  },
			  "dataSrc" : ""
			}
		}
	
		$('#event-listings-table tbody td img').live( 'click', function () {
			var nTr = this.parentNode.parentNode;
			if ( this.src.match('details_close') )
			{
				/* This row is already open - close it */
				this.src = '<?php echo plugins_url('images/details_open.png', dirname(__FILE__)); ?> ';
				eventTable.fnClose( nTr );
			}
			else
			{
				/* Open this row */
				this.src = '<?php echo plugins_url('images/details_close.png', dirname(__FILE__)); ?> ';
				var newTr = eventTable.fnOpen( nTr, 'Loading data...', 'details' );
				var aData = eventTable.fnGetData( nTr );
				fnSetEventDetails(eventTable, newTr, aData.id, aData.eventName);
			}
		} );
		
		/* Formating function for row details */
		function fnSetEventDetails ( oTable, nTr, iEventId, sEventName)
		{		
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events/'+ iEventId + '/races'))			
				.done(function(data) {				
					var sOut = '<table class="table table-condensed">';
					sOut += '<tr><th>Date</th><th>Description</th><th>Course Type</th><th>County</th><th>Country</th><th>Conditions</th><th>Venue</th><th>Distance</th><th>Grand Prix Race?</th><th>Number of Results</th></tr>';
					for (var i = 0; i < data.length; i++) {					
						sOut += '<tr>';
					
						var eventResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
						var anchor = '<a href="' + eventResultsUrl;
						if (eventResultsUrl.indexOf("?") >= 0) {
							anchor += '&eventId=' + data[i].id + '&date=' + data[i].date + '&event=' + sEventName;
						} else {
							anchor += '?eventId=' + data[i].id + '&date=' + data[i].date+ '&event=' + sEventName;
						}
						anchor += '">' + data[i].date + '</a>';	
						sOut += '<td>'+ anchor + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].description)+ '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].courseType) + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].county) + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].countryCode) + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].conditions) + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].venue) + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].distance) + '</td>';
						sOut += '<td class="text-center">';
						if (data[i].isGrandPrixRace == 1)
							sOut += '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
						sOut += '</td>';
						sOut += '<td>' + data[i].count + '</td>';
						sOut += '</tr>';
					}
					
					sOut += '</table>';
					
					$('td', nTr).html(sOut);
				}				
			);	
		}
		
		function nullToEmptyString(value) {
			return (value == null) ? "" : value;
		}
	});
</script>

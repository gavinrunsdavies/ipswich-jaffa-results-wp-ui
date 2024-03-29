<div class="section"> 
	<table class="display" id="event-listings-table">
		<thead>
			<tr>
				<th data-hide="always"></th>
				<th></th>
				<th>Event Name</th>
				<th data-hide="phone,tablet">Website</th>
				<th>Last Race Date</th>
				<th>Total Number of Results</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<style>
	.showHideRacesCell {
  		cursor: pointer;
  		user-select: none;
	}	
	
	.showHideRacesCell:hover {
		color: var(--primary-color);
	}
</style>
<script type="text/javascript">
	jQuery(document).ready(function($) {	

		var tableElement = $('#event-listings-table');
		
		var eventTable = tableElement.dataTable({
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
				class: "center showHideRacesCell",
				searchable: false,
				sortable: false,
				// Member name, add hyperlink to profile
				render: function ( data, type, row, meta ) {				
					return '<i class="showHideRaces fa fa-chevron-down"></i>';
				}		
			 },
			 {
				data: "name"
			 },
			 {
				data: "website",
				class: "left",
				searchable: false,
				render: function ( data, type, row, meta ) {		
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
		
		getCountryNameJson();
		
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
		
		function getCountryNameJson() {			
			$.getJSON( '<?php echo plugins_url('data/countryCodes.json', dirname(__FILE__)); ?> ')
				.done(function( data ) {
					countryNames = data;
				});
		}
		
		function getCountryName(code) {
			// Do not translate GB
			if (code == 'GB' || code == "")
				return code;	
			var countryName = "";
			$.each( countryNames, function( i, item ) {					
				if ( item.code == code ) {
				  countryName = item.name;
				  return false;
				}
			});
			
		  return countryName;
		}
		
		$(document).on("click", '#event-listings-table tbody td .showHideRaces', function () {
			var icon = $(this);
			var icon_fa_icon = icon.attr('data-icon');
            var nTr = this.parentNode.parentNode;
			
			if (icon_fa_icon === "chevron-up") {
				eventTable.fnClose( nTr );
				icon.attr('data-icon', 'chevron-down');
			} else {
				var newTr = eventTable.fnOpen( nTr, 'Loading data...', 'details' );
			    var aData = eventTable.fnGetData( nTr );
			    fnSetEventDetails(eventTable, newTr, aData.id, aData.eventName);
				icon.attr('data-icon', 'chevron-up');
			}
		});
		
		$(document).on('click', 'a.toggle-vis', function(){
			var tableName = $(this).attr('data-table');
			var table = $('#'+tableName).DataTable();
			var column = table.column( $(this).attr('data-column') );
 
			// Toggle the visibility
			column.visible( ! column.visible() );
			return false;
		});
		
		/* Formating function for row details */
		function fnSetEventDetails ( oTable, nTr, iEventId, sEventName)
		{		
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events/'+ iEventId + '/races'))			
				.done(function(data) {	
					var tableName = 'eventTable'+iEventId;					
					var sOut = '<div>';
					sOut += 'Toggle column: ';
					sOut += '<a class="toggle-vis" data-table="'+tableName+'"data-column="2">Course Type</a> - ';
					sOut += '<a class="toggle-vis" data-table="'+tableName+'"data-column="3">County</a> - ';
					sOut += '<a class="toggle-vis" data-table="'+tableName+'"data-column="4">Country</a> - ';
					sOut += '<a class="toggle-vis" data-table="'+tableName+'"data-column="5">Conditions</a> - ';
					sOut += '<a class="toggle-vis" data-table="'+tableName+'"data-column="6">Venue</a> - ';
					sOut += '<a class="toggle-vis" data-table="'+tableName+'"data-column="7">Distance</a> - ';
					sOut += '<a class="toggle-vis" data-table="'+tableName+'"data-column="8">Grand Prix Race?</a>';
					sOut += '</div>';
					sOut += '<table class="table table-condensed" id="' + tableName + '">';
					sOut += '<thead>';
					sOut += '<tr><th>Date</th><th>Description</th><th>Course Type</th><th>County</th><th>Country</th><th>Conditions</th><th>Venue</th><th>Distance</th><th>Grand Prix Race?</th><th>Number of Results</th></tr>';
					sOut += '</thead>';
					sOut += '<tbody>';
					for (var i = 0; i < data.length; i++) {					
						sOut += '<tr>';
					
						var eventResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
						var anchor = '<a href="' + eventResultsUrl;
						if (eventResultsUrl.indexOf("?") >= 0) {
							anchor += '&raceId=' + data[i].id;
						} else {
							anchor += '?raceId=' + data[i].id;
						}
						anchor += '">' + data[i].date + '</a>';	
						sOut += '<td>'+ anchor + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].description)+ '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].courseType) + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].county) + '</td>';
						sOut += '<td>' + getCountryName(nullToEmptyString(data[i].countryCode)) + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].conditions) + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].venue) + '</td>';
						sOut += '<td>' + nullToEmptyString(data[i].distance) + '</td>';
						sOut += '<td class="text-center">';
						if (data[i].isGrandPrixRace == 1)
							sOut += '<i class="fa fa-check" aria-hidden="true"></i>';
						sOut += '</td>';
						sOut += '<td>' + data[i].count + '</td>';
						sOut += '<td>' + data[i].meetingId + '</td>';
						sOut += '</tr>';
					}
					sOut += '</tbody>';
					sOut += '</table>';
					
					$('td', nTr).html(sOut);					
					$('#' + tableName).DataTable({
						paging : false,
						searching: false,
						order: [[ 0, "desc" ]],
						columnDefs: [
							{
								targets: [ 2, 3, 4, 5, 6, 10],
								visible: false
							}
						],
						// add meeting Id as grouped rows
						drawCallback: function ( settings ) {
							var api = this.api();
							var rows = api.rows( {page:'current'} ).nodes();
							var last=null;
							var meetingIdColumnIndex = 10;
							api.column(meetingIdColumnIndex, {page:'current'} ).data().each( function ( meetingId, i ) {
								if (meetingId > 0) {
									if ( last !== meetingId ) {
										last = meetingId; // Assume success!
										$.ajax(
											getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events/'+ iEventId + '/meetings/' + meetingId))			
											.done(function(meetingData) {	
												if (meetingData.length > 0) {
													var meetingDates = meetingData[0].fromDate;
													if (meetingData[0].fromDate != meetingData[0].toDate) {
														meetingDates += ' - ' + meetingData[0].toDate;
													}											
													$(rows).eq( i ).before(
														'<tr class="group"><td colspan="11">Meeting: <strong>'+meetingData[0].name+'</strong> ('+meetingDates+')</td></tr>'
													);							 
												}												
											});
									}
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

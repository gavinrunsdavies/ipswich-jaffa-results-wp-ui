<?php
	wp_enqueue_script(
		'dataTables.rowGroup.min',
		'https://cdn.datatables.net/rowgroup/1.5.1/js/dataTables.rowGroup.min.js',
		array('jquery.dataTables.min'),
		null,
		true
	);
?>
<div class="section"> 
	<table class="display" id="event-listings-table">
		<thead>
			<tr>
				<th></th>
				<th>Event Name</th>
				<th>Last Race</th>
				<th>Results Count</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<style>
	.event-detail {
    font-size: smaller;
    font-style: italic;
    color: #888;
}
</style>
<script>
    const link = document.createElement("link");
    link.rel = "stylesheet";
    link.href = "https://cdn.datatables.net/rowgroup/1.5.1/css/rowGroup.dataTables.min.css";
    document.head.appendChild(link);
</script>
<script type="text/javascript">
	jQuery(document).ready(function($) {	

		var tableElement = $('#event-listings-table');		
		var eventTable = tableElement.DataTable({
			pageLength : 25,
			columns:[
			 {
				className: 'dt-control',
				data: null,
				sortable: false,
				defaultContent: ''
			 },
			 {
				data: "name",
				render: function (data, type, row, meta) {									
					let html = data;
					let link = '';
					if (row.website) {
                        if (!row.website.startsWith('http')) {
							link = 'http://'+row.wwebsite;
						} else {
							link = row.website;
						}
						
						link = `<a href="${link}" target="_blank">${row.website}</a>`;
                        html += `<div class="event-detail">${link}</div>`;
					}  

                    return html;
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
			processing : true,
			autoWidth : true,	
			order: [[ 2, "desc" ]],
			scrollX: false,
			ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events')
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

		eventTable.on('click', 'td.dt-control', function (e) {
            let tr = e.target.closest('tr');
			let row = eventTable.row(tr);
			let data = row.data();

			if (row.child.isShown()) {
				row.child.hide();
			} else {
				row.child('<div>Loading...</div>').show();
				setEventDetails(eventTable, row, data.id);
			}
		});
		
		/* Formating function for row details */
		function setEventDetails (oTable, nTr, iEventId)
		{		
			$.ajax(
				getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/events/'+ iEventId + '/races'))			
				.done(function(data) {	
					var tableName = 'eventTable'+iEventId;
					sOut = '<table class="table table-condensed" id="' + tableName + '">';
					sOut += '<thead>';
					sOut += '<tr><th>Description</th><th>Distance</th><th>Grand Prix?</th><th>Count</th><th>Date</th><th>Course Type</th><th>Area</th><th>County</th><th>Country</th><th>Venue</th></tr>';
					sOut += '</thead>';
					sOut += '</table>';
					nTr.child($(sOut)).show();
					
					$('#' + tableName).DataTable({
                        data: data,
						paging : false,
						searching: false,
						order: [[ 4, "desc" ]],
						 columns: [
                          { 
                            data: 'description',
                            render: function(data) {
                              return nullToEmptyString(data);
                            }
                          },
                          { 
                            data: 'distance',
                            render: function(data) {
                              return  nullToEmptyString(data);
                            }
                          },
                          { 
                            data: 'isGrandPrixRace',
                            render: function(data) {
                              return data === "1" ? `<i class="fa fa-check" aria-hidden="true"></i>` : ``;
                            }
                          },
                          { 
                            data: 'count',
                            render: function(data, type, row, meta) {
                                var eventResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
        						var link = `${eventResultsUrl}?raceId=${row.id}`
                                return `<a href="${link}" target="_blank">${data}</a>`;
                            }
                          },
                          // Grouping fields (hidden in table)
                          { data: 'date', visible: false },
                          { data: 'courseType', visible: false },
                          { data: 'area', visible: false },
                          { data: 'county', visible: false },
                          { data: 'countryCode', visible: false },
                          { data: 'venue', visible: false }
                        ],
                        responsive: true,
                        rowGroup: {
                            dataSrc: function(row) {
                                return [
                                  row.date,
                                  row.courseType,
                                  row.area,
                                  row.county,
                                  row.countryCode,
                                  row.venue
                                ].join('|');
                            },
                            startRender: function(rows, group) {
                                const parts = group.split('|');
                                return `${parts[0]} | ${parts[1]} | ${parts[2]}, ${parts[3]}, ${getCountryName(nullToEmptyString(parts[4]))} | ${parts[5]}`;
                            }
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

<div class="section"> 
	<style>
		div.center-panel { margin-top:3em}
	</style>
	<div style="display:block" class="center-panel">		
		<table class="table table-striped table-bordered" id="results-year-table" style="width:100%">	
			<caption style="font-weight:bold; padding: 0.5em">Number of Results By Year</caption>				
			<thead>
				<tr>					
					<th>Year</th>
					<th>Number of Results</th>
				</tr>
			</thead>			
			<tbody>				
			</tbody>
		</table>
	</div>
	<div style="display:block" class="center-panel">		
		<table class="table table-striped table-bordered" id="personal-best-year-table" style="width:100%">	
			<caption style="font-weight:bold; padding: 0.5em">Personal Bests By Year</caption>				
			<thead>
				<tr>	
					<th>Year</th>
					<th>Number of Personal Bests</th>
				</tr>
			</thead>			
			<tbody>				
			</tbody>
		</table>
	</div>
	<div style="display:block" class="center-panel">		
		<table class="table table-striped table-bordered" id="personal-best-total-table" style="width:100%">	
			<caption style="font-weight:bold; padding: 0.5em">Personal Best Total</caption>				
			<thead>
				<tr>					
					
					<th>Name</th>								
					<th>Number of Personal Bests</th>
					<th>First PB</th>
					<th>Last PB</th>
				</tr>
			</thead>			
			<tbody>				
			</tbody>
		</table>
	</div>	
	<div style="display:block" class="center-panel">		
		<table class="table table-striped table-bordered" id="top-jaffa-attended-races-table" style="width:100%">	
			<caption style="font-weight:bold; padding: 0.5em">Races with most JAFFA results</caption>				
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
	<div style="display:block" class="center-panel">		
		<table class="table table-striped table-bordered" id="top-member-results-table" style="width:100%">	
			<caption style="font-weight:bold; padding: 0.5em">Members with most results</caption>				
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
	<div style="display:none" class="center-panel">		
		<table class="table table-striped table-bordered" id="top-jaffa-attended-races-year-table" style="width:100%">	
			<caption style="font-weight:bold; padding: 0.5em">Races with most JAFFA results by year</caption>				
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
	<div id="map-uk-county-results" style="display:block" class="center-panel">		
		<h4>Map showing location of results across the UK and English counties</h4>
		<em>This data is based on event course location (e.g. <a href="http://coursemeasurement.org.uk/">http://coursemeasurement.org.uk/</a>) and this data is largely incomplete. As such the following map is not a true representation of where races have occured and should only be used as a guide.</em>
		<div class="map" id="countyMap" style="width: 100%; height: 500px;"></div>			
		<div style="width: 25%; display: inline">
			Speed: Slow <input class="speed" type="range" min="100" max="900" step="100" value="300" style="width: 25%; display: inline" > Fast,
		</div>
		Cumalative <input class="cumalative" type="checkbox" value="1">
		<input class="play" type="button" value="start" />
		Current Year: <span class="year"></span>	
	</div>
	<div id="map-world-results" style="display:block" class="center-panel">		
		<h4>Map showing location of results across the world.</h4>
		<em>This data is based on event course location and this data is largely incomplete. As such the following map is not a true representation of where races have occured and should only be used as a guide.</em>
		<div class="map" id="worldMap" style="width: 100%; height: 500px;"></div>			
		<div style="width: 25%; display: inline">
			Speed: Slow <input class="speed" type="range" min="100" max="900" step="100" value="300" style="width: 25%; display: inline" > Fast,
		</div>
		Cumalative <input class="cumalative" type="checkbox" value="1">
		<input class="play" type="button" value="start" />
		Current Year: <span class="year"></span>	
		<div class="info" style="height: 100px; overflow: auto">
		<ul></ul>
		</div>		
	</div>
</div>
<!-- amCharts javascript sources -->
<script type="text/javascript" src="http://www.amcharts.com/lib/3/ammap.js"></script>
<script type="text/javascript" src="http://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
<script type="text/javascript" src="http://www.amcharts.com/lib/3/themes/none.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {	
					
		var resultsYearTable = $('#results-year-table');	
			
		resultsYearTable.DataTable({			
			  "columns": [
             { data: "year" },
             { data: "count" }           
         ],
		    order: [[ 0, "desc" ]],			
			paging: true,
			displayLength : 10,
			lengthChange : false,
			processing    : true,	
			searching : false,
			ajax    	  : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/statistics/type/3')					
		});
		
		var pbYearTable = $('#personal-best-year-table');	
			
		pbYearTable.DataTable({			
			  "columns": [
             { data: "year" },
             { data: "count" }           
         ],
		    order: [[ 1, "desc" ]],			
			paging: true,
			displayLength : 10,
			lengthChange : false,
			processing    : true,				
			searching : false,
			ajax    	  : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/statistics/type/5')					
		});
		
		var pbTable = $('#personal-best-total-table');	
			
		pbTable.DataTable({			
			  "columns": [
             { data: "name" },
             { data: "count" },
             { data: "firstPB" },
             { data: "lastPB" }            
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
			ajax    	  : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/statistics/type/4')					
		});
		
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
			
		var startYear = 1977;
		var endYear = (new Date()).getFullYear();	
		
		var worldMap = AmCharts.makeChart("worldMap", {
			"type" : "map",
			"theme" : "none",
			"pathToImages" : "http://www.amcharts.com/lib/3/images/",
			"dataProvider" : {
				"map" : "worldLow",
				"getAreasFromMap" : true
			},
			"areasSettings" : {
				"autoZoom" : false,
				"color" : "#CCCCCC",
				"selectable" : true,
				"selectedColor" : "#F7931E"
			},
			"smallMap" : {}
		});
			
		var countyMap = AmCharts.makeChart("countyMap", {
			"type" : "map",
			"theme" : "none",
			"pathToImages" : "http://www.amcharts.com/lib/3/images/",
			"dataProvider" : {
				"mapURL" : "<?php echo plugin_dir_url(__FILE__); ?>ukCounties.svg",				
				"getAreasFromMap" : true
			},
			"areasSettings" : {
				"autoZoom" : false,
				"color" : "#CCCCCC",
				"selectable" : true,
				"selectedColor" : "#F7931E"
			}
		});
				
		var countries = {};
		var worldMapData;
		$.ajax(getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/statistics/type/2'))
			.done(function(data) {
				worldMapData = data;
				
				for (var i = 0; i < worldMapData.length; i++) {			
					if (worldMapData[i].country_code in countries) {
						countries[worldMapData[i].country_code] += parseInt(worldMapData[i].count);
					} else {
						countries[worldMapData[i].country_code] = parseInt(worldMapData[i].count);
					}			
				}
				$.each( countries, function( key, value ) {
				 $('#map-world-results div.info ul').append('<li>Country: ' + key + '. Results: ' + value + '</li>');			
				});	
			});
			
		var counties = {};
		var ukCountyMapData;
		$.ajax(getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/statistics/type/1'))
			.done(function(data) {
				ukCountyMapData = data;
				
				/*for (var i = 0; i < ukCountyMapData.length; i++) {			
					if (ukCountyMapData[i].county in countries) {
						counties[ukCountyMapData[i].county] += parseInt(ukCountyMapData[i].count);
					} else {
						counties[ukCountyMapData[i].county] = parseInt(ukCountyMapData[i].count);
					}
				}	

				$.each( counties, function( key, value ) {
				 $('#map-uk-county-results div.info ul').append('<li>County: ' + key + '. Results: ' + value + '</li>');		
				});*/
			});				
			
		$('div').find('.play').click(function() {
			toggleMapPlay($(this).parent().attr("id"));
		});
		
		function stopMapAnimation(button, interval) {
			// stop playing (clear interval)
			clearInterval(interval);
			currentYear = startYear;	
			interval = 0;
			button.value = "start";
		}
		
		function resetMapColour(map) {			
			for(var a = 0; a < map.dataProvider.areas.length; a++) {
				map.dataProvider.areas[a].color = "#CCCCCC";
				map.dataProvider.areas[a].colorReal = "#CCCCCC";
				map.returnInitialColor(map.dataProvider.areas[a]);
			} 			
		}
		
		function toggleMapPlay(containerId) {
			var data;
			var map;
			if (containerId == 'map-world-results') {
				data = worldMapData;
				map = worldMap;
			} else if (containerId == 'map-uk-county-results') {
				data = ukCountyMapData;
				map = countyMap;
			}
			
			var interval = 0;
			var currentYear = startYear;
			var container = $('#'+containerId);			
			var speed = 1000 - container.find('.speed')[0].value; // time between frames in milliseconds			
			var button = container.find('.play')[0];
			var year = container.find('.year')[0];
			var cumalative = container.find('.cumalative')[0];
			// check if animation is playing (intveral is set)
			if (interval) {
				stopMapAnimation(button, interval);				
				return;
			} else {

				// start playing
				resetMapColour(map);
				button.value = "Running...";
				interval = setInterval(function () {

					if (currentYear > endYear) {
						stopMapAnimation(button, interval);	
						return;
					}
					
					//reset colour
					if (!cumalative.checked) {
						resetMapColour(map);
					}

					// set data to the chart for the current frame
					for (var c = 0; c < data.length; c++) {
						var resultYear = data[c].year;
						if (resultYear == currentYear) {
							var areaCode = data[c].country_code != null ? data[c].country_code : data[c].county ;
							if (areaCode != null) {							
								if (containerId == 'map-uk-county-results') {
									for(var a = 0; a < map.dataProvider.areas.length; a++) {
										var area = map.dataProvider.areas[a];
										if (areaCode == area.title) {
											area.color = "#F7931E";
											area.colorReal = area.color;
											map.returnInitialColor(area);
											break;
										}
									}
								} else {
									var area = map.getObjectById(areaCode);
									if (area != null) {																	
										area.color = "#F7931E";
										area.colorReal = area.color;
										map.returnInitialColor(area);									
									}		
								}
							}
						}
					}											

					// set frame indicator
					year.innerHTML = currentYear;

					currentYear++;

				}, speed);
			}
		}		

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
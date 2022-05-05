<div class="section"> 
	<div id="map-uk-county-results" style="display:block" class="formRankCriteria">		
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

</div>
<!-- amCharts javascript sources -->
<script type="text/javascript" src="https://www.amcharts.com/lib/3/ammap.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/maps/js/worldLow.js"></script>
<script type="text/javascript" src="https://www.amcharts.com/lib/3/themes/none.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {	
									
		var startYear = 1977;
		var endYear = (new Date()).getFullYear();	
			
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
			
		var counties = {};
		var ukCountyMapData;
		$.ajax(getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/statistics/type/1'))
			.done(function(data) {
				ukCountyMapData = data;
				// TODO SHOW IN TABLE?
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
			var data = ukCountyMapData;
			var map = countyMap;
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
								for(var a = 0; a < map.dataProvider.areas.length; a++) {
									var area = map.dataProvider.areas[a];
									if (areaCode == area.title) {
										area.color = "#F7931E";
										area.colorReal = area.color;
										map.returnInitialColor(area);
										break;
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

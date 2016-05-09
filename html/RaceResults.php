<div class="section"> 
	<div class="center-panel">
		<div class="row">
			<div class="col-sm-6">Event:</div>
			<div class="col-sm-6" id="eventName"></div>
		</div>
		<div class="row">
			<div class="col-sm-2">Race:</div>
			<div class="col-sm-4"  id="description"></div>
			<div class="col-sm-2">Date:</div>
			<div class="col-sm-4" id="date"></div>
		</div>
		<div class="row">
			<div class="col-sm-2">Venue:</div>
			<div class="col-sm-4" id="venue"></div>
			<div class="col-sm-2">Conditions:</div>
			<div class="col-sm-4" id="conditions"></div>
		</div>
		<div class="row">
			<div class="col-sm-2">Country:</div>
			<div class="col-sm-2" id="countryCode"></div>
			<div class="col-sm-2">County:</div>
			<div class="col-sm-2" id="county"></div>
			<div class="col-sm-2">Area:</div>
			<div class="col-sm-2" id="area"></div>
		</div>
		<div class="row">
			<div class="col-sm-2">Distance:</div>
			<div class="col-sm-2" id="distance"></div>
			<div class="col-sm-2">Grand Prix Race?:</div>
			<div class="col-sm-2" id="isGrandPrixRace"></div>
			<div class="col-sm-1">Course Type:</div>
			<div class="col-sm-1" id="courseType"></div>
			<div class="col-sm-1">Course:</div>
			<div class="col-sm-1" id="course"></div>
		</div>
		
		<span style="font-weight:bold; padding: 0.5em">Race Results for </span>		
		<table id="jaffa-race-results-table" class="table table-striped table-bordered no-wrap">	
			<thead>
				<tr>
					<th>Position</th>					
					<th>Name</th>
					<th>Time</th>
					<th>Personal Best</th>
					<th>Season Best</th>
					<th>Category</th>
					<th>Standard</th>
					<th>Info</th>		
					<th>Age Grading</th>			
				</tr>
			</thead>
			<tfoot>
				<tr>				
					<th>Position</th>					
					<th>Name</th>
					<th>Time</th>
					<th>Personal Best</th>
					<th>Season Best</th>
					<th>Category</th>
					<th>Standard</th>
					<th>Info</th>		
					<th>Age Grading</th>
				</tr>
			</tfoot>
     	</table>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function ($) {

		$.getJSON(
		  '<?php echo get_site_url(); ?>/wp-json/ipswich-jaffa-api/v2/races/<?php echo $_GET['raceId']; ?>',
		  function(data) {
			for (var key in data) {
				var element = document.getElementById(key);
				if (element != null)
					element.innerHTML = data[key];
			}
		  }
		);
		
		var tableElement = $('#jaffa-race-results-table');

		var table = tableElement.dataTable({
				pageLength : 50,
				serverSide : false,
				columns : [{
						data : "position"
					}, {
						data : "runnerName",
						render : function (data, type, row, meta) {
							var html = '<a href="<?php echo $this->memberProfilePageUrl; ?>?runner_id=' + row.runnerId + '">' + data + '</a>';
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
						data : "time"
					}, {
						data : "isPersonalBest",
						render : function (data, type, row, meta) {
							return '<input type="checkbox" value="1" disabled="disabled"' + (data == 1 ? ' checked="checked"' : '') + '/>';
						},
						className : 'text-center'
					}, {
						data : "isSeasonBest",
						render : function (data, type, row, meta) {
							return '<input type="checkbox" value="1" disabled="disabled"' + (data == 1 ? ' checked="checked"' : '') + '/>';
						},
						className : 'text-center'
					}, {
						data : "categoryCode"
					}, {
						data : "standardType"
					}, {
						data : "info"
					}, {
						data : "percentageGrading",
						render : function (data, type, row, meta) {
							return data > 0 ? data + '%' : '';
						}
					},

				],
				processing : true,
				autoWidth : false,
				scrollX : true,
				order : [[1, "asc"], [4, "asc"]],
				ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/race/<?php echo $_GET['raceId']; ?>')
			});

			function getAjaxRequest(url) {
				return {
					//"async": false,
					"url" : '<?php echo get_site_url(); ?>' + url,
					"method" : "GET",
					"headers" : {
						"cache-control" : "no-cache"
					},
					"dataSrc" : ""
				}
			}
		});
</script>

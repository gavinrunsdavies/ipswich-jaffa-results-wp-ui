<?php
	//$memberResults = $this->getMemberResults();
	$distances = array('5km' => 1, '5m' => 2, '10k' => 3, '10m' => 4, 'HM' => 5, '20m' => 7, 'M' => 8);
	
	function executeCall($path)
	{
		//  Initiate curl
		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		$url = get_site_url().$path;
		curl_setopt($ch, CURLOPT_URL, $url);
		// Execute
		$result=curl_exec($ch);
		// Closing
		curl_close($ch);
	}
	
	$result = executeCall("/wp-json/ipswich-jaffa-api/v2/results/runner/".$_GET['runnerId']);
	$runnerResults = json_decode($result, true);

	function getMemberPersonalBestResults()
	{		
		if (!isset($runnerResults))
			return array();
		
		$pb = array();		
		// 0 = result id, 1 = event name, 2 = distance id, 3 = position, 4 = result, 5 = racedate
		foreach ($runnerResults as $result)
		{
			if ($result['distance_id'] != 0)
			{			
				// All time PB
				if (isset($pb['ALL']))
				{
					if (isset($pb['ALL'][$result['distance_id']]))
					{
						if ($result['time'] < $pb['ALL'][$result['distance_id']]['time'] || 
							($result['time'] == $pb['ALL'][$result['distance_id']]['time'] && $result['date'] < $pb['ALL'][$result['distance_id']]['date']))
						{
							$pb['ALL'][$result['distance_id']] = $result;
						}
					}
					else
					{
						$pb['ALL'][$result['distance_id']] = $result;
					}
				}
				else
				{
					$pb['ALL'] = array();
					$pb['ALL'][$result['distance_id']] = $result;
				}
				
				// Annual PB
				$year = substr($result['date'], 0, 4);
				if (isset($pb[$year]))
				{			
					if (isset($pb[$year][$result['distance_id']]))
					{
						if ($result['time'] < $pb[$year][$result['distance_id']]['time'] || 
							($result['time'] == $pb[$year][$result['distance_id']]['time'] && $result['date'] < $pb[$year][$result['distance_id']]['date']))
						{
							$pb[$year][$result['distance_id']] = $result;
						}
					}
					else
					{
						$pb[$year][$result['distance_id']] = $result;
					}
				}
				else
				{
					$pb[$year] = array();
					$pb[$year][$result['distance_id']] = $result;
				}
			}
		}
		
		// Order results - ALL, year descending
		ksort($pb);
		
		return $pb;
	}
	
	function getMemberRankings($distances, $year = '')
	{	
		$results = array();

		return $results;
	} // end function GetMemberRankings
	
	function getStandardCertificates()
	{
		return;
	} // end function GetStandardCertificates
	
	function getStandardCertificateUrl($standard, $event, $date, $result)
	{
		return sprintf("%s?name=%s&standard=%s&event=%s&date=%s&time=%s&filepath=%s/&",
			plugins_url('php/standards/printcertificate.php', dirname(__FILE__)),
			$this->name,
			$standard,
			$event,
			$date,
			$result,
			plugin_dir_path(dirname(__FILE__)).'php/standards');
	} // end functio GetStandardCertificates
?>
<div class="section">
	<div class="jumbotron center-panel">
		<div class="row">
			<div class="col-md-3 col-md-offset-3"><strong>Name:</strong></div>
			<div class="col-md-3"><?php //echo $this->getName(); ?></div>
		</div>
		<div class="row">
			<div class="col-md-3 col-md-offset-3"><strong>Gender:</strong></div>
			<div class="col-md-3"><?php //echo $this->getGender(); ?></div>
		</div>
		<div class="row">
			<div class="col-md-3 col-md-offset-3"><strong>Age Group:</strong></div>
			<div class="col-md-3"><?php //echo $this->getAgeCategory(); ?></div>
		</div>
		<div class="row">
			<div class="col-md-3 col-md-offset-3"><strong>Standard Certificates:</strong></div>
			<div class="col-md-3"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#standardCertificatesModal">
  Show certificates
</button></div>
		</div>
	</div>	
	<div id="member-ranking" class="center-panel">
		<table class="table table-striped table-bordered" id="member-ranking-table">
		<caption style="font-weight:bold">Club Rankings for <?php //echo $this->getName(); ?></caption>
			<thead>
				<tr>
					<th></th>
					<th>5k</th>
					<th>5m</th>
					<th>10k</th>
					<th>10m</th>
					<th>HM</th>
					<th>20m</th>
					<th>M</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th></th>
					<th>5k</th>
					<th>5m</th>
					<th>10k</th>
					<th>10m</th>
					<th>HM</th>
					<th>20m</th>
					<th>M</th>
				</tr>
			</tfoot>
			<tbody>
				<?php
				//$overallRanking = $this->getMemberRankings($distances);
				printf('<tr>');
				printf('<td>All Time</td>');
				foreach ($distances as $distance => $distanceId)
				{
					$ranking = $overallRanking[$distanceId];
					if (count($ranking) > 0)
					{
						printf('<td class="rank-result" data-contenttitle="All time ranking for %s" data-contentwrapper="#overallRankInfo%d"><a href="#" class="trigger">%s</a></td>', $distance,$ranking['id'], $ranking['Rank']);
					}
					else
					{
						printf('<td></td>');
					}
				}
				printf('</tr>');
				printf('<tr>');
				printf('<td>%s</td>', date("Y"));
				//$thisYearsRanking = $this->getMemberRankings($distances, date("Y"));
				foreach ($distances as $distance => $distanceId)
				{
					$ranking = $thisYearsRanking[$distanceId];

					if (count($ranking) > 0)
					{
						printf('<td class="rank-result" data-contenttitle="%s ranking for %s" data-contentwrapper="#thisYearsRankInfo%d"><a href="#" class="trigger">%s</a></td>', date("Y"), $distance, $ranking['id'], $ranking['Rank']);
					}
					else
					{
						printf('<td></td>');
					}
				}
				printf('</tr>');
				?>
			</tbody>
		</table>
		<?php
		foreach ($distances as $distance => $distanceId)
		{
			$overallDistanceRank = $overallRanking[$distanceId];
			$yearDistanceRank = $thisYearsRanking[$distanceId];

			printf('<div id="overallRankInfo%s" class="hide">', $overallDistanceRank['id']);
			print('<div class="row">');
			print('<div class="col-md-6">Event</div>');
			printf('<div class="col-md-6">%s</div>', $overallDistanceRank['Event']);
			print('<div class="col-md-6">Date</div>');
			printf('<div class="col-md-6">%s</div>', $overallDistanceRank['racedate']);
			print('<div class="col-md-6">Result</div>');
			printf('<div class="col-md-6">%s</div>', $overallDistanceRank['result']);
			print('<div class="col-md-6">Position in Event</div>');
			printf('<div class="col-md-6">%s</div>', $overallDistanceRank['position']);
			print('<div class="col-md-6">Rank</div>');
			printf('<div class="col-md-6">%s</div>', $overallDistanceRank['Rank']);
			print('</div>');
			print('</div>');
			
			printf('<div id="thisYearsRankInfo%s" class="hide">', $yearDistanceRank['id']);			
			print('<div class="row">');
			print('<div class="col-md-6"><strong>Event</strong></div>');
			printf('<div class="col-md-6">%s</div>', $yearDistanceRank['Event']);
			print('<div class="col-md-6"><strong>Date</strong></div>');
			printf('<div class="col-md-6">%s</div>', $yearDistanceRank['racedate']);
			print('<div class="col-md-6"><strong>Result</strong></div>');
			printf('<div class="col-md-6">%s</div>', $yearDistanceRank['result']);
			print('<div class="col-md-6"><strong>Position in Event</strong></div>');
			if ($yearDistanceRank['position'] > 0) {
				printf('<div class="col-md-6">%s</div>', $yearDistanceRank['position']);
			}
			print('<div class="col-md-6"><strong>Rank</strong></div>');
			printf('<div class="col-md-6">%s</div>', $yearDistanceRank['Rank']);
			print('</div>');
			print('</div>');
		}
		?>
		
		<p style="font-size: smaller">The above Ipswich JAFFA Running Club rankings show where <?php //echo $this->GetName(); ?> ranks amongst other Ipswich JAFFA members (past and present). Ranking category: <?php //echo $this->GetGender();?>.</p>
		<p style="font-size: smaller">Click on an above ranking to find out more information about the result.</p>
	</div>
	<div id="member-race-predictions" class="center-panel">
		<div id="member-race-predictions-current">
			<table class="table table-striped table-bordered" id="member-race-predictions-current-table">
			<?php // $year = $this->getYearOfLastResult(); ?>
			<caption style="font-weight:bold">Race predictions based on best known performances for last year of competition (<?php echo $year; ?>)</caption>
				<thead>
					<tr>
						<th></th>
						<th>5k</th>
						<th>5m</th>
						<th>10k</th>
						<th>10m</th>
						<th>HM</th>
						<th>20m</th>
						<th>M</th>
					</tr>
				</thead>			
				<tbody>
					<?php
					$displayDistances = array('5km' => 1, '5m' => 2, '10k' => 3, '10m' => 4, 'HM' => 5, '20m' => 7, 'M' => 8);
					//$results = $this->getMemberPersonalBestResults();

					foreach ($displayDistances as $distanceRow => $distanceIdRow)
					{
						// Use second record only.
						printf('<tr>');
						printf('<td>%s</td>', $distanceRow);
						foreach ($displayDistances as $distance => $distanceId)
						{
							if (isset($results[$year][$distanceId]))
							{
								if ($distanceIdRow == $distanceId)
								{
									printf('<td class="success"><strong>%s</strong></td>', $results[$year][$distanceId]['time']);
								}
								else
								{
									// printf('<td>%s</td>', $this->getPredictedTime($distanceId, $results[$year][$distanceId]['time'], $distanceIdRow));
								}
							}
							else
							{
								printf('<td>-</td>');
							}
						}
						printf('</tr>');
					}
					?>
				</tbody>
			</table>
		</div>
		<div id="member-race-predictions-best">
			<table class="table table-striped table-bordered" id="member-race-predictions-best-table">
			<caption style="font-weight:bold">Race predictions based on best known performances.</caption>
				<thead>
					<tr>
						<th></th>
						<th>5k</th>
						<th>5m</th>
						<th>10k</th>
						<th>10m</th>
						<th>HM</th>
						<th>20m</th>
						<th>M</th>
					</tr>
				</thead>				
				<tbody>
					<?php
					foreach ($displayDistances as $distanceRow => $distanceIdRow)
					{
						// Use first record only.
						printf('<tr>');
						printf('<td>%s</td>', $distanceRow);

						foreach ($displayDistances as $distance => $distanceId)
						{
							if (isset($results['ALL'][$distanceId]))
							{
								if ($distanceIdRow == $distanceId)
								{
									printf('<td class="success"><strong>%s</strong></td>', $results['ALL'][$distanceId]['time']);
								}
								else
								{
								//	printf('<td>%s</td>', $this->getPredictedTime($distanceId, $results['ALL'][$distanceId]['time'], $distanceIdRow));
								}
							}
							else
							{
								printf('<td>-</td>');
							}
						}
						printf('</tr>');
					}
					?>
				</tbody>
			</table>
		</div>
		<p style="font-size: smaller">The above race predictions are based on the known performances and calculated using the formula: T2 = T1 x (D2/D1)^1.06, where D1 is known distance, D2 is target distance, T1 is result for distance D1 and T2 is predicted time for target distance D2. Read predictions in columns not rows. Entries in bold show the achieved time (T1).</p>
		<br />
	</div>
	<div id="member-personal-best-results" class="center-panel">
		<table class="table table-striped table-bordered" id="member-personal-best-results-table">
		<caption style="font-weight:bold">The Best Known Performances for <?php //echo $this->GetName(); ?></caption>
			<thead>
				<tr>
					<th>Year</th>
					<th>5k</th>
					<th>5m</th>
					<th>10k</th>
					<th>10m</th>
					<th>HM</th>
					<th>20m</th>
					<th>M</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Year</th>
					<th>5k</th>
					<th>5m</th>
					<th>10k</th>
					<th>10m</th>
					<th>HM</th>
					<th>20m</th>
					<th>M</th>
				</tr>
			</tfoot>
			<tbody>
				<?php
				// foreach ($results as $year => $pb)
				// {
					// printf('<tr>');
					// printf('<td>%s</td>', $year);

					// foreach ($distances as $distance => $distanceId)
					// {
						// if (isset($pb[$distanceId]))
						// {
							// printf('<td>%s</td>', $pb[$distanceId]['time']);				
						// }
						// else
						// {
							// printf('<td>-</td>');
						// }
					// }
					// printf('</tr>');
				// }
				?>
			</tbody>
		</table>
	</div>	
	<div id="chartdiv" class="center-panel" style=" width: 100%; height: 500px;"></div>
	<div id="member-results" class="center-panel">
		<table class="table table-striped table-bordered" id="member-results-table">
			<thead>
				<tr>
					<th data-hide="always">Race Id</th>	
					<th>Race</th>
					<th>Date</th>
					<th>Position</th>
					<th>Time</th>
					<th>Personal Best</th>
					<th>Standard</th>
					<th>Info</th>
					<th>Age Grading</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th data-hide="always">Race Id</th>	
					<th>Race</th>
					<th>Date</th>
					<th>Position</th>
					<th>Time</th>
					<th>Personal Best</th>
					<th>Standard</th>
					<th>Info</th>
					<th>Age Grading</th>
				</tr>
			</tfoot>
			<tbody>			
			</tbody>
		</table>
	</div>
</div>
<div class="modal fade" id="standardCertificatesModal" tabindex="-1" role="dialog" aria-labelledby="Standard Certificates" aria-hidden="true">
  <div class="modal-dialog" style="z-index:10000">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Standard Certificates achieved by <?php //echo $this->getName(); ?></h4>
	  </div>
	  <div class="modal-body">		
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>Standard</th>
					<th>Details</th>
					<th>Certificate</th>
				</tr>
			</thead>
			<tbody>
			<?php
			// $certificates = $this->getStandardCertificates();
			// for ($i = 0; $i < count($certificates); $i++)
			// {
				// echo "<tr>";
				// printf('<td>%s</td>', $certificates[$i]['name']);
				// printf('<td>%s, on %s. Time %s</td>',
					// $certificates[$i]['event'],
					// $certificates[$i]['date'],
					// $certificates[$i]['result']);
				// printf('<td><a href="%s" target="_blank">View</a></td>',
					// $this->getStandardCertificateUrl($certificates[$i]['name'], $certificates[$i]['event'], $certificates[$i]['date'], $certificates[$i]['result']));
				// echo "</tr>";
			// }
			?>
			</tbody>
		</table>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- amCharts javascript sources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="http://www.amcharts.com/lib/3/plugins/dataloader/dataloader.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {	
		/*$('#member-ranking-table, #member-race-predictions-current-table, #member-race-predictions-best-table').DataTable({
			pageLength : 20,
			serverSide : false,
			processing : true,
			paging : false,
			searching: false,
			autoWidth : false,
			ordering: false,
			scrollX: true			
		});	

		$('#member-personal-best-results-table').DataTable({
			pageLength : 20,
			serverSide : false,
			processing : true,
			autoWidth : false,
			scrollX: true			
		});	*/

		$('#member-results-table').DataTable({
			pageLength : 20,
			serverSide : false,
			processing : true,
			autoWidth : false,
			order: [[ 2, "desc" ]],
			scrollX: true,
			columns: [
				{ 
					data: "raceId",
					visible: false  
				},
				{
					data: "raceName",
					render: function ( data, type, row, meta ) {	
						var resultsUrl = '<?php echo $raceResultsPageUrl; ?>';
						var anchor = '<a href="' + resultsUrl;						
						anchor += '?raceId=' + row.raceId;						
						anchor += '">' + row.eventName + nullToEmptyString(data) + '</a>';								
						return anchor;
					}
				},
				{
					data: "date"					
				},
				{
					data: "position",
					render : function (data, type, row, meta) {
						return data > 0 ? data : '';
					}
				},
				{
					data: "time",
					render : function (data, type, row, meta) {
						return data != '00:00:00' ? data : '';
					}
				},
				{
					data: "isPersonalBest",
					render : function (data, type, row, meta) {
							return '<input type="checkbox" value="1" disabled="disabled"' + (data == 1 ? ' checked="checked"' : '') + '/>';
					},
					className : 'text-center'
				},
				{
					data: "standard"
				},
				{
					data: "info"
				},
				{
					data: "percentageGrading",
					render : function (data, type, row, meta) {
						return data > 0 ? data + '%' : '';
					}
				}
				],
				ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/runner/<?php echo $_GET['runnerId']; ?>')			
		});
		
		function nullToEmptyString(value) {
			return (value == null) ? "" : + " - " + value;
		}
		
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

		$('.rank-result > .trigger').popover({
			html: true,
			placement: 'top',
			container: 'body',
			title: function(){
				return $(this).parent().data('contenttitle');
			},
			content: function(){
				return $($(this).parent().data('contentwrapper')).html();
			}
		});
		
		var chart = AmCharts.makeChart("chartdiv", {
  "type": "serial",
  "theme": "light",
  "marginRight": 40,
  "marginLeft": 40,
  "autoMarginOffset": 20,
  "dataDateFormat": "YYYY-MM-DD",
  "valueAxes": [{
    "id": "v1",
    "axisAlpha": 0,
    "position": "left",
    "ignoreAxisWidth": true
  }],
  "balloon": {
    "borderThickness": 1,
    "shadowAlpha": 0
  },
  "graphs": [{
    "id": "g1",
    "balloon": {
      "drop": false,
      "adjustBorderColor": false,
      "color": "#ffffff"
    },
    "bullet": "round",
    "bulletBorderAlpha": 1,
    "bulletColor": "#FFFFFF",
    /*"bulletSize": 2,*/
    "hideBulletsCount": 50,
    "lineThickness": 2,
    "title": "red line",
    "useLineColorForBulletBorder": true,
    "valueField": "percentageGrading",
    "balloonText": "<span style='font-size:14px;'>[[eventName]]<br />[[time]], [[value]]%</span>"
  }],
  "chartScrollbar": {
    "graph": "g1",
    "oppositeAxis": false,
    "offset": 30,
    "scrollbarHeight": 80,
    "backgroundAlpha": 0,
    "selectedBackgroundAlpha": 0.1,
    "selectedBackgroundColor": "#888888",
    "graphFillAlpha": 0,
    "graphLineAlpha": 0.5,
    "selectedGraphFillAlpha": 0,
    "selectedGraphLineAlpha": 1,
    "autoGridCount": true,
    "color": "#AAAAAA"
  },
  "chartCursor": {
    "pan": true,
    "valueLineEnabled": true,
    "valueLineBalloonEnabled": true,
    "cursorAlpha": 1,
    "cursorColor": "#258cbb",
    "limitToGraph": "g1",
    "valueLineAlpha": 0.2
  },
  "valueScrollbar": {
    "oppositeAxis": false,
    "offset": 50,
    "scrollbarHeight": 10
  },
  "categoryField": "date",
  "categoryAxis": {
    "parseDates": true,
    "dashLength": 1,
    "minorGridEnabled": true
  },
 "dataLoader": {
	"url": "http://test.ipswichjaffa.org.uk/wp-json/ipswich-jaffa-api/v2/results/runner/<?php echo $_GET['runnerId']; ?>",
	"format": "json",
	"postProcess" : removeZeroResults
  }
});

chart.addListener("rendered", zoomChart);

zoomChart();
var dataCount = 0;
function removeZeroResults(data) {
 var parsedData = [];
 for (var i = 0; i < data.length; i++) {
	if (data[i].percentageGrading != "0.00") {	
		parsedData.unshift(data[i]);
	}
 }
 dataCount = parsedData.length;
 return parsedData;
}

function zoomChart() {
  chart.zoomToIndexes(dataCount - 40, dataCount - 1);
}

	});
</script>
<style>
.site-content {
	padding-top: 0;
}

@media only screen and (max-width: 768px) {
	.page-header {
		padding: 0;
	}
	.page-content, .entry-content, .entry-summary {
		margin: 0;
	}
}
</style>
<div class="section">
<div class="formRankCriteria">
	<form action="#" title="Select ranking criteria">
		<label for="distance">Distance</label>
		<select id="distance" name="distance" size="1" title="Select distance">
			<option value="0" selected="selected">Optional. Please select...</option>

		</select>
		<label for="sex">Gender</label>
		<select id="sex" name="sex" size="1" title="Select gender">
			<option value="0" selected="selected">Optional: Please select...</option>
			<option value="2">Men's</option>
			<option value="3">Ladies</option>
		</select>
		<label for="year">Year</label>
		<select id="year" name="year" size="1" title="Select year">
			<option value="0" selected="selected">Optional: Please select...</option>
			<?php
for ($y = date("Y"); $y >= 1977; $y--) {
    printf('<option value="%d">%d</option>', $y, $y);
}
?>
		</select>
		<label for="distinct">Distinct runners?</label>
		<input id="distinct" type="checkbox" name="distinct" value="1" checked="checked"/>
		<input id="wma-rank-submit" type="button" value="Get Rankings"/>
	</form>
</div>
	<div id="wma-ranking-results" style="display:none" class="center-panel">
		<table class="display" id="wma-ranking-results-table" style="width:100%">
			<caption>Member Age Grading</caption>
			<thead>
				<tr>
					<th data-priority="2">Rank</th>
					<th>Runner Id</th>
					<th data-priority="1">Name</th>
					<th>Event Id</th>
					<th data-priority="5">Event</th>
					<th data-priority="6">Date</th>
					<th data-priority="4">Performance</th>
					<th data-priority="3">Age Grading</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($) {

		$.getJSON(
		  '<?php echo esc_url(home_url()); ?>/wp-json/ipswich-jaffa-api/v2/distances',
		  function(data) {
			var name, select, option;

			// Get the raw DOM object for the select box
			select = document.getElementById('distance');

			// Clear the old options
			select.options.length = 0;
			select.options.add(new Option('Optional: Please select...', 0));

			// Load the new options
			for (var i = 0; i < data.length; i++) {
				select.options.add(new Option(data[i].text, data[i].id));
			}
		  }
		);

		var wmaDt = null;
		$('#wma-rank-submit').click(function () {
			$('#wma-ranking-results').hide();

			var tableElement = $('#wma-ranking-results-table');

			if (wmaDt != null) {
				wmaDt.destroy();
			}

			wmaDt = tableElement.DataTable({
				responsive: true,
				pageLength : 50,
				columns : [
				{
					data: "rank"
				},
				{
					data: "runnerId",
					visible: false
				},
				{
				data: "name",
				render: function ( data, type, row, meta ) {
					var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
					var anchor = '<a href="' + resultsUrl;
					anchor += '?runner_id=' + row.runnerId;
					anchor += '">' + data + '</a>';

					return anchor;
				}
			},
			{
				data: "eventId",
				visible: false
			},
			{
				data: "event",
				render: function ( data, type, row, meta ) {
					var resultsUrl = '<?php echo $eventResultsPageUrl; ?>';
					var anchor = '<a href="' + resultsUrl;
					anchor += '?raceId=' + row.raceId;
					if (row.race != null) {
						anchor += '">' + data + ' ' + row.race + '</a>';
					} else {
						anchor += '">' + data + '</a>';
					}

					return anchor;
				}
			},
			{
				data: "date"
			},
			{
				data: "performance",
				render : function (data, type, row, meta) {
					if (row.resultUnitTypeId == "3") {
						return Number(data).toLocaleString();
					}

					return ipswichjaffarc.secondsToTime(row.performance);						
				},
				className : 'text-right'
			},
			{
				data: "percentageGrading",
				render : function (data, type, row, meta) {
					return data + '%';
				}
			}
			],
			ajax    	  : {
				url : '<?php echo esc_url(home_url()); ?>/wp-json/ipswich-jaffa-api/v2/results/ranking/wma/',
				data : {
					"distanceId" : $('#distance').val(),
					"sexId": $('#sex').val(),
					"year":  $('#year').val(),
					"distinct":  $('#distinct').is(':checked') ? 1 : 0
				},
				dataSrc : ""
			}
			});

			$('#wma-ranking-results').show();
		});
	});
</script>

<style>
	.site-content {
		padding-top: 0;
	}

	@media only screen and (max-width: 768px) {
		.page-header {
			padding: 0;
		}

		.page-content,
		.entry-content,
		.entry-summary {
			margin: 0;
		}
	}

	.formRankCriteria {
		margin-top: 2em;
	}
</style>
<p><a href="#overall-club-records">Main Club Records</a> | <a href="#club-records-distances">Other Club Records</a> | <a href="#category-club-records">Main Category Records</a> | <a href="#custom-category-club-records">Other Category Records</a></p>
<div class="section" id="club-records-top">
	<div>
		<table class="display" id="overall-club-records">
			<caption>Overall Club Records</caption>
			<thead>
				<tr>
					<th data-priority="3">Distance</th>
					<th data-priority="1">Record Holder</th>
					<th data-priority="4">Event</th>
					<th data-priority="2">Record</th>
				</tr>
			</thead>
		</table>
		<div class="formRankCriteria">
			<label for="distances">Select alternative distances (multiple sections allowed).</label>
			<select id="club-records-distances" class="distance" name="distance" size="5" title="Select distance" multiple>
			</select>
			<input id="club-records-submit" class="distance-submit" type="button" value="Get Records" disabled="disabled"/>
		</div>
		<div id="custom-club-records" style="display:none; margin-bottom: 2em;">	
			<table class="display" id="custom-club-records-table">
				<caption>Other Club Records</caption>
				<thead>
					<tr>
						<th data-priority="3">Distance</th>
						<th data-priority="1">Record Holder</th>
						<th data-priority="4">Event</th>
						<th data-priority="2">Record</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
	<?php
	$distances = array('5 km' => 1, '5 m' => 2, '10 km' => 3, '10 m' => 4, 'Half marathon' => 5, '20 m' => 7, 'Marathon' => 8);
	?>
	<h4 style="text-align:center">Category record holders for the most common distances can be found below.</h4>
	<h4 style="text-align:center">
		<?php
		echo '| ';
		foreach ($distances as $text => $distanceId) {
			$tabId = "$distanceId";
			echo '<a href="#' . $distanceId . '">' . $text . '</a> | ';
		}
		?>
	</h4>
	<div id="category-club-records">
		<?php
		foreach ($distances as $text => $distanceId) {
		?>
			<div id="<?php echo $distanceId; ?>">
				<table class="display club-records">
					<caption><?php echo $text; ?></caption>
					<thead>
						<tr>
							<th data-priority="3">Category</th>
							<th data-priority="1">Record Holder</th>
							<th data-priority="4">Event</th>
							<th data-priority="2">Record</th>
						</tr>
					</thead>
				</table>
				<a style="float:right" href="#club-records-top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
			</div>
		<?php
		}
		?>
	</div>
	<div class="formRankCriteria">
		<label for="distances">Select an alternative distances for full category record holders.</label>
		<select id="club-category-records-distance" class="distance" name="distance" size="5" title="Select distance">
		</select>
		<input id="club-category-records-submit" class="distance-submit" type="button" value="Get Category Records" disabled="disabled"/>
	</div>
	<div id="custom-category-club-records" style="display:none; margin-bottom: 2em;">	
		<table class="display" id="custom-category-club-records-table">
			<caption></caption>
			<thead>
				<tr>
					<th data-priority="3">Category</th>
					<th data-priority="1">Record Holder</th>
					<th data-priority="4">Event</th>
					<th data-priority="2">Record</th>
				</tr>
			</thead>
		</table>
		<a style="float:right" href="#club-records-top">Top <i class="fa fa-chevron-up" aria-hidden="true"></i></a>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		$.getJSON(
		  '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/distances',
		  function(data) {
			var name, select, option;

			distanceSelect = document.getElementsByClassName('distance');

			for (let i = 0; i < distanceSelect.length; i++) {
				distanceSelect[i].options.length = 0;

				for (var j = 0; j < data.length; j++) {
					distanceSelect[i].options.add(new Option(data[j].text, data[j].id));
				}
			}

			$('.distance-submit').prop('disabled', false);
		  }
		);

		$('#club-records-submit').click(function () {
			var distanceIds = $('#club-records-distances').val();
			if (distanceIds == 0)
				return;

			$('#custom-club-records').hide();

			$('#custom-club-records-table').DataTable({
				responsive: {
					details: {
						renderer: function(api, rowIdx, columns) {
							var data = $.map(columns, function(col, i) {
								return col.hidden ?
									'<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
									'<td>' + col.title + ':' + '</td> ' +
									'<td>' + col.data + '</td>' +
									'</tr>' :
									'';
							}).join('');

							return data ?
								$('<table/>').append(data) :
								false;
						}
					}
				},
				serverSide: false,
				paging: false,
				destroy : true,
				searching: false,
				processing: true,
				ordering: false,
				autoWidth: false,
				scrollX: true,
				columns: [{
						data: "distance"
					},
					{
						data: "runnerName",
						searchable: true,
						sortable: true,
						render: function(data, type, row, meta) {
							var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
							var anchor = '<a href="' + resultsUrl;
							anchor += '?runner_id=' + row.runnerId;
							anchor += '">' + data + '</a>';

							return anchor;
						}
					},
					{
						data: "eventName",
						render: function(data, type, row, meta) {
							var resultsUrl = '<?php echo $raceResultsPageUrl; ?>';
							var anchor = '<a href="' + resultsUrl;
							anchor += '?raceId=' + row.raceId;
							anchor += '">' + data + ' (' + row.date + ') </a>';

							return anchor;
						}
					},
					{
						data: "performance",
						render: function(data, type, row, meta) {
							if (row.resultUnitTypeId == "3") {
								return Number(data).toLocaleString() + "m";
							}

							return ipswichjaffarc.secondsToTime(data);
						},
						className: 'text-right'
					}
				],
				ajax: getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/records/?distanceIds=' + distanceIds)
			});

			$('#custom-club-records').show();
		});

		$('#overall-club-records').DataTable({
			responsive: {
				details: {
					renderer: function(api, rowIdx, columns) {
						var data = $.map(columns, function(col, i) {
							return col.hidden ?
								'<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
								'<td>' + col.title + ':' + '</td> ' +
								'<td>' + col.data + '</td>' +
								'</tr>' :
								'';
						}).join('');

						return data ?
							$('<table/>').append(data) :
							false;
					}
				}
			},
			serverSide: false,
			paging: false,
			searching: false,
			processing: true,
			ordering: false,
			autoWidth: false,
			scrollX: true,
			columns: [{
					data: "distance"
				},
				{
					data: "runnerName",
					searchable: true,
					sortable: true,
					render: function(data, type, row, meta) {
						var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
						var anchor = '<a href="' + resultsUrl;
						anchor += '?runner_id=' + row.runnerId;
						anchor += '">' + data + '</a>';

						return anchor;
					}
				},
				{
					data: "eventName",
					render: function(data, type, row, meta) {
						var resultsUrl = '<?php echo $raceResultsPageUrl; ?>';
						var anchor = '<a href="' + resultsUrl;
						anchor += '?raceId=' + row.raceId;
						anchor += '">' + data + ' (' + row.date + ') </a>';

						return anchor;
					}
				},
				{
					data: "performance",
					render: function(data, type, row, meta) {
						return ipswichjaffarc.secondsToTime(data);
					},
					className: 'text-right'
				}
			],
			ajax: getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/records')
		});

		$('#club-category-records-submit').click(function () {
			var distanceId = $('#club-category-records-distance').val();
			if (distanceId == 0)
				return;

			$('#custom-category-club-records').hide();

			$('#custom-category-club-records-table').DataTable({
				responsive: {
					details: {
						renderer: function(api, rowIdx, columns) {
							var data = $.map(columns, function(col, i) {
								return col.hidden ?
									'<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
									'<td>' + col.title + ':' + '</td> ' +
									'<td>' + col.data + '</td>' +
									'</tr>' :
									'';
							}).join('');

							return data ?
								$('<table/>').append(data) :
								false;
						}
					}
				},
				pageLength: 50,
				destroy : true,
				serverSide: false,
				paging: false,
				searching: false,
				processing: true,
				autoWidth: false,
				scrollX: true,
				columns: [{
						data: "categoryCode"
					},
					{
						data: "runnerName",
						searchable: true,
						sortable: true,
						render: function(data, type, row, meta) {
							var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
							var anchor = '<a href="' + resultsUrl;
							anchor += '?runner_id=' + row.runnerId;
							anchor += '">' + data + '</a>';

							return anchor;
						}
					},
					{
						data: "eventName",
						render: function(data, type, row, meta) {
							var resultsUrl = '<?php echo $raceResultsPageUrl; ?>';
							var anchor = '<a href="' + resultsUrl;
							anchor += '?raceId=' + row.raceId;
							anchor += '">' + data + ' (' + row.date + ') </a>';

							return anchor;
						}
					},
					{
						data: "performance",
						render: function(data, type, row, meta) {
							return ipswichjaffarc.secondsToTime(data);
						},
						className: 'text-right'
					}
				],
				ajax: getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/records/distance/' + distanceId)
			});

			$('#custom-category-club-records').show();
		});

		$('.club-records').each(function(index, value) {
			var table = $(value);
			table.DataTable({
				responsive: {
					details: {
						renderer: function(api, rowIdx, columns) {
							var data = $.map(columns, function(col, i) {
								return col.hidden ?
									'<tr data-dt-row="' + col.rowIndex + '" data-dt-column="' + col.columnIndex + '">' +
									'<td>' + col.title + ':' + '</td> ' +
									'<td>' + col.data + '</td>' +
									'</tr>' :
									'';
							}).join('');

							return data ?
								$('<table/>').append(data) :
								false;
						}
					}
				},
				pageLength: 50,
				serverSide: false,
				paging: false,
				searching: false,
				processing: true,
				autoWidth: false,
				scrollX: true,
				columns: [{
						data: "categoryCode"
					},
					{
						data: "runnerName",
						searchable: true,
						sortable: true,
						render: function(data, type, row, meta) {
							var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
							var anchor = '<a href="' + resultsUrl;
							anchor += '?runner_id=' + row.runnerId;
							anchor += '">' + data + '</a>';

							return anchor;
						}
					},
					{
						data: "eventName",
						render: function(data, type, row, meta) {
							var resultsUrl = '<?php echo $raceResultsPageUrl; ?>';
							var anchor = '<a href="' + resultsUrl;
							anchor += '?raceId=' + row.raceId;
							anchor += '">' + data + ' (' + row.date + ') </a>';

							return anchor;
						}
					},
					{
						data: "performance",
						render: function(data, type, row, meta) {
							return ipswichjaffarc.secondsToTime(data);
						},
						className: 'text-right'
					}
				],
				ajax: getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/records/distance/' + table.parent().attr('id'))
			});
		});

		function getAjaxRequest(url) {
			return {
				"url": url,
				"method": "GET",
				"headers": {
					"cache-control": "no-cache"
				},
				"dataSrc": ""
			}
		}
	});
</script>
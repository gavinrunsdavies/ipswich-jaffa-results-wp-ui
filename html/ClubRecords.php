<div class="section"> 
	<div id="club-records" class="center-panel tabpanel">
	<?php
	$distances = array('5 km' => 1, '5 m' => 2, '10 km' => 3, '10 m' => 4, 'Half marathon' => 5, '20 m' => 7, 'Marathon' => 8);
	?>
	<!-- Nav tabs -->
		<ul class="nav nav-tabs" role="tablist">
			<?php
			$active = "active";
			foreach ($distances as $text => $distanceId)
			{		
				$tabId = "$distanceId";
			?>
			<li role="presentation" class="<?php echo $active; ?>"><a href="#<?php echo $tabId; ?>" aria-controls="<?php echo $tabId; ?>" role="tab" data-toggle="tab"><?php echo $text; ?></a></li>
			<?php
				if ($active != "") {
					$active = "";
				}
			}
			?>
		</ul>
			<!-- Tab panes -->
		<div class="tab-content">
			<?php
			$active = "active";
			foreach ($distances as $text => $distanceId)
			{		
				$tabId = $distanceId;
			?>	
			<div role="tabpanel" class="tab-pane <?php echo $active; ?>" id="<?php echo $tabId; ?>">
				<table class="table table-striped table-bordered club-records">	
					<thead>
						<tr>
							<th>Category</th>
							<th>Record Holder</th>					
							<th>Event</th>
							<th>Record</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Category</th>
							<th>Record Holder</th>
							<th>Event</th>
							<th>Record</th>				
						</tr>
					</tfoot>					
				</table>	
			</div>		
		<?php
				if ($active != "") {
					$active = "";
				}
		}
		?>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		
		$('.club-records').each(function (index, value){
			var table = $(value);
			table.DataTable({
				pageLength : 50,
				serverSide : false,
				paging : false,
				searching: false,
				processing : true,
				autoWidth : false,
				scrollX: true,
				columns:[
				 {
					data: "categoryCode"
				 },
				 {
					data: "runnerName",
					searchable: true,
					sortable: true,
					render: function ( data, type, row, meta ) {	
						var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
						var anchor = '<a href="' + resultsUrl;
						anchor += '?runner_id=' + row.runnerId;					
						anchor += '">' + data + '</a>';								

						return anchor;
					}
				 },
				 {
					data: "eventName",
					render: function ( data, type, row, meta ) {	
						var resultsUrl = '<?php echo $raceResultsPageUrl; ?>';
						var anchor = '<a href="' + resultsUrl;
						anchor += '?raceId=' + row.raceId;					
						anchor += '">' + data + ' (' + row.date + ') </a>';								

						return anchor;
					}
				 },
				 {
					data: "result"
				 }
				],
				ajax    	  : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/results/records/distance/' + table.parent().attr('id'))		
			});
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
		
		$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
			e.target // activated tab 
			e.relatedTarget // previous tab 
			var table = $.fn.dataTable.fnTables(true);
			if (table.length > 0) {
				$(table).css('width', '100%');
				$(table).dataTable().fnAdjustColumnSizing();
			}
		})
	});
</script>
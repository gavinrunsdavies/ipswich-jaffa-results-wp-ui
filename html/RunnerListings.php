<?php
wp_enqueue_style( 'jquery-ui-style', 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
wp_enqueue_script('jquery-ui-dialog');
?>
<div class="section">
<style>
* {
  box-sizing: border-box;
}

.formCompareRunners .input-container {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  width: 100%;
  margin-bottom: 15px;
}

.formCompareRunners .icon {
  padding: 15px;
  background: var(--font-color);
  color: white;
  min-width: 50px;
  text-align: center;
  height: auto;
}

.formCompareRunners .input-field {
  width: 100%;
  padding: 10px;
  outline: none;
   border-left: none;
   border-right: none;
   border: 2px solid var(--font-color);
	border-radius: 0;
}

.formCompareRunners .compare {
  width: 100%;
}

.formCompareRunners .fa-times:hover {
  cursor: pointer;
}

.form-container {
	margin-bottom: 5em;
}

#comparison-chart {
	width: 100%;
	height: 500px;
}

</style>
<p>You can select up to four Ipswich JAFFA members (past and present) to compare race positions head-to-head. Only races where each selected member competited will be compared. Therefore if you get no results back it is likely that the selected members never raced each other all in same race. To add members to the comparison list please click on the checkboxes. To remove a player from the list either click the cross in the comparison list or untick the checkbox. Hint: the search functionality of the table makes finding members very easy!</p>
<div class="form-container">		
	<form class="formCompareRunners">
		<div class="input-container">
			<i class="fa fa-user icon"></i>
			<input class="input-field" type="text" placeholder="Runner 1 ..." disabled>
			<i class="fa fa-times icon"></i>
			<input type="hidden" class="compareRunnerId" id="compareRunner1" value="0" />
		</div>
		<div class="input-container">
			<i class="fa fa-user icon"></i>
			<input class="input-field" type="text" placeholder="Runner 2 ..." disabled>
			<i class="fa fa-times icon"></i>
			<input type="hidden" class="compareRunnerId" id="compareRunner2" value="0"/>
		</div>
		<div class="input-container">
			<i class="fa fa-user icon"></i>
			<input class="input-field" type="text" placeholder="Runner 3 ..." disabled>
			<i class="fa fa-times icon"></i>
			<input type="hidden" class="compareRunnerId" id="compareRunner3" value="0"/>
		</div>
		<div class="input-container">
			<i class="fa fa-user icon"></i>
			<input class="input-field" type="text" placeholder="Runner 4 ..." disabled>
			<i class="fa fa-times icon"></i>
			<input type="hidden" class="compareRunnerId" id="compareRunner4" value="0"/>
		</div>
		<button type="button" class="compare" id="compareRunnersButton">Compare</button>
	</form>
</div>

	<table class="display" id="runner-listings-table">
		<caption>Ipswich JAFFA Members - Past and Present</caption>
		<thead>
			<tr>
				<th>Name</th>
				<th>Gender</th>
				<th>Compare</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
<div id="compareRunnersDialog" title="Compare Runners Chart">
	<div id="comparison-chart"></div>
</div>
<script src="https://cdn.amcharts.com/lib/4/core.js"></script>
<script src="https://cdn.amcharts.com/lib/4/charts.js"></script>
<script src="https://cdn.amcharts.com/lib/4/themes/animated.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function ($) {

		var tableElement = $('#runner-listings-table');

		var runnersTable = tableElement.DataTable({
			pageLength : 25,
			columns : [{
				data : "name",
				searchable : true,
				sortable : true,
				render : function (data, type, row, meta) {
					var resultsUrl = '<?php echo $memberResultsPageUrl; ?>';
					var anchor = '<a href="' + resultsUrl;
					anchor += '?runner_id=' + row.id;
					anchor += '">' + data + '</a>';

					return anchor;
				}
			}, {
				data : "sex",
				searchable : false,
				sortable : true
			},{
				data : "id",
				searchable : false,
				sortable : true,
				visible : true,
				render : function (data, type, row, meta) {
					return '<input type="checkbox" value="' + data + '" class="compare" name="compare"/>';
				}
			}],
			rowId : 'id',
			processing : true,
			autoWidth : false,
			order : [[0, "asc"]],
			scrollX : true,
			ajax : getAjaxRequest('/wp-json/ipswich-jaffa-api/v2/runners')
		});

		function getAjaxRequest(url) {
			return {
				"url" : '<?php echo esc_url( home_url() ); ?>' + url,
				"method" : "GET",
				"headers" : {
				"cache-control" : "no-cache"
				},
				"dataSrc" : ""
			};
		}	

		var limit = 4;
		var chart;
		$('#runner-listings-table tbody').on('click', 'input.compare', function () {
			var row = $(this).closest('tr');
			if (runnersTable.rows('.success').data().length >= limit && !row.hasClass('success')) {
				$(this).prop( "checked", false );
				alert("Maximum of " + limit + " members can be compared. Please deselect one before reselecting your choice.");
			} else {
				var id = runnersTable.row(row).id();
				var name = $(row).children()[0].innerText;

				if (row.hasClass('success')) {
					removeRunnerFromComparisonList(id);
				} else {
					addRunnerToComparisonList(id, name);
				}

				row.toggleClass('success');
			}
		});

		function addRunnerToComparisonList(id, name) {
			$('.formCompareRunners div.input-container').each(function (index, element) {
				if ($(element).find('.compareRunnerId').val() == 0) {
					$(element).find('.compareRunnerId').val(id);
					$(element).find('.input-field').val(name);
					return false;
				}
			});
		}

		function removeRunnerFromComparisonList(id) {
			$('.formCompareRunners div.input-container').each(function (index, element) {
				if ($(element).find('.compareRunnerId').val() == id) {
					$(element).find('.compareRunnerId').val(0);
					$(element).find('.input-field').val('');
					return false;
				}
			});
		}

		$('.formCompareRunners').on('click', '.fa-times', function () {
			var parent = $(this).parent();
			var id = parent.find('.compareRunnerId').val();
			parent.find('.compareRunnerId').val(0);
			parent.find('.input-field').val('');

			$('#' + id).toggleClass('success');
			$('#' + id).find('input.compare').prop( "checked", false );
		});

		var compareRunnersDialog = $("#compareRunnersDialog").dialog({
			autoOpen: false,
			modal: true,
			width: $(window).width() *0.66, // width is 2/3 of window.
			buttons: {        	
				Close: function() {
					compareRunnersDialog.dialog( "close" );
				}
			},
			close: function() {
				if (chart) {
					chart.dispose();
				}
			}
		});

		$('#compareRunnersButton').click(function () {
			var runners = [];
			$('.formCompareRunners div.input-container').each(function (index, element) {
				if ($(element).find('.compareRunnerId').val() > 0) {
					var runner = {
						id : $(element).find('.compareRunnerId').val(),
						name : $(element).find('.input-field').val()
					};
					runners.push(runner);
				}
			});

			if (runners.length > 0) {
				showChart(runners);				
			}
		});

	  	function showChart(runners) {
			var runnerIds = [];
			for (var i = 0; i < runners.length; i++) {
				runnerIds.push(runners[i].id);
			}

			$.ajax({
				type : "POST",
				url : '/wp-json/ipswich-jaffa-api/v2/results/runner/compare',
				data : JSON.stringify({
					runnerIds : runnerIds
				}),
				contentType : "application/json"
			})
			.done(function (data) {
				chart = createChart(data, runners);
				compareRunnersDialog.dialog('open');
			})
			.fail(function () {
				alert("No common results for the selected runners. Please reselect your choices.");
			});
	  	}

	  	function createChart(data, runners) {
			am4core.ready(function() {
				am4core.useTheme(am4themes_animated);
				var newChart = am4core.create("comparison-chart", am4charts.XYChart);

				var dateAxis = newChart.xAxes.push(new am4charts.DateAxis());
				dateAxis.renderer.grid.template.location = 0;
				dateAxis.renderer.axisFills.template.disabled = true;
				dateAxis.renderer.ticks.template.disabled = true;

				var valueAxis = newChart.yAxes.push(new am4charts.ValueAxis());
				valueAxis.tooltip.disabled = true;
				valueAxis.renderer.minWidth = 35;
				valueAxis.renderer.axisFills.template.disabled = true;
				valueAxis.renderer.ticks.template.disabled = true;

				var scrollbarX = new am4core.Scrollbar();
				newChart.scrollbarX = scrollbarX;

				newChart.cursor = new am4charts.XYCursor();

				newChart.legend = new am4charts.Legend();
				newChart.legend.position = "right";
				newChart.legend.scrollable = true;				

				var linesColours = ["#0066FF", "#FF6600", "#006600", "#0000FF"];

				// Create series
				for (var i = 1; i <= runners.length; i++) {									
					var series = newChart.series.push(new am4charts.LineSeries());
					series.dataFields.valueY = "position" + i;
					series.dataFields.dateX = "date";
					series.stroke = am4core.color(linesColours[i-1]);
					series.strokeWidth = 3;
					series.tooltipText = "{eventName}\nPosition: {position" + i + "}\nTime: {time" + i + "}\nGrading: {percentageGrading" + i + "}%";
					series.tooltip.pointerOrientation = "vertical";
					series.tooltip.getFillFromObject = false;
					series.tooltip.label.fill = am4core.color(linesColours[i-1]);
					series.tooltip.background.fillOpacity = 0.5;	
					series.bullets.push(new am4charts.CircleBullet());
					series.data = data;
					series.name = runners[i-1].name;
				}

			    return newChart;
			});
	  	} 
	});
//]]>

</script>

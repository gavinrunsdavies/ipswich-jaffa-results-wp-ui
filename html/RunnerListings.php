<div class="section"> 
  <div class="center-panel" id="compareRunnersPanel">	  
    <div class="row">
		<div class="col-sm-6">
		<form class="form-horizontal">
		  <div class="form-group compareRunner">
			<label for="compareRunnerName1" class="col-sm-1 control-label"><span class="glyphicon glyphicon-user"></span></label>
			<div class="col-sm-9">
			 <span id="compareRunnerName1" class="compareRunnerName form-control"></span>
			  <input type="hidden" id="compareRunnerId1" value="0" class="compareRunnerId">
			</div>
			<div class="col-sm-2">
				<button class="btn btn-default" type="button" style="background-color:#000; border-color:#ccc">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
			</div>
		  </div>
		  <div class="form-group compareRunner">
			<label for="compareRunnerName2" class="col-sm-1 control-label"><span class="glyphicon glyphicon-user"></span></label>
			<div class="col-sm-9">
			<span id="compareRunnerName2" class="compareRunnerName form-control"></span>
			 <input type="hidden" id="compareRunnerId2" value="0" class="compareRunnerId">
			</div>
			 <div class="col-sm-2">
				<button class="btn btn-default" type="button" style="background-color:#000; border-color:#ccc">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
			</div>
		  </div>
		   <div class="form-group compareRunner">
			<label for="compareRunnerName3" class="col-sm-1 control-label"><span class="glyphicon glyphicon-user"></span></label>
			<div class="col-sm-9">
				<span id="compareRunnerName3" class="compareRunnerName form-control"></span>
				<input type="hidden" id="compareRunnerId3" value="0" class="compareRunnerId">
			</div>
			 <div class="col-sm-2">
				<button class="btn btn-default" type="button" style="background-color:#000; border-color:#ccc">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
			</div>
		  </div>
		  <div class="form-group compareRunner">
			<label for="compareRunnerName4" class="col-sm-1 control-label"><span class="glyphicon glyphicon-user"></span></label>
			<div class="col-sm-9">
			<span id="compareRunnerName4" class="compareRunnerName form-control"></span>
			<input type="hidden" id="compareRunnerId4" value="0" class="compareRunnerId">
			</div>
			 <div class="col-sm-2">
				<button class="btn btn-default" type="button" style="background-color:#000; border-color:#ccc">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
			</div>
		  </div>
		  <div class="form-group">
			<div class="col-sm-offset-1 col-sm-11">
			  <button type="button" class="btn btn-default" id="compareRunnersButton">Compare</button>
			</div>
		  </div>
		</form>
		</div>
		<div class="col-sm-6">
		You can now select up to four Ipswich JAFFA members (past and present) to compare race positions head-to-head. Only races where each selected member competited will be compared. Therefore if you get no results back it is likely that the selected members never raced each other all in same race. To add members to the comparison list please click on the checkboxes. To remove a player from the list either click the cross in the comparison list or untick the checkbox. Hint: the search functionality of the table makes finding members very easy!
		</div>
	</div>
	</div>
	
	<div class="center-panel">
		<table class="table table-striped table-bordered" id="runner-listings-table">
			<thead>
				<tr>
					<th>Name</th>
					<th>Gender</th>
					<th>Compare</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Name</th>
					<th>Gender</th>
					<th>Compare</th>
				</tr>
			</tfoot>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<div class="modal fade" id="compareRunnersModal" tabindex="-1" role="dialog" aria-labelledby="Compare Runners">
  <div class="modal-dialog modal-lg" role="document" style="z-index:10000">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Compare Runners</h4>
	  </div>
	  <div class="modal-body">		
		<div id="chartdiv" style="width: 100%; height: 500px;"></div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	  </div>
	</div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="http://www.amcharts.com/lib/3/plugins/dataloader/dataloader.min.js"></script>
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
	            var box = '<input type="checkbox" value="' + data + '" class="compare" name="compare"/>';

	            return box;
	          }
	        }
	      ],
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
	    $('.compareRunner').each(function (index, element) {
	      if ($(element).find('.compareRunnerId').val() == 0) {
	        $(element).find('.compareRunnerId').val(id);
	        $(element).find('.compareRunnerName').text(name);
	        $(element).find('button').val(id);
	        return false;
	      }
	    });
	  }

	  function removeRunnerFromComparisonList(id) {
	    $('.compareRunner').each(function (index, element) {
	      if ($(element).find('.compareRunnerId').val() == id) {
	        $(element).find('.compareRunnerId').val(0);
	        $(element).find('.compareRunnerName').text('');
	        $(element).find('button').val(0);
	        return false;
	      }
	    });
	  }

	  $('.compareRunner button').click(function () {
	    var id = this.value;
	    removeRunnerFromComparisonList(id);

	    $('#' + id).toggleClass('success');
		$('#' + id).find('input.compare').prop( "checked", false );
	  });
	  
	  $('#compareRunnersModal').on('hidden.bs.modal', function (e) {
		chart.clear();
	  });

	  $('#compareRunnersButton').click(function () {

	    var runners = [];
	    $('.compareRunner').each(function (index, element) {
	      if ($(element).find('.compareRunnerId').val() > 0) {
	        var runner = {
	          id : $(element).find('.compareRunnerId').val(),
	          name : $(element).find('.compareRunnerName').text()
	        };
	        runners.push(runner);
	      }
	    });

	    if (runners.length > 0) {
	      showChart(runners);
	      $('#compareRunnersModal').modal('show');
	    }
	  });

	  function showChart(runners) {
	    var runnerIds = [];
	    for (var i = 0; i < runners.length; i++) {
	      runnerIds.push(runners[i].id);
	    }

	    $.ajax({
	      type : "POST",
	      url : '<?php echo esc_url( home_url() ); ?>' + '/wp-json/ipswich-jaffa-api/v2/results/runner/compare',
	      data : JSON.stringify({
	        runnerIds : runnerIds
	      }),
	      contentType : "application/json"
	    })
	    .done(function (data) {
	        chart = createChart(data);
			for (var i = 0; i < runners.length; i++) {
			  var g = createGraph(runners[i].id, runners[i].name, i + 1);
			  chart.addGraph(g);
			}

			chart.addListener("rendered", zoomChart);

			zoomChart(chart);
	    });
	  }

	  function createChart(data) {
	    var newChart = AmCharts.makeChart("chartdiv", {
	        "type" : "serial",
	        "theme" : "light",
	        "marginRight" : 40,
	        "marginLeft" : 40,
	        "autoMarginOffset" : 20,
	        "dataDateFormat" : "YYYY-MM-DD",
	        "valueAxes" : [{
	            "id" : "v1",
	            "axisAlpha" : 0,
	            "position" : "left",
	            "ignoreAxisWidth" : true
	          }
	        ],
	        "balloon" : {
	          "borderThickness" : 1,
	          "shadowAlpha" : 0
	        },
	        "graphs" : [],
			
	        "chartScrollbar" : {
	          "oppositeAxis" : false,
	          "offset" : 30,
	          "scrollbarHeight" : 80,
	          "backgroundAlpha" : 0,
	          "selectedBackgroundAlpha" : 1,
	          "selectedBackgroundColor" : "#cccccc",
	          "graphFillAlpha" : 0,
	          "graphLineAlpha" : 0.5,
	          "selectedGraphFillAlpha" : 0,
	          "selectedGraphLineAlpha" : 1,
	          "autoGridCount" : true,
	          "color" : "#000000"
	        },
	        "chartCursor" : {
	          "pan" : true,
	          "valueLineEnabled" : true,
	          "valueLineBalloonEnabled" : true,
	          "cursorAlpha" : 1,
	          "cursorColor" : "#258cbb",
	          "limitToGraph" : "g1",
	          "valueLineAlpha" : 0.2
	        },
	        "valueScrollbar" : {
	          "oppositeAxis" : false,
	          "offset" : 50,
	          "scrollbarHeight" : 10
	        },
	        "categoryField" : "date",
	        "categoryAxis" : {
	          "parseDates" : true,
	          "dashLength" : 1,
	          "minorGridEnabled" : true
	        },
	        "legend" : {
			  "autoMargins" : true,
	          "horizontalGap" : 5,
	          "maxColumns" : 1,
	          "position" : "right",
	          "useGraphSettings" : true,
	          "markerSize" : 10
	        },
	        "dataProvider" : data
	      });	   

	    return newChart;
	  }

	  function createGraphBalloon() {
		 var balloon = new AmCharts.AmBalloon();
	  balloon.drop = false;
	  balloon.adjustBorderColor = false;
	  balloon.color = "#ffffff";
	  
		return balloon;
	  }
		  
	  var linesColours = ["#0066FF", "#FF6600", "#006600", "#0000FF"];

	  function createGraph(id, name, index) {
	    var g = new AmCharts.AmGraph();
	    g.id = id;
	    g.balloon = createGraphBalloon();
	    g.bullet = "round";
	    g.bulletBorderAlpha = 1;
	    g.bulletColor = "#FFFFFF";
	    g.hideBulletsCount = 50;
	    g.lineThickness = 2;
	    g.lineColor = linesColours[index - 1];
	    g.title = name;
	    g.useLineColorForBulletBorder = true;
	    g.valueField = "position" + index;
	    g.balloonText = "<span style='font-size:14px;'>[[eventName]]<br />[[position" + index + "]], [[time" + index + "]], [[percentageGrading" + index + "]]%</span>";

	    return g;
	  }

	  function zoomChart(chart) {
	    chart.zoomToIndexes(-40, -1);
	  }
	});
//]]> 

</script>

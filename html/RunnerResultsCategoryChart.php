<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/hierarchy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<script>

jQuery(document).ready(function($) {

  $.ajax('<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/statistics/resultCountByCategoryAndCourse')
    .done(function(data) {
      createRunnerResultsCountChart(data);
    });	

  function createRunnerResultsCountChart(data) {

    am5.ready(function() {

      var root = am5.Root.new("chartdiv");

      const myTheme = am5.Theme.new(root);

      myTheme.rule("RoundedRectangle", ["hierarchy", "node", "shape", "depth1"]).setAll({
        strokeWidth: 2
      });

      myTheme.rule("RoundedRectangle", ["hierarchy", "node", "shape", "depth2"]).setAll({
        fillOpacity: 0,
        strokeWidth: 1,
        strokeOpacity: 0.2
      });

      myTheme.rule("Label", ["node", "depth1"]).setAll({
        forceHidden: true
      });

      myTheme.rule("Label", ["node", "depth2"]).setAll({
        fontSize: 10
      });

      root.setThemes([
        am5themes_Animated.new(root),
        myTheme
      ]);

      // Create wrapper container
      var container = root.container.children.push(
        am5.Container.new(root, {
          width: am5.percent(100),
          height: am5.percent(100),
          layout: root.verticalLayout
        })
      );

      // Create series
      // https://www.amcharts.com/docs/v5/charts/hierarchy/#Adding
      var series = container.children.push(
        am5hierarchy.Treemap.new(root, {
          sort: "descending",
          singleBranchOnly: false,
          downDepth: 1,
          upDepth: 0,
          initialDepth: 1,
          valueField: "count",
          categoryField: "name",
          childDataField: "courseTypes",
          nodePaddingOuter: 0,
          nodePaddingInner: 0
        })
      );

      series.get("colors").set("step", 1);

      container.children.moveValue(
        am5hierarchy.BreadcrumbBar.new(root, {
          series: series
        }), 0
      );

      // {
      // "categoryName":"LU20",
      // "courseTypes":[
      //    {
      //       "name":"Undefined",
      //       "count":"28"
      //    },
      //    {
      //       "name":"Cross Country",
      //       "count":"47"
      //    },

function processData(data) {
  var treeData = [];

  return [{
    name: "Root",
    courseTypes: data
  }];
}

series.data.setAll(processData(data));
series.set("selectedDataItem", series.dataItems[0]);

}); // end am5.ready()
  } // end function createRunnerResultsCountChart()
});
</script>
<script>

</script>
<div> 
	<div id="chartdiv" style="width: 100%; height: 700px; max-width:100%; clear: both;"></div>
</div>
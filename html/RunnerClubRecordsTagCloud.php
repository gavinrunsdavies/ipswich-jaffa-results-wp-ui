<div> 
	<div id="chartdiv" style="width: 100%; height: 300px; clear: both;"></div>
</div>

<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/wc.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>

<script>
am5.ready(function() {

	jQuery(document).ready(function($) {
		
		url = '<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/results/records/count';

		$.getJSON(url,				
			function(data) {

				var root = am5.Root.new("chartdiv");
				root.setThemes([
					am5themes_Animated.new(root)
				]);

				// Add wrapper container
				var container = root.container.children.push(am5.Container.new(root, {
					width: am5.percent(100),
					height: am5.percent(100),
					layout: root.verticalLayout
				}));

				var title = container.children.push(am5.Label.new(root, {
					text: "Most common programming languages",
					fontSize: 20,
					x: am5.percent(50),
					centerX: am5.percent(50)
				}));

				// https://www.amcharts.com/docs/v5/charts/word-cloud/
				var series = root.container.children.push(am5wc.WordCloud.new(root, {
					categoryField: "name",
					valueField: "count",
					maxFontSize: am5.percent(15)
				}));

				series.labels.template.setAll({
					fontFamily: "Courier New",
					tooltipText: "{name}: [bold]{value}[/]"
				});

				// Add click event on words
				// https://www.amcharts.com/docs/v5/charts/word-cloud/#Events
				series.labels.template.events.on("click", function(ev) {
					const id  = ev.target.dataItem.get("id");
					window.open("<?php echo $memberResultsPageUrl; ?>?runner_id={id}");
				});

				series.data.setAll(data);	
			}
		);	
	}); // jquery
}); // end am5.ready()
</script>
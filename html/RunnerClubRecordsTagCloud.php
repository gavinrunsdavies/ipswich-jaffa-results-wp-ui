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
					text: "Runner with the most current club records",
					fontSize: 20,
					x: am5.percent(50),
					centerX: am5.percent(50)
				}));

				var series = container.children.push(am5wc.WordCloud.new(root, {
					categoryField: "name",
					valueField: "count",
					maxFontSize: am5.percent(50)
				}));
				
				series.data.processor = am5.DataProcessor.new(root, {
					numericFields: ["count"]
				});

				series.labels.template.setAll({
					paddingTop: 5,
					paddingBottom: 5,
					paddingLeft: 5,
					paddingRight: 5,
					fontFamily: "Courier New",
					tooltipText: "{name}: [bold]{value}[/]",
					cursorOverStyle: "pointer",
					fill: am5.color(0xe88112)
				});

				// Add click event on words
				series.labels.template.events.on("click", function(ev) {
					const id  = ev.target.dataItem.dataContext.id;
					window.open("<?php echo $memberResultsPageUrl; ?>?runner_id=" + id);
				});

				series.data.setAll(data);	
			}
		);	
	}); // jquery
}); // end am5.ready()
</script>
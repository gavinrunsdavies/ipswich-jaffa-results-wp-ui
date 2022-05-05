<script>
jQuery(document).ready(function($) {

    $.ajax('<?php echo esc_url( home_url() ); ?>/wp-json/ipswich-jaffa-api/v2/events')
        .done(function(data) {
            displayLatestResults(data, 5);
    });	

    function displayLatestResults(data, limit) {
        var list = $('#ipswich-jaffa-latest-results-shortcode');
        var raceResultsUrl = '<?php echo $eventResultsPageUrl; ?>';
        for (var i = 0; i < data.length && i < limit; i++) {	
            var item = 	'<li><a href="' + raceResultsUrl + '?eventId=' + data[i].id + '&date=' + data[i].lastRaceDate + '" title="Click for full results.">' 
                + data[i].name +'</a> from the ' + formatDate(data[i].lastRaceDate) + '.</li>';				
			list.append(item)
        }
    }

    function formatDate(date) {
        var event = new Date(date);
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        return event.toLocaleDateString(undefined, options);
    }
});
</script>
<div>
    <ul id="ipswich-jaffa-latest-results-shortcode">
    </ul>
</div>
			
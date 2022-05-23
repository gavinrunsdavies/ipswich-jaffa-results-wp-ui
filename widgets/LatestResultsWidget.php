<?php
/*
Plugin Name: Ipswich JAFFA Latest Results Widget
Plugin URI: http://www.ipswichjaffa.org.uk
Description: Display Ipswich JAFFA running Club latest results widget
Author: Gavin Davies
Version: 3.0.0.0
Author URI: http://www.ipswichjaffa.org.uk

*/

/**
* Ipswich JAFFA Latest Results Widget class.
* This class handles everything that needs to be handled with the widget:
* the settings, form, display, and update.
*
* @since 0.1
*/

namespace IpswichJAFFARunningClubResults;

class LatestResultsWidget extends \WP_Widget
{
    /*
     * Class properties
     */

    /**
     * Constructor. Widget setup.
     */
    function __construct()
	{
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'jaffa', 'description' => __('Ipswich JAFFA Latest Results', 'jaffa') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'ipswich-jaffa-latest-results-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'ipswich-jaffa-latest-results-widget', __('Ipswich JAFFA Latest Results', 'jaffa'), $widget_ops, $control_ops );
    } // end constuctor

    /**
     * How to display the widget on the screen.
     */
    public function Widget( $args, $instance )
	{
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$numberOfResults = $instance['numberOfResults'];
		$linkUrl = get_permalink($instance['raceresultpageid']);

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
		{
			echo $before_title . $title . $after_title;
		}

		// Add widget content
		$this->addContent($numberOfResults, $linkUrl);

		/* After widget (defined by themes). */
		echo $after_widget;
    } // end function Widget

    /**
     * Update the widget settings.
     */
    public function Update( $new_instance, $old_instance )
	{
      $instance = $old_instance;

      $instance['title'] = strip_tags($new_instance['title']);
	  $instance['numberOfResults'] = strip_tags($new_instance['numberOfResults']);
	  $instance['raceresultpageid'] = strip_tags($new_instance['raceresultpageid']);

      return $instance;
    } // end function Update

    /**
     * Displays the widget settings controls on the widget panel.
     * Make use of the get_field_id() and get_field_name() function
     * when creating your form elements. This handles the confusing stuff.
     */
    public function Form( $instance )
	{

      /* Set up some default widget settings. */
      $defaults = array( 'title' => __('Latest Member Results', 'Latest Member Results'), 'numberOfResults' => 5, 'raceresultpageid' => 0);
      $instance = wp_parse_args( (array) $instance, $defaults ); ?>

      <!-- Widget Title: Text Input -->
      <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'jaffa'); ?></label>
        <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
      </p>

	  <p>
        <label for="<?php echo $this->get_field_id( 'numberOfResults' ); ?>"><?php _e('Number of results to display:', 'jaffa'); ?></label>
        <input id="<?php echo $this->get_field_id( 'numberOfResults' ); ?>" name="<?php echo $this->get_field_name( 'numberOfResults' ); ?>" value="<?php echo $instance['numberOfResults']; ?>" style="width:100%;" />
      </p>
	  
	  <p>
        <label for="<?php echo $this->get_field_id( 'raceresultpageid' ); ?>"><?php _e('Race results page id:', 'jaffa'); ?></label>
        <input id="<?php echo $this->get_field_id( 'raceresultpageid' ); ?>" name="<?php echo $this->get_field_name( 'raceresultpageid' ); ?>" value="<?php echo $instance['raceresultpageid']; ?>" style="width:100%;" />
      </p>

    <?php
    } // end function Form

    private function addContent($numberOfResults, $linkUrl)
	{		
		echo '<div>';
		echo '<ul>';

		// get data
		$results = $this->getLatestResults();

		 for ($i = 0; $i < count($results) && $i < $numberOfResults; $i++)
		 {
			echo '<li>';
			echo '<p class="result"><a href="'.$linkUrl.'?eventId='.$results[$i]->id.'&date='.$results[$i]->lastRaceDate.'" title="Click for full results.">'.$results[$i]->name.'</a> from the '.$this->formatDate($results[$i]->lastRaceDate).'.</p>';
			echo '</li>';
		}

		echo '</ul>';
		echo '</div>';
	} // end function addContent

	private function formatDate($date)
	{
		$bits = explode('-', $date);
		if ($bits[0] != '')
		{
			$return = date("jS F Y", mktime(0, 0, 0, $bits[1], $bits[2], $bits[0]));
		}

		return $return;
    }  // end function formatDate

	private function getLatestResults()
	{
		$url = esc_url( home_url() ).'/wp-json/ipswich-jaffa-api/v2/events';
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Cache-Control: no-cache"));
		$response = curl_exec($curl);			
		curl_close($curl);
		$decoded = json_decode($response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			return null;
		}

		return $decoded;
	} // end function getLatestResults

} // End class LatestResultsWidget
?>
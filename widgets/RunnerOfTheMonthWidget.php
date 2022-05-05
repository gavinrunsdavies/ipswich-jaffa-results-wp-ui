<?php
/*
Plugin Name: Ipswich JAFFA Runner of the Month Widget
Plugin URI: http://www.ipswichjaffa.org.uk
Description: Display the Ipswich JAFFA running Club Runner of the month
Author: Gavin Davies
Version: 3.0.0.0
Author URI: http://www.ipswichjaffa.org.uk
*/
  
/**
* Ipswich JAFFA Runner of the Month Widget class.
* This class handles everything that needs to be handled with the widget:
* the settings, form, display, and update.
*
* @since 0.1
*/
namespace IpswichJAFFARunningClubResults;

class RunnerOfTheMonthWidget extends \WP_Widget 
{     
    /**
     * Constructor. Widget setup.
     */
    function __construct() 
	{
      /* Widget settings. */
      $widget_ops = array( 'classname' => 'jaffa', 'description' => __('Ipswich JAFFA Runner of the Month', 'jaffa') );

      /* Widget control settings. */
      $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'ipswich-jaffa-rotmw-widget' );

      /* Create the widget. */
      $this->WP_Widget( 'ipswich-jaffa-rotmw-widget', __('Ipswich JAFFA Runner of the Month Winner', 'jaffa'), $widget_ops, $control_ops );   
    } // end constuctor

    /**
     * How to display the widget on the screen.
     */
    public function Widget( $args, $instance )
	{
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$memberResultsPageId = get_permalink($instance['memberresultspageid']);

		/* Before widget (defined by themes). */
		echo $before_widget;
		
		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
		{
			echo $before_title . $title . $after_title;
		}

		// Add widget content
		$this->addContent($memberResultsPageId);

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
	  $instance['memberresultspageid'] = strip_tags($new_instance['memberresultspageid']);

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
		$defaults = array( 'title' => __('John Jarrold Runner of the Month', 'John Jarrold Runner of the month'), ); 
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'memberresultspageid' ); ?>"><?php _e('Member results page id:', 'jaffa'); ?></label>
			<input id="<?php echo $this->get_field_id( 'memberresultspageid' ); ?>" name="<?php echo $this->get_field_name( 'memberresultspageid' ); ?>" value="<?php echo $instance['memberresultspageid']; ?>" style="width:100%;" />
		</p>
     
		<?php
    } // end function Form    
        
    private function addContent($linkUrl) 
	{
		echo '<div id="ipswich-jaffa-rotm">';
		
		$winners = $this->getWinners();	
		$date   = \DateTime::createFromFormat('!m', ($winners[0]->month + 1));
		$monthName = $date->format('F');
		echo '<h5>'.$monthName.' '.$winners[0]->year.'</h5>';
		$lastMonth  = $winners[0]->month;
		echo '<ul>';		
		foreach ($winners as $winner)
		{	
			if ($lastMonth != $winner->month) {
				break;		
			}

			echo '<li>';				
			echo '<div>';
			echo '<h4>';
			echo $winner->category;
			echo ' - ';
			echo '<a href="'.$linkUrl.'?runner_id='.$winner->id.'">'.$winner->name.'</a>';
			echo '</h4>';
			echo '</div>';
			echo '</li>';										
		} 
		
		echo '</ul>';
		echo '</div>';
	} // end function addContent
		
	private function getWinners() 
	{		
		$url = esc_url( home_url() ).'/wp-json/ipswich-jaffa-api/v2/runnerofthemonth/winners/';
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);			
		curl_close($curl);
		$decoded = json_decode($response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			return null;
		}

		return $decoded;
	} // end function getWinners

} // End class IpswichJaffa_RunnerOfTheMonthWidget
?>
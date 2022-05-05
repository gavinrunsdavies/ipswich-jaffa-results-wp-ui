<?php 
/**
* Ipswich JAFFA Runner Results Tag cloud Widget.
* This class handles everything that needs to be handled with the widget:
* the settings, form, display, and update.
*
* @since 0.1
*/
namespace IpswichJAFFARunningClubResults;

class RunnerResultsTagCloudWidget extends \WP_Widget 
{     
    /**
     * Constructor. Widget setup.
     */
    function __construct() 
	{
      $this->WP_Widget( 'ipswich-jaffa-runner-results-tag-cloud-widget', 'Ipswich JAFFA Runner Results Tag Cloud', $widget_ops, $control_ops );   
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
		$defaults = array( 'title' =>'Ipswich JAFFA Runner Results Tag Cloud' ); 
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id( 'memberresultspageid' ); ?>">Member results page id:</label>
			<input id="<?php echo $this->get_field_id( 'memberresultspageid' ); ?>" name="<?php echo $this->get_field_name( 'memberresultspageid' ); ?>" value="<?php echo $instance['memberresultspageid']; ?>" style="width:100%;" />
		</p>
     
		<?php
    } // end function Form    
        
	/**
	 * @memberResultsPageUrl used file
	 */
    private function addContent($memberResultsPageUrl)
	{
		ob_start();
		require_once "RunnerResultsTagCloud.php";
		$content = ob_get_clean();

		echo $content;
	} // end function addContent

} // End class RunnerResultsTagCloudWidget
?>
<?php
/*
Plugin Name: Ipswich JAFFA On This Day Widget
Plugin URI: http://www.ipswichjaffa.org.uk
Description: Display a AI generated summary of past race results of this day in history
Author: Gavin Davies
Version: 1.0.0.0
Author URI: https://www.ipswichjaffa.org.uk
*/
  
namespace IpswichJAFFARunningClubResults;

class OnThisDayWidget extends \WP_Widget 
{     
    function __construct() 
  	{
      /* Widget settings. */
      $widget_ops = array( 'classname' => 'jaffa', 'description' => __('Ipswich JAFFA On This Day', 'jaffa') );

      /* Widget control settings. */
      $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'ipswich-jaffa-on-this-day-widget' );

      /* Create the widget. */
      $this->WP_Widget( 'ipswich-jaffa-on-this-day-widget', __('Ipswich JAFFA On This Day', 'jaffa'), $widget_ops, $control_ops );   
    } // end constuctor

    /**
     * How to display the widget on the screen.
     */
    public function Widget( $args, $instance )
	  {
  		extract( $args );
  
  		/* Our variables from the widget settings. */
  		$title = apply_filters('widget_title', $instance['title'] );
  
  		/* Before widget (defined by themes). */
  		echo $before_widget;
  		
  		/* Display the widget title if one was input (before and after defined by themes). */
  		if ( $title )
  		{
  			echo $before_title . $title . $after_title;
  		}
  
  		// Add widget content
  		$this->addContent();
  
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

      return $instance;
    } 

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
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title</label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
    </p>
     
		<?php
    } 
        
    private function addContent() 
  	{
		ob_start();
		require_once "OnThisDay.php";
		$content = ob_get_clean();

		echo $content;
  	}		
} 
?>

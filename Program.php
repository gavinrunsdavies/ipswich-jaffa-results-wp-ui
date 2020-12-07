<?php
/*
Plugin Name: Ipswich JAFFA Running Club Results
Plugin URI:
Description: The new Ipswich JAFFA RC Results plugin. Requires bootstrap to be part of the theme.
Version: 2.1
Author: Gavin Davies
Author URI: https://github.com/gavinrunsdavies/
*/
namespace IpswichJAFFARunningClubResults;
		
$go = new Program();

class Program 
{
	const JQUERY_HANDLE= 'jquery';	

	const JQUERY_DATATABLES_HANDLE = 'jquery.dataTables.min';
	
	const JQUERY_DATATABLES_BOOTSTRAP_HANDLE = 'datatables.bootstrap';
	
	const JQUERY_DATATABLES_BUTTONS_HANDLE = 'dataTables.buttons.min';
	
	const JQUERY_DATATABLES_BUTTONS_PRINT_HANDLE = 'buttons.print.min';

	function __construct() {		
		add_action('init', array($this, 'registerShortCodes'));
	}
		
	public function registerShortCodes()	{				
		add_shortcode('ipswich-jaffa-running-club-results', array( $this, 'processShortCode' ));
	}	
		
	public function processShortCode($attr, $content = "") {
		$atts = shortcode_atts(
			array('memberresultspageid' => 0, 
				  'eventresultspageid' => 0,
				  'feature' => ''), 
			$attr);
		
		$memberResultsPageUrl = get_permalink($atts['memberresultspageid']);	
		$eventResultsPageUrl = get_permalink($atts['eventresultspageid']);		
		$raceResultsPageUrl = get_permalink($atts['eventresultspageid']);
		
		$feature = $atts['feature'];		
		
		if ($feature != '') {			
			$this->scripts();
			$this->styles();
			
			ob_start();
			require_once "html/$feature.php";
			$content = ob_get_clean();
		}
		
		return $content;
	}
		
	private function scripts() {
		wp_enqueue_script(self::JQUERY_HANDLE);
			
		wp_enqueue_script(
			self::JQUERY_DATATABLES_HANDLE,
			 plugins_url('/lib/datatables.min.js', __FILE__ ),
			array(self::JQUERY_HANDLE),
			null,
			true
		);
		
		wp_enqueue_script(
			self::JQUERY_DATATABLES_BOOTSTRAP_HANDLE,
			plugins_url('/lib/dataTables.bootstrap.min.js', __FILE__ ),
			array(self::JQUERY_DATATABLES_HANDLE),
			null,
			true
		);
		
		wp_enqueue_script(
			self::JQUERY_DATATABLES_BUTTONS_HANDLE,
			plugins_url('/lib/dataTables.buttons.min.js', __FILE__ ),
			array(self::JQUERY_DATATABLES_HANDLE),
			null,
			true
		);	

		wp_enqueue_script(
			self::JQUERY_DATATABLES_BUTTONS_PRINT_HANDLE,
			plugins_url('/lib/buttons.print.min.js', __FILE__ ),
			array(self::JQUERY_DATATABLES_BUTTONS_HANDLE),
			null,
			true
		);				
	}
	
	private function styles()
	{		
		wp_enqueue_style(
			'datatables.bootstrap.min.css',
			plugins_url('/lib/dataTables.bootstrap.min.css', __FILE__ )
		);	

		wp_enqueue_style(
			'buttons.dataTables.min.css',
			plugins_url('/lib/buttons.dataTables.min.css', __FILE__ )
		);		
	}
}
?>
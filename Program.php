<?php
/*
Plugin Name: Ipswich JAFFA Running Club Results
Plugin URI:
Description: The new Ipswich JAFFA RC Results plugin. Requires bootstrap to be part of the theme.
Version: 2.0
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
	
	function __construct() {		
		add_action('init', array($this, 'registerShortCodes'));
	}
		
	public function registerShortCodes()	{				

		add_shortcode('ipswich-jaffa-running-club-results', array( $this, 'processShortCode' ));
		
		add_action('wp_print_styles', array($this, 'styles'));
		add_action('wp_print_scripts', array($this, 'scripts'));
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
			
			ob_start();
			require_once "html/$feature.php";
			$content = ob_get_clean();
		}
		
		return $content;
	}
		
	public function scripts() {
		wp_enqueue_script(self::JQUERY_HANDLE);
			
		wp_enqueue_script(
			self::JQUERY_DATATABLES_HANDLE,
			'https://cdn.datatables.net/r/bs/dt-1.10.9/datatables.min.js',
			array(self::JQUERY_HANDLE));
		
		wp_enqueue_script(
			self::JQUERY_DATATABLES_BOOTSTRAP_HANDLE,
			'http://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.js',
			array(self::JQUERY_DATATABLES_HANDLE));
	}
	
	public function styles()
	{		
		wp_enqueue_style(
			'datatables.bootstrap.min.css', // Unique identifier
			'http://cdn.datatables.net/plug-ins/3cfcc339e89/integration/bootstrap/3/dataTables.bootstrap.css'
		);			
	}
}
?>
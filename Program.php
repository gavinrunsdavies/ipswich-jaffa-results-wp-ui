<?php
/*
Plugin Name: Ipswich JAFFA Running Club Results
Plugin URI:
Description: The new Ipswich JAFFA RC Results plugin.
Version: 3.0
Author: Gavin Davies
Author URI: https://github.com/gavinrunsdavies/
*/

namespace IpswichJAFFARunningClubResults;

require_once plugin_dir_path(__FILE__) . 'widgets/LatestResultsWidget.php';
require_once plugin_dir_path(__FILE__) . 'widgets/RunnerOfTheMonthWidget.php';
require_once plugin_dir_path(__FILE__) . 'php/LatestResultsMenuItems.php';

use IpswichJAFFARunningClubResults\Php\LatestResultsMenuItems as LatestResultsMenuItems;

$go = new Program();

class Program
{
	const JQUERY_HANDLE = 'jquery';
	const JQUERY_DATATABLES_HANDLE = 'jquery.dataTables.min';
	const JQUERY_DATATABLES_RESPONSIVE_HANDLE = 'dataTables.responsive.min';

	public function __construct()
	{
		add_action('init', array($this, 'registerShortCodes'));
		add_action('wp_enqueue_scripts', array($this, 'styles'));
		add_action('widgets_init', array($this, 'registerWidgets'));
		$latestResultsMenu = new LatestResultsMenuItems();
		add_filter('wp_get_nav_menu_items', array($latestResultsMenu, 'addLatestResultsMenuItems'), 10, 2);
	}

	public function registerShortCodes()
	{
		add_shortcode('ipswich-jaffa-running-club-results', array($this, 'processShortCode'));
	}

	public function styles()
	{
		wp_enqueue_style(
			'IpswichJaffaResults.css',
			plugins_url('/lib/IpswichJaffaResults.css', __FILE__)
		);

		wp_enqueue_style(
			'dataTables.dataTables.min.css',
			'https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css'
		);

		wp_enqueue_style(
			'responsive.dataTables.min.css',
			'https://cdn.datatables.net/responsive/3.0.4/css/responsive.dataTables.min.css'
		);
	}

	public function registerWidgets()
	{
		register_widget('IpswichJAFFARunningClubResults\LatestResultsWidget');
		register_widget('IpswichJAFFARunningClubResults\RunnerOfTheMonthWidget');
	}

	public function processShortCode($attr, $content = "")
	{
		$atts = shortcode_atts(
			array(
				'memberresultspageid' => 0,
				'eventresultspageid' => 0,
				'feature' => ''
			),
			$attr
		);

		// These are used in the feature files.
		$memberResultsPageUrl = get_permalink($atts['memberresultspageid']);
		$eventResultsPageUrl = get_permalink($atts['eventresultspageid']);
		$raceResultsPageUrl = get_permalink($atts['eventresultspageid']);

		$feature = $atts['feature'];

		if ($feature != '') {
			// Load scripts in the footer and only for the shortcode. Styles are loaded for all and in the header
			$this->scripts();

			ob_start();
			$filename = "html/$feature.php";
			if (file_exists(dirname(__FILE__) . '/' . $filename)) {
				require_once $filename;
			} else {
				echo "Filename $filename does not exist.";
			}
			$content = ob_get_clean();
		}

		return $content;
	}

	private function scripts()
	{
		wp_enqueue_script(self::JQUERY_HANDLE);

		wp_enqueue_script(
			self::JQUERY_DATATABLES_HANDLE,
			'https://cdn.datatables.net/2.3.1/js/dataTables.min.js',
			array(self::JQUERY_HANDLE),
			null,
			true
		);

		wp_enqueue_script(
			self::JQUERY_DATATABLES_RESPONSIVE_HANDLE,
			'https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.min.js',
			array(self::JQUERY_DATATABLES_HANDLE),
			null,
			true
		);

		wp_enqueue_script(
			'IpswichJaffaResults.js',
			plugins_url('/lib/IpswichJaffaResults.js?version=1.1.1', __FILE__),
			null,
			null,
			true
		);
	}
}

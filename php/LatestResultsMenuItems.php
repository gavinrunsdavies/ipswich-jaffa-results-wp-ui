<?php

namespace IpswichJAFFARunningClubResults\Php;

class LatestResultsMenuItems
{
	const MENU_SLUG = 'top-banner';

	private function creatCustomNavMenuItem($title, $url, $order, $parent = 0)
	{
		$item = new \stdClass();
		$item->ID = 1000000 + $order + $parent;
		$item->db_id = $item->ID;
		$item->title = $title;
		$item->url = $url;
		$item->menu_order = $order;
		$item->menu_item_parent = $parent;
		$item->type = '';
		$item->object = '';
		$item->object_id = '';
		$item->classes = array();
		$item->target = '';
		$item->attr_title = '';
		$item->description = '';
		$item->xfn = '';
		$item->status = '';
		return $item;
	}

	public function addLatestResultsMenuItems($items, $menu)
	{
		if ($menu->slug == self::MENU_SLUG) {
			$results = $this->getLatestResults();
			$order = 100;
			$top = $this->creatCustomNavMenuItem('Latest Results', '/', $order);
			$items[] = $top;
			$format = '<span style="font-size: smaller">%s races, %s results, %s PBs, %s SBs.</span>';
			for ($i = 0; $i < count($results); $i++) {
				$order++;
				$item = $results[$i]->name . ' (' . $this->formatDate($results[$i]->date) . ')<br>';
				$item .= sprintf(
					$format,
					$results[$i]->countOfRaces,
					$results[$i]->countOfResults,
					$results[$i]->countOfPersonalBests,
					$results[$i]->countOfSeasonalBests
				);

				$url = '/member-results/race-results/?raceId=' . $results[$i]->lastRaceId;
				$items[] = $this->creatCustomNavMenuItem($item, $url, $order, $top->ID);
			}
		}

		return $items;
	}

	private function getLatestResults()
	{
		$url = esc_url(home_url()) . '/wp-json/ipswich-jaffa-api/v2/races/latest?count=10&time=' . time();
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		curl_close($curl);
		$decoded = json_decode($response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			return null;
		}

		return $decoded;
	}

	private function formatDate($date)
	{
		$bits = explode('-', $date);
		if ($bits[0] != '') {
			$return = date("jS F Y", mktime(0, 0, 0, $bits[1], $bits[2], $bits[0]));
		}

		return $return;
	}
}

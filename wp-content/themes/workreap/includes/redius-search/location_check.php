<?php
/**
 *@Class Radius Check
 *@return {}
 */
if (!class_exists('RadiusCheck')) {
	class RadiusCheck {
 	var $maxLat;
	var $minLat;
	var $maxLong;
	var $minLong;
	function __construct($Latitude, $Longitude, $Miles) {
		global $maxLat,$minLat,$maxLong,$minLong;
		$EQUATOR_LAT_MILE = 69.172;
		$maxLat = $Latitude + $Miles / $EQUATOR_LAT_MILE;
		$minLat = $Latitude - ($maxLat - $Latitude);
		$maxLong = $Longitude + $Miles / (cos($minLat * M_PI / 180) * $EQUATOR_LAT_MILE);
		$minLong = $Longitude - ($maxLong - $Longitude);
	}
	function MaxLatitude() {
		return $GLOBALS["maxLat"];
	}
	function MinLatitude() {
		return $GLOBALS["minLat"];
	}
	function MaxLongitude() {
		return $GLOBALS["maxLong"];
	}
	function MinLongitude() {
		return $GLOBALS["minLong"];
	} 
	}
}
/*
 *@Class Distance Check
 *@return {}
 */
if (!class_exists('DistanceCheck')) {
	class DistanceCheck {  
		function Calculate($dblLat1,$dblLong1,$dblLat2,$dblLong2) {
			$EARTH_RADIUS_MILES = 3963;
			$dist = 0;		 
			
			//convert degrees to radians
			$dblLat1  = $dblLat1 * M_PI / 180;
			$dblLong1 = $dblLong1 * M_PI / 180;
			$dblLat2  = $dblLat2 * M_PI / 180;
			$dblLong2 = $dblLong2 * M_PI / 180;
		 
			if ($dblLat1 != $dblLat2 || $dblLong1 != $dblLong2)
			{
				//the two points are not the same
				$dist =
				sin($dblLat1) * sin($dblLat2)
				+ cos($dblLat1) * cos($dblLat2)
				* cos($dblLong2 - $dblLong1);
				 
				$dist =
				$EARTH_RADIUS_MILES
				* (-1 * atan($dist / sqrt(1 - $dist * $dist)) + M_PI / 2);
			}		
			return $dist;
		} 
	}
}
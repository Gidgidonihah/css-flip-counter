<?php

define('START_DB', 'db/start.txt');
define('VALUES_DB', 'db/values.txt');

function calculateValues($timeframe=1){
	$current_total = null;
	$average = null;
	$averages = array();
	$previous = null;

	$values_info = array_reverse(file(VALUES_DB));

	foreach($values_info as $arr){
		$obj = json_decode($arr);
		if(!$previous){
			$current_total = $obj->total;
		}elseif($timeframe){

			$seconds_diff = $previous->timestamp - $obj->timestamp;
			$count_diff = $previous->total - $obj->total;

			$denom = $seconds_diff / $timeframe;

			$averages[] = $count_diff / $denom;
		}
		$previous = $obj;
	}

	if(count($averages) > 1){
		$average = ceil(array_sum($averages) / count($averages));
	}

	return jsonify($current_total, $average);
}

function jsonify($total, $average=null){
	$arr = array(
		'timestamp' => time(),
		'total' => intval($total),
	);
	if($average){
		$arr['average'] = $average;
	}

	return json_encode($arr);
}

// getting the starting value if it exists
function getStartValue(){
	if(file_exists(START_DB)){
		$start_info = json_decode(array_pop(file(START_DB)));
		return ($start_info->total ? $start_info->total : 0);
	}
	return 0;
}

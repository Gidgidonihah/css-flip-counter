<?php
	include_once('functions.php');

	$start_val = getStartValue();

	if($_POST && isset($_POST['submitted'])){
		if(isset($_POST['start_val']) && intval($_POST['start_val'])){
			file_put_contents(START_DB, jsonify($_POST['start_val']));
			file_put_contents(VALUES_DB, jsonify($_POST['start_val']) . "\n", FILE_APPEND | LOCK_EX);
			$start_val = $_POST['start_val'];
		}elseif(isset($_POST['new_total']) && intval($_POST['new_total'])){
			file_put_contents(VALUES_DB, jsonify($_POST['new_total']) . "\n", FILE_APPEND | LOCK_EX);
		}
		include_once('admin.html');
	}elseif(isset($_GET['admin'])){
		include_once('admin.html');
	}else{
		$timeframe = (isset($_GET['timeframe']) ? intval($_GET['timeframe']) : null);
		die(calculateValues($timeframe));
	}

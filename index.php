<?php

	$timeframe = 1;

	include_once('functions.php');

	$start_val = getStartValue();
	$info = json_decode(calculateValues($timeframe));

?><!DOCTYPE html>
<html lang="en" class="no-js">
<head>
	<meta charset="utf-8"/>
	<title>CSS Flip-Counter Revisited</title>
	<meta name="description" content="An experiment in CSS animation for my flip counter script."/>
	<meta name="keywords" content="HTML,CSS,JavaScript,counter,apple-style,flip,animate,digit,demo"/>
	<meta name="author" content="Chris Nanney"/>

	<!-- Counter script -->
	<script type="text/javascript" src="js/flipcounter.js"></script>
	<script type="text/javascript" src="js/modernizr.custom.21954.js"></script>

	<!-- Counter styles -->
	<link rel="stylesheet" type="text/css" href="css/style.css"/>
</head>

<body>

<h1>Ward Total</h1>

<div class="clearfix">
	<div class="counter-wrapper">
		<ul class="flip-counter huge" id="wardTotal">
			<li class="start-value"><?php echo($info->total); ?></li>
		</ul>
	</div>
</div>

<h2>Nightly Total</h2>

<div class="clearfix">
	<div class="counter-wrapper">
		<ul class="flip-counter large" id="nightlyTotal">
			<li class="start-value"><?php echo($info->total-$start_val); ?></li>
		</ul>
	</div>
</div>

<h3>Ward Goal</h3>

<div class="clearfix">
	<div class="counter-wrapper">
		<ul class="flip-counter small" id="wardGoal">
			<li class="start-value">34,000</li>
		</ul>
	</div>
</div>

<script>
	var r, info, wardTotalCounter, nightlyTotalCounter, wardGoalCounter;

	wardTotalCounter = new flipCounter('wardTotal', {
		inc: <?php echo($info->average); ?>,
		pace: <?php echo($timeframe*1000); ?>,
		digits: 6
	});

	nightlyTotalCounter = new flipCounter('nightlyTotal', {
		inc: <?php echo($info->average); ?>,
		pace: <?php echo($timeframe*1000); ?>,
		digits: 4
	});

	var wardGoalCounter = new flipCounter('wardGoal', {
		auto: false
	});

	function runCounters(){
		var r = new XMLHttpRequest();
		r.open("GET", "update.php?timeframe=<?php echo($timeframe); ?>", true);
		r.onreadystatechange = function () {
			if (r.readyState != 4 || r.status != 200) return;
			info = JSON.parse(r.responseText);

			console.log(info);

			if(wardTotalCounter){
				wardTotalCounter.setIncrement(info.average);
			}

			if(nightlyTotalCounter){
				nightlyTotalCounter.setIncrement(info.average);
			}
		};
		r.send();
	}

	setTimeout(runCounters, 60000)
</script>
</body>
</html>

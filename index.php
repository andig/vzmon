<?php 
	$browser = (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")) ? 'iphone' : ''; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" encoding="en">
<head>
	<title>PVmon</title>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type"> 
	<meta content="utf-8" http-equiv="encoding">
<?php if ($browser == 'iphone') { ?>
	<!-- iPhone settings -->
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link rel="apple-touch-icon" sizes="57x57" href="img/home-57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="img/home-72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="img/home-114.png" />
	<link rel="apple-touch-startup-image" href="img/startup.png" />	
<?php } else { ?>
	<meta name="viewport" content="width=device-width" />
<?php } ?>
	<link rel="shortcut icon" href="../vz/favicon.ico" type="image/ico" />

	<!-- css -->
	<link rel="stylesheet" href="css/pvmon.css" type="text/css" />

	<!-- js -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script> 

    <script type="text/javascript" src="js/flot/jquery.flot.js"></script> 
    <script type="text/javascript" src="js/flot/jquery.flot.time.js"></script> 
    <script type="text/javascript" src="js/curvedLines.js"></script> 

	<script type="text/javascript" src="js/mustache.js"></script>
	<script type="text/javascript" src="js/skycons.js"></script>

	<script type="text/javascript" src="js/options.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
</head>

<body>
	<!-- templates -->
	<div class="hidden">
		<div id="templateWeather">
			<div>
				<div class="largeValue">{{currently.temperature}}°</div>
				<div class="unit">{{currently.summary}}</div>
			</div>
		</div>
		<div id="templateNow">
			{{value}}<span class="unit">{{unit}}</span>
		</div>
		<div id="templateToday">
	 		<h1 class="right">{{value}}<span class="unit">{{unit}} today</span></h1>
		</div>
		<div id="templateTotal">
	 		<h1 class="right">{{value}}<span class="unit">{{unit}} total</span></h1>
		</div>
	</div>

	<!-- weather -->
	<div class="row">
		<div class="w-300">
		    <canvas id="weather-icon" width="90" height="90"></canvas>
			<div id="weather" class="text">
				<div class="largeValue">-°</div>
				<div class="unit">Current condition</div>
			</div>	   
		 </div>
	</div>

	<!-- current values -->
	<div class="row nowrap" style="margin-top: 5px;">
		<div class="w-150">
			<h2>Solar generation</h2>
			<div id="generation" class="largeValue">- <span class="unit">kW</span></div>
		</div><!-- 
	 --><div class="w-150">
			<div id="generationToday">
		 		<h1 class="right">- <span class="unit">kWh today</span></h1>
			</div><!-- 
		 --><div id="generationTotal">
		 		<h1 class="right">- <span class="unit">kWh total</span></h1>
		 	</div>
		</div>
	</div>

	<div class="chart center">
		<div id="flot" class="w-300"></div>
	</div>

	<div class="row nowrap">
		<div class="w-150">
			<h2>Usage</h2>
			<div id="bezug" class="largeValue">- <span class="unit">kW</span></div>
		</div><!-- 
	 --><div class="w-150">
			<div id="bezugToday">
		 		<h1 class="right">- <span class="unit">kWh today</span></h1>
			</div><!-- 
		 --><div id="bezugTotal" class="right">
		 		<h1 class="right">- <span class="unit">kWh total</span></h1>
		 	</div>
		</div>
	</div>

<script type="text/javascript">

// UUIDs
var uuid_bezug, uuid_lief, uuid_gen;

function updateDials() {
	$.when(
		$.getJSON(vzAPI + "/data/" + uuid_bezug + ".json?padding=?" + "&from=today&to=now").fail(failHandler),
		$.getJSON(vzAPI + "/data/" + uuid_gen + ".json?padding=?" + "&from=today&to=now").fail(failHandler),
		$.getJSON(vzAPI + "/data/" + uuid_lief + ".json?padding=?" + "&from=today&to=now").fail(failHandler)
	).done(function(json_bezug, json_lief, json_gen) {
		// console.log(json_bezug[0], json_lief[0], json_gen[0]);

		var bezug = json_bezug[0].data.tuples[json_bezug[0].data.tuples.length-1][1];
		var gen = -json_gen[0].data.tuples[json_gen[0].data.tuples.length-1][1];   // pos.
		// var lief = -json_lief[0].data.tuples[json_lief[0].data.tuples.length-1][1]; // pos.
		// var netto = lief - bezug;
		// var gesamt = bezug + gen - lief;

        // current values
		$("#bezug").html(Mustache.render($("#templateNow").html(), formatNumber(bezug, formatCurrent)));
		$("#generation").html(Mustache.render($("#templateNow").html(), formatNumber(gen, formatCurrent)));

        // daily values
		$("#bezugToday").html(Mustache.render($("#templateToday").html(), formatNumber(json_bezug[0].data.consumption, formatConsumption)));
		$("#generationToday").html(Mustache.render($("#templateToday").html(), formatNumber(-json_gen[0].data.consumption, formatConsumption)));

		// totals
		$("#bezugTotal").html(Mustache.render($("#templateTotal").html(), formatNumber(channels.bezug.totalValue + json_bezug[0].data.consumption/1000.0, formatTotals)));
		$("#generationTotal").html(Mustache.render($("#templateTotal").html(), formatNumber(channels.generation.totalValue - json_gen[0].data.consumption/1000.0, formatTotals)));
	});			
}

function updatePlot() {
	$.when(
		$.getJSON(vzAPI + "/data/" + uuid_bezug + ".json?padding=?" + "&from=5:00&to=now&tuples=100").fail(failHandler),
		$.getJSON(vzAPI + "/data/" + uuid_lief + ".json?padding=?" + "&from=5:00&to=now&tuples=100").fail(failHandler)
		// $.getJSON(vzAPI + "/data/" + uuid_gen + ".json?padding=?" + "&from=today&to=now").fail(failHandler)
	).done(function(json_bezug, json_lief, json_gen) {
		// console.log(json_bezug, json_lief, json_gen);

		json_bezug[0].data.tuples.shift();
		json_lief[0].data.tuples.shift();

		var series = [
			{ data: json_bezug[0].data.tuples, color: "#666" }, // e61703
			{ data: json_lief[0].data.tuples, color: "#222" }, // 046b34
		];

		$.plot($("#flot"), series, plotOptions);
	});
}

function updateWeather() {
	$.getJSON(weatherAPI + "&callback=?", function(forecast) {
		// console.log(forecast);
		forecast.currently.temperature = Math.round(forecast.currently.temperature);

		plotOptions.xaxis.min = Math.floor(forecast.daily.data[0].sunriseTime / 3600) * 3600 * 1000;
		plotOptions.xaxis.max = Math.ceil(forecast.daily.data[0].sunsetTime / 3600) * 3600 * 1000;

		$("#weather").html(Mustache.render($("#templateWeather").html(), forecast));

		icons.set($("#weather-icon").get(0), mapWeatherIcon(forecast.currently.icon));
		icons.play();
	}).fail(failHandler);
}

$(document).ready(function() {
	var icons = new Skycons();

	plotOptions.yaxis.tickFormatter = unitFormatter;
	$.plot($("#flot"), [{data:[]}], plotOptions);

	$.getJSON(vzAPI +"/channel.json?padding=?", function(json) {
 		// get UUIDs
		uuid_bezug = getUUID(json, "Bezug");
		uuid_lief = getUUID(json, "Lieferung");
		uuid_gen = getUUID(json, "Erzeugung");
		// console.log("Bezug: " + uuid_bezug);
		// console.log("Lief: " + uuid_lief);
		// console.log("Gen: " + uuid_gen);

		$.when(
			$.getJSON(vzAPI + "/data/" + uuid_bezug + ".json?padding=?" + "&from=" + channels.bezug.totalAtDate + "&to=today&tuples=1").fail(failHandler),
			$.getJSON(vzAPI + "/data/" + uuid_gen + ".json?padding=?" + "&from=" + channels.generation.totalAtDate + "&to=today&tuples=1").fail(failHandler)
		).done(function(json_bezug, json_gen) {
			// console.log(json_bezug[0]);
			channels.bezug.totalValue += Math.abs(json_bezug[0].data.consumption) / 1000.0;
			channels.generation.totalValue += Math.abs(json_gen[0].data.consumption) / 1000.0;

			updateDials();
		});

		var refreshFunction = function() {
			updateDials();
			updatePlot();
			updateWeather();
			return(false);
		}

		// run
		refreshFunction();
		setInterval(refreshFunction, 60*1000); // 60s
		$("#refresh").click(refreshFunction);
 	}).fail(failHandler);
 });

</script>


</body>
</html>
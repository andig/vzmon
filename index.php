<?php 
	$browser = (strpos($_SERVER['HTTP_USER_AGENT'],"iPhone")) ? 'iphone' : ''; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" encoding="en" <?php if ($browser == 'iphone') { ?> manifest="manifest.appcache" <?php } ?>>
<head>
	<title>VZmon</title>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type"> 
	<meta content="utf-8" http-equiv="encoding">
<?php if ($browser == 'iphone') { ?>
	<!-- iPhone settings -->
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
	<meta name="apple-mobile-web-app-capable" content="yes">

	<link rel="apple-touch-icon" sizes="57x57" href="img/home-57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="img/home-72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="img/home-114.png" />

	<!-- Startup images --> 
	<!-- Source: https://gist.github.com/tfausak/2222823 -->

	<!-- iPhone - iPod touch - 320x460 --> 
	<link rel="apple-touch-startup-image" href="img/startup.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)">
	<!-- iPhone - iPod (Retina) - 640x920 --> 
	<link rel="apple-touch-startup-image" href="img/startup@2x.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)">
	<!-- iPhone 5 - iPod 5 (Retina) - 640x1096 --> 
	<link rel="apple-touch-startup-image" href="img/startup-568h@2x.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)">

	<!-- iPhone -->
	<!-- <link rel="apple-touch-startup-image" href="img/startup-320x480.png" media="screen and (max-device-width: 320px)"> -->
	<!-- iPhone (Retina) -->
	<!-- <link rel="apple-touch-startup-image" href="img/startup-640x960.png" media="screen and (device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)"> -->
	<!-- iPhone 5 -->
	<!-- <link rel="apple-touch-startup-image" href="img/startup-640x1136.png" media="screen and (device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)"> -->
<?php } else { ?>
	<meta name="viewport" content="width=device-width" />
<?php } ?>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico" />

	<!-- css -->
	<link rel="stylesheet" href="css/vzmon.css" type="text/css" />

	<!-- js -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script> 

    <script type="text/javascript" src="js/flot/jquery.flot.js"></script> 
    <script type="text/javascript" src="js/flot/jquery.flot.time.js"></script> 
    <script type="text/javascript" src="js/curvedLines.js"></script> 

	<script type="text/javascript" src="js/mustache.js"></script>
	<script type="text/javascript" src="js/skycons.js"></script>

	<script type="text/javascript" src="js/options.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>

	<style type="text/css">
		@media screen and (/*orientation:landscape*/ min-width: 480px) { 
			.row {
			    /*max-width: 768px !important;*/
			}
		}
		/* hide numbers until data available */
		.row.nowrap {
			display: none;
		}
	</style>
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
	<div id="generation" class="row nowrap" style="margin-top: 5px;">
		<div class="w-150">
			<h2>Solar generation</h2>
			<div id="generationNow" class="largeValue">- <span class="unit">kW</span></div>
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

	<div id="bezug" class="row nowrap">
		<div class="w-150">
			<h2>Usage</h2>
			<div id="bezugNow" class="largeValue">- <span class="unit">kW</span></div>
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

	<div id="lieferung" class="row nowrap">
		<div class="w-150">
			<h2>Supply</h2>
			<div id="lieferungNow" class="largeValue">- <span class="unit">kW</span></div>
		</div><!-- 
	 --><div class="w-150">
			<div id="lieferungToday">
		 		<h1 class="right">- <span class="unit">kWh today</span></h1>
			</div><!-- 
		 --><div id="lieferungTotal" class="right">
		 		<h1 class="right">- <span class="unit">kWh total</span></h1>
		 	</div>
		</div>
	</div>

<script type="text/javascript">

// UUIDs
var uuid = {};

// forecast.io weather icons
var icons;

function updateWeather() {
	$.getJSON(weatherAPI + "&callback=?", function(forecast) {
		// console.log(forecast);
		forecast.currently.temperature = Math.round(forecast.currently.temperature);

		if (typeof forecast.daily.data[0] !== "undefined") {
			// xaxis minimum		
			console.info("[updateWeather] Sunrise: " + forecast.daily.data[0].sunriseTime);
			plotOptions.xaxis.min = Math.floor(forecast.daily.data[0].sunriseTime / 3600) * 3600 * 1000;
			options.sunriseTime = new Date(forecast.daily.data[0].sunriseTime * 1000).getUTCHours() + ":00";

			// xaxis maximum
			console.info("[updateWeather] Sunset: " + forecast.daily.data[0].sunsetTime);
			plotOptions.xaxis.max = Math.ceil(forecast.daily.data[0].sunsetTime / 3600) * 3600 * 1000;
		}

		$("#weather").html(Mustache.render($("#templateWeather").html(), forecast));

		icons.set($("#weather-icon").get(0), mapWeatherIcon(forecast.currently.icon));
		icons.play();
	}).fail(failHandler);
}

function updateDatabaseStatus() {
	var formatTotals = {array:true, decimals:0, si:false, unit:'kWh'};
	$.getJSON(vzAPI + "/capabilities.json?padding=?&section=database", function(json) {
		console.log(json.capabilities.database);
		$("#database").html(Mustache.render($("#templateDatebase").html(), {
			dbrows: formatNumber(json.capabilities.database.rows / 1000, {decimals:0, si:false}),
			dbsize: formatNumber(json.capabilities.database.size / 1024 / 1024, {decimals:1, si:false})
		}));
	}).fail(failHandler);
}

function updateChannels() {
	for (var channel in channels) {
		updateChannel(channel);
	}	
}

function updateChannel(channel) {
	$.getJSON(vzAPI + "/data/" + uuid[channel] + ".json?padding=?&from=today&to=now", function(json) {
		// console.info(json);
		if (typeof json.data == "undefined") {
			console.error("[updateChannel] No current data for channel " + channel);
			return;
		}
		if (typeof json.data.tuples == "undefined") {
			console.error("[updateChannel] No current data.tuples for channel " + channel);
			return;
		}
		if (typeof json.data.consumption == "undefined") {
			console.error("[updateChannel] No current data.consumption for channel " + channel);
			return;
		}

		$("#" + channel + "Now").html(Mustache.render($("#templateNow").html(), 
			formatNumber(Math.abs(json.data.tuples[json.data.tuples.length-1][1]), formatCurrent)));
		$("#" + channel + "Today").html(Mustache.render($("#templateToday").html(), 
			formatNumber(Math.abs(json.data.consumption), formatConsumption)));
		$("#" + channel + "Total").html(Mustache.render($("#templateTotal").html(), 
			formatNumber((channels[channel].totalValue || 0) + Math.abs(json.data.consumption/1000.0), formatTotals)));

		$("#" + channel).show();
	}).fail(failHandler);
}

function invertSeries(data) {
	console.info("[invertSeries] data length " + data.length);
	for (var i=0; i<data.length; i++) {
		data[i][1] = -data[i][1];
	}
}

function updatePlot() {
	console.info("[updatePlot]");

	var deferred = [];
	var data = {};

	// generate one request per channel
	for (var channel in channels) {
		// only if channel is to be plotted
		if (typeof channels[channel].plotOptions !== "undefined") {
			console.info("[updatePlot] getting " + channel);
			deferred.push(
				$.getJSON(vzAPI + "/data/" + uuid[channel] + ".json?padding=?&from=" + options.sunriseTime + "&to=now&tuples=" + options.plotTuples).success(
					$.proxy(function(json) {
						console.info("[updatePlot] got " + this._channel);

						if (typeof json.data.tuples == "undefined") {
							console.error("[updatePlot] No consumption data.tuples for channel " + this._channel);
							return;
						}		

						// convert result
						json.data.tuples.shift();
						if ((channels[this._channel].sign || +1) < 0) {
							invertSeries(json.data.tuples);
						}

						// store
						data[this._channel] = json;
					}, {_channel: channel})
				).fail(failHandler)
			);
		}
	}

	// add all data to plot series
	$.when.apply(null, deferred).done(function() {
		console.info("[updatePlot] all json finished");

		// sort series as defined in options
		var sorted = {};

		for (var channel in channels) {
			if (typeof data[channel] != "undefined") { 
				sorted[channel] = data.channel;
			}
		}

		var series = [];

		// use sorted data for building plot series
		for (var channel in sorted) {
			var s = { data: data[channel].data.tuples };
			// fuse series plot options
			for (var prop in channels[channel].plotOptions) { 
				s[prop] = channels[channel].plotOptions[prop];
			}
			series.push(s);
		}

		$.plot($("#flot"), series, plotOptions);
	});
}

$(document).ready(function() {
	icons = new Skycons();

	plotOptions.yaxis.tickFormatter = unitFormatter;
	$.plot($("#flot"), [{data:[]}], plotOptions);

	$.getJSON(vzAPI +"/channel.json?padding=?", function(json) {
 		// get UUIDs for defined channels
 		for (var channel in channels) {
 			// console.info("Channel " + channel);
 			uuid[channel] = getUUID(json, channels[channel].name);
 			console.info("[init] UUID " + uuid[channel] + " " + channel);

 			if (typeof channels[channel].totalAtDate == "undefined") {
 				// no total defined, update directly 
	 			updateChannel(channel);
	 		}
			else {
	 			// do a one-time update of the totals if defined...
	 			$.getJSON(vzAPI + "/data/" + uuid[channel] + ".json?padding=?&from=" + channels[channel].totalAtDate + "&to=today&tuples=1",
	 				$.proxy(function(json) {
			 			// console.info(json);
						if (typeof json.data.consumption == "undefined") {
							console.error("[init] No consumption data for channel " + this._channel);
							return;
						}

		 				channels[this._channel].totalValue = (channels[this._channel].totalValue || 0) + Math.abs(json.data.consumption) / 1000.0;

		 				// ... then update
		 				updateChannel(this._channel);
		 			}, {_channel: channel})
		 		).fail(failHandler);
	 		}
 		}

		var refreshFunction = function(initial) {
			updateWeather();
			updatePlot();
			if (!initial) updateChannels();
			return(false);
		}

		// run initial update
		refreshFunction(true);
		setInterval(refreshFunction, (options.updateInterval || 1) * 60 * 1000); // 60s
		$("#refresh").click(refreshFunction);
 	}).fail(failHandler);
 });

</script>

</body>
</html>
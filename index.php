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
<?php } else { ?>
	<meta name="viewport" content="width=device-width" />
<?php } ?>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico" />

	<!-- css -->
	<link rel="stylesheet" href="css/vzmon.css" type="text/css" />

	<!-- js -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>

	<script type="text/javascript" src="js/mustache.js"></script>
	<script type="text/javascript" src="js/skycons.js"></script>

	<script type="text/javascript" src="js/options.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>

	<!-- plotting -->
    <script type="text/javascript" src="js/flot/jquery.flot.js"></script>
    <script type="text/javascript" src="js/flot/jquery.flot.time.js"></script>
    <script type="text/javascript" src="js/flot/curvedLines.js"></script>

	<script type="text/javascript" src="js/rickshaw/rickshaw.min.js"></script>
	<script type="text/javascript" src="js/rickshaw/d3.v2.min.js"></script>
	<script type="text/javascript" src="js/rickshaw/bullet.js"></script>
	
	<script type="text/javascript" src="js/plot.js"></script>

	<link rel="stylesheet" href="css/rickshaw.min.css" type="text/css" />

	<style type="text/css">
		@media screen and (/*orientation:landscape*/ min-width: 480px) {
			.row {
			    /*max-width: 768px !important;*/
			}
		}
		/*.rickshaw_graph .x_tick .title { bottom: -18px; }	*/
	</style>
</head>

<body>
	<!-- 
		HTML templates (hidden)
	-->
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

	<!--
		HTML document structure

		if a channel is defined in channels, this section will be parsed to see if a suitable representation can be found
	-->

	<!-- weather -->
	<div class="row">
		<div class="w-300">
		    <canvas id="weather-icon" width="90" height="90"></canvas>
			<div id="weather-text" class="text">
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

	<div class="row">
		<div id="chart"></div>
	</div>

	<div class="row">
		<div id="perf"></div>
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

var uuid = {};		// UUIDs
var icons;			// forecast.io weather icons
var plot;			// chart abstraction

var generationToday = 0,
	generationYesterday = 0,
	generationMax = 0;

function updateWeather() {
	var url = weatherAPI + "&callback=?";
	$.getJSON(url, function(forecast) {
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

		$("#weather-text").html(Mustache.render($("#templateWeather").html(), forecast));
		$("#weather").show();

		icons.set($("#weather-icon").get(0), mapWeatherIcon(forecast.currently.icon));
		if (typeof options.animate !== "undefined" && options.animate) {			
			icons.play();
		}
	}).fail(failHandler(url));
}

function updateDatabaseStatus() {
	var url = vzAPI + "/capabilities/database.json?padding=?";
	$.getJSON(url, function(json) {
		console.log("[updateDatabaseStatus]" + json.capabilities.database);
		$("#database").html(Mustache.render($("#templateDatebase").html(), {
			dbrows: formatNumber(json.capabilities.database.rows / 1000, {decimals:0, si:false}),
			dbsize: formatNumber(json.capabilities.database.size / 1024 / 1024, {decimals:1, si:false})
		}));
	}).fail(failHandler(url, "updateDatabaseStatus"));
}

function updateChannels() {
	console.info("[updateChannels]");
	for (var channel in channels) {
		updateChannel(channel);
	}
}

function updateChannel(channel) {
	console.info("[updateChannel] " + channel);

	// is the channel used at all?
	var templates = ["Now", "Today", "Total"],
		numTemplates = 0;

	for (var i in templates) {
		var template = templates[i];
		if ($("#" + channel + template).length > 0) {
			numTemplates++;
			break;
		}
	}
	// not used - exit
	if (!numTemplates) return;

	// get data
	var url = vzAPI + "/data/" + uuid[channel] + ".json?padding=?&client=raw&from=today&to=now";
	$.getJSON(url, function(json) {
		if (typeof json.data.tuples == "undefined") {
			console.error("[updateChannel] No current data.tuples for channel " + channel);
			return;
		}
		if (typeof json.data.consumption == "undefined") {
			console.error("[updateChannel] No current data.consumption for channel " + channel);
			return;
		}

		// remember
		if (channel == "generation") {
			generationToday = Math.abs(json.data.consumption);

			if (typeof options.power !== "undefined") {
				updatePerf("generation");
			}
		}

		$("#" + channel + "Now").html(Mustache.render($("#templateNow").html(),
			formatNumber(Math.abs(json.data.tuples[json.data.tuples.length-1][1]), formatCurrent)));
		$("#" + channel + "Today").html(Mustache.render($("#templateToday").html(),
			formatNumber(Math.abs(json.data.consumption), formatConsumption)));
		$("#" + channel + "Total").html(Mustache.render($("#templateTotal").html(),
			formatNumber(Math.abs(channels[channel].totalValue || 0) + Math.abs(json.data.consumption), formatTotals)));

		$("#" + channel).show();
	}).fail(failHandler(url, "updateChannel"));
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
			var url = vzAPI + "/data/" + uuid[channel] + ".json?padding=?&client=raw&from=" + options.sunriseTime + "&to=now&tuples=" + options.plotTuples;
			deferred.push(
				$.getJSON(url).success(
					$.proxy(function(json) {
						console.info("[updatePlot] got " + this._channel);

						if (typeof json.data.tuples == "undefined") {
							console.error("[updatePlot] No consumption data.tuples for channel " + this._channel);
							return;
						}

						// convert result
						json.data.tuples.shift();
						if ((channels[this._channel].sign || +1) < 0) {
							for (var i=0; i<json.data.tuples.length; i++) {
								json.data.tuples[i][1] *= (channels[this._channel].sign || +1);
							}
						}

						// store
						data[this._channel] = json;
					}, {_channel: channel})
				).fail(failHandler(url, "updatePlot"))
			);
		}
	}

	// add all data to plot series
	$.when.apply(null, deferred).done(function() {
		console.info("[updatePlot] getting channels finished");

		// sort series as defined in options
		var sorted = [];

		for (var channel in channels) {
			if (typeof data[channel] !== "undefined") {
				sorted[channel] = data.channel;
			}
		}

		plot.render(data, sorted);
	});
}

function updatePerfChart(today, yesterday, max) {
	console.info("[updatePerfChart] " + formatNumber(today) + "," + formatNumber(yesterday) + "," + formatNumber(max));

	var data = [{
		"title": "Ratio",
		"subtitle": "kWh/kWp",
		"ranges": [5,6,7.5],
		"measures": [yesterday/options.power, today/options.power],
		"markers": [max/options.power]
	}];

	var margin = {top: 5, right: 0, bottom: 15, left: 40},
	    width = $("#perf").width() - margin.left - margin.right,
	    height = $("#perf").height() - margin.top - margin.bottom;

	var chart = d3.bullet()
	    .width(width)
	    .height(height);

	var svg = d3.select("#perf").selectAll("svg");

	// bullet chart already created?
	if (svg[0].length > 0) {
		svg.data(data).call(chart.duration(500));
		return;
	}

	// create
	svg = svg.data(data).enter()
		.append("svg")
			.attr("class", "bullet")
			.attr("width", width + margin.left + margin.right)
			.attr("height", height + margin.top + margin.bottom)
		.append("g")
			.attr("transform", "translate(" + margin.left + "," + margin.top + ")")
			.call(chart);

	var title = svg.append("g")
		.style("text-anchor", "end")
		.attr("transform", "translate(-6," + height / 2 + ")");

	title.append("text")
		.attr("class", "title")
		.text(function(d) { return d.title; });

	title.append("text")
		.attr("class", "subtitle")
		.attr("dy", "1em")
		.text(function(d) { return d.subtitle; });

	$("#perf").show();
}

function updatePerf(channel) {
	console.info("[updatePerf] " + channel);

	// historical values already collected?
	if (generationYesterday + generationMax > 0) {
		updatePerfChart(generationToday / 1000.0, generationYesterday, generationMax);
	}
	else {
		// get data
		var date = new Date();
		var url = vzAPI + "/data/" + uuid[channel] + ".json?padding=?&client=raw&from=1.1." + date.getFullYear() + "&to=now&group=day";
		$.getJSON(url, function(json) {
			if (typeof json.data.tuples == "undefined") {
				console.error("[updatePerf] No current data.tuples for channel " + channel);
				return;
			}
			if (typeof json.data.consumption == "undefined") {
				console.error("[updatePerf] No current data.consumption for channel " + channel);
				return;
			}
			console.info("[updatePerf] got daily data (" + json.data.tuples.length + " data points)");

			// remove current day remainder
			json.data.tuples.pop(); 
			// find best day this year
			generationMax = json.data.tuples.reduce(function(previousValue, currentValue, index, array) {
				var perf = Math.abs(currentValue[1]) * 24 / 1000.0;
				if (perf > (options.maxPerf || 8.0) * options.power) perf = previousValue;
				return Math.max(previousValue, perf); 
			}, 0);

			// get yesterday's value from end of data
			generationYesterday = (json.data.tuples.length > 0) ? Math.abs(json.data.tuples[json.data.tuples.length-1][1]) * 24 / 1000.0 : 0;

			updatePerfChart(generationToday / 1000.0, generationYesterday, generationMax);
		}).fail(failHandler(url, "updatePerf"));
	}
}

$(document).ready(function() {
	// setup
	icons = new Skycons();
	// instantiate plot library by name - either RickshawD3 or Flot 
	plot = new RickshawD3($("#chart"));

	var url = vzAPI +"/channel.json?padding=?&client=raw";
	$.getJSON(url, function(json) {
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
	 			var url = vzAPI + "/data/" + uuid[channel] + ".json?padding=?&client=raw&from=" + channels[channel].totalAtDate + "&to=today&tuples=1";
	 			$.getJSON(url,
	 				$.proxy(function(json) {
			 			// console.info(json);
						if (typeof json.data.consumption == "undefined") {
							console.error("[init] No consumption data for channel " + this._channel);
							return;
						}
						if (json.data.consumption == 0) {
							console.warn("[init] Consumption 0 for channel " + this._channel + " (" + json.data.tuples + " tuples)");
						}

		 				channels[this._channel].totalValue = Math.abs(channels[this._channel].totalValue || 0) * 1000.0 + Math.abs(json.data.consumption);

		 				// ... then update
		 				updateChannel(this._channel);
		 			}, {_channel: channel})
		 		).fail(failHandler(url, "init"));
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
 	}).fail(failHandler(url, "init"));
});

</script>

</body>
</html>

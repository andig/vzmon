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
<!--
    <script type="text/javascript" src="../videodb/javascript/jquery/jquery-1.9.0.min.js"></script>
-->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.js"></script>

	<script type="text/javascript" src="js/mustache.js"></script>
	<script type="text/javascript" src="js/skycons.js"></script>

	<script type="text/javascript" src="js/options.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>

	<!-- libraries -->	
	<script type="text/javascript" src="js/nprogress.js"></script>
	<script type="text/javascript" src="js/jquery.jpanelmenu.min.js"></script>
    <script type="text/javascript" src="js/console.js"></script>

	<!-- plotting -->
	<script type="text/javascript" src="js/plot.js"></script>
	<script type="text/javascript" src="js/rickshaw/d3.v2.min.js"></script>
	<script type="text/javascript" src="js/rickshaw/rickshaw.min.js"></script>
	<script type="text/javascript" src="js/rickshaw/bullet.js"></script>
	
	<link rel="stylesheet" href="css/rickshaw.min.css" type="text/css" />

	<style type="text/css">
		@media screen and (/*orientation:landscape*/ min-width: 480px) {
			.row {
			    /*max-width: 768px !important;*/ }
		}

		.row:nth-of-type(1) {
			margin-top: 4px; }

		/* Header */
		header.primary { height: 49px; color: #333; background-color: #dadada; position: relative; border-bottom: solid 1px #f8f8f8; border-radius: 3px 3px 0 0;}
		header.primary .icon { z-index: 22; position: absolute; top: 0; left: 0; height: 49px; width: 49px; font-size: 26px; line-height: 49px; text-align: center; color: #333; }
		header.primary .side { left: auto; right: 0; }
		header.primary hgroup { text-align: center }
		header.primary hgroup h1 { z-index: 20; position: absolute; top: 0; left: 50px; right: 50px; color:#333; margin: 0; line-height: 49px; margin: 0; font-size: 20px; }
	</style>
</head>

<body>
	<div class="menu-trigger">
		<a href="#"><span>&#9776;</span></a>
	</div>

	<nav id="menu" class="hidden">
		<ul>
			<li><a href="#" panel="#main">Main</a>
				<ul id="submenuMain">
					<li class="active"><a href="#" id="plotDay">Day</a></li>
					<li><a href="#" id="plotMonth">Month</a></li>
					<li><a href="#" id="plotYear">Year</a></li>
				</ul>
			</li>
			<!-- <li><a href="#" panel="#database">Database</a></li> -->
			<li><a href="#" panel="#console" id="debug">Console</a></li>
			<li><a href="#" panel="#main" id="reset">Reset</a></li>
		</ul>
	</nav>

	<div id="database" class="panel hidden">
		<h2>Database status</h2>
		<h1 id="dbSize">Size: - <span class="unit">MB</span></h1>
		<h1 id="dbRows">Rows: - <span class="unit">total</span></h1>
	</div>

	<div id="console" class="panel hidden">
		<h2>Console</h2>
		<ul class="console"></ul>
	</div>

	<!-- 
		Templates (hidden)
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
		Main panel
	-->
	<div id="main" class="panel">

		<!-- weather -->
		<div id="weather" class="row">
			<div class="w-300">
			    <canvas id="weather-icon" width="90" height="90"></canvas>
				<div id="weather-text" class="text">
					<div class="largeValue">-°</div>
					<div class="unit">Current condition</div>
				</div>
			 </div>
		</div>

		<div class="row">
			<div id="chart"></div>
		</div>

		<div class="row">
			<div id="perf"></div>
		</div>

		<!-- current values -->
		<div id="generation" class="row nowrap">
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
	</div>

<script type="text/javascript">

var icons;			// forecast.io weather icons
var plot;			// chart abstraction
var jPM;			// menu

var uuid = {};			// UUIDs
var plotMode = "day";

/**
 * Get local weather and show
 */
function updateWeather() {
	console.info('[updateWeather]');

	var valid = Math.floor(new Date().getTime()/1000/300); // 5min

	// use cache?
	if (options.cache) {
		var data = cache.get("weather", valid);
		if (data) {
			return;
		}
	}

	var url = weatherAPI + "&callback=?";
	$.getJSON(url, function(json) {
		json.currently.temperature = Math.round(json.currently.temperature);

		if (typeof json.daily.data[0] !== "undefined") {
			console.info("[updateWeather] sunrise/sunset: " + 
				currentTime(new Date(json.daily.data[0].sunriseTime * 1000)) +"/"+ 
				currentTime(new Date(json.daily.data[0].sunsetTime * 1000)));

			// xaxis minimum
			plotOptions.xaxis.min = Math.floor(json.daily.data[0].sunriseTime / 3600) * 3600 * 1000;
			options.sunriseTime = new Date(json.daily.data[0].sunriseTime * 1000).getUTCHours() + ":00";

			// xaxis maximum
			plotOptions.xaxis.max = Math.ceil(json.daily.data[0].sunsetTime / 3600) * 3600 * 1000;
		}

		$("#weather-text").html(Mustache.render($("#templateWeather").html(), json));
		$("#weather").show();

		icons.set($("#weather-icon").get(0), mapWeatherIcon(json.currently.icon));
		if (typeof options.animate !== "undefined" && options.animate) {			
			icons.play();
		}

		cache.put("weather", valid, valid);
	}).fail(failHandler(url));
}

/**
 * Get database status
 */
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

/**
 * Get total values
 */
function updateTotals() {
	console.info("[updateTotals]");
	var deferred = [];
    var today = currentDate();

	for (var channel in channels) {
		// channel without defined total
		if (typeof channels[channel].totalAtDate == "undefined") continue;

		var totalStorage = (options.cache) ? cache.get("totals." + channel) : false;
		if (totalStorage) {
			console.info("[updateTotals] " + channel +" - "+ channels[channel].totalAtDate +":"+ channels[channel].totalValue
													 +" - "+ totalStorage.totalAtDate +":"+ totalStorage.totalValue);
			channels[channel].totalValue = totalStorage.totalValue;
			channels[channel].totalAtDate = totalStorage.totalAtDate;
	    }

		// totals already up-to-date
		if (channels[channel].totalAtDate == today) continue;

		// do a delta update of the totals
		var url = vzAPI + "/data/" + uuid[channel] + ".json?padding=?&client=raw&from=" + channels[channel].totalAtDate + "&to=today&tuples=1";
		console.info("[updateTotals] " + url);
		deferred.push(
			$.getJSON(url,
				$.proxy(function(json) {
					if (!json.data.consumption) {
						console.error("[updateTotals] No consumption data for channel " + this._channel + " (" + json.data.tuples + " tuples)");
						return;
					}

					var totalValue = Math.abs(channels[this.channel].totalValue || 0) + Math.abs(json.data.consumption) / 1000.0;
	 				channels[this.channel].totalValue = totalValue;

	 				// save
	 				var totalStorage = {
	 					totalValue: totalValue,
	 					totalAtDate: this.today,
	 				}
	 				cache.put("totals." + this.channel, totalStorage)

				    console.info("[updateTotals] " + this.channel + JSON.stringify(totalStorage));	 				
	 			}, {channel: channel, today: today})
	 		).fail(failHandler(url, "updateTotals"))
	 	)
	}

	$.when.apply(null, deferred).done(function() {
		console.info("[updateTotals] finished");
	});

	return(deferred);
}

/**
 * Get todays channel values and show
 */
function updateChannels() {
	console.info("[updateChannels]");

	var templates = ["Now", "Today", "Total"];

	// get data
	var url = vzAPI + "/data.json?padding=?&client=raw&from=today&to=now";
	for (var channel in channels) {
		// is the channel used at all?
		for (var i in templates) {
			if ($("#" + channel + templates[i]).length > 0) {
				// if used, add to query
				url += "&uuid[]=" + uuid[channel];
				break;
			}
		}
	}

	$.getJSON(url, function(json) {
		for (var i=0; i<json.data.length; i++) {
			var channel = getChannelFromUUID(json.data[i].uuid);
			updateChannel(channel, {data: json.data[i]});
		}
	}).fail(failHandler(url, "updateChannels"));
}

/**
 * Show single channel values
 */
function updateChannel(channel, json) {
	console.info("[updateChannel] " + channel);

	if (typeof json.data.tuples == "undefined") {
		console.error("[updateChannel] No current data.tuples for channel " + channel);
		return;
	}
	if (typeof json.data.consumption == "undefined") {
		console.error("[updateChannel] No current data.consumption for channel " + channel);
		return;
	}

	// see if power rating desired
	if (channels[channel].power > 0) {
		cache.put("perf.genToday", Math.abs(json.data.consumption) / 1000.0);
		updatePerf(channel);
	}

	$("#" + channel + "Now").html(Mustache.render($("#templateNow").html(),
		formatNumber(Math.abs(json.data.tuples[json.data.tuples.length-1][1]), formatCurrent)));
	$("#" + channel + "Today").html(Mustache.render($("#templateToday").html(),
		formatNumber(Math.abs(json.data.consumption), formatConsumption)));

	// if (totalsInitialized) {
		$("#" + channel + "Total").html(Mustache.render($("#templateTotal").html(),
			formatNumber(Math.abs(channels[channel].totalValue || 0) * 1000.0 + Math.abs(json.data.consumption), formatTotals)));
	// }

	$("#" + channel).show();
}

/**
 * Get plot data and show
 */
function updatePlot() {
	var mode = plotMode; // local copy
	console.info("[updatePlot] " + mode);

	var date = new Date();
	var valid = (mode == "day") ? Math.floor(date.getTime()/1000/300) : currentDate();

	// use cache?
	if (options.cache) {
		var data = cache.get("consumption." + mode, valid);
		if (data) {
			console.log("[updatePlot] " + JSON.stringify(data));
			plot.render(data, (mode == "year" || mode == "month"));
			return;
		}
	}

	var deferred = [];
	var data = {};

	var range;
	if (mode == "year") {
		range = "from=1.1." + date.getFullYear() + "&to=now&group=month";
	}
	else if (mode == "month") {
		range = "from=1." + (date.getMonth()+1) +"."+ date.getFullYear() + "&to=now&group=day"; // January is 0! 
	}
	else {
		range = "client=raw&from=" + options.sunriseTime + "&to=now&tuples=" + options.plotTuples;
	}
	var url = vzAPI + "/data.json?padding=?&" + range;

	// generate compound request for all channels
	for (var channel in channels) {
		// only if channel is to be plotted
		if (typeof channels[channel].plotOptions !== "undefined") {
			console.info("[updatePlot] adding " + channel + " to compound request");
			url += "&uuid[]=" + uuid[channel];
		}
	}

	// add all data to plot series
	$.getJSON(url, function(json) {
		console.info("[updatePlot] got compound request");

		for (var j=0; j<json.data.length; j++) {
			if (typeof json.data[j].tuples == "undefined") {
				console.error("[updatePlot] No consumption data.tuples for channel " + json.data[j].uuid);
				continue;
			}

			// convert result
			json.data[j].tuples.shift();
			for (var i=0; i<json.data[j].tuples.length; i++) {
				json.data[j].tuples[i][1] = Math.abs(json.data[j].tuples[i][1]);

				// kWh conversion
				if (mode == "year") {
					// variable month length calculation
					var d = new Date(json.data[j].tuples[i][0]); // end of month
					// json.data[j].tuples[i][0] = d.setDate(1); // first of month
					// if (i == json.data[j].tuples.length-1) {
					// 	json.data[j].tuples[i][1] *= 24 * d.getDate();
					// }
					// else {
						json.data[j].tuples[i][1] *= 24 * daysInMonth(d.getMonth(), d.getFullYear());
					// }
					console.log("[updatePlot] " + json.data[j].tuples[i][0] +" "+ currentDate(d) +" "+ json.data[j].tuples[i][1]);
				}
				else if (mode == "month") {
					json.data[j].tuples[i][1] *= 24;
				}
			}

			// store
			data[getChannelFromUUID(json.data[j].uuid)] = {data: json.data[j]};
		}

		// save
		cache.put("consumption." + mode, data, valid);

		// sync UI - still desired?
		if (mode == plotMode) {
			plot.render(data, (mode == "year" || mode == "month"));
		}
	}).fail(failHandler(url, "updatePlot"))
}

function updatePerfChart(power) {
	var perf = cache.get("perf");
	console.info("[updatePerfChart] " + formatNumber(perf.genToday) + "," + formatNumber(perf.genYesterday) + "," + formatNumber(perf.genMax));

	var data = [{
		"title": "Ratio",
		"subtitle": "kWh/kWp",
		"ranges": [5,6,7.5],
		"measures": [perf.genYesterday/power, (perf.genToday||0)/power],
		"markers": [perf.genMax/power]
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
	var today = currentDate();
	var power = channels[channel].power;
	console.info("[updatePerf] " + channel + " (" + power + "kWp)");

	// use cache?
	if (options.cache && cache.get("perf", today)) {
		updatePerfChart(power);
		return;
	}
	
    var from = "1.1." + new Date().getFullYear();
	var url = vzAPI + "/data/" + uuid[channel] + ".json?padding=?&client=raw&from=" + from + "&to=today&group=day";
	$.getJSON(url, function(json) {
		if (typeof json.data.tuples == "undefined") {
			console.error("[updatePerf] No current data.tuples for channel " + channel);
			return;
		}
		if (typeof json.data.consumption == "undefined") {
			console.error("[updatePerf] No current data.consumption for channel " + channel);
			return;
		}
		console.info("[updatePerf] got daily data (" + json.data.tuples.length + " days)");

		// remove current day remainder
		json.data.tuples.pop(); 
		// find best day this year
		var genMax = json.data.tuples.reduce(function(previousValue, currentValue, index, array) {
			var perf = Math.abs(currentValue[1]) * 24 / 1000.0;
			if (perf > (options.maxPerf || 8.0) * power) perf = previousValue;
			return Math.max(previousValue, perf); 
		}, 0);

		// get yesterday's value from end of data
		var genYesterday = (json.data.tuples.length > 0) ? Math.abs(json.data.tuples[json.data.tuples.length-1][1]) * 24 / 1000.0 : 0;

		// save
		cache.put("perf", { genMax: genMax, genYesterday: genYesterday, genToday: cache.get("perf.genToday") }, today);

		updatePerfChart(power);
	}).fail(failHandler(url, "updatePerf"));
}

function createProgressBar() {
	if (typeof NProgress !== 'undefined') {
		NProgress.numCalls = NProgress.completedCalls = 0;
		NProgress.advance = function() {
			NProgress.completedCalls++;
			// console.log(NProgress.status +" "+ NProgress.completedCalls +"/"+ NProgress.numCalls)
			NProgress.set(Math.max(NProgress.status, NProgress.completedCalls / NProgress.numCalls));
			if (NProgress.numCalls == NProgress.completedCalls) {
				NProgress.numCalls = NProgress.completedCalls = 0;
			}
		}

		// bind to jQuery ajax events
		$(document).ajaxSend(function() {
			NProgress.numCalls++;
			NProgress.set(Math.max(NProgress.status, NProgress.completedCalls / NProgress.numCalls));
		}).ajaxSuccess(function() {
			NProgress.advance();
		}).ajaxError(function() {
			NProgress.advance();
		});
	}
}

function initializeChannels(json) {
	console.info("[initializeChannels]");
	console.error(JSON.stringify(channels));
	console.error(JSON.stringify(json));

	// get UUIDs for defined channels
	for (var channel in channels) {
		// console.info("Channel " + channel);
		uuid[channel] = getUUID(json, channels[channel].name);
		console.info("[initializeChannels] UUID " + uuid[channel] + " " + channel);
	}

	// wait until totals are initialized
	$.when.apply(null, updateTotals()).done(function() {
		// run initial update
		refreshData();
		// repeat 
		setInterval(refreshData, (options.updateInterval || 1) * 60 * 1000); // 60s
	});

	// assign to button
	$("#refresh").click(refreshData);
}

function setPlotMode(mode) {
	console.info("[setPlotMode] " + mode);
	
	var target;
	if (mode == "year")
		target = "#plotYear";
	else if (mode == "month")
		target = "#plotMonth";
	else 
		target = "#plotDay";

	$("#jPanelMenu-menu li").removeClass("active");
	$(target).closest("li").addClass("active");

	plotMode = mode;
	cache.put("plot.mode", mode);
}

function createMenu() {
	jPM = $.jPanelMenu({
		direction: "right",
		openPosition: 200,
		before: function() {
			icons.pause();
		},
		after: function() {
			if (typeof options.animate !== "undefined" && options.animate) icons.play();
		},
	});
	jPM.on();
	// jPM.open();

	// jPM doesn't remove original menu...
	$("#menu").remove();

	// handlers
	$("#jPanelMenu-menu a").click(function() {
		// make menu target visible
		var panel = $(this).attr("panel");
		$(".panel").hide();
		$(panel).show();

		if (panel && panel !== "#main") {
			$("#submenuMain").hide();
		} else {
			$("#submenuMain").show();
			$("#main").show();
		}

		switch ($(this).attr("id")) {
			case "reset":
				resetCache();
				refreshData();
				break;
			case "debug":
				var msgs = console.getMsgs(true);
				for (var i=0; i<msgs.length; i++) {
					var msg = msgs[i];
					$("ul.console").append($("<li class='" + msg.type + "'><span>" + 
						formatTime(msg.time) + "</span>" + formatType(msg.value) + "</li>"));
				}
				break;
			case "plotDay":
				setPlotMode("day");
				updatePlot();
				break;
			case "plotMonth":
				setPlotMode("month");
				updatePlot();
				break;
			case "plotYear":
				setPlotMode("year");
				updatePlot();
				break;
		}

		jPM.close();
	});
}

function initializeSettings() {
	setPlotMode(cache.get("plot.mode") || "day");
}

function refreshData() {
	updateWeather();
	updatePlot();
	updateChannels();
}

function resetCache() {
	cache.put("channels", "");
	cache.put("totals", "");
	cache.put("perf", "");
	cache.put("consumption", "");
}

$(document).ready(function() {
	// setup
	icons = new Skycons();
	// instantiate plot library by name - either RickshawD3 or Flot 
	plot = new RickshawD3($("#chart"));
	// add progress bar + menu
	createMenu();
	createProgressBar();

	initializeSettings();

	// use cache?
	var hash = getChannelHash(); // get before initializeChannels
	if (options.cache) {
		var json = cache.get("channels", hash);
		if (json) {
			initializeChannels(json);
			return;
		}
	}

	// fallback to default initialization by reading channel meta data
	var url = vzAPI +"/channel.json?padding=?";
	$.getJSON(url, function(json) {
		initializeChannels(json);
		cache.put("channels", json, hash); // use hash from before initializeChannels
	}).fail(failHandler(url, "init"));
});

</script>

</body>
</html>

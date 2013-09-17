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
    <?php if ($browser == 'iphone') { ?><script type="text/javascript" src="js/console.js"></script><?php } ?>

	<!-- plotting -->
	<script type="text/javascript" src="js/plot.js"></script>
	<script type="text/javascript" src="js/rickshaw/d3.v2.min.js"></script>
	<script type="text/javascript" src="js/rickshaw/rickshaw.min.js"></script>
	<script type="text/javascript" src="js/rickshaw/bullet.js"></script>
	
	<link rel="stylesheet" href="css/rickshaw.min.css" type="text/css" />

	<style type="text/css">
		@media screen and (/*orientation:landscape*/ min-width: 480px) {
			.row {
			    max-width: 480px !important; }
			.large-2 {
				width: 50%; 
				position: relative; 
				float: left; }
			#weather-icon {
				width: 42px !important;
				height: 42px !important; }
			#chart, #chart > svg {
				width: 220px !important;
				height: 144px !important; }
			#perf, #perf > svg {
				width: 220px !important; 
				height: 36px;}

			h1, h2 > span, h3 > span, #weather-text > div {
				font-size: 18px !important; }
			h2 {
				font-size: 54px !important; }
			h3 {
				font-size: 26px !important; }
		}
/*
		.row {
			border: 1px solid red; }*/

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
			<?php if ($browser == 'iphone') { ?><li><a href="#" panel="#console" id="debug">Console</a></li><?php } ?>
			<li><a href="#" panel="#main" id="reset">Reset</a></li>
		</ul>
	</nav>

	<!-- 
		Templates (hidden)
	-->
	<div class="hidden">
		<div id="templateWeather">
			<h2 class="small-2">{{currently.temperature}}<span>° {{currently.summary}}</span></h2>
		</div>
		<div id="templateData">
			<div id="{{name}}" class="row hidden">
				<h1>{{title}}</h1>
				<h2 id="{{name}}Now" class="small-2"><span>kW</span></h2><div class="small-2">
			 		<h3 id="{{name}}Today"><span>kWh today</span></h3>
			 	 	<h3 id="{{name}}Total"><span>kWh total</span></h3>
				</div>
			</div>
		</div>
		<div id="templateNow">
			<h2 class="small-2">{{value}}<span>{{unit}}</span></h2>
		</div>
		<div id="templateToday">
		 	<h3>{{value}}<span>{{unit}} today</span></h3>
		</div>
		<div id="templateTotal">
		 	<h3>{{value}}<span>{{unit}} total</span></h3>
		</div>
	</div>

	<!-- 
		Panels
	-->
	<div id="database" class="panel hidden">
		<h1>Database status</h1>
		<h3 id="dbSize">Size: - <span class="unit">MB</span></h3>
		<h3 id="dbRows">Rows: - <span class="unit">total</span></h3>
	</div>

	<div id="console" class="panel hidden">
		<h1>Console</h1>
		<ul class="console"></ul>
	</div>

	<div id="main" class="panel">
		<div class="row">

			<div class="large-2">
				<!-- weather -->
				<div id="weather" class="row hidden">
				    <canvas id="weather-icon" width="54" height="54"></canvas>
					<div id="weather-text">
						<h2></h2>
						<div></div>
					</div>
				</div>

				<div class="row">
					<div id="chart"></div>
				</div>

				<div class="row">
					<div id="perf"></div>
				</div>
			</div>

			<div id="mainData" class="large-2">
<!--
				<div id="generation" class="row hidden">
					<h1>Generation</h1>
					<h2 id="generationNow" class="small-2"><span>kW</span></h2><div class="small-2">
				 		<h3 id="generationToday"><span>kWh today</span></h3>
				 	 	<h3 id="generationTotal"><span>kWh total</span></h3>
					</div>
				</div>

				<div id="bezug" class="row hidden">
					<h1>Usage</h1>
					<h2 id="bezugNow" class="small-2"><span>W</span></h2><div class="small-2">
				 		<h3 id="bezugToday"><span>kWh today</span></h3>
				 	 	<h3 id="bezugTotal"><span>kWh total</span></h3>
					</div>
				</div>

				<div id="lieferung" class="row hidden">
					<h1>Supply</h1>
					<h2 id="lieferungNow" class="small-2"><span>W</span></h2><div class="small-2">
				 		<h3 id="lieferungToday"><span>kWh today</span></h3>
				 	 	<h3 id="lieferungTotal"><span>kWh total</span></h3>
					</div>
				</div>
			</div>
-->
		</div>
	</div>

<script type="text/javascript">

var icons;			// forecast.io weather icons
var plot;			// chart abstraction
var jPM;			// menu

var uuid = {};		// UUIDs
var plotMode = "day";

/**
 * Get local weather and show
 */
function updateWeather() {
	console.info('[updateWeather]');

	// do the hard lifting
	var worker = function(json) {
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
	}

	// use cache?
	var valid = Math.floor(new Date().getTime()/1000/300); // 5min
	if (options.cache) {
		var json = cache.get("weather", valid);
		if (json) {
			worker(json);
			return;
		}
	}

	var url = weatherAPI + "&callback=?";
	$.getJSON(url, function(json) {
		worker(json);
		cache.put("weather", json, valid);
	}).fail(failHandler(url, "updateWeather"));
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
		console.info("[updateTotals] done");
	});

	return(deferred);
}

/**
 * Get todays channel values and show
 */
function updateCurrentValues() {
	console.info("[updateCurrentValues]");

	var worker = function(json) {
		for (var i=0; i<json.data.length; i++) {
			var channel = getChannelFromUUID(json.data[i].uuid);
			updateChannel(channel, {data: json.data[i]});
		}
	}

	// use cache?
	var valid = Math.floor(new Date().getTime() / (1000 * (options.updateInterval||1) * 60)); // 1min
	if (options.cache) {
		var json = cache.get("current", valid);
		if (json) {
			worker(json);
			return;
		}
	}

	// get data
	var templates = ["Now", "Today", "Total"];
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
		worker(json);
		cache.put("current", json, valid);
	}).fail(failHandler(url, "updateCurrentValues"));
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
		updatePerformance(channel);
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

function stackData(data) {
	console.info("[stackData]"); // + JSON.stringify(data));

	var channels = [];
	for (var channel in data) {
		channels.push(channel);
	}

	for (var i=0; i<data[channels[0]].data.tuples.length; i++) {
		var total = 0;
		for (var j=channels.length-1; j>=0; j--) {
			var channel = channels[j];
			// difference
			var diff = Math.max(data[channel].data.tuples[i][1] - total, 0);
			data[channel].data.tuples[i][1] = diff;
			total += diff;
		}
	}

	// sort result for stacked bar chart
	var res = {};
	for (var j=channels.length-1; j>=0; j--) {
		res[channels[j]] = data[channels[j]];
	}
	return(res);
}

/**
 * Get plot data and show
 */
function updatePlot() {
	var mode = plotMode; // local copy
	console.info("[updatePlot] " + mode);

	var date = new Date();
	var valid = (mode == "day") ? Math.floor(date.getTime()/1000/300) : currentDate();
	var consumption = mode == "year" || mode == "month";

	// use cache?
	if (options.cache) {
		var data = cache.get("consumption." + mode, valid);
		if (data) {
			plot.render(data, consumption);
			return;
		}
	}

	var range;
	if (mode == "year") {
		range = "from=1.1." + date.getFullYear() + "&to=today&group=month";
	}
	else if (mode == "month") {
		range = "from=1." + (date.getMonth()+1) +"."+ date.getFullYear() + "&to=today&group=day"; // January is 0! 
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

		var data = {};
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
					json.data[j].tuples[i][1] *= 24 * daysInMonth(d.getMonth(), d.getFullYear());
				}
				else if (mode == "month") {
					json.data[j].tuples[i][1] *= 24;
				}
			}

			// store
			data[getChannelFromUUID(json.data[j].uuid)] = {data: json.data[j]};
		}

		if (consumption) data = stackData(data);

		// sync UI - still desired?
		if (mode == plotMode) {
			plot.render(data, consumption);
		}

		// save after plotting
		cache.put("consumption." + mode, data, valid);
	}).fail(failHandler(url, "updatePlot"))
}

function updatePerformanceChart(power) {
	var perf = cache.get("perf");
	console.info("[updatePerformanceChart] " + formatNumber(perf.genToday) + "," + formatNumber(perf.genYesterday) + "," + formatNumber(perf.genMax));

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

function updatePerformance(channel) {
	var today = currentDate();
	var power = channels[channel].power;
	console.info("[updatePerformance] " + channel + " (" + power + "kWp)");

	// use cache?
	if (options.cache && cache.get("perf", today)) {
		updatePerformanceChart(power);
		return;
	}
	
    var from = "1.1." + new Date().getFullYear();
	var url = vzAPI + "/data/" + uuid[channel] + ".json?padding=?&client=raw&from=" + from + "&to=today&group=day";
	$.getJSON(url, function(json) {
		if (typeof json.data.tuples == "undefined") {
			console.error("[updatePerformance] No current data.tuples for channel " + channel);
			return;
		}
		if (typeof json.data.consumption == "undefined") {
			console.error("[updatePerformance] No current data.consumption for channel " + channel);
			return;
		}
		console.info("[updatePerformance] got daily data (" + json.data.tuples.length + " days)");

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

		updatePerformanceChart(power);
	}).fail(failHandler(url, "updatePerformance"));
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
	console.info("[initializeChannels] " + JSON.stringify(json));

	// get UUIDs for defined channels
	for (var channel in channels) {
		// console.info("Channel " + channel);
		uuid[channel] = getUUID(json, channels[channel].name);
		console.info("[initializeChannels] UUID " + uuid[channel] + " " + channel);

		if (channels[channel].totalValue !== "undefined") {
			$("#mainData").append(Mustache.render($("#templateData").html(), {name: channel, title: channels[channel].name}));
		}
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
	
	// year -> #plotYear
	var target = "#plot" + mode.charAt(0).toUpperCase() + mode.slice(1);
	$("#jPanelMenu-menu li").removeClass("active");
	$(target).closest("li").addClass("active");

	plotMode = mode;
	cache.put("plot.mode", mode);
}

function createMenu() {
	jPM = $.jPanelMenu({
		direction: "right",
		openPosition: 95,
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
		}
		else {
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
			case "plotMonth":
			case "plotYear":
				var target = $(this).attr("id").substring(4).toLowerCase();
				setPlotMode(target);
				updatePlot();
				break;
		}

		jPM.close();
	});
}

function resetCache() {
	cache.put("channels", "");
	cache.put("totals", "");
	cache.put("perf", "");
	cache.put("consumption", "");
}

function refreshData() {
	updateWeather();
	updatePlot();
	updateCurrentValues();
}

function initializeSettings() {
	setPlotMode(cache.get("plot.mode") || "day");

	var worker = function(json) {
		initializeChannels(json);

		// listen to resize
		$(window).bind('orientationchange resize', function(event) {
			refreshData();
		});
	}

	// use cache?
	var hash = getChannelHash(); // get before initializeChannels
	if (options.cache) {
		var json = cache.get("channels", hash);
		if (json) {
			worker(json);
			return;
		}
	}

	// fallback to default initialization by reading channel meta data
	var url = vzAPI +"/channel.json?padding=?";
	$.getJSON(url, function(json) {
		worker(json);
		cache.put("channels", json, hash); // use hash from before initializeChannels
	}).fail(failHandler(url, "init"));
}

$(document).ready(function() {
	// setup
	icons = new Skycons();
	plot = new RickshawD3($("#chart")); // instantiate plot library by name - either RickshawD3 or Flot

	createMenu();
	createProgressBar();
	initializeSettings();
});

</script>

</body>
</html>

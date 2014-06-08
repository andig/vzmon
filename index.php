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
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

	<link rel="apple-touch-icon" sizes="57x57" href="img/home-57.png" />
	<link rel="apple-touch-icon" sizes="72x72" href="img/home-72.png" />
	<link rel="apple-touch-icon" sizes="114x114" href="img/home-114.png" />

	<!-- Startup images https://gist.github.com/tfausak/2222823 -->

	<!-- iPhone - iPod touch - 320x460 -->
	<link rel="apple-touch-startup-image" href="img/startup.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 1)">
	<!-- iPhone - iPod (Retina) - 640x920 -->
	<link rel="apple-touch-startup-image" href="img/startup@2x.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2)">
	<!-- iPhone 5 - iPod 5 (Retina) - 640x1096 -->
	<link rel="apple-touch-startup-image" href="img/startup-568h@2x.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2)">

	<style type="text/css">
		/* iPhone-specific styles */
		.standalone {
			margin: 0!important;
			padding: 0!important;
			height: 20px!important; /* iOS7 WebApp */
			background-color: #ccc;
		}
		.jPanelMenu-panel {
			padding: 0!important;
		}
		.menu-trigger a span {
			top: 22px!important;
		}
	</style>
<?php } else { ?>
	<meta name="viewport" content="width=device-width" />
<?php } ?>
	<link rel="shortcut icon" href="img/favicon.ico" type="image/ico" />

	<!-- css -->
	<link rel="stylesheet" href="css/vzmon.css" type="text/css" />

	<!-- js -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.js"></script>

	<script type="text/javascript" src="js/skycons.js"></script>
	<script type="text/javascript" src="js/moment.min.js"></script>

	<script type="text/javascript" src="js/options.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>

	<!-- libraries -->
	<script type="text/javascript" src="js/nprogress.js"></script>
	<script type="text/javascript" src="js/jquery.jpanelmenu.min.js"></script>
    <?php if ($browser == 'iphone') { ?><script type="text/javascript" src="js/console.js"></script><?php } ?>

	<!-- plotting -->
	<script type="text/javascript" src="js/plot.js"></script>
<!--
	<script type="text/javascript" src="js/rickshaw/d3.v2.min.js"></script>
	<script type="text/javascript" src="js/rickshaw/rickshaw.min.js"></script>
-->
	<script type="text/javascript" src="js/rickshaw/d3.v2.js"></script>
	<script type="text/javascript" src="js/rickshaw/rickshaw.js"></script>
	<script type="text/javascript" src="js/rickshaw/bullet.js"></script>

	<link rel="stylesheet" href="css/rickshaw.min.css" type="text/css" />

	<style type="text/css">
		@media screen and (/*orientation:landscape*/ min-width: 480px) {
			.row, .panel {
			    max-width: 480px !important; }
			.large-2 {
				width: 50%;
				padding: 0 10px;
				float: left; }
			#weather-icon {
				width: 42px !important;
				height: 42px !important; }
			#chart {
				width: 220px !important;
				height: 155px !important; }
			#perf {
				width: 220px !important;
				height: 36px !important; }

			nav ul {
				font-size: 18px; }

			h1, h2 > span:not(.value), h3 > span:not(.value) {
				font-size: 18px !important; }
			h2 {
				font-size: 58px !important;
				line-height: 1.2 !important; }
			h3 {
				font-size: 26px !important; }
		}

		.row {
			/*background-color: #888888;*/
			/*border: 1px solid red;*/ }

		/* Header */
		header.primary { height: 49px; color: #333; background-color: #dadada; position: relative; border-bottom: solid 1px #f8f8f8; border-radius: 3px 3px 0 0;}
		header.primary .icon { z-index: 22; position: absolute; top: 0; left: 0; height: 49px; width: 49px; font-size: 26px; line-height: 49px; text-align: center; color: #333; }
		header.primary .side { left: auto; right: 0; }
		header.primary hgroup { text-align: center }
		header.primary hgroup h1 { z-index: 20; position: absolute; top: 0; left: 50px; right: 50px; color:#333; margin: 0; line-height: 49px; margin: 0; font-size: 20px; }
	</style>
</head>

<body>
	<div class="standalone"></div>
	<div class="menu-trigger">
		<a href="#"><span>&#9776;</span></a>
	</div>

	<nav id="menu" class="hidden">
		<div class="standalone"></div>
		<ul>
			<li class="section">Plant</li>
			<li class="active" section="plant"><a href="#">Default</a></li>

			<li class="section">Range</li>
			<li section="range" class="active"><a href="#" id="day">Day</a></li>
			<li section="range"><a href="#" id="month">Month</a></li>
			<li section="range"><a href="#" id="year">Year</a></li>

			<li class="section">Mode</li>
			<li section="mode" class="active"><a href="#" id="fixed">Fixed</a></li>
			<li section="mode"><a href="#" id="rolling">Rolling</a></li>

			<li class="section">Functions</li>
			<!-- <li><a href="#" panel="#database">Database</a></li> -->
			<?php if ($browser == 'iphone') { ?><li><a href="#" panel="#console" id="debug">Console</a></li><?php } ?>
			<li><a href="#" id="reset">Reset</a></li>
		</ul>
	</nav>

	<!--
		Panels
	-->
	<div id="main" class="panel">
		<div class="large-2">
			<div id="weather" class="hidden">
			    <canvas id="weather-icon" width="54" height="54"></canvas>
				<h2 class="small-2"><span class="value"></span><span>° </span><span class="summary"></span></h2>
			</div>

			<div id="chart"></div>

			<div id="perf"></div>
		</div>

		<div id="data" class="large-2">
			<div class="template">
				<h1 class="title"></h1>
				<h2 class="small-2 now hidden"><span class="value"></span><span class="unit"></span></h2><div class="small-2">
					<h3 class="today hidden"><span class="value"></span><span class="unit"></span><span> today</span></h3>
					<h3 class="total hidden"><span class="value"></span><span class="unit"></span><span> total</span></h3>
				</div>
			</div>
		</div>
	</div>

	<div id="database" class="panel hidden">
		<h1>Database status</h1>
		<h3 id="dbSize">Size: - <span class="unit">MB</span></h3>
		<h3 id="dbRows">Rows: - <span class="unit">total</span></h3>
	</div>

	<div id="console" class="panel hidden">
		<h1>Console</h1>
		<ul class="console">
			<li class="template hidden"><span class="time">{{time}}</span><span class="value">{{value}}</span></li>
		</ul>
	</div>

<script type="text/javascript">

var icons;			// forecast.io weather icons
var plot;			// chart abstraction
var jPM;			// menu

var plotRange = "day";
var plotMode = "fixed";

/**
 * Get local weather and show
 */
function updateWeather() {
	// do the hard lifting
	var worker = function(json) {
		json.currently.temperature = Math.round(json.currently.temperature);

		if (typeof json.daily.data[0] !== "undefined") {
			// console.debug("[updateWeather] sunrise/sunset: " +
			// 	moment.unix(json.daily.data[0].sunriseTime).format(options.formats.time) +"/"+
			// 	moment.unix(json.daily.data[0].sunsetTime).format(options.formats.time));

			options.sunriseTime = moment.unix(json.daily.data[0].sunriseTime).subtract('hours',1).format(options.formats.time);
			options.sunsetTime = moment.unix(json.daily.data[0].sunsetTime).add('hours',1).format(options.formats.time);

			// xaxis
			options.plot.xaxis.min = Math.floor(json.daily.data[0].sunriseTime / 3600) * 3600 * 1000;
			options.plot.xaxis.max = Math.ceil(json.daily.data[0].sunsetTime / 3600) * 3600 * 1000;
		}

		$("#weather .value").html(json.currently.temperature);
		$("#weather .summary").html(json.currently.summary);
		$("#weather").removeClass('hidden');

		icons.set($("#weather-icon").get(0), mapWeatherIcon(json.currently.icon));
		if (options.animate) icons.play();
	}

	// sanity check
	if (typeof weatherAPI == "undefined" || !API_KEY) {
		console.error('[updateWeather] API_KEY not defined. Check js/options.js.');
		return;
	}

	var hash = Math.floor(new Date().getTime()/1000/600); // 10min
	var url = weatherAPI + "&callback=?";
	var deferred = fetchCached("vzmon.weather", worker, function() {
		return $.getJSON(url).fail(failHandler(url, "updateWeather"));
	}, hash);
	if (deferred.promise) {
		console.debug('[updateWeather]');
	}
}

/**
 * Get database status
 */
function updateDatabaseStatus() {
	var url = vzAPI + "/capabilities/database.json?padding=?";
	$.getJSON(url, function(json) {
		console.debug("[updateDatabaseStatus]" + json.capabilities.database);
		$("#database").html(Mustache.render($("#templateDatebase").html(), {
			dbrows: formatNumber(json.capabilities.database.rows / 1000, {decimals:0, si:false}),
			dbsize: formatNumber(json.capabilities.database.size / 1024 / 1024, {decimals:1, si:false})
		}));
	}).fail(failHandler(url, "updateDatabaseStatus"));
}

/**
 * Show total if daily total is available
 */
function showTotals(channel, consumption) {
	var today = moment().startOf("day").format(options.formats.date);
	if ((channel.total || {}).atDate == today) {
		var n = formatNumber(Math.abs(channel.total.value || 0) * 1000.0 + Math.abs(consumption), options.numbers.totals);
		$("#channel-" + channel.uuid + " .total .value").html(n.value);
		$("#channel-" + channel.uuid + " .total .unit").html(n.unit);
		$("#channel-" + channel.uuid + " .total").show();
	}
}

/**
 * Get total values
 */
function updateTotals() {
	var deferred = [];
    var today = moment().startOf("day").format(options.formats.date);

	channels.forEach(function(channel) {
		// channel without defined total
		if (typeof (channel.total || {}).value == "undefined") return;

		var totalStorage = (options.cache) ? cache.get("vzmon.totals." + channel.uuid) : false;
		if (totalStorage) {
			console.debug("[updateTotals] " + channel.name + " " +
				channel.total.atDate + ":" + channel.total.value + " " +
				totalStorage.atDate + ":" + totalStorage.value);
			channel.total.value = totalStorage.value;
			channel.total.atDate = totalStorage.atDate;
	    }

		// display
		if (channel.total.atDate == today) {
			showTotals(channel,	$("#channel-" + channel.uuid + " .today .value").html());
			return;
		}

		// do a delta update of the totals group=day to enforce use of aggregation table
		var url = vzAPI + "/data/" + channel.uuid + ".json?padding=?&options=exact&from=" + channel.total.atDate + "&to=today&group=day&tuples=1";
		$.getJSON(url, function(json) {
			channel.total.value = Math.abs(channel.total.value || 0) + Math.abs(json.data.consumption) / 1000.0;
			channel.total.atDate = today;

			// save
			cache.put("vzmon.totals." + channel.uuid, channel.total);
			console.debug("[updateTotals] " + channel.name + " " + JSON.stringify(channel.total));

			// display
			showTotals(channel, $("#channel-" + channel.uuid + " .today .value").html());
		}).fail(failHandler(url, "updateTotals"))
	});
}

/**
 * Get todays channel values and show
 */
function updateCurrentValues(initial) {
	console.debug("[updateCurrentValues]");

	var workerNow = function(json) {
		console.log("[updateCurrentValues.workerNow]");
		var to = 0;
		for (var i=0; i<json.data.length; i++) {
			var channel = filterProperties(channels, 'uuid', json.data[i].uuid) ;
			var data = json.data[i];

			if (typeof data.tuples == "undefined") {
				console.error("[updateChannel] No current data.tuples for channel " + channel.name);
				return;
			}

			var n = formatNumber(Math.abs(data.tuples[data.tuples.length-1][1]), options.numbers.current);
			$("#channel-" + channel.uuid + " .now .value").html(n.value);
			$("#channel-" + channel.uuid + " .now .unit").html(n.unit);
			$("#channel-" + channel.uuid + " .now").show();

			to = Math.max(to, data.to);
		}
 		cache.put("vzmon.heartbeat", to);
	}

	var workerToday = function(json) {
		console.log("[updateCurrentValues.workerToday]");
		for (var i=0; i<json.data.length; i++) {
			var channel = filterProperties(channels, 'uuid', json.data[i].uuid) ;
			var data = json.data[i];

			if (typeof data.consumption == "undefined") {
				console.error("[updateChannel] No current data.consumption for channel " + channel.name);
				return;
			}

			// see if power rating desired
			if (channel.power > 0) {
				cache.put("vzmon.perf.genToday", Math.abs(data.consumption) / 1000.0);
				updatePerformance(channel);
			}

			var n = formatNumber(Math.abs(data.consumption), options.numbers.consumption);
			$("#channel-" + channel.uuid + " .today .value").html(n.value);
			$("#channel-" + channel.uuid + " .today .unit").html(n.unit);
			$("#channel-" + channel.uuid + " .today").show();

			showTotals(channel, data.consumption);
		}
	}

	var workerDailyTotalAndPlot = function() {
		console.log("[updateCurrentValues.workerDailyTotalAndPlot]");
		// get data if the channel us used
		var url = vzAPI + "/data.json?padding=?&from=today&to=now&group=hour&tuples=1" + channels.map(function(channel) {
			return (typeof channel.total !== "undefined") ? "&uuid[]=" + channel.uuid : null;
		}).join('');

		fetchCached("vzmon.currenttoday", workerToday, function() {
			return $.getJSON(url).fail(failHandler(url, "updateCurrentValues.Today"));
		}/*, hash*/);

		updatePlot();
	}

	// use cache?
	var hash = Math.floor(new Date().getTime() / ((options.updateInterval||60) * 1000)); // 1min
	// get data if the channel us used
	var url = vzAPI + "/data.json?padding=?&from=now" + channels.map(function(channel) {
		return "&uuid[]=" + channel.uuid;
	}).join('');

	var heartbeat = cache.get("vzmon.heartbeat");
	var deferred = fetchCached("vzmon.currentnow", workerNow, function() {
		return $.getJSON(url).fail(failHandler(url, "updateCurrentValues.Now"));
	}/*, hash*/);

	if (initial) {
		workerDailyTotalAndPlot();
	}
	else if (deferred.promise) { // instanceof Deferred
		deferred.done(function() {
			if (heartbeat !== cache.get("vzmon.heartbeat")) {
				console.log('<heartbeat>');
				workerDailyTotalAndPlot();
			}
		});
	}
}

/**
 * Get plot data and show
 */
function updatePlot() {
	var range = plotRange, mode = plotMode; // local copy
	console.log("[updatePlot] " + range);

	var hash = (range == "day") ? null : moment().startOf("day").format(options.formats.date);
	var consumption = range == "year" || range == "month";

	var workerPlot = function(json) {
		console.log("[updatePlot.workerPlot]");

		var data = [];
		for (var j=0; j<json.data.length; j++) {
			if (typeof json.data[j].tuples == "undefined") {
				console.error("[updatePlot] No tuples for channel " + json.data[j].uuid);
				continue;
			}

			// conversion
			json.data[j].tuples.forEach(function(tuple) {
				tuple[0] /= 1000;
				tuple[1] = Math.abs(tuple[1]);
				// kWh conversion
				if (range == "year") {
					tuple[1] *= 24 * moment.unix(tuple[0]).daysInMonth();
				}
				else if (range == "month") {
					tuple[1] *= 24;
				}
			});

			// convert result
			data[getChannelFromUUID(json.data[j].uuid)] = json.data[j].tuples;
		}
		// if (consumption) data = stackData(data);

		// sync UI - still desired?
		if (range == plotRange && mode == plotMode) {
			plot.render(data, consumption);
		}
	}

	var dataRange;
	// TODO fix period start
	if (range == "year") {
		var from = (mode == "fixed") ?
						moment().startOf('year').subtract('hours', 1) :
						moment().subtract('years', 1).startOf('month').subtract('hours', 1);
		dataRange = "from=" + from.format(options.formats.date) + "&to=now&group=month";
	}
	else if (range == "month") {
		var from = (mode == "fixed") ?
						moment().startOf('month').subtract('hours', 1) :
						moment().subtract('months', 1).startOf('day').subtract('hours', 1);
		dataRange = "from=" + from.format(options.formats.date) + "&to=now&group=day"; // January is 0!
	}
	else {
		var from = (mode == "fixed") ?
						moment(options.sunriseTime, options.formats.time) :
						moment().subtract('hours', 24);
		// calculate tuples to preserve similar appearance independent from data amount
		var tuples = Math.max(Math.round(moment().diff(from) * (options.plot.tuples || 200) / (24 * 3600000)), 5);
		dataRange = "from=" + from.format(options.formats.dateTime) + "&to=now&tuples=" + tuples;
	}

	// generate compound request for all channels
	var url = vzAPI + "/data.json?padding=?&options=exact&" + dataRange + channels.map(function(channel) {
		return (typeof channel.plot !== "undefined") ? "&uuid[]=" + channel.uuid : null;
	}).join('');

	fetchCached("vzmon.consumption." + range + "." + mode, workerPlot, function() {
		return $.getJSON(url).fail(failHandler(url, "updateCurrentValues.Now"));
	}, hash);
}

function updatePerformanceChart(power) {
	var perf = cache.get("vzmon.perf");
	console.debug("[updatePerformanceChart] " + formatNumber(perf.genToday) + "," + formatNumber(perf.genYesterday) + "," + formatNumber(perf.genMax));

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
	var today = moment().startOf("day").format(options.formats.date);
	var power = channel.power;
	console.debug("[updatePerformance] " + channel.name + " (" + power + "kWp)");

	// use cache?
	if (options.cache && cache.get("vzmon.perf", today)) {
		updatePerformanceChart(power);
		return;
	}

	var from = "1.1." + new Date().getFullYear();
	var url = vzAPI + "/data/" + channel.uuid + ".json?padding=?&options=exact&from=" + from + "&to=today&group=day";
	$.getJSON(url, function(json) {
		if (typeof json.data.tuples == "undefined") {
			console.error("[updatePerformance] No current data.tuples for channel " + channel.name);
			return;
		}
		console.debug("[updatePerformance] got daily data (" + json.data.tuples.length + " days)");

		// remove current day remainder - not needed if query to=today
		// json.data.tuples.pop();
		// find best day this year
		var genMax = json.data.tuples.reduce(function(previousValue, currentValue, index, array) {
			var perf = Math.abs(currentValue[1]) * 24 / 1000.0;
			if (perf > (options.maxPerf || 8.0) * power) perf = previousValue;
			return Math.max(previousValue, perf);
		}, 0);

		// get yesterday's value from end of data
		var genYesterday = (json.data.tuples.length > 0) ? Math.abs(json.data.tuples[json.data.tuples.length-1][1]) * 24 / 1000.0 : 0;

		// save
		cache.put("vzmon.perf", {
			genMax: genMax,
			genYesterday: genYesterday,
			genToday: cache.get("vzmon.perf.genToday")
		}, today);

		updatePerformanceChart(power);
	}).fail(failHandler(url, "updatePerformance"));
}

function createProgressBar() {
	if (typeof NProgress !== 'undefined') {
		NProgress.numCalls = NProgress.completedCalls = 0;
		NProgress.advance = function() {
			NProgress.completedCalls++;
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

function setPlotRange(range, mode) {
	console.debug("[setPlotRange] " + range +" "+ mode);
	plotRange = range;
	plotMode = mode;
	cache.put("vzmon.plot.range", range);
	cache.put("vzmon.plot.mode", mode);
}

function refreshData(initial) {
	updateWeather();
	updateCurrentValues(initial);
}

function selectMenu(el) {
	console.debug("[selectMenu] " + $(el).attr("id"));
	var section = $(el).closest("li").attr("section");
	if (section) {
		$("#jPanelMenu-menu li[section=\""+section+"\"]").removeClass("active");
		$(el).closest("li").addClass("active");
	}
	return(el);
}

function createMenu() {
	jPM = $.jPanelMenu({
		direction: "right",
		openPosition: 110,
		before: function() {
			icons.pause();
		},
		after: function() {
			if (options.animate) icons.play();
		},
	});
	jPM.on();
	// jPM.open();
	// jPM doesn't remove original menu...
	$("#menu").remove();

	// defaults
	setPlotRange(cache.get("vzmon.plot.range") || "day", cache.get("vzmon.plot.mode") || "fixed");
	selectMenu("#" + plotRange);
	selectMenu("#" + plotMode);
	// if (window.navigator.standalone) { // fullscreen mode }

	// handlers
	$("#jPanelMenu-menu a").click(function() {
		// make menu target visible
		var panel = $(this).attr("panel") || "#main";
		$(".panel").hide();
		$(panel).show();

		selectMenu(this);

		switch ($(this).attr("id")) {
			case "reset":
				cache.put("vzmon", "");
				refreshData(true);
				break;
			case "debug":
				var msgs = console.getMsgs(true);
				for (var i=0; i<msgs.length; i++) {
					var msg = msgs[i];
					$('#console .template').clone().appendTo('#console ul').addClass(msg.type + " msg");
					$('#console .msg .time').html(formatTime(msg.time));
					$('#console .msg .value').html(formatType(msg.value));
					$('#console .msg').removeClass('template hidden msg');
				}
				break;
			case "day":
			case "month":
			case "year":
				setPlotRange($(this).attr("id"), plotMode);
				updatePlot();
				break;
			case "fixed":
			case "rolling":
				setPlotRange(plotRange, $(this).attr("id"));
				updatePlot();
				break;
		}

		jPM.close();
	});
}

function initializeSettings() {
	var worker = function(json) {
		// get UUIDs for defined channels
		channels.forEach(function(channel) {
			channel.uuid = filterProperties(json.channels, 'title', channel.name).uuid;
			console.debug("[initializeSettings] " + channel.name + " " + channel.uuid);
			// add channel UI
			if (typeof channel.total !== "undefined") {
				$('#data .template').clone().appendTo('#data').attr('id', 'channel-' + channel.uuid).removeClass('template');
				$('#channel-' + channel.uuid + ' .name').html(channel);
				$('#channel-' + channel.uuid + ' .title').html(channel.name);
			}
		});

		// run update
		updateTotals();
		refreshData(true);
		setInterval(refreshData, (options.updateInterval||60) * 1000); // 60s

		// assign to button
		$("#refresh").click(refreshData);

		// listen to resize
		$(window).bind('orientationchange resize', function(event) {
		    if (this.resizeTimeOut) clearTimeout(this.resizeTimeOut);
		    this.resizeTimeOut = setTimeout(function() {
		        $(this).trigger('resizeEnd');
		    }, 250);
		});

		$(window).bind('resizeEnd', function() {
			// resize finished - fix calling refresh during requests
			if (!(NProgress||{}).numCalls) {
				refreshData();
			}
		});
	}

	var hash = getChannelHash(); // get before initializeChannels
	var url = vzAPI +"/channel.json?padding=?";
	fetchCached("vzmon.channels", worker, function() {
		return $.getJSON(url).fail(failHandler(url, "initializeSettings"));
	}, hash);
}

/**
 * Cache-aware fetching of network ressources
 *
 * @param key		cache key
 * @param worker	function to call after successful data retrieval
 * @param deferred	function to call when cached data unavailable
 * @param hash		hash to determine cached data validity
 */
function fetchCached(key, worker, deferred, hash) {
	if (hash) {
		var json = cache.get(key, hash);
		if (json) {
			worker(json);
			return true;
		}
	}

	return deferred().done(function(json) {
		cache.put(key, json, hash);
		worker(json);
	});
}

$(document).ready(function() {
	// setup
	icons = new Skycons();
	plot = new RickshawD3($("#chart")); // instantiate plot library

	<?php if ($browser == 'iphone') { ?>
	// if (navigator.standalone) $('body').addClass('standalone');
	<?php } ?>

	createMenu();
	createProgressBar();
	initializeSettings();
});

</script>

</body>
</html>

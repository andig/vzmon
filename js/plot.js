/**
 * VZmon plot library abstration layer
 *
 * Currently supported plotting libraries are flot and rickshaw
 */

/**
 * Charting prototype object
 */
function Plot(element) {
	this.element = element;
}

Plot.prototype = {
	render: function(data) {}
}


/**
 * Flot charting implementation
 */
function Flot(element) {
	// Call the parent constructor
	Plot.call(this, element);

	plotOptions.yaxis.tickFormatter = this.unitFormatter;
	$.plot(element, [{data:[]}], plotOptions);
}

Flot.prototype = new Plot();

Flot.prototype.constructor = Flot;

Flot.prototype.unitFormatter = function(v, axis) {
	return (v > 0) ? formatNumber(v/1000.0, {decimals: 1}) + 'kW' : '';
}

Flot.prototype.render = function(data) {
	// use sorted data for building plot series
	var series = [];

	// use sorted data for building plot series
	for (var channel in data) {
		if (typeof channels[channel].plotOptions == 'undefined' || typeof data[channel] == "undefined") continue;

		var s = { data: data[channel].data.tuples };
		// fuse series plot options
		for (var prop in channels[channel].plotOptions) {
			s[prop] = channels[channel].plotOptions[prop];
		}
		series.push(s);
	}

	$.plot($(this.element), series, plotOptions);
}


/**
 * Rickshaw/D3 charting implementation
 */

function RickshawD3(element) {
	// Call the parent constructor
	Plot.call(this, element);

	// custom renderer - solves https://github.com/shutterstock/rickshaw/issues/121
	//						and https://github.com/shutterstock/rickshaw/issues/277
	Rickshaw.namespace('Rickshaw.Graph.Renderer.UnstackedArea');
	Rickshaw.Graph.Renderer.UnstackedArea = Rickshaw.Class.create(Rickshaw.Graph.Renderer.Area, {
		name: 'unstackedarea',
		defaults: function($super) {
			return Rickshaw.extend($super(), {
				unstack: true,
				fill: false,
				stroke: false
			});
		}
	} );
}

RickshawD3.prototype = new Plot();

RickshawD3.prototype.constructor = RickshawD3;

RickshawD3.prototype.unitFormatterkW = function(v) {
	return (v > 0) ? formatNumber(v/1000.0, {decimals: 1}) + 'kW' : '';
}

RickshawD3.prototype.unitFormatterkWh = function(v) {
	return (v > 0) ? formatNumber(v/1000.0, {decimals: 0}) + 'kWh' : '';
}

RickshawD3.prototype.render = function(data, consumption) {
	// use sorted data for building plot series
	var series = [];

	function mapVZtoRS(tuple) {
		return { x: tuple[0]/1000.0, y: tuple[1] }
	}

	for (var channel in channels) {
		if (typeof channels[channel].plotOptions == 'undefined' || typeof data[channel] == "undefined") continue;

		var stroke = (typeof channels[channel].plotOptions.color == 'undefined') ? null : channels[channel].plotOptions.color;
		var color = (typeof channels[channel].plotOptions.lines.fillColor == 'undefined' || !channels[channel].plotOptions.lines.fill) ? 'none' : channels[channel].plotOptions.lines.fillColor;
		var interpolation = (typeof channels[channel].plotOptions.lines.interpolation == 'undefined') ? 'cardinal' : channels[channel].plotOptions.lines.interpolation;

		series.push({
			name: channels[channel].name,
			data: data[channel].data.tuples.map(mapVZtoRS),
			stroke: (stroke) ? stroke : 'steelblue',
			color: (color) ? color : 'steelblue',
			// TODO fixme
			interpolation: interpolation,
		});
	}

	// build graph
	$(this.element).html("");
	var graph = new Rickshaw.Graph( {
		element: $(this.element).get(0), //document.querySelector('#chart'),
		width: $(this.element).width(),
		height: $(this.element).height(),
		renderer: (consumption) ? "bar" : Rickshaw.Graph.Renderer.UnstackedArea,
		stroke: true,
		//preserve: true,
		series: series
	});
	if (consumption) graph.interpolation = "linear";
	if (consumption) graph.renderer.unstack = true;
	graph.render();

	var xAxis = new Rickshaw.Graph.Axis.Time({
		graph: graph,
		orientation: 'bottom',
		timeFixture: new Rickshaw.Fixtures.Time.Local()
	});
	xAxis.render();

	var yAxis = new Rickshaw.Graph.Axis.Y({
		graph: graph,
		tickFormat: (consumption) ? this.unitFormatterkWh : this.unitFormatterkW,
	});
	yAxis.render();
}

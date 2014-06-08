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
		},
	});

	Rickshaw.namespace('Rickshaw.Graph.Renderer.CustomBars');
	Rickshaw.Graph.Renderer.CustomBars = Rickshaw.Class.create(Rickshaw.Graph.Renderer.Bar, { 
		name: 'custom-bars', 
		render: function($super, args) { 
			this.unstack = false;
			$super(args); 
			this.unstack = true;
		}
	});
}

RickshawD3.prototype = new Plot();

RickshawD3.prototype.constructor = RickshawD3;

RickshawD3.prototype.unitFormatterkW = function(v) {
	return (v > 0) ? formatNumber(v/1000.0, {decimals: 1}) + 'kW' : '';
}

RickshawD3.prototype.unitFormatterkWh = function(v) {
	return (v > 0) ? formatNumber(v/1000.0, {decimals: 0}) + 'kWh' : '';
}

RickshawD3.prototype.render = function(pdata, consumption) {
	var series = [];
	// in order of data provided
	pdata.forEach(function(data, index) {
		var channel = channels[index]; // channels and data use same indexing

		var stroke = (typeof channel.plot.color == 'undefined') ? null : channel.plot.color;
		var color = (typeof channel.plot.lines.fillColor == 'undefined' || !channel.plot.lines.fill) ? 'none' : channel.plot.lines.fillColor;
		var interpolation = (typeof channel.plot.lines.interpolation == 'undefined') ? 'cardinal' : channel.plot.lines.interpolation;

		series.push({
			name: channel.name,
			data: data.map(function(tuple) {
				return { x: tuple[0], y: tuple[1] }
			}),
			stroke: (stroke) ? stroke : 'steelblue',
			color: (color) ? color : 'steelblue',
			interpolation: (interpolation) ? interpolation : 'cardinal',
		});
	});

	// build graph
	$(this.element).html("");
	var graph = new Rickshaw.Graph({
		element: 	$(this.element).get(0), //document.querySelector('#chart'),
		width: 		$(this.element).width(),
		height: 	$(this.element).height(),
		series: 	series,
		renderer: 	(consumption) ? 'bar' : Rickshaw.Graph.Renderer.UnstackedArea,
		stroke: 	true,
		// preserve: true,
	});
	if (consumption) {
		graph.interpolation = 'linear';
		graph.interpolation = 'step-after';
		graph.superStack = true;
	}
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

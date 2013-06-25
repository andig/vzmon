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
	render: function(data, sorted) {}
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
	return formatNumber(v/1000.0, {decimals: 1}) + 'kW';
}

Flot.prototype.render = function(data, sorted) {
	// use sorted data for building plot series
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

	$.plot($(this.element), series, plotOptions);
}


/**
 * Rickshaw/D3 charting implementation
 */
function RickshawD3(element) { 
	// Call the parent constructor 
	Plot.call(this, element);
}

RickshawD3.prototype = new Plot(); 

RickshawD3.prototype.constructor = RickshawD3;

RickshawD3.prototype.unitFormatter = function(v) {
	return formatNumber(v/1000.0, {decimals: 1}) + 'kW';
}

RickshawD3.prototype.render = function(data, sorted) {
	// use sorted data for building plot series
	var series = [];

	function mapVZtoRS(tuple) {
		return { x: tuple[0]/1000.0, y: tuple[1] }
	}
	
	for (var channel in sorted) {
		var stroke = (typeof channels[channel].plotOptions.color == "undefined") ? null : channels[channel].plotOptions.color;
		var color = (typeof channels[channel].plotOptions.lines.fillColor == "undefined") ? null : channels[channel].plotOptions.lines.fillColor;

		series.push({
			name: channels[channel].name,
			data: data[channel].data.tuples.map(mapVZtoRS),
			color: (color) ? color : 'steelblue',
			stroke: (stroke) ? stroke : 'steelblue'
		});
	}

	// build graph
	$(this.element).html("");
	var graph = new Rickshaw.Graph( {
		element: $(this.element).get(0), //document.querySelector('#chart'),
		width: $(this.element).width(),
		height: $(this.element).height(),
		renderer: 'area',
		stroke: true,
		//preserve: true,
		series: series
	});
	// graph.forceY([0]);
	graph.renderer.unstack = true;
	graph.render();

	var xAxis = new Rickshaw.Graph.Axis.Time({
		graph: graph,
		orientation: 'bottom'
	});
	xAxis.render();

	var yAxis = new Rickshaw.Graph.Axis.Y({
		graph: graph,
		tickFormat: this.unitFormatter
	});
	yAxis.render();
}

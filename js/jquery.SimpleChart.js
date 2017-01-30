// Simple Graph JQuery Plugin
// By Robert Haddad (SmokinPuppy) @ 2014

var graph;
var c;
var xPadding = 30;
var yPadding = 30;
var xfixer = 0.98;
var yfixer = 0.98;
var data = [{
	values:[
		{X:5,Y:50},
		{X:10,Y:100}
	],
	color:"red",
	title:"Red Color"
},{
	values:[
		{X:1,Y:60},
		{X:6,Y:70}
	],
	color:"blue",
	title:"Blue Color"
}];


function isInt(n) {
   return typeof n === 'number';
}

function isFloat(n){
	return n % 1 == 0;
}
function drawAxes(graph,c,maxValX,maxValY,data){
	c.lineWidth = 2;
	c.strokeStyle = '#333';
	c.font = 'italic 8pt sans-serif';
	c.textAlign = "center";

	// Draw the axises
	c.beginPath();
	c.moveTo(xPadding, 0);
	c.lineTo(xPadding, graph.height - yPadding);
	c.lineTo(graph.width, graph.height - yPadding);
	c.stroke();

	// Draw the X value texts
	for(var i = 1; i <= maxValX; i++){ //for(var i = 0; i < data.values.length; i ++) {
	// uses data.values[i].X
		//c.fillText(data.values[i].X, getXPixel(data.values[i].X), graph.height - yPadding + 20);
		c.lineTo(i*xfixer, graph.width);
		c.fillText(i, getXPixel(i*xfixer,data,graph,maxValX), graph.height - yPadding + 20);

	}
	// Draw the Y value texts
	c.textAlign = "right"
	c.textBaseline = "middle";

	for(var i = 0; i <= 100; i += 10) {
		c.fillText(i, xPadding - 10, getYPixel(i*yfixer,data,graph,maxValY));
	}
}

function drawGraph(data,graph,c, color,maxValX,maxValY){
	c.strokeStyle = color;

	// Draw the line graph
	c.beginPath();
	c.moveTo(getXPixel(data.values[0].X*xfixer,data,graph,maxValX), getYPixel(data.values[0].Y,data,graph,maxValY));
	for(var i = 1; i < data.values.length; i ++) {
		c.lineTo(getXPixel(data.values[i].X*xfixer,data,graph,maxValX), getYPixel(data.values[i].Y,data,graph,maxValY));
	}
	c.stroke();

	// Draw the dots
	c.fillStyle = color;

	for(var i = 0; i < data.values.length; i ++) {
		c.beginPath();
		c.arc(getXPixel(data.values[i].X*xfixer,data,graph,maxValX), getYPixel(data.values[i].Y,data,graph,maxValY), 4, 0, Math.PI * 2, true);
		c.fill();
	}
}

// Returns the max Y value in our data list
function getMaxY(data) {
	var max = 0;

	for(var i = 0; i < data.values.length; i ++) {
		if(data.values[i].Y > max) {
			max = data.values[i].Y;
		}
	}

	max += 10 - max % 10;
	return max;
}

// Returns the max X value in our data list
function getMaxX(data) {
	var max = 0;

	for(var i = 0; i < data.values.length; i ++) {
		if(data.values[i].X > max) {
			max = data.values[i].X;
		}
	}

	max += 10 - max % 10;
	return max;
}

// Return the x pixel for a graph point
function getXPixel(val,data,graph,maxValX) {
	return ((graph.width - xPadding) / maxValX) * val + (xPadding);
}

// Return the y pixel for a graph point
function getYPixel(val,data,graph,maxValY) {
	return graph.height - (((graph.height - yPadding) / maxValY) * val) - yPadding;
}

(function( $ ) {

    $.fn.SimpleChart = function(options) {
 		var graph, c;
        // Here are the default options.
        var settings = $.extend({
            // These are the defaults.
			lineWidth: 2,				// Chart Line Width
			strokeColor: "#333",		// Axis Lines Color
			borderColor: "#333",		// Border Color
			borderWidth: 3,				// Border Width
            backgroundColor: "#FFF",	// Background color of chart
			backgroundImg: "images/monthly_grid.png", // Background Image for chart (i.e Grid Layout)
			data: data,					// Default plot
			title: "Simple Chart",		// Default Title of chart
			titleFontSize: 16,			// Title Font size
			titleColor: "#FFF",			// Title Color
			showTitle: true,			// If True show title
			titleBGColor: '#006',		// Title Background Color
			xTitle: "",					// Title of X Axis
			yTitle: "",					// Title of Y Axis
			xTitleBGColor: '#006',		// Title X Axis background Color
			yTitleBGColor: '#006',		// Title Y Axis background Color
			xFontSize: 16,				// X Axis Title Font Size
			yFontSize: 16, 				// Y Axis Title Font Size
			xTitleColor: "#000",		// X Axis Title Color
			yTitleColor: "#000",		// Y Axis Title Color
			maxValX: 12,				// Maximum value of data XAxis
			maxValY: 100,				// Maximum value of data YAxis
			width: 500,					// Width of Graph
			height: 300,				// Height of Graph
			margin: 20,					// Margin between each graph
			showKey: true,				// Show chart key
			toolTip: ''					// If set, this will use jQuery Tooltip
        }, options );

		var mainDivId = this.uniqueId(); // Generate Unique ID for container div
		var titleColor

		// Generate Bar with Options
		var useTitle = "";
		var useKey = "";
		var useBackground = "";
		if(settings.showTitle){
			useTitle = '<div class="graph_header">' + settings.title + '</div>';
		}
		if(settings.showKey){
			useKey = '<div class="graphKey"></div>';
		}
		if(settings.backgroundImg != ""){
			useBackground = 'style="background:url(' + settings.backgroundImg + ') no-repeat"';
		}
		this.append('<div class="graph">' +
				useTitle +
				'<div class="YAxis">' + settings.yTitle + ' </div>' +
				'<canvas width="' + settings.width + '" height="' + settings.height + '" class="main_graph" ' + useBackground + '></canvas>' +
				'<div class="XAxis">' + settings.xTitle + '</div>' +
				useKey +
   			'</div>');

		// Initiate Tool Tip
		if(settings.toolTip !== ''){
			$(mainDivId.selector).attr('title',settings.toolTip);
			$(mainDivId.selector).tooltip();
		}

		// Populate Key
		if(settings.showKey){
			for(i in settings.data){
				$(mainDivId.selector + ' .graphKey').append('<div class="input-color"><div class="color-box" style="background-color:' + settings.data[i].color + ';"></div><div class="name">' + settings.data[i].title + '</div></div>');
			}
		}

		// Generate CSS from options
		//Graph
		$(mainDivId.selector + ' .graph').css({
			'float':'left',
			'margin': settings.margin + 'px',
			'position': 'relative',
			'width': (settings.width + 90) + 'px',
			'height': (settings.height + 80) + 'px',
			'background': settings.backgroundColor
		});

		// Container
        this.css({
			'position': 'relative',
			'margin':'20px'
		});

		// Graph Header
		$(mainDivId.selector + ' .graph_header').css({
			'position':'absolute',
			'top':'0px',
			'left':'0px',
			'width': (settings.width + 24) + 'px',
			'height':'50px',
			'line-height':'50px',
			'font':'Georgia, "Times New Roman", Times, serif',
			'font-size': settings.titleFontSize + 'px',
			'font-weight':'bold',
			'text-align':'center',
			'border-top': settings.borderColor + ' solid ' + settings.borderWidth + 'px',
			'border-left':settings.borderColor + ' solid ' + settings.borderWidth + 'px',
			'border-right':settings.borderColor + ' solid ' + settings.borderWidth + 'px',
			'color': settings.titleColor,
			'background':settings.titleBGColor
		});

		// Main Graph
		$(mainDivId.selector + ' .main_graph').css({
			'border': settings.borderColor + ' solid ' + settings.borderWidth + 'px',
			'position':'absolute',
			'top':(50 + settings.borderWidth) + 'px',
			'left':'24px',
		});

		// YAxis
		$(mainDivId.selector + ' .YAxis').css({
			'position':'absolute',
			'top': (50 + settings.borderWidth) + 'px',
			'left':'0px',
			'width':'11px',
			'height': (settings.height + 20 + settings.borderWidth) +'px',
			'border-left':settings.borderColor + ' solid ' + settings.borderWidth + 'px',
			'border-bottom':settings.borderColor + ' solid ' + settings.borderWidth + 'px',
			'padding':'5px',
			'text-align':'center',
			'word-wrap': 'break-word',
			'color': settings.yTitleColor,
			'background':settings.yTitleBGColor
		});

		// XAxis
		$(mainDivId.selector + ' .XAxis').css({
			'position':'absolute',
			'top': (settings.height + 50 + settings.borderWidth) + 'px',
			'left':'24px',
			'height': (30 + settings.borderWidth) + 'px',
			'line-height':(30 + settings.borderWidth) + 'px',
			'width':(settings.width + settings.borderWidth) + 'px',
			'text-align':'center',
			'border-right':settings.borderColor + ' solid ' + settings.borderWidth + 'px',
			'border-bottom':settings.borderColor + ' solid ' + settings.borderWidth + 'px',
			'color': settings.xTitleColor,
			'background': settings.xTitleBGColor
		});

		//Graph Key Container
		$(mainDivId.selector + ' .graphKey').css({
			'position':'absolute',
			'top': (50 + settings.borderWidth) + 'px',
			'right':'0px',
			'width':'50px',
			'height': (settings.height + 23 + settings.borderWidth) + 'px',
		});

		// Graph Key Color Box
		$(mainDivId.selector + ' .graphKey .input-color').css({
    		'position': 'relative'
		});

		// Graph Key Title Padding
		$(mainDivId.selector + ' .graphKey .input-color .name').css({
    		'padding-left': '20px'
		});

		// Graph Key Color Box
		$(mainDivId.selector + ' .graphKey .color-box').css({
    		'width': '10px',
    		'height': '10px',
		    'display': 'inline-block',
		    'position': 'absolute',
		    'left': '5px',
		    'top': '5px',
		});

		graph = $($(this).selector + ' .main_graph')[0];
		c = graph.getContext('2d');
		drawAxes(graph,c,settings.maxValX,settings.maxValY,settings.data[0]);
		for(i in settings.data){
			drawGraph(settings.data[i],graph,c,settings.data[i].color,settings.maxValX,settings.maxValY);
		}
	}
}( jQuery ));
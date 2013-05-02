// path to your VZ middleware
var vzAPI = "http://localhost/middleware.php";

// path to forecast.io API
var API_KEY = ""; // goto forecast.io to obtain your own API key
var COORDINATES = "52.0,9.0";           // your geo coordinates - find out by using Google Maps

var weatherAPI = "https://api.forecast.io/forecast/" + API_KEY + "/" + COORDINATES + "?units=ca&exclude=flags,alerts,minutely";

// VZ channel information
var channels = {
  generation: {
    name: "Generation",
    totalValue: 8840.0,
    totalAtDate: "1.4.2013"
  }
  bezug: {
    name: "Bezug",
    totalValue: 3152.0,
    totalAtDate: "1.4.2013"
  }
  lieferung: {
    name: "Lieferung"
  }
}

// number formatting
var formatCurrent = {array:true, pretty:true, si:true, unit:'W'},
    formatConsumption = {array:true, pretty:true, si:true, unit:'Wh'},
    formatTotals = {array:true, decimals:0, si:false, unit:'kWh'};

// Chart settings
var plotOptions = {
  series: {
    curvedLines: {
      active: true, }
    },
  curvedLines: {
    apply: true,
    fit: true,
    fitPointDist: 0.1 },
  xaxis: {
    mode: 'time',
    timezone: 'browser',
  	minTickSize: [1, "hour"],
  	timeformat: "%H:%M",
  	},
  yaxis: {
  	maxTickSize: 1,
    transform: function(v) { return -v; },
  	inverseTransform: function(v) { return -v; }
  	},
  grid: {
    backgroundColor: {
      colors: ['#ffffff', '#ffffff'] },
    borderWidth: {
      top: 0,
      right: 0,
      bottom: 0,
      left: 0 }
    },
  lines: {
    show: true,
    steps: false,
    lineWidth: 1,
    fill: true },
};

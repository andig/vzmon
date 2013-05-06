// path to your VZ middleware
var vzAPI = "http://localhost/middleware.php";

// path to forecast.io API
var API_KEY = ""; // goto forecast.io to obtain your own API key
var COORDINATES = "52.,9.";           // your geo coordinates - find out by using Google Maps

var weatherAPI = "https://api.forecast.io/forecast/" + API_KEY + "/" + COORDINATES + "?units=ca&exclude=flags,alerts,minutely";

// general options
var options = {
  updateInterval: 1, // minutes
  sunriseTime: "5:00", // chart min. x axis
}

// VZ channel information
var channels = {
  generation: {
    name: "Erzeugung", 
    color: "#222",
    sign: -1,
    totalValue: 8840.0,
    totalAtDate: "1.4.2013"
  },
  bezug: {
    name: "Bezug",
    color: "#888",
    totalValue: 3152.0,
    totalAtDate: "1.4.2013"
  },
  lieferung: {
    name: "Lieferung",
    sign: -1,
    totalValue: 7418.0,
    totalAtDate: "1.4.2013"
  }
}

// changes below this line should not be necessary

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

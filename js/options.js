/**
 * VZmon options
 *
 * Monitoring for volkszaehler.org 
 */

// path to your VZ middleware
var vzAPI = "http://.../middleware.php";

// path to forecast.io API
var API_KEY = ""; // goto forecast.io to obtain your own API key
var COORDINATES = "";           // your geo coordinates - find out by using Google Maps

var weatherAPI = "https://api.forecast.io/forecast/" + API_KEY + "/" + COORDINATES + "?units=ca&exclude=flags,alerts,minutely,hourly";

// general options
var options = {
  updateInterval: 1,   // minutes
  sunriseTime: "5:00", // chart min. x axis
  plotTuples: 100,     // number of data tuples for plot
}

// VZ channel information

/*
  Each channel follows the same definition rules. 
  You can define additional channels following this pattern:

  channelDefinition: {
    name: "channel title",      // Display name - currently not used.

    // total values
    totalValue: 8840.0,         // Meter total value at certain point in time.
    totalAtDate: "1.4.2013",    // Used for displaying meter totals.

    // plot options
    plotOptions: {              // Can be any of the flot config options, see http://flot.googlecode.com/svn/trunk/API.txt
      color: "red",             // CSS color value. If not defined, will not be plotted.
      lines: {                  // Line formatting
        fill: true,             // Defines if plot should be filled- leave false to draw a line.
      }
    }
  }
*/

var channels = {
  generation: {
    name: "Erzeugung", 
    totalValue: 8840.0,
    totalAtDate: "1.4.2013",
    plotOptions: {
      color: "#999",
      shadowSize: 2,   
      lines: {
        lineWidth: 1,
        fill: true,
      },
    },
    sign: -1,
  },

  bezug: {
    name: "Bezug",
    totalValue: eval(3152.0 - 9.0),
    totalAtDate: "1.4.2013",
    plotOptions: {
      color: "#444",
      shadowSize: 0,   
      lines: {
        lineWidth: 1,
        fill: false,
      },
    },    
  },
  
  lieferung: {
    name: "Lieferung",
    totalValue: eval(7418.0 - 11.0),
    totalAtDate: "1.4.2013",
    sign: -1,
  }
}

// changes below this line should not be necessary

// number formatting
var formatCurrent = {array:true, pretty:true, si:true, unit:'W'},
    formatConsumption = {array:true, pretty:true, si:true, unit:'Wh'},
    formatTotals = {array:true, decimals:0, si:false, unit:'kWh'};

// General chart settings
var plotOptions = {
  series: {
    curvedLines: {
      active: true, }
    },
  curvedLines: {
    apply: true,
    fit: true,
    fitPointDist: 0.1,
  },
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
    fill: false },
};

/**
 * VZmon options
 *
 * Monitoring for volkszaehler.org
 */

// path to your VZ middleware
var vzAPI = "http://localhost/middleware.php";

// path to forecast.io API
var API_KEY = "NOKEY"; // goto forecast.io to obtain your own API key
var COORDINATES = "52.0,9.0";           // your geo coordinates - find out by using Google Maps

var weatherAPI = "https://api.forecast.io/forecast/" + API_KEY + "/" + COORDINATES + "?units=ca&exclude=flags,alerts,minutely,hourly";

// general options
var options = {
  updateInterval: 1,   // minutes
  sunriseTime: "5:00", // chart min. x axis
  plotTuples: 100,     // number of data tuples for plot
  animate: true,       // set to false to disable weather icon animation
  power: 6.916,        // plant power
}

// VZ channel information

/*
  Each channel follows the same definition rules.
  You can define additional channels following this pattern:

  Make sure you have a channelDefinition called "generation" - this channel will be used for calculation performance ratio

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
      color: "orange",  // dark yellow
      shadowSize: 0,
      lines: {
        lineWidth: 2,
        fill: true,
        fillColor: "#fc0",
      },
    },
    sign: -1,
  },

  gesamtverbrauch: {
    name: "Gesamtverbrauch",
    plotOptions: {
      color: "darkred",
      shadowSize: 0,
      lines: {
        lineWidth: 2,
        fill: true,
        fillColor: "red",
      },
    },
  },

  direktverbrauch: {
    name: "Direktverbrauch",
    plotOptions: {
      color: "darkgreen",
      shadowSize: 0,
      lines: {
        lineWidth: 2,
        fill: true,
        fillColor: "green",
      },
    },
  },

  // plotOptions undefinied - only totals, no chart
  bezug: {
    name: "Bezug",
    totalValue: eval(3152.0 - 9.0 - 21.0 - 5.0),
    totalAtDate: "1.4.2013",
  },

  // plotOptions undefinied - only totals, no chart
  lieferung: {
    name: "Lieferung",
    totalValue: eval(7418.0 - 11.0 - 14.0 - 7.0),
    totalAtDate: "1.4.2013",
    sign: -1,
  }
}

// changes below this line should not be necessary

// number formatting
var formatCurrent = {array:true, pretty:true, si:true, unit:'W'},
    formatConsumption = {array:true, pretty:100, decimals:1, si:true, unit:'Wh'},
    formatTotals = {array:true, decimals:0, si:100000, unit:'Wh'};

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
    min: 0,
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

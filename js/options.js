/**
 * VZmon options
 *
 * Monitoring for volkszaehler.org
 */

// Volkszaehler API - path to middleware
//    ATTENTION: 
//    make sure this is a publicly accessible URL - test from browser if unsure. 
//    Localhost will not work in most scenarios!
var vzAPI = "http://cpuidle.dyndns.org:8038/middleware.php";
// var vzAPI = "http://localhost/vz/middleware.php";

// Forecast.io API
var API_KEY = "80c67e7668109f400e20c9dcc5ec0e16"; // goto forecast.io to obtain your own API key
var COORDINATES = "52.283228,9.837533";           // your geo coordinates - find out by using Google Maps

var weatherAPI = "https://api.forecast.io/forecast/" + API_KEY + "/" + COORDINATES + "?units=ca&exclude=flags,alerts,minutely,hourly";

/*
  Volkszaehler channel definition
  ===============================

  Each channel follows the same definition rules.
  Note:
    - Make sure you have a channelDefinition called "generation" - this channel will be used for calculation performance ratio
    - The order of channels matters- they will be draw on the chart according to the order in which they are defined.
  You can define any number of channels following this pattern:
  channelDefinition: {
    name: "channel title",      // Display name - currently not used.
    // total values
    total: {                    // if defined, totals are calculated
      value: 8840.0,            // Meter total value at certain point in time.
      atDate: "1.4.2013",       // Used for displaying meter totals.
    },
    // plot options
    plot: {                     // Can be any of the flot config options, see http://flot.googlecode.com/svn/trunk/API.txt
      color: "red",             // CSS color value. If not defined, will not be plotted.
      lines: {                  // Line formatting
        fill: true,             // Defines if plot should be filled- leave false to draw a line.
        fillColor: "rgba(255,0,0,0.1)"  // Color to use for filling the graph. Use rgba for transparency.
      }
    }
  }
*/
var channels = [
  {
    name: "Erzeugung",
    power: 6.916,        // plant power
    total: {
      value: 14932.0,
      atDate: "1.2.2014",
    },
    plot: {
      color: "orange",  // dark yellow
      shadowSize: 0,
      lines: {
        interpolation: 'step-after',
        lineWidth: 2,
        fill: true,
        fillColor: "#fc0",
      },
    },
  },

  {
    name: "Gesamtverbrauch",
    plot: {
      color: "darkred",
      shadowSize: 0,
      lines: {
        lineWidth: 2,
        fill: true,
        fillColor: "red",
      },
    },
  },

  {
    name: "Direktverbrauch",
    plot: {
      color: "darkgreen",
      shadowSize: 0,
      lines: {
        lineWidth: 2,
        fill: true,
        fillColor: "green",
      },
    },
  },

  // plot undefinied - only totals, no chart
  {
    name: "Bezug",
    total: {
      value: 4712.0,
      atDate: "1.2.2014",
    },
  },

  // plot undefinied - only totals, no chart
  {
    name: "Lieferung",
    total: {
      value: 12559.0,
      atDate: "1.2.2014",
    },
  },
]

// general options - changes below this line should not be necessary
var options = {
  version: 1,          // internal version counter
  
  updateInterval: 60,  // seconds
  sunriseTime: "5:00", // chart min. x axis
  animate: true,       // set to false to disable weather icon animation
  maxPerf: 8.0,        // maximum theoretical solar performance (used to eliminate data issues)
  cache: true,         // if true, total & performance values as well as channel definitions are cached

  formats: {
    date: "DD.MM.YYYY",           // VZ date format according to http://momentjs.com/docs/#/displaying/format/
    time: "HH:mm",                // VZ date format according to http://momentjs.com/docs/#/displaying/format/
    dateTime: "DD.MM.YYYY+HH:mm", // VZ date format according to http://momentjs.com/docs/#/displaying/format/
  },

  // number formatting
  numbers: {
    current:      {array:true, pretty:true, si:true, unit:'W'},
    totals:       {array:true, decimals:0, si:100000, unit:'Wh'},
    consumption:  {array:true, pretty:100, decimals:1, si:true, unit:'Wh'},
  },

  //  chart settings
  plot: {
    tuples: 100,     // number of data tuples for plot
    xaxis: {
      min: 5 * 3600 * 1000,
    },
  },
};


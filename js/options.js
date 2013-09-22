/**
 * VZmon options
 *
 * Monitoring for volkszaehler.org
 */
// Volkszaehler API - path to middleware
//    ATTENTION: 
//    make sure this is a publicly accessible URL - test from browser if unsure. 
//    Localhost will not work in most scenarios!
var vzAPI = "http://localhost/volkszaehler/middleware.php";

// Forecast.io API
var API_KEY = "you-api-key-here";           // goto forecast.io to obtain your own API key
var COORDINATES = "52.0,9.8";               // your geo coordinates - find out by using Google Maps
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
    total: {
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
var channels = {
  generation: {
    name: "Erzeugung",
    power: 6.916,        // plant power
    total: {
      value: 8840.0,
      atDate: "1.4.2013",
    },
    plot: {
      color: "orange",  // dark yellow
      shadowSize: 0,
      lines: {
        lineWidth: 2,
        fill: true,
        fillColor: "#fc0",
      },
    },
  },
  bezug: {
    // add more channels...
  },
}

// general options - changes below this line should not be necessary
var options = {
  updateInterval: 1,   // minutes
  sunriseTime: "5:00", // chart min. x axis
  plotTuples: 100,     // number of data tuples for plot
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
  },
};


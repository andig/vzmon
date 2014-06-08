/**
 * formatNumber
 *
 * universal number formatting
 * adopted http://kevin.vanzonneveld.net
 *
 * @param number    Number to be formatted
 * @param options   Formatting options
 *  dec_point:      decimal separator (default: .)
 *  thousands_sep:  thousands separator (default: none)
 *  decimals:       number of digits after the decimal separator (default: 1)
 *  si:             add SI-prefices to large numbrs (default: off)
 *  unit:           unit to be appended to number (default: none)
 *  pretty:         decrease decimals for large numbers (default: start with numbers >= 10)
 *  array:          return array instead of string (default: off)
 */
function formatNumber(number, options) {
  // Strip all characters but numerical ones.
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
  var // n = !isFinite(+number) ? 0 : +number,
      dec      = (typeof (options || {}).dec_point !== "undefined") ? options.dec_point : '.',
      sep      = (typeof (options || {}).thousands_sep !== "undefined") ? options.thousands_sep : '',
      prec     = (typeof (options || {}).decimals !== "undefined") ? options.decimals : 1,
      unit     = (typeof (options || {}).unit !== "undefined") ? options.unit : '',
      prefix   = (typeof (options || {}).si !== "undefined") ? ((typeof options.si === 'number') ? options.si : 1000) : false,
      pretty   = (typeof (options || {}).pretty !== "undefined") ? ((typeof options.pretty === 'number') ? options.pretty : 10) : false,
      array    = (typeof (options || {}).array !== "undefined") ? options.array : false,
      s = '',
      siPrefixes = ['k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'],
      siIndex = 0,
      toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
      };

  if (prefix) {
    // console.debug("[prefix] " + number +" "+ prefix)
    while (Math.abs(number) >= prefix && siIndex < siPrefixes.length-1) {
      number /= 1000.0;
      siIndex++;
    }
    unit = ((siIndex > 0) ? siPrefixes[siIndex-1] : '') + unit;
    // console.debug("[prefix] " + number +" "+ unit)
  }

  // pretty allows reducing decimal places for larger numbers
  if (pretty) {
    if (Math.abs(number) >= pretty*10.0) { var prec = Math.max(prec-2,0) }
      else if (Math.abs(number) >= pretty*1.0) { var prec = Math.max(prec-1,0) };
  }

  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(number, prec) : '' + Math.round(number)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  s = s.join(dec);

  if (array) {
    if (array === true) {
      return({'value': s, 'unit': unit})
    }
    var res = {};
    res[array.value] = s;
    res[array.unit] = unit;
    return(res);
  }

  return s + unit;
}

function functionName() {
  console.debug(arguments.callee.caller.name);
}

// json failure handler
function failHandler(url, context) {
  return function(jqXHR, textStatus, errorThrown) {
    // var responseText = jQuery.parseJSON(jqXHR.responseText);
    // console.error(responseText);

    // log the error to the console
    var msg = 'Failed retrieving ' + url;
    if (typeof context !== 'undefined') {
      msg = '[' + context + '] ' + msg;
    }
    console.error(msg);
    console.error('HTTP status ' + jqXHR.status + ': ' + textStatus, errorThrown, jqXHR.responseText);
  }
}

/**
 * Filter objects by properties
 *
 * @return  object property where child property 'key' matches given 'val'
 */
function filterProperties(obj, key, val) {
  return(jQuery.map(obj, function(property) {
    return (property[key] == val) ? property : null;
  })[0])
}

/**
 * Reverse mapping uuid->channel
 */
function getChannelFromUUID(aUUID) {
  return(jQuery.map(channels, function(property, idx) {
    return (property['uuid'] == aUUID) ? idx : null;
  })[0])
}

var cache = (function() {
  return {
    /**
     * Get object from localStorage by path
     */
    get: function (path, validHash, obj) {
      // console.log("[getCache] "+path+","+validHash+","+JSON.stringify(obj));
      var segments = path.trim().split('.');
      var segment = segments.shift();
      var result = (obj) ? obj[segment] : ((localStorage[segment]) ? JSON.parse(localStorage[segment]) : false);
      // console.log("[getCache] result("+segment+"): "+JSON.stringify(result));
      if (!result) return (false);

      if (segments.length) {
        return (this.get(segments.join('.'), validHash, result));
      }
      else {
        if (validHash) {
          if (validHash !== result.hash) return (false);
          delete result.hash;
        }
        return (result);
      }
    },

    /**
     * Put object to localStorage by path
     *
     * @todo Fix "hash" being added to obj when saving with hash
     */
    put: function (path, obj, validHash, root) {
      // console.log("[putCache] "+path+","+JSON.stringify(obj)+","+JSON.stringify(root));
      var segments = path.trim().split('.');
      var segment = segments.shift();

      if (root) {
        if (validHash && segments.length === 0) {
          obj.hash = validHash; // add hash to leaf node
        }
        root[segment] = (segments.length) ? this.put(segments.join('.'), obj, validHash, root[segment] || {}) : obj;
        // console.log("[putCache] root("+segment+"): " + JSON.stringify(root));
        return (root);
      }

      root = (localStorage[segment]) ? JSON.parse(localStorage[segment]) || {} : {};
      if (segments.length) {
        obj = this.put(segments.join('.'), obj, validHash, root)
      }
      else if (validHash) {
        obj.hash = validHash; // add hash to leaf node
      }
      localStorage[segment] = JSON.stringify(obj);
    }
  }
}());

/**
 * Get unique hash of channel config
 */
function getChannelHash() {
  var key = "";
  for (var channel in channels) {
    key += channel + "("+channels[channel].totalAtDate+":"+channels[channel].totalValue+")";
  }
  return(key);
}

function mapWeatherIcon(icon) {
  switch (icon) {
    case "clear-day": return(Skycons.CLEAR_DAY);
    case "clear-night": return(Skycons.CLEAR_NIGHT);
    case "partly-cloudy-day": return(Skycons.PARTLY_CLOUDY_DAY);
    case "partly-cloudy-night": return(Skycons.PARTLY_CLOUDY_NIGHT);
    case "cloudy": return(Skycons.CLOUDY);
    case "rain": return(Skycons.RAIN);
    case "sleet": return(Skycons.SLEET);
    case "snow": return(Skycons.SNOW);
    case "wind": return(Skycons.WIND);
    case "fog": return(Skycons.FOG);
    default: return(Skycons.CLEAR_DAY);
    }
}

function cloudCover(e) {
  return e.cloudCover < .2 ? 0 : e.cloudCover <.8 ? 1 : 2;
}

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
      dec      = (typeof options !== 'undefined' && typeof options.dec_point !== 'undefined') ? options.dec_point : '.',
    	sep      = (typeof options !== 'undefined' && typeof options.thousands_sep !== 'undefined') ? options.thousands_sep : '',
      prec     = (typeof options !== 'undefined' && typeof options.decimals !== 'undefined') ? options.decimals : 1,
    	unit     = (typeof options !== 'undefined' && typeof options.unit !== 'undefined') ? options.unit : '',
    	prefix   = (typeof options !== 'undefined' && typeof options.si !== 'undefined') ? 
                  ((typeof options.si === 'number') ? options.si : 1000) : false,
    	pretty   = (typeof options !== 'undefined' && typeof options.pretty !== 'undefined') ? 
                  ((typeof options.pretty === 'number') ? options.pretty : 10) : false,
    	array    = (typeof options !== 'undefined' && typeof options.array !== 'undefined') ? options.array : false,
    	s = '',
    	siPrefixes = ['k', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y'],
    	siIndex = 0,
    	toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
      };

	if (prefix) {
    // console.debug("[prefix] " + number +" "+ prefix)
/*
    // allow pre-adjusting the prefix to k,M,..
    var _prefix = prefix;
    while (Math.abs(_prefix) >= 1000 && siIndex < siPrefixes.length-1) {
      _prefix /= 1000.0;
      siIndex++;
    }
*/
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

  return s+unit;
}

function functionName() {
  console.debug(arguments.callee.caller.name);
}

// json failure handler
function failHandler(url, context) { 
  return function(jqXHR, textStatus, errorThrown) { 
    // log the error to the console 
    var msg = "Failed retrieving " + url;
    if (typeof context !== "undefined") {
      msg = "[" + context + "] " + msg;
    }
    console.error(msg);
    console.error("HTTP status " + jqXHR.status + ": " + textStatus, errorThrown, jqXHR.responseText);
  }; 
}

function getUUID(json, title) {
	return(jQuery.map(json.channels, function(value) {
		// console.log(value);
		return (value.title == title) ? value.uuid : null;
	})[0]);
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

function formatNumber(number, options) {
	// adopted http://kevin.vanzonneveld.net
	// Strip all characters but numerical ones.
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var // n = !isFinite(+number) ? 0 : +number,
		  decimals = (typeof options !== 'undefined' && typeof options.decimals !== 'undefined') ? options.decimals : 1,
    	sep = (typeof options !== 'undefined' && typeof options.thousands_sep !== 'undefined') ? options.thousands_sep : '',
    	dec = (typeof options !== 'undefined' && typeof options.dec_point !== 'undefined') ? options.dec_point : '.',
    	unit = (typeof options !== 'undefined' && typeof options.unit !== 'undefined') ? options.unit : '',
    	prefix = (typeof options !== 'undefined' && typeof options.si !== 'undefined') ? options.si : false,
    	autoprecision = (typeof options !== 'undefined' && typeof options.pretty !== 'undefined') ? options.pretty : false,
    	array = (typeof options !== 'undefined' && typeof options.array !== 'undefined') ? options.array : false,
    	s = '',
    	siPrefixes = ['k', 'M', 'G', 'T'],
    	siIndex = 0,
    	toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
      };

	if (prefix) {
    while (Math.abs(number) >= 1000 && siIndex < siPrefixes.length-1) {
      number /= 1000.0;
      siIndex++;
    }
    unit = ((siIndex > 0) ? siPrefixes[siIndex-1] : '') + unit;
	}
  
	var prec = decimals;
	if (autoprecision) {
    if (Math.abs(number) >= 100) { var prec = Math.max(prec-2,0) }
      else if (Math.abs(number) >= 10) { var prec = Math.max(prec-1,0) };
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

var failHandler = function(jqXHR, textStatus, errorThrown) {
	// log the error to the console 
	console.error("The following error occured: " + textStatus, errorThrown, jqXHR.responseText);
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

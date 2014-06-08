(function() {
	var msgs = [];
	var proxyLog = console.log;
	console.log = function(msg) {
		msgs.push({	type: "log", value: msg, time: new Date().getTime() });
		proxyLog.apply(this, arguments);
	}
	var proxyDebug = console.debug;
	console.debug = function(msg) {
		msgs.push({	type: "debug", value: msg, time: new Date().getTime() });
		proxyDebug.apply(this, arguments);
	}
	var proxyInfo = console.info;
	console.info = function(msg) {
		msgs.push({	type: "info", value: msg, time: new Date().getTime() });
		proxyInfo.apply(this, arguments);
	}
	var proxyWarn = console.warn;
	console.warn = function(msg) {
		msgs.push({	type: "warn", value: msg, time: new Date().getTime() });
		proxyWarn.apply(this, arguments);
	}
	var proxyError = console.error;
	console.error = function(msg) {
		msgs.push({	type: "error", value: msg, time: new Date().getTime() });
		proxyError.apply(this, arguments);
	}
	console.getMsgs = function(clear) {
		var m = msgs;
		if (clear) console.clearMsgs();
		return(m);
	}
	console.clearMsgs = function() {
		msgs = [];
	}
})();

function getClass(obj) { 
	if (typeof obj === "undefined") 
		return "undefined"; 
	if (obj === null)
		return "null"; 
	return
		Object.prototype.toString.call(obj).match(/^\[object\s(.*)\]$/)[1];
}

function formatType(value) {
	switch (typeof(value)) {
		case "object":
			return "[obj " + getClass(value) +"] "+ JSON.stringify(value);
			break;
		default:
			return value;
	}
}

function formatTime(time) {
	var date = new Date(time);
	var hrs = date.getHours();
	var min = date.getMinutes();
	var sec = date.getSeconds();
	var milli = date.getMilliseconds();
	return (((hrs<10) ? '0'+hrs : hrs) +':'+ ((min<10) ? '0'+min : min) +':'+ ((sec<10) ? '0'+sec : sec) +"."+ milli);
}

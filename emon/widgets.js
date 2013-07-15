/*
  Widgets based on emon_widgets:

   All emon_widgets code is released under the GNU General Public License v3.
   See COPYRIGHT.txt and LICENSE.txt.

    ---------------------------------------------------------------------
    Part of the OpenEnergyMonitor project:
    http://openenergymonitor.org

    Author: Trystan Lea: trystan.lea@googlemail.com
    If you have any questions please get in touch, try the forums here:
    http://openenergymonitor.org/emon/forum
 */

/**
 * JQuery easing functions
 */
$.extend($.easing,
{
    def: 'easeOutQuad',
    swing: function (x, t, b, c, d) {
        return $.easing[$.easing.def](x, t, b, c, d);
    },
    easeInQuad: function (x, t, b, c, d) {
        return c*(t/=d)*t + b;
    },
    easeOutQuad: function (x, t, b, c, d) {
        return -c *(t/=d)*(t-2) + b;
    },
    easeInOutQuad: function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return c/2*t*t + b;
        return -c/2 * ((--t)*(t-2) - 1) + b;
    },
    easeInCubic: function (x, t, b, c, d) {
        return c*(t/=d)*t*t + b;
    },
    easeOutCubic: function (x, t, b, c, d) {
        return c*((t=t/d-1)*t*t + 1) + b;
    },
    easeInOutCubic: function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return c/2*t*t*t + b;
        return c/2*((t-=2)*t*t + 2) + b;
    },
    easeInQuart: function (x, t, b, c, d) {
        return c*(t/=d)*t*t*t + b;
    },
    easeOutQuart: function (x, t, b, c, d) {
        return -c * ((t=t/d-1)*t*t*t - 1) + b;
    },
    easeInOutQuart: function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return c/2*t*t*t*t + b;
        return -c/2 * ((t-=2)*t*t*t - 2) + b;
    },
    easeInQuint: function (x, t, b, c, d) {
        return c*(t/=d)*t*t*t*t + b;
    },
    easeOutQuint: function (x, t, b, c, d) {
        return c*((t=t/d-1)*t*t*t*t + 1) + b;
    },
    easeInOutQuint: function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return c/2*t*t*t*t*t + b;
        return c/2*((t-=2)*t*t*t*t + 2) + b;
    },
    easeInSine: function (x, t, b, c, d) {
        return -c * Math.cos(t/d * (Math.PI/2)) + c + b;
    },
    easeOutSine: function (x, t, b, c, d) {
        return c * Math.sin(t/d * (Math.PI/2)) + b;
    },
    easeInOutSine: function (x, t, b, c, d) {
        return -c/2 * (Math.cos(Math.PI*t/d) - 1) + b;
    },
    easeInExpo: function (x, t, b, c, d) {
        return (t==0) ? b : c * Math.pow(2, 10 * (t/d - 1)) + b;
    },
    easeOutExpo: function (x, t, b, c, d) {
        return (t==d) ? b+c : c * (-Math.pow(2, -10 * t/d) + 1) + b;
    },
    easeInOutExpo: function (x, t, b, c, d) {
        if (t==0) return b;
        if (t==d) return b+c;
        if ((t/=d/2) < 1) return c/2 * Math.pow(2, 10 * (t - 1)) + b;
        return c/2 * (-Math.pow(2, -10 * --t) + 2) + b;
    },
    easeInCirc: function (x, t, b, c, d) {
        return -c * (Math.sqrt(1 - (t/=d)*t) - 1) + b;
    },
    easeOutCirc: function (x, t, b, c, d) {
        return c * Math.sqrt(1 - (t=t/d-1)*t) + b;
    },
    easeInOutCirc: function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return -c/2 * (Math.sqrt(1 - t*t) - 1) + b;
        return c/2 * (Math.sqrt(1 - (t-=2)*t) + 1) + b;
    },
    easeInElastic: function (x, t, b, c, d) {
        var s=1.70158;var p=0;var a=c;
        if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
        if (a < Math.abs(c)) { a=c; var s=p/4; }
        else var s = p/(2*Math.PI) * Math.asin (c/a);
        return -(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
    },
    easeOutElastic: function (x, t, b, c, d) {
        var s=1.70158;var p=0;var a=c;
        if (t==0) return b;  if ((t/=d)==1) return b+c;  if (!p) p=d*.3;
        if (a < Math.abs(c)) { a=c; var s=p/4; }
        else var s = p/(2*Math.PI) * Math.asin (c/a);
        return a*Math.pow(2,-10*t) * Math.sin( (t*d-s)*(2*Math.PI)/p ) + c + b;
    },
    easeInOutElastic: function (x, t, b, c, d) {
        var s=1.70158;var p=0;var a=c;
        if (t==0) return b;  if ((t/=d/2)==2) return b+c;  if (!p) p=d*(.3*1.5);
        if (a < Math.abs(c)) { a=c; var s=p/4; }
        else var s = p/(2*Math.PI) * Math.asin (c/a);
        if (t < 1) return -.5*(a*Math.pow(2,10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )) + b;
        return a*Math.pow(2,-10*(t-=1)) * Math.sin( (t*d-s)*(2*Math.PI)/p )*.5 + c + b;
    },
    easeInBack: function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        return c*(t/=d)*t*((s+1)*t - s) + b;
    },
    easeOutBack: function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        return c*((t=t/d-1)*t*((s+1)*t + s) + 1) + b;
    },
    easeInOutBack: function (x, t, b, c, d, s) {
        if (s == undefined) s = 1.70158;
        if ((t/=d/2) < 1) return c/2*(t*t*(((s*=(1.525))+1)*t - s)) + b;
        return c/2*((t-=2)*t*(((s*=(1.525))+1)*t + s) + 2) + b;
    },
    easeInBounce: function (x, t, b, c, d) {
        return c - $.easing.easeOutBounce (x, d-t, 0, c, d) + b;
    },
    easeOutBounce: function (x, t, b, c, d) {
        if ((t/=d) < (1/2.75)) {
            return c*(7.5625*t*t) + b;
        } else if (t < (2/2.75)) {
            return c*(7.5625*(t-=(1.5/2.75))*t + .75) + b;
        } else if (t < (2.5/2.75)) {
            return c*(7.5625*(t-=(2.25/2.75))*t + .9375) + b;
        } else {
            return c*(7.5625*(t-=(2.625/2.75))*t + .984375) + b;
        }
    },
    easeInOutBounce: function (x, t, b, c, d) {
        if (t < d/2) return $.easing.easeInBounce (x, t*2, 0, c, d) * .5 + b;
        return $.easing.easeOutBounce (x, t*2-d, 0, c, d) * .5 + c*.5 + b;
    }
});

function draw_dial(ctx,x,y,width,height,position,maxvalue,units,type,format,reverse)
{
  if (!ctx) return;
  maxvalue = 1 * maxvalue || 3000;
  units = units || "";
  reverse = reverse || false;
  var size = 0; if (width<height) size = width/2; else size = height/2;
  size = size - (size*0.058/2);
  x = width/2; y = height/2;

  ctx.clearRect(0,0,200,200);

  if (!position) position = 0;

  var type = type || 0;
  var segment = [
    ["#c0e392","#9dc965","#87c03f","#70ac21","#378d42","#046b34"],
    ["#e61703","#ff6254","#ffa29a","#70ac21","#378d42","#046b34"],
    ["#046b34","#378d42","#87c03f","#f8a01b","#f46722","#bf2025"],
    ["#046b34","#378d42","#87c03f","#f8a01b","#f46722","#bf2025"],
    ["#bf2025","#f46722","#f8a01b","#87c03f","#378d42","#046b34"],
    ["#bf2025","#f46722","#f8a01b","#87c03f","#378d42","#046b34"],
    ["#f46722","#f8a01b","#87c03f","#87c03f","#f8a01b","#f46722"],
    ["#a7cbe2","#68b7eb","#0d97f3","#0f81d0","#0c6dae","#08578e"],
    // temperature dial blue-red, first segment blue should mean below freezing C
    ["#b7beff","#ffd9d9","#ffbebe","#ff9c9c","#ff6e6e","#ff3d3d"],
    // temperature dial blue-red, first segment blue should mean below freezing C
    ["#e94937","#da4130","#c43626","#ad2b1c","#992113","#86170a"],
    // light: from dark grey to white
    ["#202020","#4D4D4D","#7D7D7D","#EEF0F3","#F7F7F7", "#FFFFFF"]][type];

  if (reverse) segment = segment.reverse();
  var offset = 0;

  if (type == 0) {
    if (position<0) position = 0;
  }
  else if (type == 1) {
    offset = -0.75;
  }
  else if (type == 3) {
    offset = -0.75;
  }
  else if (type == 5) {
    offset = -0.75;
  }
  else if (type == 6) {
    offset = -0.75;
  }
  else if (type == 8) {
    offset = -0.25;
  }

  if (position>maxvalue) position = maxvalue;
  var a = 1.75 - ((position/maxvalue) * 1.5) + offset;
  if (reverse) a = 2-a;

  var c = 3*0.785;
  var width = 0.785;
  var pos = 0;
  var inner = size * 0.48;

  // Segments
  for (z in segment)
  {
    ctx.fillStyle = segment[z];
    ctx.beginPath();
    ctx.arc(x,y,size,c+pos,c+pos+width+0.01,false);
    ctx.lineTo(x,y);
    ctx.closePath();
    ctx.fill();
    pos += width;
  }
  pos -= width;
  ctx.lineWidth = (size*0.058).toFixed(0);
  pos += width;
  ctx.strokeStyle = "#fff";
  ctx.beginPath();
  ctx.arc(x,y,size,c,c+pos,false);
  ctx.lineTo(x,y);
  ctx.closePath();
  ctx.stroke();

  ctx.fillStyle = "#666867";
  ctx.beginPath();
  ctx.arc(x,y,inner,0,Math.PI*2,true);
  ctx.closePath();
  ctx.fill();

  ctx.lineWidth = (size*0.052).toFixed(0);
  ctx.beginPath();
  ctx.moveTo(x+Math.sin(Math.PI*a-0.2)*inner,y+Math.cos(Math.PI*a-0.2)*inner);
  ctx.lineTo(x+Math.sin(Math.PI*a)*size,y+Math.cos(Math.PI*a)*size);
  ctx.lineTo(x+Math.sin(Math.PI*a+0.2)*inner,y+Math.cos(Math.PI*a+0.2)*inner);
  ctx.arc(x,y,inner,1-(Math.PI*a-0.2),1-(Math.PI*a+5.4),true);
  ctx.closePath();
  ctx.fill();
  ctx.stroke();

  // text
  ctx.fillStyle = "#fff";
  ctx.textAlign = "center";
  ctx.font = "bold "+(size*0.28)+"px arial";

  var value = formatNumber(position, true);
  ctx.fillText(value+units,x,y+(size*0.105));
}

function draw_cylinder(ctx,cyl_bot,cyl_top,width,height)
{
  if (!ctx) return;
  var midx = width / 2;
  var cyl_width = width - 8;
  var cyl_left = midx - (cyl_width/2);
  var top_pos = midx;
  var bh = 28;
  var bot_pos = top_pos + 6 * bh;
  ctx.clearRect(0,0,width,500);
  cyl_top = cyl_top || 0;
  cyl_bot = cyl_bot || 0;
  ctx.strokeStyle = "#fff";
  ctx.lineWidth = 8;
  var diff = 1*cyl_top - 1*cyl_bot;
  var step_diff = -diff / 5;

  // top
  ctx.fillStyle = get_color(cyl_top);
  ctx.beginPath();
  ctx.arc(midx,top_pos,cyl_width/2,Math.PI,0,false);
  ctx.closePath();
  ctx.fill();

  for (var i=0; i<6; i++) {
    var y = top_pos + i*bh;
    var step_temp = cyl_top + i*step_diff;
    ctx.fillStyle = get_color(step_temp);
    ctx.fillRect(cyl_left, y, cyl_width, bh);
  }

  // bottom
  ctx.fillStyle = get_color(cyl_bot);
  ctx.beginPath();
  ctx.arc(midx,bot_pos,cyl_width/2,0,Math.PI,false);
  ctx.closePath();
  ctx.fill();

  // cylinder border
  ctx.beginPath();
  ctx.arc(midx,top_pos,cyl_width/2,Math.PI,0,false);
  ctx.arc(midx,bot_pos,cyl_width/2,0,Math.PI,false);
  ctx.closePath();
  ctx.stroke();

  // text
  ctx.fillStyle = "#fff";
  ctx.textAlign = "center";
  ctx.font = "bold "+((width/168)*30)+"px arial";
  ctx.fillText(cyl_top.toFixed(1)+"C",midx,top_pos);
  ctx.fillText(cyl_bot.toFixed(1)+"C",midx,bot_pos+15);
}

function draw_temp(ctx,val,width,height)
{
  if (!ctx) return;
  var midx = width / 2;
  var cyl_width = width / 2;

  var bh = 28;
  var top_pos = midx;
  var bot_pos = height-width/2;
  var color = get_color(val);

  ctx.clearRect(0,0,width,height);

  ctx.strokeStyle = "#fff";
  ctx.lineWidth = 8;

  // top
  ctx.fillStyle = color;
  ctx.beginPath();
  ctx.arc(midx,top_pos,width/4,Math.PI,0,false);
  ctx.closePath();
  ctx.fill();

  // neck
  ctx.fillStyle = color;
  ctx.fillRect(width/4, top_pos, cyl_width, bot_pos-top_pos);

  // border
  // ctx.strokeStyle = "rgb(0,0,0)";
  ctx.beginPath();
  ctx.arc(midx,top_pos,cyl_width/2+ctx.lineWidth/2,Math.PI,0,false);
  ctx.arc(midx,bot_pos,cyl_width/2+ctx.lineWidth/2,0,Math.PI,false);
  ctx.closePath();
  ctx.stroke();

  // bottom
  // ctx.fillStyle = get_color(cyl_bot);
  ctx.fillStyle = color;
  ctx.beginPath();
  ctx.arc(midx,bot_pos,cyl_width,0,2*Math.PI,false);
  ctx.closePath();
  ctx.fill();

  var k = 0.7;
  // ctx.strokeStyle = "rgb(255,0,0)";
  ctx.beginPath();
  ctx.arc(midx,bot_pos,cyl_width+ctx.lineWidth/2,-0.5*k*Math.PI,(1+0.5*k)*Math.PI,false);
  // ctx.closePath();
  ctx.stroke();

  // text
  ctx.fillStyle = "#fff";
  ctx.textAlign = "center";
  ctx.font = "bold "+((width/168)*30)+"px arial";
  ctx.fillText(val.toFixed(1)+"C",midx,bot_pos+4);
}

function get_color(temperature) {
  var red = (32+(temperature*3.95)).toFixed(0);
  var green = 40;
  var blue = (191-(temperature*3.65)).toFixed(0);
  return "rgb("+red+","+green+","+blue+")";
}

/**
 * Global render options
 */
var Render = {
  options: {
      animation_interval: 10, // ms
      animation_time: 500,
      animation_function: "easeOutBack",  // http://easings.net/
      redraw: 0,
      precision: 1,
    },

  setup: function(el) {
    var width = el.width();
    var height = el.height();
    var canvas = el.children('canvas');

    // 1) Create canvas if it does not exist
    if (!canvas[0]) {
      el.html('<canvas></canvas>');
      canvas = el.children('canvas');
    }

    // 2) Resize canvas if it needs resizing
    if (canvas.attr("width") != width) canvas.attr("width", width);
    if (canvas.attr("height") != height) canvas.attr("height", height);

    if (typeof G_vmlCanvasManager != "undefined") G_vmlCanvasManager.initElement(canvas);
  },

  formatNumberString: function(number, decimals, dec_point, thousands_sep) {
    // http://kevin.vanzonneveld.net
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
      prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
      sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
      dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
      s = '',
      toFixedFix = function (n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
      };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
  },

  // Rounding precision
  formatNumber: function(number, prefix) {
    var siPrefixes = ['k', 'M', 'G', 'T'];
    var siIndex = 0;

    while (prefix && Math.abs(number) >= 1000 && siIndex < siPrefixes.length-1) {
      number /= 1000;
      siIndex++;
    }

    var n1 = Math.abs(number);
    if (n1 >= 100) { var precision = Math.max(Render.options.precision-2,0) }
      else if (n1 >= 10) { var precision = Math.max(Render.options.precision-1,0) }
        else var precision = Render.options.precision;

    number = this.formatNumberString(number, precision);

    if (prefix) {
      number += (siIndex > 0) ? '' + siPrefixes[siIndex-1] : '';
    }

    return number;
  },
}

var WidgetDial = {
  _create: function() {
    // console.log("WidgetDial._init");
    Render.setup(this.element);
  },

  value: function(val) {
    // console.log("WidgetDial.value");
    var el = this.element;
    oldval = el.attr("val");
    val = (val * 1).toFixed(2);
    if (val != oldval || Render.options.redraw == 1) {
      el.attr("val", val);
      var scale = 1*el.attr("scale") || 1;
      var format = 1*el.attr("format") || 1;
      var reverse = 1*el.attr("reverse") || 0;
      var ctx = $(el).children("canvas").first()[0].getContext("2d");
      draw_dial(ctx,0,0,el.width(),el.height(),val*scale, el.attr("max"), el.attr("units"), el.attr("type"), format, reverse);
      // draw_temp(ctx,val*scale,el.width()/4,el.height());
    }
  },

  animate: function(val) {
    var context = {
      el: this,
      step: 0,
      start: this.element.attr("val")*1 || 0,
      delta: val - (this.element.attr("val")*1 || 0),
      end: val,
    };
    var intervalFunc = function() {
      // jQuery.easing.method(null†, current_time, start_value, end_value, total_time)
      var fn = $.easing[Render.options.animation_function];
        var val = fn(0, this.step * Render.options.animation_interval, this.start, this.delta, Render.options.animation_time).toFixed(2);
        if (this.oldval != val) {
            // continue animation
        this.el.value(val);
        this.oldval = val;
        this.step += 1;
        if (this.step * Render.options.animation_interval <= Render.options.animation_time) {
          // next animation step
          setTimeout($.proxy(intervalFunc, this), Render.options.animation_interval);
        } else {
          // end value
          this.el.value(this.end);
        }
      }
    }
    setTimeout($.proxy(intervalFunc, context), Render.options.animation_interval);
  },
};

var WidgetCylinder = {
  _create: function() {
    // console.log("WidgetDial._init");
    Render.setup(this.element);
  },

  value: function(top, bot) {
    // console.log("WidgetDial.value");
    var el = this.element;
    var ctx = $(el).children("canvas").first()[0].getContext("2d");
    draw_cylinder(ctx,bot,top,el.width(),el.height());
  },
};

var WidgetValue = {
  _create: function() {
    // console.log("WidgetDial._init");
    Render.setup(this.element);
  },

  value: function(val) {
    // console.log("WidgetDial.value");
    var el = this.element;
    var units = el.attr("units");
    if (units==undefined) units = '';
    if (val==undefined) val = 0;

    var value = formatNumber(val, true);
    el.html(value+units);
  },
}

$.widget("ui.widgetDial", WidgetDial);
$.widget("ui.widgetValue", WidgetValue);
$.widget("ui.widgetCylinder", WidgetCylinder);

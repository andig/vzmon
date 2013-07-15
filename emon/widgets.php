<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" encoding="en">
<head>
	<title>vz</title>
	<meta content="text/html;charset=utf-8" http-equiv="Content-Type"> 
	<meta content="utf-8" http-equiv="encoding">

	<meta name="viewport" content="width=device-width" />
	<link rel="shortcut icon" type="image/ico" href="../vz/favicon.ico" />

	<!-- css -->

	<!-- js -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> 
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script> 

	<script type="text/javascript" src="../js/functions.js"></script>
	<script type="text/javascript" src="widgets.js"></script>

	<style>
	.container {
		margin: 0px; 
		padding: 0px;
		width: 140px; 
		height: 140px;
		display: inline-block;
	}
	.box-white, .box-grey {
	  display: inline-block; 
	  box-shadow: 0 4px 10px -1px rgba(200, 200, 200, 0.7);
	  padding: 20px; }
	  .box-white {
		background: none repeat scroll 0 0 #FFFFFF;
		border: 1px solid #E5E5E5;
	  }
	  .box-grey {
		background: none repeat scroll 0 0 #ddd;
		border: 1px solid #ccc;
	  }
	.dial {
		margin: 0px; 
		width: 140px; 
		height: 140px;
		display: inline-block;
	}
	.value {
		width: 140px;
		height: 60px;
		text-align: center;
		color: rgb(90,90,90);
		font-family: Arial, Helvetica;
		font-weight: bold;
	}
	.cylinder {
		width:160px;
		height:500px;
	}
	</style>
</head>

<body>

<div class="dial"  max="3000" scale="1" format="1" units="W" type="0"></div>
<div class="dial"  max="3000" scale="1" format="1" units="W" type="1"></div>
<div class="dial"  max="3000" scale="1" format="1" units="W" type="2"></div>
<div class="dial"  max="3000" scale="1" format="1" units="W" type="3"></div>
<div class="dial"  max="3000" scale="1" format="1" units="W" type="4"></div>
<div class="dial"  max="3000" scale="1" format="1" units="W" type="5"></div>
<div class="dial"  max="3000" scale="1" format="1" units="W" type="6"></div>
<div class="dial"  max="3000" scale="1" format="1" units="W" type="7"></div>
<div class="dial"  max="3000" scale="1" format="1" units="W" type="8"></div>
<div class="dial"  max="3000" scale="1" format="1" units="W" type="9"></div>
<div class="dial"  max="3000" scale="1" format="1" units="W" type="10"></div>

<div class="box-white">
	<div id="bez" class="container">
		<div id="dial1" class="dial"  max="3000" format="1" units="W" type="9"></div>
		<div id="val1" class="value" format="1" units="Wh"></div>
	</div>
	<div id="gen" class="container">
		<div id="dial2" class="dial"  max="6000" format="1" units="W" type="0"></div>
		<div id="val2" class="value" format="1" units="Wh"></div>
	</div>
	<div id="combined" class="container">
		<div id="dial3" class="dial"  max="3000" format="1" units="W" type="1"></div>
		<div id="val3" class="value" format="1" units="Wh"></div>
	</div>
</div>

<div id="cyl1" class="cylinder"></div>

<script type="text/javascript">

$(document).ready(function() {
	// initialize widgets
	$(".dial").widgetDial();
	$(".dial").widgetDial("value", 0);
	$(".value").widgetValue();
	$(".value").widgetValue("value", 0);
	$(".cylinder").widgetCylinder();
	$(".cylinder").widgetCylinder("value", 40, 10);

	var updater = function(uuid, el, factor) {
		var val = Math.random() * 3000;
		$(".dial").widgetDial("animate", val);
	}

	updater();
	setInterval(updater, 10*1000);
 });

</script>

</body>
</html>
<html>
<!--
/*
Copyright (C) 2010 European Broadcasting Union
http://www.ebulabs.org
*/
/*
This file is part of ebu-radiovis-ajaxplayer.
https://code.google.com/p/ebu-radiovis-ajaxplayer/

EBU-radiovis-ajaxplayer is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

EBU-radiovis-ajaxplayer is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with EBU-radiovis-ajaxplayer.  If not, see <http://www.gnu.org/licenses/>.
*/


-->
	<head>
		<title>EBU Radio tools - RadioVis Ajax Player</title>
		
		<style>
			body{
				font-family: verdana;
				font-size:13px;
			}
		</style>
		
	</head>
	<body>
	
	
	
		<script>
			/*******************************
				STOMP topic of your station:
				for more information, see RadioVIS Specification at www.radiodns.org
			********************************/
			var topic = "<?php echo $_GET["topic"]; ?>";
			

		</script>
		<script src="radiovis-webplayer/ebu-ajaxplayer.js"></script>
		<script src="radiovis-webplayer/jquery-1.6.1.min.js"></script>
		<script>
				$(document).ready(function(){
					rdnslookup();
				});
		</script>

		<div id="mainframe" style="width:320px; height:240px; overflow:hidden;">
			<div id="slideframe" style="clear:both;">
				<div id="P1" style="position:absolute;"><a href="" id='LI1'><img id='I1'></a></div>
				<div id="P0" style="position:absolute;"><a href="" id='LI0'><img id='I0'></a></div>
			</div>
		</div>
		<div id="textframe" style="width:320px;"></div>

</body></html>
<?php
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
require_once "php-radiodns/RadioDNS.php";

if(!isset($_GET['topic']) || strpos($_GET['topic'], "/topic/") === FALSE)
	die("error");


$rdns = new RadioDNS();

$topic = $_GET['topic'];

$t = explode("/", $topic);



if($t[2] == "fm")
	$rsp = $rdns->lookupFMService(strtoupper($t[3]), strtoupper($t[4]), $t[5]/100);
else if($t[2] == "dab")
	$rsp = $rdns->lookupDABService(strtoupper($t[3]), strtoupper($t[4]), strtoupper($t[5]), $t[6]);
else if($t[2] == "am")
	$rsp = $rdns->lookupAMService($t[3], $t[4]);
else if($t[2] == "hd")
	$rsp = $rdns->lookupHDService($t[3], $t[4]);
	
	
$server = $rsp["applications"]["radiovis"]["servers"][0];


//Return the server and the port of the RadioVIS service according to the topic
if(isset($server))
	die($server["target"].":".$server["port"]);
else
	die("not supported");
	



?>
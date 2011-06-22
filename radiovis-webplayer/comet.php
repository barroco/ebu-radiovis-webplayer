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


//***********************
//Parameters

//Full path to log file
//disabled
//$LOGFILE = "/var/www/logfile"; //chmod mandatory

//Array of authorized radiovis server. This script works like an interface between ajax and stomp (in fact it's the comet system). 
//To avoid unwanted request, you have to specify the destination servers in the following array
$authorized_server = array("radiodns1.ebu.ch:61613", "vis.musicradio.com:61613");





//*************************
// Don't modify below

$VIS_TOPIC = (isset($_GET['topic']) && strpos($_GET['topic'],"/topic/")!==FALSE) ? strtolower($_GET["topic"]) : "/topic/fm/ch/4000/09580/image";
$VIS_SERVER = (isset($_GET["visserver"]))? $_GET["visserver"] : "radiodns1.ebu.ch";
$VIS_PORT = (isset($_GET["visport"]))? $_GET["visport"] : 61613;

ini_set('output_buffering', 'off');
//Limit to 15 seconds the http connection
set_time_limit(15);

    define ("LINE_END", "\x00");
	
	register_shutdown_function('shutdown');

	
	
	//First Stomp Message: CONNECTION
    $msg1 = "CONNECT\n\n\n\x00";
	//Second Stomp Message: SUBCRIPTION
	$msg2 = "SUBCRIBE\ndestination: ".$VIS_TOPIC."\n\n\x00";
	
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	if(!in_array($VIS_SERVER.":".$VIS_PORT,$authorized_server))
		die("@error: not authorized");
    socket_connect ($socket, $VIS_SERVER, $VIS_PORT) or die("@error: socket error");
    socket_set_nonblock ($socket);

	//CONNECT
	socket_send($socket, $msg1, strLen($msg1), 0);
	
	//SUBCRIBE
    socket_send($socket, $msg2, strLen($msg2), 0);
			
	
	$currentmessageid = "nomessage";
    while (1) {
        
		$x = socket_normal_read ($socket, 8);

        if ($x !== false && strlen($x)){
            
			
			
			if(strpos($x, "MESSAGE")!==FALSE && strpos($x, "MESSAGE") == 0){
				$tag = "message-id:";
				$p = strpos($x, $tag)+strlen($tag);
				$messageid = trim(substr($x, $p, strpos($x, "\n", $p)-$p));
				$currentmessageid = $messageid;
					if($currentmessageid != $_GET['last_id']){
						die($x); //return message
					}
				
			}
			else if(strpos($x, "ERROR") !== FALSE)
				die("@error: Stomp error");
		}
		if(socket_last_error($socket) == 104){
			mylog("error : " .socket_last_error($socket)."\n");
			break;
		}
		if (connection_aborted()) {
			mylog('aborted!');
			break;
		}
		
    }
	
	die("");
	
	//Functions
	
	function mylog($str){
	global $LOGFILE;
		/*$handle = fopen($LOGFILE, "a") or die("error");
		fwrite($handle, $str);
		fclose($handle);*/

	}
	
	function shutdown()
	{
	global $socket;
		
		if(connection_aborted()){
			//mylog("shutdown");
			if($socket != null)
				socket_close($socket);
		}
		die("");
	}

	function socket_normal_read ($socket, $length) {
        static $sockets = array ();
        static $queues = array ();
        static $sock_num = 0;

        for ($i = 0;  isset ($sockets[$i]) && $socket != $sockets[$i]; $i++);

        if (!isset ($sockets[$i])) {
            $sockets [$sock_num] = $socket;
            $queues [$sock_num++] = "";
        }

        $recv = socket_read ($socket, $length, PHP_BINARY_READ);
        if ($recv === "") {
            if (strpos ($queues[$i], LINE_END) === false)
                return false;
        }
        else if ($recv !== false) {
            $queues[$i] .= $recv;
        }

        $pos = strpos ($queues[$i], LINE_END);
        if ($pos === false)
            return "";
        $ret = substr ($queues[$i], 0, $pos);
        $queues[$i] = substr ($queues[$i], $pos+2);

        return $ret;
    }

?>
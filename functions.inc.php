<?php

// The destinations this module provides
// returns a associative arrays with keys 'destination' and 'description'
function extaudio_destinations() {
	//static destinations
	$extens = array();
	$extens[] = array('destination' => 'app-extaudio,audioout,1', 'description' => 'Ext Audio Out');

	if (isset($extens))
		return $extens;
	else
		return null;

}

/* 	Generates dialplan for "extaudio" components
	We call this with retrieve_conf
*/
function extaudio_get_config($engine) {

	global $ext;
	switch($engine) {
		case "asterisk":

// Volume settings

			$sql = "SELECT value FROM extaudio WHERE variable = 'lineoutvol'";
			$result = sql($sql,"getRow",DB_FETCHMODE_ASSOC);
			$lineoutvol = $result['value'];
			if (!isset($lineoutvol)) {
							echo("Warning - No lineoutvol setting\n");
							$lineoutvol = 50;
							}
			$command = "amixer set Master " . $lineoutvol . "% unmute";
			exec($command,$output,$status);
			if ($status) {echo($output);}


			$sql = "SELECT value FROM extaudio WHERE variable = 'capturevol'";
			$result = sql($sql,"getRow",DB_FETCHMODE_ASSOC);
			$capturevol = $result['value'];
			if (!isset($capturevol)) {
							echo("Warning - No capturevol setting\n");
							$capturevol = 50;
							}

			//capture volume seems to cause distortion above 25%
			$reducedcapturevol = $capturevol / 4;

			$command = "amixer set Capture " . $reducedcapturevol . "% unmute";
			exec($command,$output,$status);
			if ($status) {echo($output);}


			$sql = "SELECT value FROM extaudio WHERE variable = 'pagingvol'";
			$result = sql($sql,"getRow",DB_FETCHMODE_ASSOC);
			$pagingvol = $result['value'];
			if (!isset($pagingvol)) {
							echo("Warning - No pagingvol setting\n");
							$pagingvol = 100;
							}
			$command = "amixer set PCM " . $pagingvol . "% unmute";
			exec($command,$output,$status);
			if ($status) {echo($output);}


			$sql = "SELECT value FROM extaudio WHERE variable = 'muzakvol'";
			$result = sql($sql,"getRow",DB_FETCHMODE_ASSOC);
			$muzakvol = $result['value'];
			if (!isset($muzakvol)) {
							echo("Warning - No muzakvol setting\n");
							$muzakvol = 100;
							}
			$command = "amixer set Line " . $muzakvol . "% unmute cap";
			exec($command,$output,$status);
			if ($status) {echo($output);}

			$pagingmuzak = "/usr/bin/amixer set Line 0% unmute cap";
			$nopagingmuzak = "/usr/bin/amixer set Line " . $muzakvol . "% unmute cap";


// "extaudio" destinations
			$ext->add('app-extaudio', 'audioout', '', new ext_noop('External Audio Out'));
			$ext->add('app-extaudio', 'audioout', '', new ext_ringing());
			$ext->add('app-extaudio', 'audioout', '', new ext_wait(1));
			$ext->add('app-extaudio', 'audioout', '', new ext_playback(public_address_system));
			$ext->add('app-extaudio', 'audioout', '', new ext_playback(beep));
			$ext->add('app-extaudio', 'audioout', '', new ext_system($pagingmuzak));
			$ext->add('app-extaudio', 'audioout', '', new ext_dial("console/dsp,20,A(beep)"));
			$ext->add('app-extaudio', 'h', '', new ext_noop('Hungup so returning to normal extaudio settings'));
			$ext->add('app-extaudio', 'h', '', new ext_system($nopagingmuzak));
			$ext->add('app-extaudio', 'h', '', new ext_hangup());

	break;
	}
}



?>
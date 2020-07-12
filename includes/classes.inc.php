<?php
/*
#######################################################################
# DWodp live
#      - version 1.2.4
#      - Copyright (c) 2003-2004 Dominion Web Design
#      - http://www.dominion-web.com/products/dwodp_live/
#######################################################################
#
#  This file is part of DWodp live.
#
#  DWodp live is free software; you can redistribute it and/or modify
#  it under the terms of the GNU General Public License as published by
#  the Free Software Foundation; either version 2 of the License, or
#  (at your option) any later version.
#
#  DWodp live is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU General Public License for more details.
#
#  You should have received a copy of the GNU General Public License
#  along with DWodp live; if not, write to the Free Software
#  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307
#  USA
#
#######################################################################
*/

// Class to read and write files.  Read function is used both
// for remote and cached files but use different methods to achieve it
class fileProperties {
	var $_file;
	var $DocumentEncoding;

	function fileProperties($DocumentEncoding) {
		$this->DocumentEncoding = $DocumentEncoding;
	}

	function fileRead($whichfile) {
		$this->_file=$whichfile;
		if (strstr($this->_file, "http://") == TRUE) {
			$timeout = 20;
			$domain_url = str_replace("http://", "", $this->_file);
			$domainarry = explode("/", $domain_url);
			$domain = $domainarry[0];
			// Bug fix here to make sure that _ / , : are converted back to their proper terms.
			// We then URL encode the core ISO data so DMOZ reads it OK.


			$encodedurl = encodecategory($this->_file);

			$finalfile = str_replace("http://" . $domain, "", $encodedurl);

			$fp = @fsockopen($domain, 80, $errno, $errstr, $timeout);
			if(! $fp) {
				echo ("<p>Unable to connect: Timeout of $timeout reached on port 80 to domain $domain</p>");
				exit;
			}
			else {
				fwrite($fp, "GET $finalfile HTTP/1.0\r\n");
				fwrite($fp, "Host: $domain\n");
				fwrite($fp, "User-Agent: Mozilla/2.0 (compatible; DWodp live 1.2.4)\r\n\r\n");
				while(! feof($fp)) {
					$result .= fread($fp, 512);
				}
				fclose($fp);
				$pieces = explode("\r\n\r\n", $result);
				$headers = $pieces[0];
				$response = $pieces[1];

				$filecontents = $response;
			}
			if($this->DocumentEncoding=="iso-8859-1") $filecontents = utf8_decode($filecontents);
		}
		else {
			$fp = fopen($this->_file, "r");
			$filecontents = fread($fp, 200000);
			fclose($fp);
		}
		return $filecontents;
	}

	function fileWrite($whichfile, $filecontents) {
		$this->_file=$whichfile;
		$fp = fopen ($this->_file, "w"); 
		fputs ($fp, $filecontents, strlen($filecontents));
		fclose ($fp);
	}
}

// Class for the caching process
class cacheProperties {
	var $_currcat;
	var $_currfile;
	var $_refresh;
	var $_currdatetime;
	var $_filedatetime;
	var $DocumentEncoding;

	function cacheProperties($currcat, $refresh, $dmozfile, $DocumentEncoding) {
		$this->_currcat=$currcat;
		$this->_refresh=$refresh;
		$this->_dmozfile=$dmozfile;
		$this->_currfile=$currfile = str_replace("/", "_", substr($this->_currcat=$currcat, 1)) . ".txt";
		$this->DocumentEncoding = $DocumentEncoding;
	}

	function getCurrentDateTime() {
		$this->_currdatetime = gmdate("d/m/Y H:i.s");
		return $this->currdatetime;
	}

	function getFileDateTime() {
		$this->_filedatetime= filemtime("./cache/" . $this->_currfile);
	}

	function cacheFile() {
		$this->checkCacheDir();
		$filecheck = file_exists("./cache/" . $this->_currfile);
		if ($filecheck == 1) {
			$fileDate = filemtime("./cache/" . $this->_currfile);
			$filemonth = date("m",$fileDate);
			$fileyear = date("Y",$fileDate);
			$fileday = date("d",$fileDate);
			$filesec = date("s",$fileDate);
			$filehour = date("H",$fileDate);
			$filemin = date("i",$fileDate);
			$unixfile =  mktime ($filehour, $filemin, $filesec, $filemonth, $fileday, $fileyear);
			$currmonth = date("m");
			$curryear = date("Y");
			$currday = date("d");
			$currsec = date("s");
			$currhour = date("H");
			$currmin = date("i");
			$unixcurr =  mktime ($currhour, $currmin, $currsec, $currmonth, $currday, $curryear);
			$datediff = $unixcurr - $unixfile;

			if ($datediff >= $this->_refresh) {
				$getFile = new fileProperties($this->DocumentEncoding);
				$fileread = $getFile->fileRead($this->_dmozfile);
				$getFile->fileWrite("./cache/" . $this->_currfile, $fileread);
			}
		}
		else {
			$getFile = new fileProperties($this->DocumentEncoding);
			$fileread = $getFile->fileRead($this->_dmozfile);
			$getFile->fileWrite("./cache/" . $this->_currfile, $fileread);
		}
		return "./cache/" . $this->_currfile;
	}

	// This function will only run if the cache directory doesn't exist
	// in your directory structure.  If it creates errors it may be that
	// PHP doesn't not have permission.  Simply create the cache directory
	// and the error will go away
	function checkCacheDir() {
		$filecheck = file_exists("./cache");
		if ($filecheck <> 1) {
			mkdir ("./cache", 0777);
		}
	}
}

function CheckSlashes($string) {
	if(!get_magic_quotes_gpc()) {
		$string = addslashes($string);
	}
	return $string;
}
function CheckSlashesRemove($string) {
	if(get_magic_quotes_gpc()) {
		$string = stripslashes($string);
	}
	return $string;
}

function encodecategory($string) {
	// It's easier for us here just to encode the entire url and replace back the important bits			
	$encodedurl = urlencode($string);
	$CodeSearch = array (
		"%2F",
		"%3A",
		"%3F",
		"%3D",
		"%2C",
		"%26"
	);

	$CodeReplace = array (
		"/",
		":",
		"?",
		"=",
		",",
		"&"
	);

	return $encodedurl = str_replace ($CodeSearch, $CodeReplace, $encodedurl);
}

function unhtmlentities ($string) {
   $trans_tbl = get_html_translation_table (HTML_ENTITIES);
   $trans_tbl = array_flip ($trans_tbl);
   return strtr ($string, $trans_tbl);
}

function GetEncoding($html) {
	$htmllines = explode("\n", $html);
	for ($i=0; $i<count($htmllines); $i++) {
		if (strstr($htmllines[$i], '<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=')) {
			$EncodingStartPos = strpos($htmllines[$i], '<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=');
			$EncodingEndPos = strpos($htmllines[$i], '">') - 60;
			$DocumentEncoding = substr($htmllines[$i], $EncodingStartPos+60, $EncodingEndPos);
			unset ($EncodingStartPos);
			unset ($EncodingEndPos);
		}
	}
	if (!isset($DocumentEncoding)) {
		$DocumentEncoding = 'iso-8859-1';
	}
	return $DocumentEncoding;
}

?>

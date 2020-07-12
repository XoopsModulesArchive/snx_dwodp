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
# You should only need to edit ./includes/config.inc.php and the 
# templates in ./templates/ directory
#######################################################################
*/
include_once("../../mainfile.php");

/**
* MAIN SECTION
*/

if ($xoopsConfig['startpage'] == $xoopsModule->dirname()) {
    $xoopsOption['show_rblock'] = 1;
    include(XOOPS_ROOT_PATH . "/header.php");
    if (empty($HTTP_GET_VARS['start'])) {
        make_cblock();
        echo "<br />";
    } 
} else {
    $xoopsOption['show_rblock'] = 0;
    include(XOOPS_ROOT_PATH . "/header.php");
}

error_reporting  (E_ERROR | E_WARNING | E_PARSE);
require ("./includes/config.inc.php");
require ("./includes/classes.inc.php");

global $rooturl, $searchstring, $filename;

$filename = $_SERVER['SCRIPT_NAME'];

//////////////////////////////////////////////////////////////////////////////
// Get core variables

// Set the query string for links depending on whether short mode is selected
if ($use_short == 1) {
	$browseurl = $filename;
}
else {
	$browseurl = $filename . "?c=";
}

// Set the category depending on short mode
if ($use_short == 1) {
	$browse = str_replace($_SERVER['SCRIPT_NAME'], "", $_SERVER['PHP_SELF']); 
}
else {
	$browse = $_GET['c'];
}

$searchdecode = 0;
if ($_GET['s'] && $use_short <> 1) {
	$searchdecode = 1;
}
elseif (strstr($browse, "/search") && $use_short == 1) {
	$browse = str_replace("/search", "", $browse);
	$searchdecode = 1;	
}

// Clean up category for invalid characters
$browse = CheckSlashesRemove($browse);
$browse = str_replace("..", "", $browse);
$browse = str_replace("<", "&lt;", $browse);
$browse = str_replace(">", "&gt;", $browse);

// Set the attribution category
if ($browse) {
	$attributioncat = encodecategory($browse);
}
else {
	$attributioncat = "/";
}

// Set the search string
$searchstring = $_POST['search'];
if ($searchstring == "") {
	$searchstring = $_GET['search'];
}
$searchstring = str_replace("..", "", $searchstring);
$searchstring = str_replace("<", "&lt;", $searchstring);
$searchstring = str_replace(">", "&gt;", $searchstring);

$start = $_GET['start'];
$morecat = $_GET['morecat'];
$DocumentEncoding = 'iso-8859-1';

//////////////////////////////////////////////////////////////////////////////
// Set the breadcrumb trail

if (($browse) && ($browse <> "/")) {
	$cat = $browse;
	$breadcrumb = "<p class=\"breadcrumb\"><a href='" . $filename . "'>Top</a>: ";

	$array = explode("/", $browse );
	foreach ($array as $stritem) {
		if ($stritem <> "") {
			if($DocumentEncoding == 'iso-8859-1') $stritem = utf8_decode($stritem);
			$add = $add . "/" . $stritem;
			$breadcrumb .= "<a href='" . $browseurl . str_replace("%2F", "/", urlencode($add)) . "/'>" . $stritem . "</a>: ";
		}
	}
	$breadcrumb .= "</p>";
}
elseif ($searchstring) {
	$breadcrumb = "<p class=\"breadcrumb\"><a href='" . $filename . "'>Top</a>: Search:</p>";
}

//////////////////////////////////////////////////////////////////////////////
// Set search restriction if category is defined

if (($browse <> "") && ($browse <> "/")) {
	$currentcatfix = substr(encodecategory($browse), 1);
	$searchoptions = "<select name=\"all\"><option selected=\"selected\" value=\"yes\">search the entire directory</option>
<option value=\"no\">search this category only</option>
</select>
<input type=\"hidden\" name=\"cs\" value=\"\" />
<input type=\"hidden\" name=\"cat\" value=\"$currentcatfix\">";
}
else {
	$searchoptions = "";
}

//////////////////////////////////////////////////////////////////////////////
// Set the DMOZ template config

// Set variables to look for in the DMOZ template
// This is the bit that changes when they change their template
$LinkStart = '<a href="';

$search_next = '<a href="search';
$search_next_replace = '<a href="' . $filename;

// Search url for DMOZ. Only change if DMOZ change their config
if ($_POST['all'] == "no") {
	$searchurl = "http://search.dmoz.org/cgi-bin/search?all=no&cs=&cat=" . $searchin . "&search=";
}
else {
	$searchurl = "http://search.dmoz.org/cgi-bin/search?search=";
}

// Look for start string on a category
$CatStart = "<hr>";
// Look for end string on a category
$CatEnd = '<table width="95%" cellpadding=0';

// Look for start string when searching
$SearchStart = "<CENTER>Search:";
// Look for end string when searching
$SearchEnd = "<TABLE cellpadding=0";
// Look for a null return on search results
$SearchNull = 'No <b><a href="http://dmoz.org/">Open Directory Project</a></b> results found';
// Look for a heavy load message on search results
$SearchLoad = '<I>The Open Directory search is currently under a heavy load. Please try back later.</I>';

//////////////////////////////////////////////////////////////////////////////
// Set up DWodp templates

$getFile = new fileProperties($DocumentEncoding);
$MainAttribution = $getFile->fileRead("./templates/odp_attribution.tpl");
$MainCopyright = $getFile->fileRead("./templates/copyright.tpl");
$MainSearchBox = $getFile->fileRead("./templates/searchbox.tpl");
$MainHeader = $getFile->fileRead("./templates/header.tpl");
$MainFooter = $getFile->fileRead("./templates/footer.tpl");

if ($AdvancedListings == 1 && $AllowThumbShots == 1) {
	$ThumbAttribution = $getFile->fileRead("./templates/thumbshots_attribution.tpl");
}
else {
	$ThumbAttribution = '';
}

$CodeSearch = array (
	"[searchbox]",
	"[copyright]",
	"[odp_attribution]",
	"[thumbshots_attribution]",
	"[breadcrumb]",
	"[dwodp]",
	"[currentcat]",
	"[imagedirectory]",
	"[sitetitle]",
	"[options]"
);

$CodeReplace = array (
	$MainSearchBox,
	$MainCopyright,
	$MainAttribution,
	$ThumbAttribution,
	$breadcrumb,
	$filename,
	$attributioncat,
	$Local_images,
	$sitetitle,
	$searchoptions
);

$MainAttribution = str_replace ($CodeSearch, $CodeReplace, $MainAttribution);
$MainSearchBox = str_replace ($CodeSearch, $CodeReplace, $MainSearchBox);
$MainHeader = str_replace ($CodeSearch, $CodeReplace, $MainHeader);
$MainFooter = str_replace ($CodeSearch, $CodeReplace, $MainFooter);

//////////////////////////////////////////////////////////////////////////////
// The core program

// There are three core sections, Main, Browse and Search
// Main: either renders the mainpage template or your default category
// Browse: is the current category determined by the query string
// Search: Displays search results.  These cannot be cached

if ((($browse == "") || ($browse == "/")) && ($searchstring == "")) {

	// No default category set
	if ($rootcategory == "") {
		$html = $getFile->fileRead("./templates/mainpage.tpl");
		$html = str_replace ($CodeSearch, $CodeReplace, $html);

		// Regular expression to format the links in the mainpage template
		$mainsearcharray = array(
			"/(\[)(mainlink)(=)(['\"]?)([^\"']*)(\\4])(.*)(\[\/mainlink)(((=)(\\4)([^\"']*)(\\4]))|(\]))/siU",
			"/(\[)(link)(=)(['\"]?)([^\"']*)(\\4])(.*)(\[\/link)(((=)(\\4)([^\"']*)(\\4]))|(\]))/siU"
		);
		$mainreplacearray = array(
			"<a href=\"{url}\\5\">\\7</a>",
			"<a href=\"{url}\\5\">\\7</a>"
		);
		$html = preg_replace($mainsearcharray, $mainreplacearray, $html);

		if ($use_short == 1) {
			$html = str_replace ("{url}", $filename, $html);
		}
		else {
			$html = str_replace ("{url}", $filename . "?c=", $html);
		}

		$finalhtml = $MainHeader . $html . $MainFooter;	
		echo $finalhtml;
	}
	else {

		// Default category set, this is almost the same code as the browse category section
		if ($use_cache == 1) {
			$cacheset = new cacheProperties($rootcategory, $cache_refresh, $rooturl . $rootcategory, $DocumentEncoding);
			$cachedfile = $cacheset->cacheFile();

			$html = $getFile->fileRead($cachedfile);
		}
		else {
			$html = $getFile->fileRead($rooturl . $rootcategory);
		}

//		$DocumentEncoding = GetEncoding($html);
		$MainHeader = str_replace ('[encoding]', $DocumentEncoding, $MainHeader);
		
		$HTMLStartPos = strpos( $html, "[ <a" );
		if ($HTMLStartPos <> FALSE) {
			$TestVar = TRUE;
		}

		if ($TestVar == FALSE) {
			$HTMLStartPos = strpos($html, $CatStart);
		}
		$html = substr($html, $HTMLStartPos, strlen($html));
		if($TestVar == TRUE) {
			$html = "<br /><center>" . $html;
		}

		$HTMLEndPos = strpos($html, $CatEnd);
		$html = substr($html, 0, $HTMLEndPos);

 		if ($AdvancedListings == 1) {
			if ($AllowThumbShots == 1) {
				$AdvLinks = $getFile->fileRead("./templates/advanced_thumbshots.tpl");
				$ThumbShots = $getFile->fileRead("./templates/thumbshots.tpl");
			}
			else {
				$AdvLinks = $getFile->fileRead("./templates/advanced_links.tpl");
			}
		
			$htmllines = explode("\n", $html);
			$newhtml = "";
			for ($i=0; $i<count($htmllines); $i++) {
				if (strstr($htmllines[$i], '<li><a href="http://')) {
					$LinkStartPos = strpos($htmllines[$i], 'http://');
					$LinkEndPos = strpos($htmllines[$i], '">') - $LinkStartPos;
					$tmpurl = substr($htmllines[$i], $LinkStartPos, $LinkEndPos);
					$htmllines[$i] = str_replace("<li>", "", $htmllines[$i]);
					$editosdesc = '';
					if (substr($htmllines[$i+1], 0, 3) == " - ") {
						$editorsdesc = $htmllines[$i+1];
						$htmllines[$i+1] = '';
					}
					if ($AllowThumbShots == 1) {
						$ThumbShots_tmp = str_replace("[site_url]", $tmpurl, $ThumbShots);
						$tmpline = $AdvLinks;
						$tmpline = str_replace("[thumbshots]", $ThumbShots_tmp, $tmpline);
						$tmpline = str_replace("[siteinfo]", $htmllines[$i] . $editorsdesc, $tmpline);
						$tmpline .= "\n";
					}
					else {
						$tmpline = $AdvLinks;
						$tmpline = str_replace("[siteinfo]", $htmllines[$i] . $editorsdesc, $tmpline);
						$tmpline .= "\n";
					}
					$newhtml .= $tmpline;
					unset ($ThumbShots_tmp);
					unset ($LinkEndPos);
					unset ($tmpurl);
					unset ($tmpline);
				}
				else {
					$newhtml .= $htmllines[$i] . "\n";
				}
			}
			$html = $newhtml;
			unset ($newhtml);
		}

		$html = str_replace("<hr>", "<hr noshade=\"noshade\" size=\"1\" />", $html);
		$html = str_replace($LinkStart . "/", $LinkStart . $browseurl . "/", $html);
		$html = str_replace('<img src="' . $ODP_images, '<img src="' . $Local_images, $html);

		if ($LinkTarget <> "") {
			$html = str_replace('<a href="http://', '<a target="' . $LinkTarget . '" href="http://', $html);
		}

		$finalhtml = $MainHeader . $html . $MainFooter;	
		if ($ContentTypeHeader == 1) {
			header ("Content-Type: text/html; charset=" . $DocumentEncoding);
		}
		echo $finalhtml;
	}

}

// Browse category section
elseif ($browse <> "") {
	if ($use_cache == 1) {
		$cacheset = new cacheProperties($browse, $cache_refresh, $rooturl . $browse, $DocumentEncoding);
		$cachedfile = $cacheset->cacheFile();

		$html = $getFile->fileRead($cachedfile);
	}
	else {
		$html = $getFile->fileRead($rooturl . $browse);
	}

	// New system to automatically detect the encoding of a page (as the ODP is currently making all /Word categories UTF-8)
//	$DocumentEncoding = GetEncoding($html);
	$MainHeader = str_replace ('[encoding]', $DocumentEncoding, $MainHeader);

	$HTMLStartPos = strpos($html, "[ <a");
	if($HTMLStartPos != FALSE) {
		$TestVar = TRUE;
	}

	if($TestVar == FALSE) {
		$HTMLStartPos = strpos($html, $CatStart);
	}
	$html = substr($html, $HTMLStartPos, strlen($html));
	if($TestVar == TRUE) {
		$html = "<br /><center>" . $html;
	}
	
	$HTMLEndPos = strpos($html, $CatEnd);
	$html = substr($html, 0, $HTMLEndPos);
 	if ($AdvancedListings == 1) {
		if ($AllowThumbShots == 1) {
			$AdvLinks = $getFile->fileRead("./templates/advanced_thumbshots.tpl");
			$ThumbShots = $getFile->fileRead("./templates/thumbshots.tpl");
		}
		else {
			$AdvLinks = $getFile->fileRead("./templates/advanced_links.tpl");
		}
		
		$htmllines = explode("\n", $html);
		$newhtml = "";
		for ($i=0; $i<count($htmllines); $i++) {
			if (strstr($htmllines[$i], '<li><a href="http://')) {
				$LinkStartPos = strpos($htmllines[$i], 'http://');
				$LinkEndPos = strpos($htmllines[$i], '">') - $LinkStartPos;
				$tmpurl = substr($htmllines[$i], $LinkStartPos, $LinkEndPos);
				$htmllines[$i] = str_replace("<li>", "", $htmllines[$i]);
				$editorsdesc = '';
				if (substr($htmllines[$i+1], 0, 3) == " - ") {
					$editorsdesc = $htmllines[$i+1];
					$htmllines[$i+1] = '';
				}
				if ($AllowThumbShots == 1) {
					$ThumbShots_tmp = str_replace("[site_url]", $tmpurl, $ThumbShots);
					$tmpline = $AdvLinks;
					$tmpline = str_replace("[thumbshots]", $ThumbShots_tmp, $tmpline);
					$tmpline = str_replace("[siteinfo]", $htmllines[$i] . $editorsdesc, $tmpline);
					$tmpline .= "\n";
				}
				else {
					$tmpline = $AdvLinks;
					$tmpline = str_replace("[siteinfo]", $htmllines[$i] . $editorsdesc, $tmpline);
					$tmpline .= "\n";
				}
				$newhtml .= $tmpline;
				unset ($ThumbShots_tmp);
				unset ($LinkEndPos);
				unset ($tmpurl);
				unset ($tmpline);
			}
			else {
				$newhtml .= $htmllines[$i] . "\n";
			}
		}
		$html = $newhtml;
		unset ($newhtml);
	}

	$html = str_replace("<hr>", "<hr noshade=\"noshade\" size=\"1\" />", $html);
	$html = str_replace($LinkStart . "/", $LinkStart . $browseurl . "/", $html);
	$html = str_replace( '<img src="' . $ODP_images, '<img src="' . $Local_images, $html );

	if ($LinkTarget <> "") {
		$html = str_replace('<a href="http://', '<a target="' . $LinkTarget . '" href="http://', $html);
	}

	$finalhtml = $MainHeader . $html . $MainFooter;	

	if ($ContentTypeHeader == 1) {
		header ("Content-Type: text/html; charset=" . $DocumentEncoding);
	}
	echo $finalhtml;

}

// Search section
elseif ($searchstring <> "") {
	$searchstring = CheckSlashes($searchstring);
	$searchurl = $searchurl . $searchstring . ($start == "" ? "" : "&start=" . $start) . ($morecate == "" ? "" : "&morecat=" . $morecat);
	$html = $getFile->fileRead($searchurl);

//	$DocumentEncoding = GetEncoding($html);
	$MainHeader = str_replace ('[encoding]', $DocumentEncoding, $MainHeader);


	if (strpos($html, $SearchLoad) <> FALSE) {
		$html = $getFile->fileRead("./templates/search_heavyload.tpl");
	}
	elseif (strpos($html, $SearchNull) <> FALSE) {
		$html = $getFile->fileRead("./templates/search_noresult.tpl");
	}
	else {
		$HTMLStartPos = strpos($html, $SearchStart);
		$html = substr($html, $HTMLStartPos, strlen($html));
		$HTMLEndPos = strpos($html, $SearchEnd);
		$html = substr($html, 0, $HTMLEndPos);

		$html = str_replace("<hr>", "<hr noshade=\"noshade\" size=\"1\" />", $html);
		$html = str_replace($LinkStart . "/", $LinkStart . $browseurl . "/", $html);

		if ($use_short == 1) {
			$html = str_replace("http://dmoz.org", "$filename/search", $html);
		}
		else {
			$html = str_replace("http://dmoz.org", "$filename?s=1&amp;c=", $html);
		}

		$html = str_replace($search_next, $search_next_replace, $html);

		if ($use_short == 1) {
			$html = str_replace($filename . '?browse=search?', $filename, $html);
		}
		else {
			$html = str_replace($filename . '?browse=search?', $filename . "?c=", $html);
		}

		if ($LinkTarget <> "") {
			$html = str_replace('<a href="http://', '<a target="' . $LinkTarget . '" href="http://', $html);
		}

		$html = str_replace('<img src="' . $ODP_images, '<img src="' . $Local_images, $html);

	}

	if ($ContentTypeHeader == 1) {
		header ("Content-Type: text/html; charset=" . $DocumentEncoding);
	}
	$finalhtml = $MainHeader . $html . $MainFooter;	
	echo $finalhtml;
		
}

include_once (XOOPS_ROOT_PATH . "/footer.php");

//////////////////////////////////////////////////////////////////////////////
// That's it!
//////////////////////////////////////////////////////////////////////////////
?>
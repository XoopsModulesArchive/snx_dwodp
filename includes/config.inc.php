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

// Cache the results of browse category pages to speed up your directory
$use_cache = 1;

// How often in seconds to refresh a cached version of a page
$cache_refresh = 7200;

// Use short access method
//  e.g http://domain.com/index.php/Arts/ instead of
//  http://domain.com/index.php?c=/Arts/
//  If you are using Linux or a Unix flavour on Apache and you are getting
//  a 404 error message when using short mode see
//  http://httpd.apache.org/docs-2.0/mod/core.html#acceptpathinfo
//
//  Alternatively you could use mod_rewrite to achieve this method
$use_short = 1;

// Title of your site
$sitetitle = "DWodp live";

// The root url of the Open Directory
$rooturl = "http://dmoz.org";

// Do you want to restrict your site to a default category
$rootcategory = "";

// Path to the ODP image directory
$ODP_images = "/img/";

// HTTP path to your local images directory
//  This must be a root url if you are using short mode
$Local_images = XOOPS_URL."/modules/snx_dwodp/images/";

// Target for links
//  Can be _self, _blank, _top, _self, a frame name or simply leave it blank
$LinkTarget = "";

// Use advanced site listings
//  This is more dependant of the specific HTML of the DMOZ site
$AdvancedListings = 1;

// Include content-type header
//  - this will output the content-type and character set in the HTTP headers
//    useful if your PHP installation has a character set pre-defined
//    This is a user option as it may conflict with other content managed solutions
$ContentTypeHeader = 1;

// Use thumbshots with the directory
//  Thumbshots are screenshots of web pages in thumbnail sizes from
//  http://www.thumbshots.org/
//  If you make use of this option please read
//  http://www.thumbshots.org/license.pxf and
//  http://www.thumbshots.org/attribution.pxf
//  This requires Advanced Listings to be enabled
// 1 = Enable
// 0 = Disable
$AllowThumbShots = 1;
?>
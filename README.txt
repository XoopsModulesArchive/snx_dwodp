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
README & INSTALLATION FILE

1) SUPPORT FAQ
   - http://www.dominion-web.com/products/knowledgebase/index.php?option=category&type=summary&cat=18
   (Please note as this is a freeware product we don't guarantee support levels
   as per the DWodp live license agreement)

#######################################################################
2) INSTALLATION

DWodp live should work on any web server with PHP 4.1.0 or greater and sockets support.

Unzip the files and place them in their respective directories 

Edit the file called config.inc.php from the includes directory in your favourite text editor, such as Notepad and make any changes.

If you are intending to use DWodp live with caching you must change the permissions on the 'cache' directory to be writable.  On Unix chmod 757 will suffice.

You will probably need to make changes to config.inc.php. To make changes to any of the default settings, enter the correct setting between the two sets of "quotes". 

$use_cache = Do you wish to cache offline accesses to the ODP (this will speed up your site)
$cache_refresh = How often in seconds should a cached page be refreshed (if accessed)
$use_short = Normal mode http://domain.com/index.php?c=/Arts/, Short mode http://domain.com/index.php/Arts/
$sitetitle = What's the title of your directory
$rooturl = Location of the ODP (you shouldn't need to change this).  We advise against using a DMOZ mirror site
$rootcategory = Do you want your directory to default to a certain category.  If so enter the full category name here e.g. /Arts/
$ODP_images = Image directory on the ODP site (you shouldn't need to change this).
$Local_images = Path the the images folder on your site where the ODP images are stored.
$LinkTarget = Window target for http:// defined links

Upload the files to your server using the same directory structures as defined

DWodp live should now work. (see the FAQ for more details on the templates. Any code in [] brackets is required by DWodp live to render the screens. Removing these may cause problems, but you can change their position in the template for greater customisation) 

#######################################################################
3) CHANGE LOG

Version 1.2.4 - July 2004
- Bug fix - Urlencoding of breadcrumb trail problem introduced in 1.2.3 fixed

Version 1.2.3 - July 2004
- Code change - DWodp live now released under the GNU General Public License (GPL)
- Bug fix - Force HTTP content-type header with character set (controllable through a config option)
- Bug fix - Fixed French and Spanish links after the ODP has changed the default charactersets for those languages

Version 1.2.2 - February 2004
- Code change - Detection of remote character set is now auto-detected as the /World category is being converted to UTF-8.
- Bug fix - Cleaned up some possible issues with register_globals and searching within a category

Version 1.2.1 - January 2004
- Bug fix - Incorrect variable name caused an editor's pick site description to be repeated for all descriptions in a category (if advanced listing enabled)

Version 1.2 - January 2004
+ New feature - Advanced, customisable site listings and thumbshots.org support
- Bug fix - Search results caused a problem with non-english characters.  Added a UTF8 decode routine for categories.

Version 1.1.5 - December 2003

+ New feature - Requested feature added to allow you to define a target to any links which leave your domain (does not parse the header or footers with the target, just DMOZ content)
- Bug fix - Error reporting level manually defined

Version 1.1.4 - August 2003

- Bugfix - Multiple keyword searches failed due to DMOZ site not recognising url encoded spaces properly.  URLEncoded spaces have been removed

Version 1.1.3 - August 2003

- Bugfix - Lots of encoding checking. Foreign language sites still causing problems. This should now be fixed. To keep the data working the main categories must in iso-8859-1 and search results in UTF-8. However there is a bug directly on the the DMOZ web site search (on their site) which causes foreign language category links from search pages to fail. Unfortunately as we take data directly from the live DMOZ website this bug is replicated in DWodp live 
- Bugfix - /World language character encoding fixed
- Bugfix - Check for magic_quotes_gpc in category as, if quotes were in the category name if caused the listing to fail 
- Bugfix - New url encoding routine for checking the DMOZ site 

Version 1.1.2 - July 2003

- Bugfix - ./templates/header.tpl - Encoding replacement variable added as the main directory seems to require a different encoding from the search routine. If your character set on your browser wasn't UTF-8 before some of the foreign language sections of the directory may have appeared strangely. 
- Bugfix - ./index.php - Short mode wasn't working properly due to a typo in a variable name introduced in version 1.1.1. 

Version 1.1.1 - June 2003

- Bugfix - ./templates/header.tpl - Added meta tag to make the default character set of the page UTF-8 as this is the format used by DMOZ.org. If your character set on your browser wasn't UTF-8 before some of the foreign language sections of the directory may have appeared strangely. 
- Bugfix - ./includes/classes.inc.php, ./index.php - Check on searching whether magic_quotes_gpc is enabled and run a stripslashes if necessary. 

May 2003

Renamed product to DWodp live due to release of DWodp pro 

Version 1.1 - February 2003

+ New feature - Added in the restrict to current category option for the search (thanks jjonas for the info)
- Bugfix - to breadcrumb trail to prevent someone entering a single forward slash as a category name (thanks jjonas for the info) 
- Bugfix - Prevent two full stops in a parsed category and search to stop directory traversal 
- Bugfix - Prevent angled brackets being parsed as a category and search to stop cross site scripting 

Version 1.0 - February 2003

+ First public release
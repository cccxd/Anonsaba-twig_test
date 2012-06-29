<?php
if (!headers_sent()) {
	header('Content-Type: text/html; charset=utf-8');
}
include_once('inc/classes/site.class.php');
$config = new SiteOption($value);

$cf = array();

// Caching (this needs to be set at the start because if enabled, it skips the rest of the configuration process)
	$cf['KU_APC'] = false;

$cache_loaded = false;
if ($cf['KU_APC']) {
	if (apc_load_constants('config')) {
		$cache_loaded = true;
	}
}

if (!$cache_loaded) {
	// Database
		$cf['KU_DBTYPE']          = 'mysql';	// Database type. Valid value is mysql. 
		$cf['KU_DBHOST']          = 'localhost'; // Database hostname. On SQLite this has no effect.
		$cf['KU_DBDATABASE']      = 'anonsaba'; // Database... database. On SQLite this will be the path to your database file. Secure this file.
		$cf['KU_DBUSERNAME']      = 'root'; // Database username. On SQLite this has no effect.
		$cf['KU_DBPASSWORD']      = ''; // Database password. On SQLite this has no effect.
		$cf['KU_DBPREFIX']        = ''; // Database table prefix
		$cf['KU_DBUSEPERSISTENT'] = false; // Use persistent connection to database
	// Root 
		// For the love of god change this 
		$cf['KU_ROOT'] = 'CHANGEME'; //Can do SQL injections and can not be modified, suspended, or deleted / can clear the modlog. Make this your administrator account

	// Paths and URLs
		// Main installation directory
			$cf['KU_ROOTDIR']   = realpath(dirname(__FILE__))."/"; // Full system path of the folder containing kusaba.php, with trailing slash. The default value set here should be OK.. If you need to change it, you should already know what the full path is anyway.
			$cf['KU_WEBFOLDER'] = 'CHANGEME'; // The path from the domain of the board to the folder which kusaba is in, including the trailing slash.  Example: "http://www.yoursite.com/misc/kusaba/" would have a $cf['KU_WEBFOLDER'] of "/misc/kusaba/"
			$cf['KU_WEBPATH']   = 'CHANGEME'; // The path to the index folder of kusaba, without trailing slash. Example: http://www.yoursite.com
			$cf['KU_DOMAIN']    = 'CHANGEME'; // Used in cookies for the domain parameter.  Should be a period and then the top level domain, which will allow the cookies to be set for all subdomains.  For http://www.randomchan.org, the domain would be .randomchan.org; http://zachchan.freehost.com would be zach.freehost.com

		// Board subdomain/alternate directory (optional, change to enable)
			// DO NOT CHANGE THESE IF YOU DO NOT KNOW WHAT YOU ARE DOING!!
			$cf['KU_BOARDSDIR']    = $cf['KU_ROOTDIR'];
			$cf['KU_BOARDSFOLDER'] = $cf['KU_WEBFOLDER'];
			$cf['KU_BOARDSPATH']   = $cf['KU_WEBPATH'];

		// CGI subdomain/alternate directory (optional, change to enable)
			// DO NOT CHANGE THESE IF YOU DO NOT KNOW WHAT YOU ARE DOING!!
			$cf['KU_CGIDIR']    = $cf['KU_BOARDSDIR'];
			$cf['KU_CGIFOLDER'] = $cf['KU_BOARDSFOLDER'];
			$cf['KU_CGIPATH']   = $cf['KU_BOARDSPATH'];

		// Coralized URLs (optional, change to enable)
			$cf['KU_WEBCORAL']    = ''; // Set to the coralized version of your webpath to enable.  If not set to '', URLs which can safely be cached will be coralized, and will use the Coral Content Distribution Network.  Example: http://www.kusaba.org becomes http://www.kusaba.org.nyud.net, http://www.crapchan.org/kusaba becomes http://www.crapchan.org.nyud.net/kusaba
			$cf['KU_BOARDSCORAL'] = '';

	// Templates
		$cf['KU_TEMPLATEDIR']       = $cf['KU_ROOTDIR'] . 'dwoo/templates'; // Dwoo templates directory
		$cf['KU_CACHEDTEMPLATEDIR'] = $cf['KU_ROOTDIR'] . 'dwoo/templates_c'; // Dwoo compiled templates directory.  This folder MUST be writable (you may need to chmod it to 755).  Set to '' to disable template caching



	// Pages
		$cf['KU_FIRSTPAGE'] = 'board.html'; // Filename of the first page of a board.  Only change this if you are willing to maintain the .htaccess files for each board directory (they are created with a DirectoryIndex board.html, change them if you change this)

	// File tagging
		$cf['KU_TAGS'] = array('Japanese' => 'J',
		                       'Anime'    => 'A',
		                       'Game'     => 'G',
		                       'Loop'     => 'L',
		                       'Other'    => '*'); // Used only in Upload imageboards.  These are the tags which a user may choose to use as they are posting a file.  If you wish to disable tagging on Upload imageboards, set this to ''

	// Special Tripcodes
		$cf['KU_TRIPS'] = array('#changeme'  => 'changeme',
		                        '#changeme2' => 'changeme2'); // Special tripcodes which can have a predefined output.  Do not include the initial ! in the output.  Maximum length for the output is 30 characters.  Set to array(); to disable

	// Extra features
		$cf['KU_RSS']             = true; // Whether or not to enable the generation of rss for each board and modlog
		$cf['KU_EXPAND']          = true; // Whether or not to add the expand button to threads viewed on board pages
		$cf['KU_QUICKREPLY']      = true; // Whether or not to add quick reply links on posts
		$cf['KU_WATCHTHREADS']    = true; // Whether or not to add thread watching capabilities
		$cf['KU_FIRSTLAST']       = true; // Whether or not to generate extra files for the first 100 posts/last 50 posts
		$cf['KU_BLOTTER']         = true; // Whether or not to enable the blotter feature
		$cf['KU_SITEMAP']         = false; // Whether or not to enable automatic sitemap generation (you will still need to link the search engine sites to the sitemap.xml file)
	// Check if you're reading
		$cf['readtest'] 	  = 'If you read this then delete this line'; //Just delete this line if you find it

	// Misc config
		$cf['KU_STATICMENU']        = false; // Whether or not to generate the menu files as static files, instead of linking to menu.php.  Enabling this will reduce load, however some users have had trouble with getting the files to generate
		$cf['KU_GENERATEBOARDLIST'] = true; // Set to true to automatically make the board list which is displayed ad the top and bottom of the board pages, or false to use the boards.html file

	// Language / timezone / encoding
		$cf['KU_LOCALE']  = 'en'; // The locale of kusaba you would like to use.  Locales available: en, de, et, es, fi, pl, nl, nb, ro, ru, it, ja
		$cf['KU_CHARSET'] = 'UTF-8'; // The character encoding to mark the pages as.  This must be the same in the .htaccess file (AddCharset charsethere .html and AddCharset charsethere .php) to function properly.  Only UTF-8 and Shift_JIS have been tested
		putenv('TZ=US/Central'); // The time zone which the server resides in
		$cf['KU_DATEFORMAT'] = 'd/m/y(D)H:i';

	// Debug
		$cf['KU_DEBUG'] = false; // When enabled, debug information will be printed (Warning: all queries will be shown publicly)

/*******************************************************************************************************************************************/
	// Post-configuration actions, don't modify anything below this seriously if you do it will break everything

		$cf['KU_NAME']      = $config('name'); 
		$cf['KU_SLOGAN']    = $config('slogan');
		$cf['KU_HEADERURL'] = $config('headerurl');
		$cf['KU_IRC']       = $config('IRC');
		$cf['KU_BANREASON']	= $config('banreason');
		$cf['KU_STYLES']        = $config('sitestyles');
		$cf['KU_DEFAULTSTYLE']  = $config('defaultstyle');
		$cf['KU_STYLESWITCHER'] = $config('styleswitcher');
		$cf['KU_DROPSWITCHER']	= $config('dropswitcher');
		$cf['KU_TXTSTYLES']        = $config('txtstyles');
		$cf['KU_DEFAULTTXTSTYLE']  = $config('defaulttxtstyle');
		$cf['KU_TXTSTYLESWITCHER'] = $config('txtswitcher');
		$cf['KU_MENUSTYLES']        = $config('menustyles');
		$cf['KU_DEFAULTMENUSTYLE']  = $config('defaultmenustyle');
		$cf['KU_MENUSTYLESWITCHER'] = $config('menuswitcher');
		$cf['KU_NEWTHREADDELAY'] = $config('newthreaddelay');
		$cf['KU_REPLYDELAY']     = $config('replydelay');
		$cf['KU_LINELENGTH']     = $config('linelength') * 15;
		$cf['KU_THUMBWIDTH']       = $config('thumbwidth');
		$cf['KU_THUMBHEIGHT']      = $config('thumbheight');
		$cf['KU_REPLYTHUMBWIDTH']  = $config('replythumbwidth');
		$cf['KU_REPLYTHUMBHEIGHT'] = $config('replythumbheight');
		$cf['KU_CATTHUMBWIDTH']    = $config('catwidth');
		$cf['KU_CATTHUMBHEIGHT']   = $config('catheight');
		$cf['KU_THUMBMETHOD']      = $config('thumbmethod');
		$cf['KU_ANIMATEDTHUMBS']   = $config('animatedthumbs');
		$cf['KU_NEWWINDOW']       = $config('newwindow');
		$cf['KU_MAKELINKS']       = $config('makelinks');
		$cf['KU_NOMESSAGETHREAD'] = $config('nomessagethread');
		$cf['KU_NOMESSAGEREPLY']  = $config('nomessagereply');
		$cf['KU_THREADS']         = $config('threads');
		$cf['KU_THREADSTXT']      = $config('threadstxt');
		$cf['KU_REPLIES']         = $config('replies');
		$cf['KU_REPLIESSTICKY']   = $config('replysticky'); 
		$cf['KU_THUMBMSG']        = $config('thumbmsg');
		$cf['KU_BANMSG']          = $config('banmsg');
		$cf['KU_TRADITIONALREAD'] = $config('tradread');
		$cf['KU_MODLOGDAYS']        = $config('modlogdays');
		$cf['KU_SALT']		    = $config('salt');
		$cf['KU_RANDOMSEED']        = $config('randomseed');
		$cf['KU_DIRTITLE']  = $config('dirtitle');
		$cf['KU_VERSION']    = $config('version');
		$cf['KU_TAGS']       = serialize($cf['KU_TAGS']);
		$cf['KU_TRIPS']      = serialize($cf['KU_TRIPS']);

		if (substr($cf['KU_WEBFOLDER'], -2) == '//') { $cf['KU_WEBFOLDER'] = substr($cf['KU_WEBFOLDER'], 0, -1); }
		if (substr($cf['KU_BOARDSFOLDER'], -2) == '//') { $cf['KU_BOARDSFOLDER'] = substr($cf['KU_BOARDSFOLDER'], 0, -1); }
		if (substr($cf['KU_CGIFOLDER'], -2) == '//') { $cf['KU_CGIFOLDER'] = substr($cf['KU_CGIFOLDER'], 0, -1); }

		$cf['KU_WEBPATH'] = trim($cf['KU_WEBPATH'], '/');
		$cf['KU_BOARDSPATH'] = trim($cf['KU_BOARDSPATH'], '/');
		$cf['KU_CGIPATH'] = trim($cf['KU_CGIPATH'], '/');

		if ($cf['KU_APC']) {
			apc_define_constants('config', $cf);
		}
		while (list($key, $value) = each($cf)) {
			define($key, $value);
		}
		unset($cf);
}
$readtest = array(readtest);
if (in_array('If you read this then delete this line', $readtest)) {
	echo 'You clearly didn\'t read through the config file try again';
	die();
}
// DO NOT MODIFY BELOW THIS LINE UNLESS YOU KNOW WHAT YOU ARE DOING OR ELSE BAD THINGS MAY HAPPEN
$modules_loaded = array();
$required = array(KU_ROOTDIR, KU_WEBFOLDER, KU_WEBPATH, KU_ROOT);
if (in_array('CHANGEME', $required) || in_array('', $required)){
	echo 'You must set KU_ROOTDIR, KU_WEBFOLDER, KU_WEBPATH, and KU_ROOT before installation will finish!';
	die();
}
require KU_ROOTDIR . 'lib/gettext/gettext.inc.php';

// Gettext
_textdomain('kusaba');
_setlocale(LC_ALL, KU_LOCALE);
_bindtextdomain('kusaba', KU_ROOTDIR . 'inc/lang');
_bind_textdomain_codeset('kusaba', KU_CHARSET);

// SQL  database
if (!isset($tc_db) && !isset($preconfig_db_unnecessary)) {
	require KU_ROOTDIR . 'inc/classes/database.class.php';
	if (KU_DEBUG) {
		error_reporting(E_ALL ^ E_NOTICE);
		require KU_ROOTDIR . 'inc/classes/debug-database.class.php';
		if (KU_DBUSEPERSISTENT) {
			$tc_db = new DebugDatabase(KU_DBTYPE.':host='.KU_DBHOST.';dbname='.KU_DBDATABASE, KU_DBUSERNAME, KU_DBPASSWORD, array(
				PDO::ATTR_PERSISTENT => true
			));
		} else {
			$tc_db = new DebugDatabase(KU_DBTYPE.':host='.KU_DBHOST.';dbname='.KU_DBDATABASE, KU_DBUSERNAME, KU_DBPASSWORD);
		}
		$tc_db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	} else {
		error_reporting(0);
		if (KU_DBUSEPERSISTENT) {
			$tc_db = new Database(KU_DBTYPE.':host='.KU_DBHOST.';dbname='.KU_DBDATABASE, KU_DBUSERNAME, KU_DBPASSWORD, array(
				PDO::ATTR_PERSISTENT => true
			));
		} else {
			$tc_db = new Database(KU_DBTYPE.':host='.KU_DBHOST.';dbname='.KU_DBDATABASE, KU_DBUSERNAME, KU_DBPASSWORD);
		}
	}
	$installed = '.installed';
	if (file_exists($installed)) {
		$results_events = $tc_db->GetAll('SELECT * FROM `' . KU_DBPREFIX . 'events` WHERE `at` <= ' . time());
		if (count($results_events) > 0) {
			foreach($results_events AS $line_events) {
				if ($line_events['name'] == 'sitemap') {
					$tc_db->Execute('UPDATE `' . KU_DBPREFIX . 'events` SET `at` = ' . (time() + 21600) . ' WHERE `name` = "sitemap"');
					if (KU_SITEMAP) {
						$sitemap = '<?xml version="1.0" encoding="UTF-8"?' . '>' . "\n" .
							'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n" . "\n";
						$results = $tc_db->GetAll('SELECT `name`, `id` FROM `' . KU_DBPREFIX . 'boards` ORDER BY `name` ASC');
						if (count($results) > 0) {
							foreach($results AS $line) {
								$sitemap .= '	<url>' . "\n" .
								'		<loc>' . KU_BOARDSPATH . '/' . $line['name'] . '/</loc>' . "\n" .
								'		<lastmod>' . date('Y-m-d') . '</lastmod>' . "\n" .
								'		<changefreq>hourly</changefreq>' . "\n" .
								'	</url>' . "\n";

								$results2 = $tc_db->GetAll('SELECT `id`, `bumped` FROM `' . KU_DBPREFIX . 'posts` WHERE `boardid` = ' . $line['id'] . ' AND `parentid` = 0 AND `IS_DELETED` = 0 ORDER BY `bumped` DESC');
								if (count($results2) > 0) {
									foreach($results2 AS $line2) {
										$sitemap .= '	<url>' . "\n" .
										'		<loc>' . KU_BOARDSPATH . '/' . $line['name'] . '/res/' . $line2['id'] . '.html</loc>' . "\n" .
										'		<lastmod>' . date('Y-m-d', $line2['bumped']) . '</lastmod>' . "\n" .
										'		<changefreq>hourly</changefreq>' . "\n" .
										'	</url>' . "\n";
									}
								}
							}
						}
						$sitemap .= '</urlset>';
						$fp = fopen(KU_BOARDSDIR . 'sitemap.xml', 'w');
						fwrite($fp, $sitemap);
						fclose($fp);
						unset($sitemap, $fp);
					}
				}
			}
			unset($results_events, $line_events);
		}
	}
}
function stripslashes_deep($value) {
	$value = is_array($value) ?
	array_map('stripslashes_deep', $value) :
	stripslashes($value);
	return $value;
}
if (get_magic_quotes_gpc()) {
	$_POST = array_map('stripslashes_deep', $_POST);
	$_GET = array_map('stripslashes_deep', $_GET);
	$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}
if (get_magic_quotes_runtime()) {
	set_magic_quotes_runtime(0);
}
?>

<?php
	require_once 'config.php';
	require_once KU_ROOTDIR . 'inc/functions.php';
	require_once KU_ROOTDIR .'lib/twig/lib/Twig/Autoloader.php';
	global $tc_db;
	if (file_exists("install.php")) {
		die('You are seeing this message because either you haven\'t ran the install file yet, and can do so <a href="install.php">here</a>, or already have, and <strong>must delete it</strong>.');
	}
	
	// News/FAQ/Rules
	if ($_GET['view'] == '') {
		$entries = $tc_db->GetAll("SELECT * FROM `" . KU_DBPREFIX . "front` WHERE `page` = 0 ORDER BY `timestamp` DESC LIMIT 5 OFFSET ".($_GET['page'] * 5));
	} elseif ($_GET['view'] == 'faq') {
		$entries = $tc_db->GetAll("SELECT * FROM `" . KU_DBPREFIX . "front` WHERE `page` = 1 ORDER BY `timestamp` DESC");
	} elseif ($_GET['view'] == 'rules') {
		$entries = $tc_db->GetAll("SELECT * FROM `" . KU_DBPREFIX . "front` WHERE `page` = 2 ORDER BY `order` ASC");
	}
	$pages = $tc_db->GetOne("SELECT COUNT(*) FROM `" . KU_DBPREFIX . "front` WHERE `page` = 0");
	$sections = Array();
	$results_boardsexist = $tc_db->GetAll("SELECT `id` FROM `".KU_DBPREFIX."boards` LIMIT 1");
	if (count($results_boardsexist) > 0) {
		$sections = $tc_db->GetAll("SELECT * FROM `" . KU_DBPREFIX . "sections` ORDER BY `order` ASC");
		foreach($sections AS $key=>$section) {
			$results = $tc_db->GetAll("SELECT * FROM `" . KU_DBPREFIX . "boards` WHERE `section` = '" . $section['id'] . "' ORDER BY `order` ASC, `name` ASC");
			foreach($results AS $line) {
				$sections[$key]['boards'][] = $line;
			}
		}
	}
	// Stats
	$totalposts = $tc_db->GetOne('SELECT COUNT(*) FROM `'.KU_DBPREFIX.'posts` WHERE `IS_DELETED` = 0');
	$currentusers = $tc_db->GetOne("SELECT COUNT(DISTINCT `ipmd5`) FROM `" . KU_DBPREFIX . "posts` WHERE `IS_DELETED` = 0");
		$spaceused_res = 0;
		$spaceused_src = 0;
		$spaceused_thumb = 0;
		$spaceused_total = 0;
		$files_res = 0;
		$files_src = 0;
		$files_thumb = 0;
		$files_total = 0;
		$results = $tc_db->GetAll("SELECT HIGH_PRIORITY `name` FROM `" . KU_DBPREFIX . "boards` ORDER BY `name` ASC");
		foreach ($results as $line) {
			list($spaceused_board_res, $files_board_res) = recursive_directory_size(KU_BOARDSDIR . $line['name'] . '/res');
			list($spaceused_board_src, $files_board_src) = recursive_directory_size(KU_BOARDSDIR . $line['name'] . '/src');
			list($spaceused_board_thumb, $files_board_thumb) = recursive_directory_size(KU_BOARDSDIR . $line['name'] . '/thumb');
			$spaceused_board_total = $spaceused_board_res + $spaceused_board_src + $spaceused_board_thumb;
			$spaceused_total += $spaceused_board_total;
			$activecontent = ConvertBytes($spaceused_total);
		}
	// Recent Posts
	$limit = 5; // How many post you want to show
	$disallowedboard = ''; //Boards you rather not show recent post in IE 'test'.. TODO:Make user able to add more than one board
	//end configuration
	if ($disallowedboard != '') {
		$boardid = $tc_db->GetOne('SELECT `id` FROM `'.KU_DBPREFIX.'boards` WHERE `name` = "'.$disallowedboard.'"');
	}
	$query = $tc_db->GetAll('SELECT * FROM `'.KU_DBPREFIX.'posts` WHERE `IS_DELETED` = 0 ORDER BY `timestamp` DESC LIMIT '.$limit.'');
	


echo $twig->render('index.html',  array('recentposts' => $query,
	'pages' => ($pages/5),
	'totalposts' => $totalposts,
	'currentusers' => $currentusers,
	'activecontent' => $activecontent,
	'boards' => $sections,
	'entries' => $entries,
	//'section' => $boardsection,
	'ku_webpath' => getCWebPath(),
	'KU_WEBFOLDER' => KU_WEBFOLDER,
	'KU_NAME' => KU_NAME,
	'KU_SLOGAN' => KU_SLOGAN,
	'KU_BOARDSFOLDER' => KU_BOARDSFOLDER,
	'kU_BOARDSPATH' => KU_BOARDSPATH,
	'KU_CGIPATH' => KU_CGIPATH,
	'view' =>  $_GET['view'],
	'page' => $_GET['page']
 ));

	
?>

<?
	#
	# $Id: raid_edit_timeline.php,v 1.1 2007/08/09 06:24:56 cal Exp $
	#

	include('init.txt');


	#
	# load the raid
	#

	$d_enc = AddSlashes($_GET[d].$_POST[d]);

	$raid = db_fetch_hash(db_query("SELECT * FROM et_attendance_days WHERE d='$d_enc'"));

	if (!$raid[d]){

		die("can't find raid for $_GET[d]$_POST[d]");
	}

	$smarty->assign_by_ref('raid', $raid);


	#
	# add an item?
	#

	if ($_POST[add_item]){

		$time = parse_time($_POST[time], $raid[d]);

		if (!is_user_event($_POST[event])){

			$_POST[who] = '';
		}

		db_insert('et_attendance_timeline', array(
			'd'	=> $raid[d],
			't'	=> $time,
			'event'	=> AddSlashes($_POST[event]),
			'who'	=> AddSlashes($_POST[who]),
		));

		update_raid_times($raid[d]);

		header("location: raid_edit_timeline.php?d=$raid[d]");
		exit;
	}


	#
	# remove an item?
	#

	if ($_GET[remove_item]){

		$item = AddSlashes($_GET[remove_item]);

		db_query("DELETE FROM et_attendance_timeline WHERE id='$item'");

		update_raid_times($raid[d]);

		header("location: raid_edit_timeline.php?d=$raid[d]");
		exit;
	}


	#
	# load the players
	#

	$names = array();

	$result = db_query("SELECT member_name, member_class_id, member_rank_id FROM eqdkp_bees_members ORDER BY member_name ASC");
	while ($row = db_fetch_hash($result)){

		if ($row[member_name] == '_de_loot_') continue;

		$names[] = $row[member_name];
	}

	sort($names);

	#dumper($names);
	$smarty->assign_by_ref('names', $names);


	#
	# fetch timeline
	#

	$timeline = get_sorted_timeline($raid[d]);

	#dumper($timeline);
	$smarty->assign_by_ref('timeline', $timeline);


	#
	# fetch events
	#

	$events = get_events();
	$user_events = array();

	foreach ($events as $k => $v){

		if (is_user_event($k)){

			$user_events[] = $k;
		}
	}

	$smarty->assign('events', $events);
	$smarty->assign('user_events', $user_events);


	#
	# output
	#

	$smarty->display('page_raid_edit_timeline.txt');
?>

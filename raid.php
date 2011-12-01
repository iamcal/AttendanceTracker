<?
	#
	# $Id: raid.php,v 1.1 2007/08/09 06:24:56 cal Exp $
	#

	include('init.txt');


	#
	# load the raid
	#

	$d_enc = AddSlashes($_GET[d]);

	$raid = db_fetch_hash(db_query("SELECT * FROM et_attendance_days WHERE d='$d_enc'"));

	if (!$raid[d]){

		die("can't find raid for $_GET[d]");
	}

	$smarty->assign_by_ref('raid', $raid);


	#
	# load the players
	#

	$names = array();

	$result = db_query("SELECT member_name, member_class_id, member_rank_id FROM eqdkp_bees_members ORDER BY member_name ASC");
	while ($row = db_fetch_hash($result)){

		if ($row[member_name] == '_de_loot_') continue;

		$names[$row[member_name]] = $row;
	}

	#dumper($names);
	$smarty->assign_by_ref('names', $names);


	#
	# the class map
	#

	$class_map = array(
		1	=> 'Warrior',
		2	=> 'Rogue',
		3	=> 'Hunter',
		10	=> 'Warlock',
		11	=> 'Mage',
		8	=> 'Shaman',
		7	=> 'Druid',
		5	=> 'Paladin',
		6	=> 'Priest',
	);

	#dumper($class_map);
	$smarty->assign_by_ref('class_map', $class_map);


	#
	# load the attendance
	#

	$attendance = array();

	$result = db_query("SELECT * FROM et_attendance_who WHERE d='$raid[d]'");
	while ($row = db_fetch_hash($result)){

		$attendance[$row[who]] = 1;
	}

	#dumper($attendance);
	$smarty->assign_by_ref('attendance', $attendance);


	#
	# fetch timeline
	#

	$timeline = get_sorted_timeline($raid[d]);

	#dumper($timeline);
	$smarty->assign_by_ref('timeline', $timeline);


	#
	# fetch the report
	#

	$report = get_raid_report($raid, $timeline);

	#dumper($report);
	$smarty->assign_by_ref('report', $report);


	#
	# output
	#

	$smarty->display('page_raid.txt');
?>

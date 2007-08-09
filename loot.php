<?
	#
	# $Id: loot.php,v 1.1 2007/08/09 06:24:56 cal Exp $
	#

	include('init.txt');

	include('dkp.txt');


	#
	# load the loot event
	#

	$id_enc = AddSlashes($_GET[id]);

	$event = db_fetch_hash(db_query("SELECT * FROM et_attendance_timeline WHERE id='$id_enc'"));

	if (!$event[id]){

		die("can't find event $_GET[id]");
	}

	$event[ts] = strtotime($event[t]);

	$smarty->assign_by_ref('event', $event);


	#
	# load the raid
	#

	$raid = db_fetch_hash(db_query("SELECT * FROM et_attendance_days WHERE d='$event[d]'"));

	$smarty->assign_by_ref('raid', $raid);


	#
	# load the attendance
	#

	$attendance = array();

	$result = db_query("SELECT * FROM et_attendance_who WHERE d='$raid[d]'");
	while ($row = db_fetch_hash($result)){

		$attendance[$row[who]] = 1;
	}


	#
	# fetch timeline
	#

	$timeline = get_sorted_timeline($raid[d]);


	#
	# walk the timeline and adjust the attendance
	#

	foreach ($timeline as $row){

		$row[ts] = strtotime($row[t]);

		if (is_adding_event($row[event])){

			if ($row[ts] < $event[ts]){

				# added before loot - add them

				$attendance[$row[who]] = 1;
			}else{

				# added after loot - they weren't here

				unset($attendance[$row[who]]);
			}
		}

		if (is_removing_event($row[event])){

			if ($row[ts] < $event[ts]){

				# removed before loot - remove

				unset($attendance[$row[who]]);
			}else{

				# removed after loot - do nothing
			}
		}
	}

	#dumper($attendance);

	#
	# get ordered member list
	#

	$members = array_keys($attendance);

	sort($members);

	$smarty->assign_by_ref('members', $members);


	#
	# get everyone's DKP
	#

	$dkp = load_dkp_standings();

	#dumper($dkp);


	#
	# fetch user ranks
	#

	$ranks = array();

	$result = db_query("SELECT member_name, member_rank_id FROM eqdkp_bees_members");
	while ($row = db_fetch_hash($result)){

		$ranks[$row[member_name]] = $row[member_rank_id];
	}


	#
	# get a sorted list for each tier
	#

	$lists = array();

	foreach (array(4,5,6) as $t){

		$list = array();

		foreach ($attendance as $who => $junk){

			$list[] = array(
				'who'	=> $who,
				'dkp'	=> $dkp[$who][$t],
				'rank'	=> translate_rank($ranks[$who]),
			);
		}

		usort($list, 'sort_dkp_list');

		$lists[$t] = $list;
	}

	#dumper($lists);
	$smarty->assign_by_ref('lists', $lists);


	function sort_dkp_list($a, $b){

		if ($a[rank] < $b[rank]) return -1;
		if ($a[rank] > $b[rank]) return 1;

		if ($a[dkp] > $b[dkp]) return -1;
		if ($a[dkp] < $b[dkp]) return 1;
		return 0;
	}

	function translate_rank($r){

		if ($r == 0) return 4; # unknown
		if ($r == 1) return 1; # raider
		if ($r == 2) return 1; # officer
		if ($r == 4) return 3; # casual
		if ($r == 8) return 2; # initiate
		if ($r == 16) return 3; # member
		if ($r == 32) return 4; # idle

		return 5;
	}

	function format_rank($rank){

		if ($rank == 1) return 'Radier';
		if ($rank == 2) return 'Initiate';
		if ($rank == 3) return 'Casual';
		if ($rank == 4) return 'Idle';
		return 'Unknown';
	}

	$smarty->register_modifier('format_rank', 'format_rank');


	#
	# output
	#

	$smarty->display('page_loot.txt');
?>
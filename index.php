<?
	#
	# $Id: index.php,v 1.1 2007/08/09 06:24:56 cal Exp $
	#

	include('init.txt');


	#
	# get player names
	#

	$names = array();

	$result = db_query("SELECT member_name FROM eqdkp_bees_members ORDER BY member_name ASC");
	while ($row = db_fetch_hash($result)){

		if ($row[member_name] == '_de_loot_') continue;

		$names[] = $row[member_name];
	}

	#dumper($names);
	$smarty->assign_by_ref('names', $names);


	#
	# get calendar
	#

	$calendar = get_raid_calendar();

	#dumper($calendar);
	$smarty->assign_by_ref('calendar', $calendar);


	#
	# load attendance data
	#

	$attendance = array();

	$result = db_query("SELECT * FROM et_attendance_who");
	while ($row = db_fetch_hash($result)){

		$key = "$row[d]-$row[who]";
		$row[max] = $calendar[dates][$row[d]][mins];

		if ($row[max]){
			$row[frac] = $row[mins]/$row[max];
		}else{
			$row[frac] = 0;
		}

		$row[per] = round($row[frac]*100);

		$attendance[$key] = $row;
	}
	
	#dumper($attendance);
	$smarty->assign_by_ref('attendance', $attendance);


	#
	# calculate raider averages
	#

	$averages = array();

	$total_raid_mins = 0;

	foreach ($calendar[dates] as $date){

		if (!$date[mins]){ continue; }
		$total_raid_mins += $date[mins];
	}

	$times = array();

	foreach ($attendance as $row){

		$times[$row[who]] += $row[mins];
	}

	foreach ($names as $name){

		$mins = intval($times[$name]);
		$frac = $mins / $total_raid_mins;

		$averages[$name] = array(
			'mins'	=> $mins,
			'max'	=> $total_raid_mins,
			'frac'	=> $frac,
			'per'	=> round($frac*100),
		);
	}

	#dumper($averages);
	$smarty->assign_by_ref('averages', $averages);


	#
	# output
	#

	$smarty->display('page_index.txt');
?>
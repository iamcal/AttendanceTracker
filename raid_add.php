<?
	#
	# $Id: raid_add.php,v 1.1 2007/08/09 06:24:56 cal Exp $
	#

	include('init.txt');


	#
	# add a new raid?
	#

	if ($_POST[done]){

		$date = sprintf("%04d-%02d-%02d", $_POST[date_y], $_POST[date_m], $_POST[date_d]);

		#
		# check it doesn't yet exist
		#

		list($test) = db_fetch_list(db_query("SELECT 1 FROM et_attendance_days WHERE d='$date'"));

		if ($test){

			$smarty->assign('date_taken', 1);
		}else{

			db_insert('et_attendance_days', array(
				'd'	=> $date,
				'who'	=> AddSlashes($_POST[focus]),
				'mins'	=> 0,
			));

			header("location: raid.php?d=$date");
			exit;
		}
	}
	


	#
	# default is today's date
	#

	$smarty->assign('y', date('Y'));
	$smarty->assign('m', date('n'));
	$smarty->assign('d', date('j'));


	#
	# output
	#

	$smarty->display('page_raid_add.txt');
?>

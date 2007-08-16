<?
	#
	# $Id: raid_edit.php,v 1.1 2007/08/16 19:51:10 cal Exp $
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
	# save changes?
	#

	if ($_POST[done]){

		db_update('et_attendance_days', array(
			'who'	=> AddSlashes($_POST[focus]),
		), "d='$d_enc'");

		header("location: raid.php?d=$raid[d]");
		exit;
	}


	#
	# output
	#

	$smarty->display('page_raid_edit.txt');
?>
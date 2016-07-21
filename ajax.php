<?php

/*
   +---------------------------------------------------------------+
   |        Base Plugin for e107 v7xx - by Father Barry
   |
   |        This module for the e107 .7+ website system
   |        Copyright Barry Keal 2004-2011
   |
   |        Released under the terms and conditions of the
   |        GNU General Public License (http://gnu.org).
   |
   +---------------------------------------------------------------+
*/
require_once("../../class2.php");
if (!defined('e107_INIT')){
    exit;
} //
error_reporting(E_ALL);

if (!is_object($repeater_obj)){
    require_once(e_PLUGIN . "repeater/includes/repeater_class.php");
    $repeater_obj = new repeater;
}

if ($_POST['repeaterPreviewjs']=='preview'){
	$prevs=array('preview'=>$repeater_obj->ajaxPreview());
	print json_encode($prevs);
}
if ($_POST['repeaterPreviewjs']=='numrecs'){
	$nums=array('numrecs'=>$repeater_obj->ajaxCount());
    print json_encode($nums);
}


$fp = fopen("log.txt", "w+");
fwrite($fp, print_r($_POST, true));
//fwrite($fp, print_r($repeater_obj->ajaxGet, true));
fwrite($fp, time());
fwrite($fp, "\n".$repeater_obj->ajaxPreview());
fclose($fp);
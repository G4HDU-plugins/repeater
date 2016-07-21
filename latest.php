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
if ( !defined( 'e107_INIT' ) ) {
	exit;
}
if ( !is_object( $repeater_obj ) ) {
	require_once( e_PLUGIN . "repeater/includes/rpt_class.php" );
	$repeater_obj = new rpt_class;
}
$repeater_approve = $sql->db_Count( 'rpt_table', '(*)', "WHERE rpt_int>'0'" );
// uses $text to include it in the list - do not change
$text .= "
	<div style='padding-bottom: 2px;'>
		<img src='" . e_PLUGIN . "repeater/images/rpt_16.png' style='width:16px;height:16px;vertical-align: bottom;border:0px;' alt='".RPT_E_LATEST_02."' title='".RPT_E_LATEST_02."' /> ";
if ( empty( $repeater_approve ) ) {
    $repeater_approve = 0;
}
if ( $repeater_approve ) {
    $text .= "<a href='" . e_PLUGIN . "repeater/admin_submit.php'>" . RPT_E_LATEST_03 . ": " . $repeater_approve . "</a>";
}else {
    $text .= RPT_E_LATEST_03 . ': ' . $repeater_approve;
}

$text .= '</div>';

?>
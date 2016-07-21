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
$repeater_posts = $sql->db_Count( "rpt_table", "(*)" );
if ( empty( $repeater_posts ) ) {
    $repeater_posts = 0;
}

// uses $text to include it in the list - do not change
$text .= "<div style='padding-bottom: 2px;'><img src='" . e_PLUGIN . "repeater/images/rpt_16.png' style='width:16px;height:16px;vertical-align:bottom;border:0px;' alt='" . RPT_E_STATUS_02 . "' title='" . RPT_E_STATUS_02 . "' /> " . RPT_E_STATUS_01 . ": " . $repeater_posts . "</div>";

?>
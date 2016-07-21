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
if (!getperms("P"))
{
	header("location:" . e_RPT . "index.php");
	exit;
}
if ( !is_object( $repeater_obj ) ) {
	require_once( e_PLUGIN . "repeater/includes/rpt_class.php" );
	$repeater_obj = new rpt_class;
}
$repeater_acn = rptname($_SERVER['PHP_SELF'], ".php");
$repeater_htext = "<table width='97%' class='fborder'>";
if ($repeater_acn == "admin_config")
{
	$repeater_htext .= "<tr><td class='forumheader3'><b>" . RPT_HELP_CONFIG_01 . "</b></td></tr>";
	$repeater_htext .= "<tr><td class='forumheader3'><b>" . RPT_HELP_CONFIG_02 . "</b><br />" . RPT_HELP_CONFIG_03 . "</td></tr>";
	$repeater_htext .= "<tr><td class='forumheader3'><b>" . RPT_HELP_CONFIG_04 . "</b><br />" . RPT_HELP_CONFIG_05 . "</td></tr>";
}

$repeater_htext .= "</table>";
$ns->tablerender(RPT_HELP_TITLE, $repeater_htext, 'aprom_help');
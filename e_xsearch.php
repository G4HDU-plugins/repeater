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
global $repeater_obj;
if ( !is_object( $repeater_obj ) ) {
    require_once( e_PLUGIN . "repeater/includes/rpt_class.php" );
    $repeater_obj = new rpt_class;
}

$search_info[] = array( 'sfile' => e_PLUGIN . 'repeater/search/search.php', 'qtype' => RPT_E_SEARCH_001, 'refpage' => 'index.php' );

?>
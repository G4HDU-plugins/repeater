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
require_once( "../../class2.php" );
if ( !defined( 'e107_INIT' ) ) {
    exit;
}
error_reporting( E_ALL );

if ( !is_object( $repeater_obj ) ) {
    require_once("includes/repeater_class.php" );
    $repeater_obj = new repeater;
}

//$repeater_obj->convert = new convert;
require_once( 'includes/repeater_shortcodes.php' );


$repeater_text=$repeater_obj->processMain();
require_once( HEADERF );
$ns->tablerender( "Repeater List", $repeater_text, 'rpt_plugin' );
require_once( FOOTERF );

?>
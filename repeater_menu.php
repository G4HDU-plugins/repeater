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
global $repeater_obj, $sql, $repeater_conv;
if ( !is_object( $repeater_obj ) ) {
    require_once( e_PLUGIN . "repeater/includes/rpt_class.php" );
    $repeater_obj = new rpt_class;
}

if ( !$repeater_obj->allow_access ) {

    return;
}
if ( $repeater_text = $e107cache->retrieve( "nq_rpt_plugin_menu" ) ) {
    echo $repeater_text;
} else {
    global $tp, $repeater_obj , $sql;

    $repeater_text = "";
    $repeater_arg = "
	SELECT * FROM #rpt_table limit 0,3	";
    $repeater_text .= "<div  style='text-align:left;'>"; ;
    if ( $sql->db_Select_gen( $repeater_arg, false ) ) {
        while ( $repeater_item = $sql->db_Fetch() ) {
            $repeater_text .= "
	<b><img src='" . e_PLUGIN . "repeater/images/rpt_16.png' alt='' title='' /> <a href='" . e_PLUGIN . "repeater/index.php?0.view." . $repeater_item['rpt_id'] . "' >" . $tp->html_truncate( $repeater_item['rpt_varchar'], 30, '[more...]' ) . "</a></b><br />";
        }
    }
    $repeater_text .= "</div>";
    ob_start();
    $ns->tablerender( 'Base Plugin', $repeater_text, 'rpt_menu' );
    $repeater_cache = ob_get_flush();
    $e107cache->set( "nq_rpt_plugin_menu", $repeater_cache );
}
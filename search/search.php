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

global $repeater_obj, $repeater_conv;

if ( !is_object( $repeater_obj ) ) {
    require_once( e_PLUGIN . "repeater/includes/rpt_class.php" );
    $repeater_obj = new rpt_class;
}


if ( $repeater_obj->allow_access ) {
    // if they have permission to access this plugin then do it
    $return_fields = 't.rpt_varchar,t.rpt_category,t.rpt_int,t.rpt_id,t.rpt_posted';
    // fields to be returned in search results
    $search_fields = array( 't.rpt_varchar', 't.rpt_category' );
    $weights = array( '1.2', '1.0' ); // one weighting for each seach field
    $no_results = RPT_E_SEARCH_005;
    $where = "rpt_posted>0 and";
    $order = array( 't.rpt_varchar' => DESC );
    $table = "rpt_table as t";

    $ps = $sch->parsesearch( $table, $return_fields, $search_fields, $weights, 'search_rpt_plugin', $no_results, $where, $order );
    $text .= $ps['text'];
    $results = $ps['results'];
}
function search_rpt_plugin( $row )
{
    global $repeater_obj;
    $datestamp = $repeater_obj->convert->convert_date( $row['rpt_posted'], "long" );
    $title = $row['rpt_varchar'];
    $link_id = $row['rpt_id'];
    $res['link'] = e_PLUGIN . "repeater/index.php?0.view." . $link_id . "";
    $res['pre_title'] = $title ?RPT_E_SEARCH_004 . " " : "";
    $res['title'] = $title ? $title : LAN_SEARCH_9;
    // just get the first 30 characters
    $res['summary'] = RPT_E_SEARCH_003 . " " . substr( $row['rpt_varchar'], 0, 30 ) . " - " . substr( $row['articulate_article'], 0, 60 );
    $res['detail'] = RPT_E_SEARCH_002 . " " . $datestamp;
    return $res;
}

?>
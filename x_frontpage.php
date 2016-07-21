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
$front_page['rpt_plugin'] = array( 'page' => $PLUGINS_DIRECTORY . 'repeater/index.php', 'title' => 'rpt Plugin' );

/*

$repeater_arg = "SELECT * FROM #rpt_table" ;
if ( $sql2->db_Select_gen( $repeater_arg, false, $repeater_obj->log_type, $repeater_obj->log_remark . '_rss' ) ) {
    while ( $row = $sql2->db_Fetch() ) {
        $front_page['rpt_' . $row['content_id']]['title'] = CONT_FP_1 . ': ' . $row['content_heading'];
        $front_page['rpt_' . $row['content_id']]['page'][] = array( 'page' => $PLUGINS_DIRECTORY . 'content/content.php?recent.' . $row['content_id'], 'title' => $row['content_heading'] . ' ' . CONT_FP_2 );
        if ( $sql->db_Select( "pcontent", "content_id, content_heading", "content_parent = '" . $row['content_id'] . "' ORDER BY content_heading" ) ) {
            while ( $row2 = $sql->db_Fetch() ) {
                $front_page['rpt_' . $row['content_id']]['page'][] = array( 'page' => $PLUGINS_DIRECTORY . 'content/content.php?content.' . $row2['content_id'], 'title' => $row2['content_heading'] );
            }
        }
    }
}
*/
?>
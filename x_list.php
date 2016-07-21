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

if ( !$repeater_obj->allow_access ) {
    return;
}

$LIST_CAPTION = $arr[0];
$LIST_DISPLAYSTYLE = ( $arr[2] ? '' : 'none' );

if ( $mode == 'new_page' || $mode == 'new_menu' ) {
    $lvisit = $this->getlvisit();
    $repeater_qry = ' rpt_posted>' . $lvisit ;
} else {
    $repeater_qry = 'rpt_id>0'; // got to do something!
}

$bullet = $this->getBullet( $arr[6], $mode );

$repeater_arg = "
	SELECT r.*,u.user_name
	FROM #rpt_table AS r
	LEFT JOIN #user as u on SUBSTRING_INDEX(r.rpt_poster,'.',1) = u.user_id
	WHERE " . $repeater_qry . "
	ORDER BY r.rpt_posted ASC LIMIT 0," . $arr[7];

if ( !$sql->db_Select_gen( $repeater_arg,false, $repeater_obj->log_type, $repeater_obj->log_remark.'_e_list' ) ) {
    $LIST_DATA = RPT_LIST_002;
} else {
    while ( $repeater_row = $sql->db_Fetch() ) {
        $tmp = explode( ".", $repeater_row['rpt_poster'] );
        if ( !empty( $repeater_row['user_name'] ) ) {
            $repeater_username = $tp->toHTML( $repeater_row['user_name'], false );
            $repeater_member = true;
        } else {
            $repeater_username = $tp->toHTML( $tmp[1], false );
            $repeater_member = false;
        }
        $repeater_userid = (int)$tmp[0];
        if ( !$repeater_member ) {
            // no longer a member
            $AUTHOR = $repeater_username;
        } elseif ( $repeater_member && USER ) {
            // they are a member and it is a logged in member looking
            $AUTHOR = "<a href='" . e_RPT . "user.php?id." . $repeater_userid . "'>" . $repeater_username . '</a>';
        } else {
            $AUTHOR = '';
        }

        $repeater_rowheading = $this->parse_heading( $repeater_row['rpt_varchar'], $mode );
        $ICON = $bullet;
        $HEADING = "<a href='" . e_PLUGIN . "repeater/index.php?0.view." . $repeater_row['rpt_id'] . "' title='" . $repeater_row['rpt_varchar'] . "'>" . $repeater_rowheading . "</a>";
        $CATEGORY = $repeater_row['rpt_category'];
        $DATE = ( $arr[5] ? ( $repeater_row['rpt_posted'] ? RPT_LIST_003 . $this->getListDate( $repeater_row['rpt_posted'], $mode ) : "" ) : "" );
        $INFO = "More Info";
        $LIST_DATA[$mode][] = array( $ICON, $HEADING, $AUTHOR, $CATEGORY, $DATE, $INFO );
    }
}

?>
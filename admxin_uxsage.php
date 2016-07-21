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
if ( !getperms( "P" ) ) {
    header( "location:" . e_HTTP . "index.php" );
    exit;
}
$eplug_js[] = e_PLUGIN . 'risk/includes/risk.js';
require_once( e_HANDLER . 'date_handler.php' );
$risk_conv = new convert;
require_once( e_ADMIN . "auth.php" );
require_once( e_HANDLER . "userclass_class.php" );
require_once( e_HANDLER . "calendar/calendar_class.php" );
$risk_cal = new DHTML_Calendar( true );
$risk_from = 0;
$risk_limit = 50;
if ( e_QUERY ) {
    $tmp = explode( '.', e_QUERY );
    $risk_from = (int)$tmp[0];
}
if ( !is_object( $rbook_obj ) ) {
    require_once( e_PLUGIN . "risk/includes/risk_class.php" );
    $rbook_obj = new risk;
}
$risk_msgtype = 'blank';
$risk_msgtext = "<ul>";
// get the icons
if ( isset( $_POST['risk_update'] ) ) {
    if ( isset( $_POST['risk_newdate'] ) ) {
    	$tmp=explode('-',$_POST['risk_newdate']);

		$risk_deletedate=mktime(0,0,0,$tmp[1],$tmp[0],$tmp[2]);
        if ( $sql->db_Delete( 'risk_audit', "risk_audit_date<{$risk_deletedate}", true ) ) {
            $risk_msgtype = 'success';
            $risk_msgtext .= "<li>" . RISK_AUDIT_10 . "</li>";
        }else {
            $risk_msgtype = 'error';
            $risk_msgtext .= "<li>" . RISK_AUDIT_11 . "</li>";
        }
    }
    // Update rest
}
$risk_msgtext .= "</ul>";
$risk_text .= $risk_cal->load_files() ;
$risk_text .= "

<form method='post' action='" . e_SELF . "' id='dataform'>
	<table style='" . ADMIN_WIDTH . "' class='fborder'>
		<tr>
			<td colspan='5' class='fcaption'>" . RISK_AUDIT_02 . "</td>
		</tr>
		<tr>
			<td colspan='5' class='forumheader2'>" . $prototype_obj->message_box( $risk_msgtype, $risk_msgtext ) . "</td>
		</tr>
		<tr>
			<td  class='forumheader2' style='width:20%;text-align:left;' ><b>" . RISK_AUDIT_03 . "</b></td>
			<td  class='forumheader2' style='width:20%;text-align:left;' ><b>" . RISK_AUDIT_04 . "</b></td>
			<td  class='forumheader2' style='width:20%;text-align:left;' ><b>" . RISK_AUDIT_05 . "</b></td>
			<td  class='forumheader2' style='width:30%;text-align:left;' ><b>" . RISK_AUDIT_06 . "</b></td>
			<td  class='forumheader2' style='width:10%;text-align:center;' ><b>" . RISK_AUDIT_07 . "</b></td>
		</tr>
";
$risk_numrecs = $sql->db_Count( "risk_audit", "(*)" );
if ( $sql->db_Select_gen( "SELECT a.*,u.user_name from #risk_audit as a
LEFT JOIN #user as u on substring_index(a.risk_audit_user,'.',1)=u.user_id
ORDER BY risk_audit_date desc
LIMIT $risk_from,$risk_limit", false ) ) {
    while ( $risk_row = $sql->db_Fetch() ) {
        if ( empty( $risk_row['user_name'] ) ) {
            $tmp = explode( '.', $risk_row[''], 2 );
            $risk_user = $tmp[1];
        } else {
            $risk_user = $risk_row['user_name'];
        }
        $risk_text .= "
		<tr>
			<td  class='forumheader3'>" . $risk_conv->convert_date( $risk_row['risk_audit_date'], 'short' ) . "</td>
			<td  class='forumheader3'>" . $tp->toHTML( $risk_user, false ) . "</td>
			<td  class='forumheader3'>" . $tp->toHTML( $risk_row['risk_audit_action'], false ) . "</td>
			<td  class='forumheader3'>" . $tp->toHTML( $risk_row['risk_audit_process'], false ) . "</td>
			<td  class='forumheader3' style='text-align:center;' >";
        if ( !empty( $risk_row['risk_audit_changed'] ) ) {
            $risk_text .= "<a href='#' onclick='$(\"risk_display_{$risk_row['risk_audit_id']}\").toggle();'><img src='images/info.png' style='width:16px;height:16px;' alt='view' title='view'  /></a></td>";
        } else {
            $risk_text .= "&nbsp;</td>";
        }
        $risk_text .= "
		</tr>
		<tr style='display:none;'  id='risk_display_{$risk_row['risk_audit_id']}' >
			<td colspan='5' class='fcaption'>" . $risk_row['risk_audit_changed'] . "&nbsp;</td>
		</tr>";
    }
} else {
    $risk_text .= "
		<tr>
			<td colspan='5' class='fcaption'>" . RISK_AUDIT_08 . "</td>
		</tr>";
}
$risk_newdate = '';
$risk_dateformat = "d-m-Y";
$risk_cal_options['firstDay'] = '1';
$risk_cal_options['showsTime'] = false;
$risk_cal_options['showOthers'] = false;
$risk_cal_options['weekNumbers'] = false;
$risk_cal_df = "%" . str_replace( "-", "-%", $risk_dateformat );
$risk_cal_options['ifFormat'] = $risk_cal_df;
$risk_cal_attrib['class'] = "tbox";
$risk_cal_attrib['name'] = "risk_newdate";
$risk_cal_attrib['value'] = $risk_newdate;
$risk_delfrom = $risk_cal->make_input_field( $risk_cal_options, $risk_cal_attrib );
// Submit button
$risk_obj->action = "";

$parms = $risk_numrecs . "," . $risk_limit . "," . $risk_from . "," . e_SELF . '?' . "[FROM]." . $risk_obj->action;

$risk_nextprev = $tp->parseTemplate( "{NEXTPREV={$parms}}" ) . "";

$risk_text .= "
		<tr>
			<td colspan='3' class='forumheader2' style='text-align: left;'>$risk_nextprev&nbsp;</td>
			<td colspan='2' class='forumheader2' style='text-align: left;'>" . RISK_AUDIT_09 . " " . $risk_delfrom . "&nbsp;</td>
		</tr>
		<tr>
			<td colspan='5' class='forumheader2' style='text-align: left;'>
				<input type='submit' name='risk_update' value='" . RISK_CONFIG_07 . "' class='button' />
			</td>
		</tr>
		<tr>
			<td colspan='5' class='fcaption' style='text-align: left;'>&nbsp;</td>
		</tr>
	</table>
</form>";

$ns->tablerender( RISK_AUDIT_01, $risk_text, 'risk_config' );
require_once( e_ADMIN . "footer.php" );
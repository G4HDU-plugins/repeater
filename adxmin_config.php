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
$eplug_js = e_PLUGIN . 'rpt/includes/rpt.js';
require_once( e_ADMIN . "auth.php" );
require_once( e_HANDLER . "userclass_class.php" );
if ( !is_object( $repeater_obj ) ) {
    require_once( e_PLUGIN . "repeater/includes/rpt_class.php" );
    $repeater_obj = new rpt_class;
}

$repeater_obj->msg_type = 'blank';
$repeater_obj->msg_msg = "<ul>";
// get the icons
if ( isset( $_POST['rpt_update'] ) ) {
    // Update rest
    if ( $_POST['rpt_import'] == 1 ) {
        $repeater_obj->import_csv();
    }
	$repeater_obj->prefs['rpt_adminclass']=(int)$_POST['rpt_adminclass'];
	$repeater_obj->prefs['rpt_exportclass']=(int)$_POST['rpt_exportclass'];
	$repeater_obj->prefs['rpt_viewclass']=(int)$_POST['rpt_viewclass'];
    $repeater_obj->save_prefs();
    $repeater_obj->msg_type = 'success';
    $repeater_obj->msg_msg .= "<li>" . RPT_CONFIG_08 . "</li>";
}
$repeater_obj->msg_msg .= "</ul>";
$repeater_text .= "
<form method='post' action='" . e_SELF . "' id='dataform'>
	<table style='" . ADMIN_WIDTH . "' class='fborder'>
		<tr>
			<td colspan='2' class='fcaption'>" . RPT_CONFIG_02 . "</td>
		</tr>
		<tr>
			<td colspan='2' class='forumheader2'>" . $jquery_obj->message_box( $repeater_obj->msg_type, $repeater_obj->msg_msg ) . "</td>
		</tr>";
// Main admin class
$repeater_text .= "
		<tr>
			<td style='width:30%;text-align:left;' class='forumheader3'>" . RPT_CONFIG_03 . "</td>
			<td  class='forumheader3'>" . r_userclass( "rpt_adminclass", $repeater_obj->prefs['rpt_adminclass'], "off", 'nobody,member,main,admin,classes' ) . "</td>
		</tr>";

$repeater_text .= "
		<tr>
			<td style='width:30%;text-align:left;' class='forumheader3'>" . RPT_CONFIG_05 . "</td>
			<td  class='forumheader3'>" . r_userclass( "rpt_exportclass", $repeater_obj->prefs['rpt_exportclass'], "off", 'public,nobody,member,main,admin,classes' ) . "</td>
		</tr>
		<tr>
			<td style='width:30%;text-align:left;' class='forumheader3'>" . RPT_CONFIG_06 . "</td>
			<td  class='forumheader3'>" . r_userclass( "rpt_viewclass", $repeater_obj->prefs['rpt_viewclass'], "off", 'public,nobody,member,main,admin,classes' ) . "</td>
		</tr>
		<tr>
			<td colspan='4' class='forumheader2' style='text-align: left;'>
				<input type='checkbox' name='rpt_import' id='rpt_import' value='1' class='tbox' /><label for='rpt_import'> ".RPT_CONFIG_04."</label>
			</td>
		</tr>";
// Submit button
$repeater_text .= "
		<tr>
			<td colspan='4' class='forumheader2' style='text-align: left;'>
				<input type='submit' name='rpt_update' value='" . RPT_CONFIG_07 . "' class='button' />
			</td>
		</tr>
		<tr>
			<td colspan='4' class='fcaption' style='text-align: left;'>&nbsp;</td>
		</tr>
	</table>
</form>";
$ns->tablerender( RPT_CONFIG_01, $repeater_text, 'rpt_config' );
require_once( e_ADMIN . "footer.php" );
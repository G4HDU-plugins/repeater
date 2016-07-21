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
// Plugin info -------------------------------------------------------------------------------------------------------
include_lan( e_PLUGIN . 'repeater/languages/' . e_LANGUAGE . '_rpt.php' );
$eplug_name = PLUGIN_REPEATER_02;
$eplug_version = '1.1';
$eplug_author = 'Father Barry';
$eplug_url = 'http://keal.me.uk';
$eplug_email = 'me@example.com';
$eplug_description = PLUGIN_REPEATER_06;
$eplug_compatible = 'e107 v7+';
$eplug_readme = 'admin_readme.php'; // leave blank if no readme file
$eplug_compliant = true;
$eplug_status = true;
$eplug_latest = true;
// Name of the plugin's folder -------------------------------------------------------------------------------------
$eplug_folder = 'repeater'; // no trailing slash
// Name of the admin configuration file --------------------------------------------------------------------------
$eplug_conffile = 'admin_config.php';
// Icon image and caption text ------------------------------------------------------------------------------------
$eplug_icon = $eplug_folder . '/images/rpt_32.png';
$eplug_icon_small = $eplug_folder . '/images/rpt_16.png';
$eplug_caption = PLUGIN_REPEATER_01;
// List of sql requests to create tables -----------------------------------------------------------------------------
// create tables -----------------------------------------------------------------------------------------------
$eplug_sql = file_get_contents( e_PLUGIN . $eplug_folder . '/rpt_plugin_sql.php' );
preg_match_all( '/CREATE TABLE (.*?)\(/i', $eplug_sql, $matches );
$eplug_table_names = $matches[1];
// List of sql requests to create tables -----------------------------------------------------------------------------
// Apply create instructions for every table you defined in locator_sql.php --------------------------------------
// MPREFIX must be used because datarpt prefix can be customized instead of default e107_
$eplug_tables = explode( ';', str_ireplace( 'CREATE TABLE ', 'CREATE TABLE ' . MPREFIX, $eplug_sql ) );
for ( $i = 0; $i < count( $eplug_tables ); $i++ ) {
    $eplug_tables[$i] .= ';';
}
array_pop( $eplug_tables ); // Get rid of last (empty) entry
if ( count( $eplug_tables ) == 0 ) {
    unset( $eplug_tables ); // if no tables to create, get rid of this variable
}


// Create a link in main menu (yes=TRUE, no=FALSE) -------------------------------------------------------------
$eplug_link = true;
$eplug_link_name = PLUGIN_REPEATER_03;
$eplug_link_url = e_PLUGIN . 'repeater/index.php';
$eplug_link_perms = 'everyone';// choice of everyone,guest,member,mainadmin,admin,nobody
// Text to display after plugin successfully installed ------------------------------------------------------------------
$eplug_done = PLUGIN_REPEATER_04;
// upgrading ... //
// $upgrade_add_prefs = '';
// $upgrade_remove_prefs = '';
$upgrade_alter_tables = '';

$eplug_upgrade_done = PLUGIN_REPEATER_05;
// Deleting plugin ...//
if ( !function_exists( 'rpt_uninstall' ) ) {
    function rpt_uninstall()
    {
        // get rid of the things we created
        global $sql;
        $sql->db_Delete( 'core', ' e107_name="rpt" ' );
        // $sql->db_Delete( 'rate', ' rate_table="rpt" ' );
    }
}

?>


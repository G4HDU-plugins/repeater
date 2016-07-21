<?php
/**
 * Main administration configuration.
 *
 * @package REPEATER
 * @copyright 2008-2015 Barry Keal G4HDU
 * @license GPL
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @author Barry Keal G4HDU <www.g4hdu.co.uk>
 * @version 1.0.1
 */
require_once( "../../class2.php" );
if (!defined('e107_INIT')){
	exit;
}

//error_reporting(E_ALL);
if ( !getperms( "P" ) ) {
    header( "location:" . e_BASE . "index.php" );
    exit;
}

$eplug_admin = true;
if ( !getperms( "P" ) || !e107::isInstalled( 'repeater' ) ) {
	header( "location:" . e_BASE . "index.php" );
	exit() ;
}
e107::lan('repeater','English_admin',true); //load the admin language file
e107::js('repeater','js/repeater.js','jquery');	// Load Plugin javascript and include jQuery framework
e107::css('repeater','css/repeater.css');		// load css file



//include_lan(e_PLUGIN . 'repeater/languages/' . e_LANGUAGE . '_global.php');
require_once( 'includes/repeater_class.php' );
$rep=new repeater;
//$rep->parseCsv();
require_once( e_HANDLER . "form_handler.php" );

require_once( "handlers/admin.php" );
new plugin_repeater_admin();

require_once( e_ADMIN . "auth.php" );;
e107::getAdminUI()->runPage();
require_once( e_ADMIN . "footer.php" );
?>
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
    require_once( e_PLUGIN . "repeater/includes/rpt_class.php" );
    $repeater_obj = new rpt_class;
}
if ( !is_object( $repeater_scrape ) ) {
    require_once( e_PLUGIN . "repeater/includes/simple_html_dom.php" );
    // $repeater_scrape = new simple_html_dom;
}
$html = file_get_html( 'http://www.ukrepeater.net/repeaterlist1.htm' );
// $repeater_scrape->load_file('http://www.ukrepeater.net/repeaterlist1.htm');
foreach( $html->find( 'tr' ) as $article ) {
    $call = $article->find( 'td', 0 )->plaintext;
    if ( substr( $call, 0, 2 ) === 'GB' ) {
        $status = strtoupper( trim( $article->find( 'td', 14 )->plaintext ) );
        switch ( $status ) {
        	case '':
        		$item['op'] = 0;;
        		break;
            case 'OPERATIONAL':
                $item['op'] = 1;
                break;
            case 'NOT OPERATIONAL':
                $item['op'] = 3;
                break;


            default:
                $item['op'] = 2;
        } // switch
        $sql->db_Update( 'repeater', "repeater_op='{$item['op']}',repeater_status='{$status}' WHERE repeater_callsign='{$call}'", true );
    }
}
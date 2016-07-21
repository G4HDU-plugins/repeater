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
class rpt_template{
    /**
    * Constructor
    */
    function __construct(){
        if (!defined('USER_WIDTH')){
            define(USER_WIDTH, 'width:100%;');
        }
    }
    function REPEATER_LIST(){
        $retval = "
<div id='repeaterContainer' class='group' >
<table  class='fborder' id='repeaterTable'>
	<tr >
		<td class='forumheader2 repeaterHead' colspan='3' >Select</td>
		<td class='forumheader2 repeaterHead repeaterTableHelp' colspan='2' >{REPEATER_HELP}</td>
	</tr>
	<tr >
		<td id='repeaterRegionHead' class='forumheader2 repeaterColumnHead' >Region</td>
		<td id='repeaterModeHead'   class='forumheader2 repeaterColumnHead' >Mode</td>
		<td id='repeaterStatusHead' class='forumheader2 repeaterColumnHead' >Status</td>
		<td id='repeaterBandHead'   class='forumheader2 repeaterColumnHead' >Band</td>
		<td id='repeaterMemoHead'   class='forumheader2 repeaterColumnHead' >Comment</td>
	</tr>
	<tr >
		<td class='forumheader3 repeaterTableDrop' style='vertical-align:text-top' >{REPEATER_REGION}</td>
		<td class='forumheader3 repeaterTableDrop' style='vertical-align:text-top' >{REPEATER_MODE}</td>
		<td class='forumheader3 repeaterTableDrop' style='vertical-align:text-top' >{REPEATER_STATUS}</td>
		<td class='forumheader3 repeaterTableDrop' style='vertical-align:text-top' >{REPEATER_BAND}</td>
		<td class='forumheader3 repeaterTableDrop' style='vertical-align:text-top' >{REPEATER_NOTE}</td>
	</tr>

	<tr class='rr '>
		<td class='forumheader2 repeaterColumnHead' colspan='1' >Distance</td>
		<td class='forumheader2 repeaterColumnHead' colspan='2' >&nbsp;</td>
		<td class='forumheader2 repeaterColumnHead' colspan='2' >Repeaters Selected</td>
	</tr>

	<tr >
		<td class='forumheader3 wwww' colspan='1' style='vertical-align:top' >Within {REPEATER_MILES}</td>
		<td class='forumheader3 wwww' colspan='2' style='vertical-align:top' >of Locator {REPEATER_LOCATOR}</td>
		<td class='forumheader3 wwww' colspan='2' style='vertical-align:top' ><span id='rpt_numrecs'>&nbsp;</span></td>
	</tr>
	<tr >
		<td colspan='5' id='repeaterTableOpt' class='forumheader2 repeaterHead '>Options</td>
	</tr>
	<tr class='rr '>
		<td class='forumheader2 repeaterColumnHead' >Order</td>
		<td class='forumheader2 repeaterColumnHead'>Bank</td>
		<td class='forumheader2 repeaterColumnHead'>Scan</td>
		<td class='forumheader2 repeaterColumnHead'>Power</td>
		<td class='forumheader2 repeaterColumnHead'>Enc</td>

	</tr>
	<tr >
		<td class='forumheader3 wwww'>{REPEATER_ORDER}</td>
		<td class='forumheader3 wwww'>{REPEATER_BANK}</td>
		<td class='forumheader3 wwww'>{REPEATER_SCAN}</td>
		<td class='forumheader3 wwww'>{REPEATER_POWER}</td>
		<td class='forumheader3 wwww'>{REPEATER_ENC}</td>

	</tr>
	<tr class='rr '>
		<td class='forumheader2 repeaterColumnHead' >Memory Start</td>
		<td class='forumheader2 repeaterColumnHead' >Memory Interval</td>
		<td class='forumheader2 repeaterColumnHead' >Format</td>
		<td class='forumheader2 repeaterColumnHead' colspan='2' >Output</td>
	</tr>
	<tr >
		<td class='forumheader3 wwww' >{REPEATER_START}</td>
		<td class='forumheader3 wwww' >{REPEATER_STEP}</td>
		<td class='forumheader3 wwww' >{REPEATER_FORMAT}</td>
		<td class='forumheader3 wwww' colspan = '2' >{REPEATER_OUTPUT}</td>
	</tr>
	<tr >
		<td colspan='5' style='text-align:center;' class='forumheader3'>{REPEATER_PREVIEW} {REPEATER_MAKE} {REPEATER_RESET} </td>
	</tr>
</table>
</div>
<div id='rpt_previewArea' style='display:none;' >
	<div id='rpt_previewAreaImg'  >
		<img src='images/tower_ani2.gif' style='padding:300;' alt='loading' /><br />Retrieving Data<br />Please Wait
	</div>
	<div id='rpt_previewAreaContent' style='display:inline;'  >&nbsp;</div>
</div>";
        return $retval;
    }
    /**
    *
    * @param $
    * @return
    * @author
    * @version
    */
    function not_permitted(){
        global $sc_style;
        $sc_style['RPT_INACTIVE']['pre'] = '';
        $sc_style['RPT_INACTIVE']['post'] = '';
        $retval = "
   	<table style='" . USER_WIDTH . "' class='fborder'>
   		<tr>
   			<td  class='fcaption'>" . RPT_LIST_002 . "</td>
   		</tr>
   		<tr>
   			<td  class='forumheader'>{REPEATER_INACTIVE}</td>
   		</tr>
   	</table>";
        return $retval;
    }
}
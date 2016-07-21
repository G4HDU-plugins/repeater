<?php
/*
* e107 website system
*
* Copyright (c) 2008-2013 e107 Inc (e107.org)
* Released under the terms and conditions of the
* GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
*
* Custom repeater_setup install/uninstall/update routines
*
*/

class repeater_setup{
    /*
	   function install_pre($var)
	   {
	   // print_a($var);
	   // echo "custom install 'pre' function<br /><br />";
	   }
	*/
    function install_post($var){
        $sql = e107::getDb();
        $mes = e107::getMessage();
        $query = "
		REPLACE INTO #repeater_ctcss (repeater_ctcss_id, repeater_ctcss, repeater_user_lastupdated) VALUES
			('A', 67.0, 0),
			('B', 71.9, 0),
			('C', 77.0, 0),
			('D', 82.5, 0),
			('E', 88.5, 0),
			('F', 94.8, 0),
			('G', 103.5, 0),
			('H', 110.9, 0),
			('J', 118.8, 0);";

        $status = ($sql->db_Select_gen($query)) ? E_MESSAGE_SUCCESS : E_MESSAGE_ERROR;
        $mes->add("Adding Default table data to table: ctcss", $status);

        $query2 = "
REPLACE INTO #repeater_region (repeater_region_id, repeater_region_name, repeater_region_code, repeater_region_lastupdated) VALUES
	(1, 'Undefined', '', 0),
	(2, 'Midlands', 'MIDL', 0),
	(3, 'N.Ireland', 'NI', 0),
	(4, 'North England', 'NOR', 0),
	(5, 'Scotland', 'SCOT', 0),
	(6, 'South East England', 'SE', 0),
	(7, 'South West England', 'SW', 0),
	(8, 'Wales / Marches', 'WM', 0);";

        $status = ($sql->db_Select_gen($query2)) ? E_MESSAGE_SUCCESS : E_MESSAGE_ERROR;
        $mes->add("Adding Default table data to table: Regions", $status);

        $query3 = "
	REPLACE INTO #repeater_band (repeater_band_id, repeater_band_name, repeater_band_lastupdated) VALUES
	(1, 'Unknown', 0),
	(2, '2M', 0),
	(3, '70CM', 0),
	(4, '6M', 0),
	(5, '23CM', 0),
	(6, '10M', 0);";
        $status = ($sql->db_Select_gen($query3)) ? E_MESSAGE_SUCCESS : E_MESSAGE_ERROR;
        $mes->add("Adding Default table data to table: Bands", $status);
/*
        $query5 = "
	REPLACE INTO #repeater_band (repeater_band_id, repeater_band_name, repeater_band_lastupdated) VALUES
	(1, '2M', 0),
	(2, '70CM', 0),
	(3, '6M', 0),
	(4, '23CM', 0),
	(5, '10M', 0);";
        $status = ($sql->db_Select_gen($query5)) ? E_MESSAGE_SUCCESS : E_MESSAGE_ERROR;
        $mes->add("Adding Default table data to table: Bands", $status);
*/
        $query4 = "
    	REPLACE INTO #repeater_status (repeater_status_id, repeater_status, repeater_statusDescription, repeater_status_lastupdated) VALUES
    		(1, 'UNKNOWN','Unknown status', 0),
    		(2, 'OPERATIONAL','The repeater is operational', 0),
    		(3, 'BEACON MODE','The repeater is operating in beacon mode', 0),
    		(4, 'NOT OPERATIONAL','The repeater is not currently operational', 0),
    		(5, 'LICENSED','Licensed but not yet operaqtional', 0),
    		(6, 'TESTING','Testing but not operational', 0),
    		(7, 'REDUCED OUTPUT','Repeater running on reduced transmit power', 0),
    		(8, 'POOR RX','Receiver performance degraded - deaf!', 0),
    		(9, 'REDUCED PERFORMANCE', 'The repeater has overall impaired performance',0),
    		(10, 'NO NETWORK', 'No network connection for internet connectivity',0),
    		(11, 'UNDEFINED','Not defined in list', 0);";
        $status = ($sql->db_Select_gen($query4)) ? E_MESSAGE_SUCCESS : E_MESSAGE_ERROR;
        $mes->add("Adding Default table data to table: Status", $status);

    	$query5 = "
    	REPLACE INTO #repeater_type (repeater_type_id, repeater_type_name, repeater_typeDescription, repeater_type_lastupdated) VALUES
	(1, 'UNKNOWN', NULL, 0),
	(2, 'AV', NULL, 0),
	(3, 'DM FUSION', NULL, 0),
	(4, 'DM D-STAR', NULL, 0),
	(5, 'DUALMODE', NULL, 0),
	(6, 'DM DMR', NULL, 0),
	(7, 'DSTAR', NULL, 0),
	(8, 'DMR', NULL, 0);";
    	$status = ($sql->db_Select_gen($query5)) ? E_MESSAGE_SUCCESS : E_MESSAGE_ERROR;
    	$mes->add("Adding Default table data to table: Type", $status);


    	$query6 = "
 REPLACE INTO #repeater_channel (repeater_channelID, repeater_channelName, repeater_channelBandfk, repeater_channelTx, repeater_channelRx, repeater_channelLastupdate) VALUES
	(1, 'UNKNOWN', 1, 0.0000, 0.0000, 0),
	(2, 'RV48', 2, 145.0000, 145.6000, 0),
	(3, 'RV49', 2, 145.0125, 145.6125, 0),
	(4, 'RV50', 2, 145.0250, 145.6250, 0),
	(5, 'RV51', 2, 145.0375, 145.6375, 0),
	(6, 'RV52', 2, 145.0500, 145.6500, 0),
	(7, 'RV53', 2, 145.0625, 145.6625, 0),
	(8, 'RV54', 2, 145.0750, 145.6750, 0),
	(9, 'RV55', 2, 145.0875, 145.6875, 0),
	(10, 'RV56', 2, 145.1000, 145.7000, 0),
	(11, 'RV57', 2, 145.1125, 145.7125, 0),
	(12, 'RV58', 2, 145.1250, 145.7250, 0),
	(13, 'RV59', 2, 145.7375, 145.1375, 0),
	(14, 'RV60', 2, 145.1500, 145.7500, 0),
	(15, 'RV61', 2, 145.1625, 145.7625, 0),
	(16, 'RV62', 2, 145.1750, 145.7750, 0),
	(17, 'RV63', 2, 145.1875, 145.7875, 0),
	(18, '6M', 4, 29.2100, 50.5200, 0),
	(19, 'R50-01', 4, 51.2200, 50.7200, 0),
	(20, 'R50-02', 4, 51.2300, 50.7300, 0),
	(21, 'R50-03', 4, 51.2400, 50.7400, 0),
	(22, 'R50-04', 4, 51.2500, 50.7500, 0),
	(23, 'R50-05', 4, 51.2600, 50.7600, 0),
	(24, 'R50-06', 4, 51.2700, 50.7700, 0),
	(25, 'R50-07', 4, 51.2800, 50.7800, 0),
	(26, 'R50-08', 4, 51.2900, 50.7900, 0),
	(27, 'R50-09', 4, 51.3000, 50.8000, 0),
	(28, 'R50-10', 4, 51.3100, 50.8100, 0),
	(29, 'R50-11', 4, 51.3200, 50.8200, 0),
	(30, 'R50-12', 4, 51.3300, 50.8300, 0),
	(31, 'R50-13', 4, 51.3400, 50.8400, 0),
	(32, 'R50-14', 4, 51.3500, 50.8500, 0),
	(33, 'R50-15', 4, 51.3600, 50.8600, 0),
	(34, 'RM0', 5, 1297.0000, 1291.0000, 0),
	(35, 'RM0A', 5, 1293.8500, 1299.8500, 0),
	(36, 'RM12', 5, 1291.3000, 1297.3000, 0),
	(37, 'RM14A', 5, 1277.3500, 1297.3500, 0),
	(38, 'RM15', 5, 1291.3750, 1297.3750, 0),
	(39, 'RM2', 5, 1291.0500, 1297.0500, 0),
	(40, 'RM3', 5, 1291.0750, 1297.0750, 0),
	(41, 'DVU13', 3, 430.1625, 439.1625, 0),
	(42, 'DVU32', 3, 430.4000, 439.4000, 0),
	(43, 'DVU33', 3, 430.4125, 439.4125, 0),
	(44, 'DVU34', 3, 430.4250, 439.4250, 0),
	(45, 'DVU35', 3, 430.4375, 439.4375, 0),
	(46, 'DVU36', 3, 430.4500, 439.4500, 0),
	(47, 'DVU37', 3, 430.4625, 439.4625, 0),
	(48, 'DVU38', 3, 430.4750, 439.4750, 0),
	(49, 'DVU39', 3, 430.4875, 439.4875, 0),
	(50, 'DVU41', 3, 430.5125, 439.5125, 0),
	(51, 'DVU43', 3, 430.5375, 439.5375, 0),
	(52, 'DVU46', 3, 430.5750, 439.5750, 0),
	(53, 'DVU48', 3, 430.6000, 439.6000, 0),
	(54, 'DVU49', 3, 430.6125, 439.6125, 0),
	(55, 'DVU51', 3, 430.6375, 439.6375, 0),
	(56, 'DVU53', 3, 430.6625, 439.6625, 0),
	(57, 'DVU54', 3, 430.6750, 439.6750, 0),
	(58, 'DVU55', 3, 430.6875, 439.6875, 0),
	(59, 'DVU56', 3, 430.7000, 439.7000, 0),
	(60, 'DVU57', 3, 430.7125, 439.7125, 0),
	(61, 'DVU59', 3, 430.7375, 439.7375, 0),
	(62, 'DVU73', 3, 430.9125, 439.9125, 0),
	(63, 'RB0', 3, 434.6000, 433.0000, 0),
	(64, 'RB01', 3, 434.6250, 433.0250, 0),
	(65, 'RB02', 3, 434.6500, 433.0500, 0),
	(66, 'RB03', 3, 434.6750, 433.0750, 0),
	(67, 'RB04', 3, 434.7000, 433.1000, 0),
	(68, 'RB05', 3, 434.7250, 433.1250, 0),
	(69, 'RB06', 3, 434.7500, 433.1500, 0),
	(70, 'RB07', 3, 434.7750, 433.1750, 0),
	(71, 'RB08', 3, 434.8000, 433.2000, 0),
	(72, 'RB09', 3, 434.8250, 433.2250, 0),
	(73, 'RB10', 3, 434.8500, 433.2500, 0),
	(74, 'RB11', 3, 434.8750, 433.2750, 0),
	(75, 'RB12', 3, 434.9000, 433.3000, 0),
	(76, 'RB13', 3, 434.9250, 433.3250, 0),
	(77, 'RB14', 3, 434.9500, 433.3500, 0),
	(78, 'RB15', 3, 434.9750, 433.3750, 0),
	(79, 'RU66', 3, 438.4250, 430.8250, 0),
	(80, 'RU68', 3, 438.4500, 430.8500, 0),
	(81, 'RU69', 3, 438.4625, 430.8625, 0),
	(82, 'RU70', 3, 438.4750, 430.8750, 0),
	(83, 'RU71', 3, 438.4875, 430.8875, 0),
	(84, 'RU72', 3, 438.5000, 430.9000, 0),
	(85, 'RU74', 3, 438.5250, 430.9250, 0),
	(86, 'RU75', 3, 438.5375, 430.9375, 0),
	(87, 'RU76', 3, 438.5500, 430.9500, 0),
	(88, 'RU77', 3, 438.5625, 430.9625, 0),
	(89, 'RU78', 3, 438.5750, 430.9750, 0);";

	$status = ($sql->db_Select_gen($query6)) ? E_MESSAGE_SUCCESS : E_MESSAGE_ERROR;
    	$mes->add("Adding Default table data to table: Channels", $status);


	}

    /*
	   function uninstall_options()
	   {

	   }


	   function uninstall_post($var)
	   {
	   // print_a($var);
	   }

	   function upgrade_post($var)
	   {
	   // $sql = e107::getDb();
	   }
	*/
}

?>
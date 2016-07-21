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

include_lan(e_PLUGIN . "repeater/languages/" . e_LANGUAGE . "_rpt.php");

if (!defined('e107_INIT')){
    exit;
}

e107::js('repeater', 'js/bootstrap-dialog.min.js', 'jquery'); // Load Plugin javascript and include jQuery framework
e107::js('repeater', 'js/repeater.js', 'jquery'); // Load Plugin javascript and include jQuery framework
e107::css('repeater', 'css/repeater.css'); // load css file
e107::css('repeater', 'css/bootstrap-dialog.min.css'); // load css file
e107::lan('repeater', 'repeater'); // e_PLUGIN.'guestbook/languages/'.e_LANGUAGE.'/guestbook.php'

class repeater{
    public $allow_access = false;
    // public $convert;
    protected $template;
    public $log_type;
    public $log_remark;
    protected $data;
    protected $sc;
    protected $prefs;
    protected $sql;
    protected $tp;
    protected $frm;
    protected $csvFile = array();
    protected $mes;
    // foreign key tables
    protected $fkeys = array();
    protected $validKeys = array();

    /**
    *
    * @param $
    * @return
    * @author
    * @version
    */
    function __construct(){
        $this->mes = e107::getMessage();
        $this->prefs = e107::getPref();
        $this->tp = e107::getParser();
        $this->frm = e107::getForm();
        $this->sql = e107::getDB();

        $this->load_prefs();
        $this->defaultData();
        $this->log_type = 'debug_repeater';
        $this->log_remark = 'plugin_repeater';
        $this->allow_access = check_class($this->prefs['rpt_viewclass']);
        $this->validKeys = array('bands', 'channels', 'types', 'regions', 'ctcss', 'status');

        /*
        $mins = 1;
        $dir = 'output';
        if ($handle = opendir($dir)){
            while (false !== ($file = readdir($handle))){
                if ($file[0] == '.' || is_dir($dir . '/' . $file)){
                    continue;
                }
                if ((time() - filemtime($dir . '/' . $file)) > ($mins * 60)){
                    unlink($dir . '/' . $file);
                }
            }
            closedir($handle);
        }
        if (USER){
            $this->user_read();
        }else{
            $this->defaultData();
        }
  */
    }
    // ********************************************************************************************
    // *
    // * Plugin load and Save prefs
    // *
    // ********************************************************************************************
    /**
    *
    * @param $
    * @return
    * @author
    * @version
    */ function getdefaultprefs(){
        $this->prefs = array("rpt_viewclass" => 255,
            "rpt_adminclass" => 255,
            );
    }
    /**
    *
    * @param $
    * @return
    * @author
    * @version
    */
    function save_prefs(){
        global $sql, $eArrayStorage;
        // save preferences to datarpt
        $tmp = $eArrayStorage->WriteArray($this->prefs);
        $sql->db_Update("core", "e107_value='$tmp' where e107_name='rpt'", false);
        return;
    }
    /**
    *
    * @param $
    * @return
    * @author
    * @version
    */
    function load_prefs(){
        global $sql, $eArrayStorage;
        // get preferences from datarpt
        $num_rows = $sql->db_Select("core", "*", "e107_name='rpt' ");
        $row = $sql->db_Fetch();
        if (empty($row['e107_value'])){
            // insert default preferences if none exist
            $this->getDefaultPrefs();
            $tmp = $eArrayStorage->WriteArray($this->prefs);
            $sql->db_Insert("core", "'rpt', '$tmp' ");
            $sql->db_Select("core", "*", "e107_name='rpt' ");
        }else{
            $this->prefs = $eArrayStorage->ReadArray($row['e107_value']);
        }
        return;
    }
    /**
    *
    * @param $
    * @return
    * @author
    * @version
    */
    function clear_cache(){
        global $e107cache;
        // list all the items you wish to clear associated with this plugin
        $e107cache->clear('nq_rpt_plugin_menu');
        $e107cache->clear('rpt_plugin_rss');
    }

    /**
    * repeater::processMain()
    *
    * @return
    */
    public function processMain(){
        $this->pageLoad();
        if (isset($_POST['repeaterMake'])){
            // $this->retrieveData();
            $this->generate();
            // $this->user_audit();
        }
        /*
        if (isset($_POST['rpt_reset'])){
            $this->defaultData();
            if (USER){
                $this->user_write();
            }
        }
    	   */
        $this->getTemplate();
        $this->sc = new repeater_shortcodes();
        $this->loadForeignDB(true);
        $this->sc->fkeys = $this->fkeys;
        $this->sc->data = $this->data;
        // var_dump($this->sc->fkeys);
        $repeater_text = $this->frm->open('repeater_form', 'post', null, $options);
        $repeater_text .= $this->frm->hidden('repeaterPreviewjs', 'numrecs');
        $repeater_text .= $this->tp->parsetemplate($this->template->REPEATER_LIST(), true, $this->sc);
        $repeater_text .= $this->frm->close();
        return $repeater_text;
    }
    protected function pageLoad(){
        $this->data = $SESSION['repeater'];
        // if last time is zero or more than 60 minutes elapsed
        if (intval($this->data['repeaterLast']) == 0 || (time() - $this->data['repeaterLast']) > 3600){
            // get default data
            $this->defaultData();
        }
        // if $_POST then get the form fields
        if (isset($_POST)){
            foreach($_POST as $key => $value){
                if (substr($key, 0, 8) === 'repeater')
                    $this->data[$key] = $value;
            }
        }
        if (isset($_POST['repeaterReset'])){
            // reset button clicked
            $this->defaultData();
        }
        // set the update time and save the data in a session
        $this->data['repeaterLast'] = time();
        // var_dump($this->data);
        $SESSION['repeater'] = $this->data;
    }
    /**
    * repeater::getTemplate()
    *
    * @return
    */
    protected function getTemplate(){
        if (file_exists(THEME . 'repeater_template.php')){
            define(RPT_THEME, THEME . 'repeater_template.php');
        }else{
            define(RPT_THEME, 'templates/repeater_template.php');
        }
        require_once(RPT_THEME);
        $this->template = new rpt_template;
    }
    /**
    * repeater::fetchCsv()
    *
    * @return
    */
    protected function fetchCsv(){
        $retval = false;
        $csvFile = "http://www.ukrepeater.net/csvcreatewithstatus.php";
        $file_handle = fopen($csvFile, 'r');
        if ($file_handle === false){
            $this->mes->addError('Error reading remote file');
        }else{
            $numrecs = 0;
            while (!feof($file_handle)){
                $this->csvFile[] = fgetcsv($file_handle);
                $numrecs++;
            }
            $retval = $numrecs;
        }
        fclose($file_handle);
        return $retval;
    }
    /**
    * repeater::import_csv()
    *
    * @return
    */
    protected function importCsv(){
        global $sql, $tp;
        $retval = false;
        $csvFile = 'csvfiles/repeaterlist-status.csv';
        // check file exists
        if (!is_readable($csvFile)){
            $this->mes->addError('Missing File');
        }else{
            $file_handle = fopen($csvFile, 'rt');
            if ($file_handle === false){
                $this->mes->addError('Error reading file');
            }else{
                $numrecs = 0;
                while (!feof($file_handle)){
                    $this->csvFile[] = fgetcsv($file_handle);
                    $numrecs++;
                }
                $retval = $numrecs;
            }
            fclose($file_handle);
        }
        return $retval;
    }
    /**
    * repeater::loadForeignDB()
    *
    * @param boolean $all
    * @return
    */
    protected function loadForeignDB($all = false){
        if ($all){
            // get fields for selections
            $this->sql->select("repeater_band", "repeater_band_id,repeater_band_name", '', 'nowhere', false);
            while ($row = $this->sql->fetch()){
                $this->fkeys['bands'][$row['repeater_band_id']] = strtoupper($row['repeater_band_name']);
            }
            $this->sql->select("repeater_channel", "repeater_channelID,repeater_channelName", '', 'nowhere', false);
            while ($row = $this->sql->fetch()){
                $this->fkeys['channels'][$row['repeater_channelID']] = strtoupper($row['repeater_channelName']);
            }
            $this->sql->select("repeater_ctcss", "repeater_ctcss_id,repeater_ctcss", '', 'nowhere', false);
            while ($row = $this->sql->fetch()){
                $this->fkeys['ctcss'][$row['repeater_ctcss_id']] = strtoupper($row['repeater_ctcss']);
            }
            $this->sql->select("repeater_region", "repeater_region_id,repeater_region_name", '', 'nowhere', false);
            while ($row = $this->sql->fetch()){
                $this->fkeys['regions'][$row['repeater_region_id']] = strtoupper($row['repeater_region_name']);
            }
            $this->sql->select("repeater_status", "repeater_status_id,repeater_status", '', 'nowhere', false);
            while ($row = $this->sql->fetch()){
                $this->fkeys['status'][$row['repeater_status_id']] = strtoupper($row['repeater_status']);
            }
            $this->sql->select("repeater_type", "repeater_type_id,repeater_type_name", '', 'nowhere', false);
            while ($row = $this->sql->fetch()){
                $this->fkeys['types'][$row['repeater_type_id']] = strtoupper($row['repeater_type_name']);
            }
        }else{
            // get minimum fields
            $this->sql->select("repeater_band", "repeater_band_id,repeater_band_name", '', 'nowhere', false);
            while ($row = $this->sql->fetch()){
                $this->fkeys['bands'][$row['repeater_band_id']] = strtoupper($row['repeater_band_name']);
            }
            $this->sql->select("repeater_channel", "repeater_channelID,repeater_channelName", '', 'nowhere', false);
            while ($row = $this->sql->fetch()){
                $this->fkeys['channels'][$row['repeater_channelID']] = strtoupper($row['repeater_channelName']);
            }
            $this->sql->select("repeater_ctcss", "repeater_ctcss_id,repeater_ctcss", '', 'nowhere', false);
            while ($row = $this->sql->fetch()){
                $this->fkeys['ctcss'][$row['repeater_ctcss_id']] = strtoupper($row['repeater_ctcss']);
            }
            $this->sql->select("repeater_region", "repeater_region_id,repeater_region_code", '', 'nowhere', false);
            while ($row = $this->sql->fetch()){
                $this->fkeys['regions'][$row['repeater_region_id']] = strtoupper($row['repeater_region_code']);
            }
            $this->sql->select("repeater_status", "repeater_status_id,repeater_status", '', 'nowhere', false);
            while ($row = $this->sql->fetch()){
                $this->fkeys['status'][$row['repeater_status_id']] = strtoupper($row['repeater_status']);
            }
            $this->sql->select("repeater_type", "repeater_type_id,repeater_type_name", '', 'nowhere', false);
            while ($row = $this->sql->fetch()){
                $this->fkeys['types'][$row['repeater_type_id']] = strtoupper($row['repeater_type_name']);
            }
        }
        // var_dump($this->fkeys);
    }
    /**
    * repeater::getFK()
    *
    * @param boolean $table
    * @param mixed $value
    * @return
    */
    protected function getFK($table = false, $value = null){
        $retval = null;
        // var_dump($this->validKeys);
        // var_dump($table);
        if (in_array($table, $this->validKeys)){
            // allowed fk name
            $value = strtoupper($value);
            $key = array_search($value, $this->fkeys[$table]);
            if (!is_null($key)){
                $retval = $key;
            }else{
                $retval = 1;
            }
        }
        return $retval;
    }
    /**
    * repeater::parseCsv()
    *
    * @return
    */
    public function parseCsv($remote = true){
        $this->loadForeignDB();
        $remote = false;
        if ($remote){
            $this->fetchCsv();
        }else{
            $this->importCsv();
        }
        $errored = false;
        foreach($this->csvFile as $key => $value){
            /*
        	   array (size=16)
        	   0 => string 'repeater' (length=8)
        	   1 => string 'band' (length=4)
        	   2 => string 'channel' (length=7)
        	   3 => string 'tx1' (length=3)
        	   4 => string 'rx1' (length=3)
        	   5 => string 'type' (length=4)
        	   6 => string 'locator' (length=7)
        	   7 => string 'where' (length=5)
        	   8 => string 'NGR' (length=3)
        	   9 => string 'region' (length=6)
        	   10 => string 'ctcss' (length=5)
        	   11 => string 'keeper' (length=6)
        	   12 => string 'lat' (length=3)
        	   13 => string 'lng' (length=3)
        	   14 => string 'status' (length=6)
        	   15 => string '' (length=0)

        	   */
            $record['repeater_id'] = 0;
            $record['repeater_callsign'] = $this->tp->toDB($value['0']);
            $record['repeater_band_fk'] = $this->getFK('bands', $value['1']); //fk
            $record['repeater_channel_fk'] = $this->getFK('channels', $value['2']); // fk
            $record['repeater_rx'] = $this->tp->toDB($value['3']);
            $record['repeater_tx'] = $this->tp->toDB($value['4']);
            $record['repeater_type_fk'] = $this->getFK('types', $value['5']); // fk
            $record['repeater_locator'] = $this->tp->toDB($value['6']);
            $record['repeater_town'] = $this->tp->toDB($value['7']);
            $record['repeater_ngr'] = $this->tp->toDB($value['8']);
            $record['repeater_region_fk'] = $this->getFK('regions', $value['9']); // fk
            $record['repeater_ctcss_fk'] = $this->getFK('ctcss', $value['10']); // fk
            $record['repeater_keeper'] = $this->tp->toDB($value['11']);
            $record['repeater_lat'] = $this->tp->toDB($value['12']);
            $record['repeater_long'] = $this->tp->toDB($value['13']);
            $record['repeater_status_fk'] = $this->getFK('status', $value['14']); // fk
            // var_dump($record);
            $result = $this->sql->insert('repeater', $record, false);
            if ($result === false){
                $errored = true;
            }
        }
        if ($errored){
            $this->mes->addError("Error updating repeater  list. See log for details");
        }else{
            $this->mes->addSuccess("Repeater list updated OK.");
        }
    }
    /**
    *
    * @param $count
    * @return
    * @author
    * @version
    */
    function ajaxCount(){
        // if we are counting then do it
        $select = $this->makeQuery(false);
        $this->sql->gen($select);
        $row = $this->sql->fetch();
        return $row['numrecs'];
    }
    function ajaxPreview(){
        // otherwise get the rest of the query
        $select = $this->makeQuery(true);
        if (count($this->ajaxGet['repeaterNote']) > 0){
            $this->ajaxData['repeaterNote'] = $this->ajaxGet['repeaterNote'];
        }else{
            $this->ajaxData['repeaterNote'] = array();
        }
        $this->ajaxData['repeaterFormat'] = (int)$this->ajaxGet['repeaterFormat'];
        if ($this->ajaxData['repeaterFormat'] == 0){
            $this->ajaxData['repeaterFormat'] = 0;
        }
        // $this->ajaxData['repeaterOutput'] = (int)$this->ajaxGet['repeaterOutput'];
        // if ($this->ajaxData['repeaterOutput'] == 0){
        // $this->ajaxData['repeaterOutput'] = 1;
        // }
        $this->ajaxData['repeaterBank'] = $this->ajaxGet['repeaterBank'];
        $this->ajaxData['repeaterScan'] = $this->ajaxGet['repeaterScan'];
        $this->ajaxData['repeaterPower'] = $this->ajaxGet['repeaterPower'];
        if ($this->ajaxData['repeaterPower'] == 0){
            $this->ajaxData['repeaterPower'] = 4;
        }
        $this->ajaxData['repeaterTone'] = $this->ajaxGet['repeaterTone'];
        $this->ajaxData['repeaterStart'] = (int)$this->ajaxGet['repeaterStart'];
        if ($this->ajaxData['repeaterStart'] == 0){
            $this->ajaxData['repeaterStart'] = 1;
        }
        $this->ajaxData['repeaterStep'] = (int)$this->ajaxGet['repeaterStep'];
        if ($this->ajaxData['repeaterStep'] == 0){
            $this->ajaxData['repeaterStep'] = 1;
        }
        $this->ajaxData['rpt_break'] = (int)$this->ajaxGet['rpt_break'];
        if ($this->ajaxData['rpt_break'] == 0){
            $this->ajaxData['rpt_break'] = 1;
        }
        $numrecs = $this->sql->gen($select, false);
        $retval = "<div style='max-height:500px;overflow-y:scroll;overflow-x:hidden;'>
        	<div >
        		<span class='repeaterPreviewHead repeaterPreviewLeft repeaterPreview60'  >Callsign</span>
        		<span class='repeaterPreviewHead repeaterPreviewRight repeaterPreview40' >Band</span>
        		<span class='repeaterPreviewHead repeaterPreviewRight repeaterPreview70' >Rx</span>
        		<span class='repeaterPreviewHead repeaterPreviewRight repeaterPreview05' >&nbsp;</span>
        		<span class='repeaterPreviewHead repeaterPreviewLeft repeaterPreview130' >Region</span>
        		<span class='repeaterPreviewHead repeaterPreviewRight repeaterPreview40' >CTCSS</span>
        		<span class='repeaterPreviewHead repeaterPreviewRight repeaterPreview05' >&nbsp;</span>
        		<span class='repeaterPreviewHead repeaterPreviewLeft repeaterPreview70' >Type</span>
        		<span class='repeaterPreviewHead repeaterPreviewLeft repeaterPreview130' >Town</span>
        		<span class='repeaterPreviewHead repeaterPreviewLeft repeaterPreview40' >Locator</span>
        		<span class='repeaterPreviewHead repeaterPreviewRight repeaterPreview40' >Miles</span>
        		<span class='repeaterPreviewHead repeaterPreviewLeft repeaterPreview130' >Status</span>
        	</div>
        	";
        while ($row = $this->sql->fetch()){
            switch ($row['repeater_status_fk']){
                case '2':
                    $op_class = 'repeaterOperational';
                    break;
                case '7':
                case '8':
                case '9':
                case '10':
                    $op_class = 'repeaterWarning';
                    break;
                case '1':
                case '3':
                case '4':
                case '5':
                case '6':
                case '11':
                default:
                    $op_class = 'repeaterNotOperational';
                    break;
            } // switch
            $retval .= "
		<div class='repeaterPreviewZebra' >
			<span class='repeaterPreviewCell repeaterPreviewLeft  repeaterPreview60 $op_class' ><b>{$row['repeater_callsign']}</b></span>
			<span class='repeaterPreviewCell repeaterPreviewRight repeaterPreview40' >{$row['repeater_band_name']}</span>
			<span class='repeaterPreviewCell repeaterPreviewRight repeaterPreview70' >{$row['repeater_rx']}</span>
			<span class='repeaterPreviewCell repeaterPreviewRight repeaterPreview05' >&nbsp;</span>
			<span class='repeaterPreviewCell repeaterPreviewLeft  repeaterPreview130' >{$row['repeater_region_name']}</span>
			<span class='repeaterPreviewCell repeaterPreviewRight repeaterPreview40' >{$row['repeater_ctcss']}</span>
			<span class='repeaterPreviewCell repeaterPreviewRight repeaterPreview05' >&nbsp;</span>
			<span class='repeaterPreviewCell repeaterPreviewLeft  repeaterPreview70' >{$row['repeater_type_name']}</span>
			<span class='repeaterPreviewCell repeaterPreviewLeft  repeaterPreview130' >" . ucwords(strtolower(substr($row['repeater_town'], 0, 14))) . "</span>
			<span class='repeaterPreviewCell repeaterPreviewLeft  repeaterPreview40' >{$row['repeater_locator']}</span>
			<span class='repeaterPreviewCell repeaterPreviewRight repeaterPreview40' >" . ($row['distance'] > 0?intval($row['distance']):'n/a') . "</span>
			<span class='repeaterPreviewCell repeaterPreviewLeft  repeaterPreview130 $op_class' ><b>" . ucwords(strtolower(substr($row['repeater_status'], 0, 19))) . "</b></span>
		</div>";
        }
        $retval .= "</div>";
        return $retval;
    }

    protected function makeQuery($full = true){
        $query = " WHERE repeater_id > 0 ";
        if (!isset($_POST['repeater_region'][0]) && count($_POST['repeater_region']) > 0){
            // not all and there is one or more regions
            $regionArray = array_keys($_POST['repeater_region']);
            $regionSet = implode(',', $regionArray);
            $query .= " and find_in_set(repeater_region_fk,'{$regionSet}')";
        }
        if (!isset($_POST['repeater_type'][0]) && count($_POST['repeater_type']) > 0){
            // not all and there is one or more types
            $typeArray = array_keys($_POST['repeater_type']);
            $typeSet = implode(',', $typeArray);
            $query .= " and find_in_set(repeater_type_fk,'{$typeSet}')";
        }
        if (!isset($_POST['repeater_band'][0]) && count($_POST['repeater_band']) > 0){
            // not all and there is one or more bands
            $bandArray = array_keys($_POST['repeater_band']);
            $bandSet = implode(',', $bandArray);
            $query .= " and find_in_set(repeater_band_fk,'{$bandSet}')";
        }
        if (!isset($_POST['repeater_status'][0]) && count($_POST['repeater_status']) > 0){
            // not all and there is one or more bands
            $statusArray = array_keys($_POST['repeater_status']);
            $statusSet = implode(',', $statusArray);
            $query .= " and find_in_set(repeater_status_fk,'{$statusSet}')";
        }
        $having = '';
        $distanceArg = '';
        if ($_POST['repeaterMiles'] > 0 && $_POST['repeaterLocator'] !== ''){
            $this->loc2latlong($_POST['repeaterLocator']);
            $having = " having distance <= {$_POST['repeaterMiles']} ";
            // select a field called distance based on lat lon of repeater and the specified locator
            $distanceArg = ",ROUND((DEGREES(ACOS(SIN(RADIANS({$this->lat})) * SIN(RADIANS(repeater_lat)) + COS(RADIANS({$this->lat})) * COS(RADIANS(repeater_lat)) * COS(RADIANS({$this->lon} + (repeater_long*-1))))) * 60 * 1.1515),1) AS `distance` ";
        }else{
            // $_POST['repeaterOrder'] = 1;
        }
        $order = " ORDER BY ";
        switch ($_POST['repeaterOrder']){
            case 1:
                $order .= "repeater_callsign";
                break;
            case 2:
                $order .= "repeater_region_name";
                break;
            case 3:
                $order .= "repeater_band_name";
                break;
            case 4:
                $order .= "repeater_rx";
                break;
            case 5:
                $order .= "repeater_region_name,repeater_callsign";
                break;
            case 6:
                $order .= "repeater_region_name,repeater_band";
                break;
            case 7:
                $order .= "repeater_region_name,repeater_rx";
                break;
            case 8:
                $order .= "repeater_band_name,repeater_callsign";
                break;
            case 9:
                $order .= "repeater_band_name,repeater_region_name";
                break;
            case 10:
                $order .= "repeater_band_name,repeater_rx";
                break;
            case 11:
                $order .= "repeater_rx,repeater_callsign";
                break;
            case 12:
                $order .= "repeater_rx,repeater_region_name";
                break;
            case 13:
                $order .= "distance ASC";
                break;
            default:
                $order .= "repeater_callsign";
        } // switch
        if ($full){
            $select = "
			SELECT * FROM
            	(SELECT * {$distanceArg} FROM #repeater
				 {$having}  ) as tmp
				left join #repeater_band on tmp.repeater_band_fk=repeater_band_id
				left join #repeater_channel on tmp.repeater_channel_fk=repeater_channelID
				left join #repeater_type on tmp.repeater_type_fk=repeater_type_id
				left join #repeater_region on tmp.repeater_region_fk=repeater_region_id
				left join #repeater_ctcss on tmp.repeater_ctcss_fk=repeater_ctcss_id
				left join #repeater_status on tmp.repeater_status_fk=repeater_status_id
				{$query}
				{$order}
				";
        }else{
            $select = "SELECT count(*) as numrecs FROM(SELECT repeater_id {$distanceArg} FROM #repeater {$query} $having) as tmp";
        }
        return $select;
    }
    /*
    protected function retrieveData(){
        if (count($_POST['rpt_region']) > 0){
            $this->data['rpt_region'] = $_POST['rpt_region'];
        }else{
            $this->data['rpt_region'] = array();
        }
        if (count($_POST['rpt_band']) > 0){
            $this->data['rpt_band'] = $_POST['rpt_band'];
        }else{
            $this->data['rpt_band'] = array();
        }
        if (count($_POST['rpt_mode']) > 0){
            $this->data['rpt_mode'] = $_POST['rpt_mode'];
        }else{
            $this->data['rpt_mode'] = array();
        }
        if (count($_POST['repeaterNote']) > 0){
            $this->data['repeaterNote'] = $_POST['repeaterNote'];
        }else{
            $this->data['repeaterNote'] = array();
        }
        $this->data['repeaterFormat'] = (int)$_POST['repeaterFormat'];
        $this->data['repeaterOutput'] = (int)$_POST['repeaterOutput'];
        if ($this->data['repeaterOutput'] == 0){
            $this->data['repeaterOutput'] = 1;
        }
        $this->data['repeaterMiles'] = (int)$_POST['repeaterMiles'];
        $this->data['repeaterLocator'] = $_POST['repeaterLocator'];
        $this->data['repeaterOrder'] = (int)$_POST['repeaterOrder'];
        if ($this->data['repeaterOrder'] == 0){
            $this->data['repeaterOrder'] = 1;
        }
        $this->data['repeaterBank'] = $_POST['repeaterBank'];
        $this->data['repeaterScan'] = $_POST['repeaterScan'];
        $this->data['repeaterPower'] = $_POST['repeaterPower'];
        if ($this->data['repeaterPower'] == 0){
            $this->data['repeaterPower'] = 4;
        }
        $this->data['repeaterTone'] = $_POST['repeaterTone'];
        $this->data['repeaterStart'] = (int)$_POST['repeaterStart'];
        if ($this->data['repeaterStart'] == 0){
            $this->data['repeaterStart'] = 1;
        }
        $this->data['repeaterStep'] = (int)$_POST['repeaterStep'];
        if ($this->data['repeaterStep'] == 0){
            $this->data['repeaterStep'] = 1;
        }
        $this->data['rpt_break'] = (int)$_POST['rpt_break'];
        if ($this->data['rpt_break'] == 0){
            $this->data['rpt_break'] = 1;
        }
        if (USER){
            $this->user_write();
        }
    }
	   */
    function generate(){
        // set up output file type xls, csv or txt
        $this->filename = 'output/' . md5(time());

        $repeaterFormat = (int)$_POST['repeaterFormat'];

        switch ($_POST['repeaterOutput']){
            case 2:
                $this->filename .= '.xls'; // XLS
                break;
            case 3:
                $this->filename .= '.txt'; // screen ;
                break;
            case 1:
            default:
                $this->filename .= '.csv'; // csv
                // header('Content-Type: text/csv; charset=utf-8');
                // header("Content-Disposition: attachment; filename={$this->filename}");
        } // switch
        switch ($repeaterFormat){
            case 1:
                $this->repeaterFile = new FTxx00();

                break;
            case 2:
                $this->repeaterFile = new FTBasic();
                break;
            case 3:
                $this->repeaterFile = new FTVBR5K();
                break;
            case 4:
                $this->repeaterFile = new VX2();
                break;
            case 5:
                $this->repeaterFile = new VX5();
                break;
            case 6:
                $this->repeaterFile = new VX6();
                break;
            case 7:
                $this->repeaterFile = new VX7();
                break;
            case 7:
                $this->repeaterFile = new chirp();
                break;
            case 0:
            default:
                $this->repeaterFile = new FTB_Standard();
                break;
        } // switch
        $fp = fopen($this->filename, 'wt');
    	$first = $this->repeaterFile->initialise();
        fputcsv($fp, $first);

        $arg = $this->makeQuery(true);
        $numrecs = $this->sql->gen($arg, true);
        while ($row = $this->sql->fetch()){
            $this->repeaterFile->addData($row);
            $line = $this->repeaterFile->outputData();
            fputcsv($fp, $line);
        }
        fclose($fp);
        switch ($_POST['repeaterOutput']){
            case 2:
                // XLS
                break;
            case 3:
                // to the screen
                // header('Content-Type: application/txt; charset=utf-8');
                // header("Content-Disposition: inline; filename=repeaters.txt");
                readfile($this->filename);
                exit();
                break;
            case 1:
            default: // as a file to download
                header('Content-Type: application/csv; charset=utf-8');
                header("Content-Disposition: attachment; filename=repeaters.csv");
                readfile($this->filename);
                exit();
                // csv
        } // switch
    }
    function output_chirp(){
        // chirp csv
        // Location,Name,Frequency,Duplex,Offset,Tone,rToneFreq,cToneFreq,DtcsCode,DtcsPolarity,Mode,TStep,Skip,Comment,URCALL,RPT1CALL,RPT2CALL
    }
    function output_FTB_Standard(){
        // ,Freq,Mode,Shift,Offset,TX Freq,Enc/Dec,Tone,Code,Show,Name,Power,Scan,Clk,Step,Scan2,Scan3,Scan4,Scan5,Scan6,Description,Bank1,Bank2,Bank3,Bank4,Bank5,Bank6,Bank7,Bank8,Bank9,Bank10,Bank11,Bank12,Bank13,Bank14,Bank15,Bank16,Bank17,Bank18,Bank19,Bank20,Bank21,Bank22,Bank23,Bank24,PRFRQ,SMSQL,RXATT,BELL,Masked,Internet,DCSinv
        $output = array('#', 'Freq', 'Mode', 'Shift', 'Offset', 'TX Freq', 'Enc/Dec', 'Tone', 'Code', 'Show', 'Name', 'Power', 'Scan', 'Clk', 'Step', 'Scan2', 'Scan3', 'Scan4', 'Scan5', 'Scan6', 'Description', 'BANK 1', 'BANK 2', 'BANK 3', 'BANK 4', 'BANK 5', 'BANK 6', 'BANK 7', 'BANK 8', 'BANK 9', 'BANK10', 'BANK11', 'BANK12', 'BANK13', 'BANK14', 'BANK15', 'BANK16', 'BANK17', 'BANK18', 'BANK19', 'BANK20', 'BANK21', 'BANK22', 'BANK23', 'BANK24', 'PRFRQ', 'SMSQL', 'RXATT', B'ELL', 'Masked', 'Internet', 'DCSinv');
        $fp = fopen($this->filename, 'wt');
        fputcsv($fp, $output);
        switch ($_POST['repeaterScan']){
            case 1:
                $scan = 'Off';
                break;
            case 2:
                $scan = 'Pref';
                break;
            case 3:
                $scan = 'Skip';;
                break;
            case 0:
            default:
                $scan = '';
        } // switch
        switch ($_POST['repeaterPower']){
            case 1:
                $power = 'LOW1';
                break;
            case 2:
                $power = 'MID2';
                break;
            case 3:
                $power = 'MID1';
                break;
            case 4:
                $power = 'HIGH';;
                break;
            default:
                $enc = '';
        } // switch
        switch ($_POST['repeaterTone']){
            case 1:
                $enc = 'ENC';
                break;
            case 2:
                $enc = 'ENC/DEC';
                break;
            case 3:
                $enc = 'DCS';;
                break;
            case 0:

            default:
                $enc = 'OFF';
        } // switch
        $i = $_POST['repeaterStart'];
        for($bc = 1;$bc <= 24;$bc++){
            $bank[$bc] = '';
            if ($_POST['repeaterBank'] == $bc){
                $bank[$bc] = 'X';
            }
        }
        foreach($this->row as $value){
            $split = ($value['repeater_split'] > 0?'Plus':'Minus');
            // #,Freq,Mode,Shift,Offset,TX Freq,Enc/Dec,Tone,Code,Show,Name,Power,Scan,
            // Clk,Step,Scan2,Scan3,Scan4,Scan5,Scan6,Description,
            // Bank1,Bank2,Bank3,Bank4,Bank5,Bank6,Bank7,Bank8,Bank9,Bank10,Bank11,Bank12,Bank13,Bank14,Bank15,Bank16,Bank17,Bank18,Bank19,Bank20,Bank21,Bank22,Bank23,Bank24,PRFRQ,SMSQL,RXATT,BELL,Masked,Internet,DCSinv
            $csv = array($i,
                number_format($value['repeater_rx'], 4) . ' ',
                'FM',
                $split,
                abs($value['repeater_split']),
                number_format($value['repeater_tx'], 4) . ' ',
                $enc,
                $value['repeater_ctcss'],
                '023' ,
                '',
                $value['repeater_callsign'],
                $power,
                $scan,
                'OFF',
                '12.5 K',
                '',
                '',
                '',
                '',
                '',
                $value['repeaterNote'],
                $bank[1],
                $bank[2],
                $bank[3],
                $bank[4],
                $bank[5],
                $bank[6],
                $bank[7],
                $bank[8],
                $bank[9],
                $bank[10],
                $bank[11],
                $bank[12],
                $bank[13],
                $bank[14],
                $bank[15],
                $bank[16],
                $bank[17],
                $bank[18],
                $bank[19],
                $bank[20],
                $bank[21],
                $bank[22],
                $bank[23],
                $bank[24],
                '1600 HZ',
                'OFF',
                'OFF',
                'OFF',
                'NO',
                'OFF',
                'RN-TN'
                );
            fputcsv($fp, $csv);
            $i = $i + $_POST['repeaterStep'];
        }
    }

    function output_FTxx00(){
        // #,Freq,Mode,Shift,Offset,TX Freq,Enc/Dec,Tone,Code,Show,Name,Power,Scan,Clk,Step,Scan2,Scan3,Scan4,Scan5,Scan6,Description
        /*       $output = array('#', 'Freq', 'Mode', 'Shift', 'Offset', 'TX Freq', 'Enc/Dec', 'Tone', 'Code', 'Show', 'Name', 'Power', 'Scan', 'Clk', 'Step', 'Scan2', 'Scan3', 'Scan4', 'Scan5', 'Scan6', 'Description');
        $fp = fopen($this->filename, 'wt');
        fputcsv($fp, $output);
        switch ($_POST['repeaterScan']){
            case 1:
                $scan = 'off';
                break;
            case 2:
                $scan = 'Pref';
                break;
            case 3:
                $scan = 'Skip';;
                break;
            case 0:
            default:
                $scan = '';
        } // switch
        switch ($_POST['repeaterPower']){
            case 1:
                $power = 'LOW';
                break;
            case 2:
                $power = 'MID2';
                break;
            case 3:
                $power = 'MID1';
                break;
            case 4:
                $power = 'HIGH';;
                break;
            default:
                $enc = '';
        } // switch
        switch ($_POST['repeaterTone']){
            case 1:
                $enc = 'CTCSS Enc';
                break;
            case 2:
                $enc = 'CTCSS Enc+Dec';
                break;
            case 3:
                $enc = 'DCS';;
                break;
            case 0:

            default:
                $enc = '';
        } // switch
    */
        $i = $_POST['repeaterStart'];
        foreach($this->row as $value){
            $split = ($value['repeater_split'] > 0?'Plus':'Minus');
            $csv = array($i,
                number_format($value['repeater_rx'], 4) . ' ',
                'FM',
                $split,
                abs($value['repeater_split']),
                $value['repeater_tx'],
                '',
                $value['repeater_ctcss'],
                '023' ,
                'Name',
                $value['repeater_callsign'],
                $power,
                $scan,
                'Off',
                '',
                '',
                '',
                '',
                '',
                '',
                $value['repeaterNote']
                );
            // fputcsv($fp, $csv);
            $i = $i + $_POST['repeaterStep'];
        }
        // fclose($fp);
    }
    function output_FTBasic(){
        // Memory,RX Freq,RX Mode,Shift,TX Shift/Freq,TX Mode,CTCSS/DCS,Tone/Code,Show,Name,Scan,Group,Description
        $output = array('Memory', 'RX Freq', 'RX Mode', 'Shift', 'TX Shift/Freq', 'TX Mode', 'CTCSS/DCS', 'Tone/Code', 'Show', 'Name', 'Scan', 'Group' , 'Description');
        $fp = fopen($this->filename, 'wt');
        fputcsv($fp, $output);
        switch ($_POST['repeaterScan']){
            case 1:
                $scan = 'off';
                break;
            case 2:
                $scan = 'Pref';
                break;
            case 3:
                $scan = 'Skip';;
                break;
            case 0:
            default:
                $scan = '';
        } // switch
        switch ($_POST['repeaterPower']){
            case 1:
                $power = 'LOW';
                break;
            case 2:
                $power = 'MID2';
                break;
            case 3:
                $power = 'MID1';
                break;
            case 4:
                $power = 'HIGH';;
                break;
            default:
                $enc = '';
        } // switch
        switch ($_POST['repeaterTone']){
            case 1:
                $enc = 'Enc';
                break;
            case 2:
                $enc = 'Enc/Dec';
                break;
            case 3:
                $enc = 'DCS';;
                break;
            case 0:

            default:
                $enc = 'Off';
        } // switch
        $i = $_POST['repeaterStart'];
        foreach($this->row as $value){
            $split = ($value['repeater_split'] > 0?'Plus':'Minus');
            $csv = array($i,
                number_format($value['repeater_rx'], 4) . ' ',
                'FM',
                $split,
                abs($value['repeater_split']),
                '',
                $enc,
                $value['repeater_ctcss'],
                'Name',
                $value['repeater_callsign'],
                'Y',
                $value['repeaterNote']
                );
            fputcsv($fp, $csv);
            $i = $i + $_POST['repeaterStep'];
        }

        fclose($fp);
    }
    function output_FTVBR5K(){
        // Frequency,Mode,Name,Bank
        $output = array('Frequency', 'Mode', 'Name', 'Bank');
        $fp = fopen($this->filename, 'wt');
        fputcsv($fp, $output);
        foreach($this->row as $value){
            $split = ($value['repeater_split'] > 0?'Plus':'Minus');
            $csv = array(number_format($value['repeater_rx'], 4) . ' ',
                'FMN',
                $value['repeater_callsign'],
                '',
                );
            fputcsv($fp, $csv);
        }

        fclose($fp);
    }
    function output_VX2(){
        // VX2 #,Tag,Freq,Name,Mode,Scn Md,Step,Masked,RPT SH,Shift,TS/DCS,Tone,DCS,TX Pwr,Dev,Clk Sh
        $output = array('#', 'Tag', 'Freq', 'Name', 'Mode', 'Scn Md', 'Step', 'Masked', 'RPT SH', 'Shift', 'TS/DCS', 'Tone', 'DCS', 'TX Pwr', 'Dev', 'Clk Sh');
        $fp = fopen($this->filename, 'wt');
        fputcsv($fp, $output);
        switch ($_POST['repeaterScan']){
            case 1:
                $scan = 'off';
                break;
            case 2:
                $scan = 'Pref';
                break;
            case 3:
                $scan = 'Skip';;
                break;
            case 0:
            default:
                $scan = '';
        } // switch
        switch ($_POST['repeaterPower']){
            case 1:
                $power = 'LOW';
                break;
            case 2:
                $power = 'LOW';
                break;
            case 3:
                $power = 'HIGH';
                break;
            case 4:
                $power = 'HIGH';;
                break;
            default:
                $enc = '';
        } // switch
        switch ($_POST['repeaterTone']){
            case 1:
                $enc = 'TONE';
                break;
            case 2:
                $enc = 'T SQL';
                break;
            case 3:
                $enc = 'DCS';;
                break;
            case 0:

            default:
                $enc = 'OFF';
        } // switch
        $i = $_POST['repeaterStart'];
        foreach($this->row as $value){
            $split = ($value['repeater_split'] > 0?'RPT+':'RPT-');
            $csv = array($i,
                $value['repeater_callsign'],
                number_format($value['repeater_rx'], 4) . ' ',
                'ALPHA',
                'NFM',
                'OFF',
                '5 kHz',
                'False',
                $split,
                abs($value['repeater_split']),
                $enc,
                $value['repeater_ctcss'],
                '023' ,
                $power,
                'NORM',
                'OFF',
                );
            fputcsv($fp, $csv);
            $i = $i + $_POST['repeaterStep'];
        }

        fclose($fp);
    }
    function output_VX5(){
        // #,Tag,RX Freq,Mode,Skip,Step,Masked,RPT SH,ShiftFrq,TS/DCS,Tone,DCS,TX Pwr,TX Dev,Clk Sh,Icon,MG1,MG2,MG3,MG4,MG5
        $output = array('#', 'Tag', 'RX Freq', 'Mode', 'Skip', 'Step', 'Masked', 'RPT SH', 'ShiftFrq', 'TS/DCS', 'Tone', 'DCS', 'TX Pwr', 'TX Dev', 'Clk Sh', 'Icon', 'MG1', 'MG2', 'MG3', 'MG4', 'MG5');
        $fp = fopen($this->filename, 'wt');
        fputcsv($fp, $output);
        switch ($_POST['repeaterScan']){
            case 1:
                $scan = 'OFF';
                break;
            case 2:
                $scan = 'PREF';
                break;
            case 3:
                $scan = 'SKIP';;
                break;
            case 0:
            default:
                $scan = '';
        } // switch
        switch ($_POST['repeaterPower']){
            case 1:
                $power = 'L1';
                break;
            case 2:
                $power = 'L2';
                break;
            case 3:
                $power = 'L3';
                break;
            case 4:
                $power = 'HIGH';;
                break;
            default:
                $enc = '';
        } // switch
        switch ($_POST['repeaterTone']){
            case 1:
                $enc = 'TONE';
                break;
            case 2:
                $enc = 'TSQL';
                break;
            case 3:
                $enc = 'DCS';;
                break;
            case 0:

            default:
                $enc = 'OFF';
        } // switch
        $i = $_POST['repeaterStart'];
        foreach($this->row as $value){
            $split = ($value['repeater_split'] > 0?'+RPT':'-RPT');
            $csv = array($i,
                $value['repeater_callsign'],
                number_format($value['repeater_rx'], 4) . ' ',
                'NFM',
                'OFF',
                '5 kHz',
                'NO',
                $split,
                abs($value['repeater_split']),
                $enc,
                $value['repeater_ctcss'],
                '023' ,
                $power,
                'NORM',
                'OFF',
                '13',
                '',
                '',
                '',
                '',
                ''
                );
            fputcsv($fp, $csv);
            $i = $i + $_POST['repeaterStep'];
        }

        fclose($fp);
    }
    function output_VX6(){
        // #,Tag,RX Freq,Name,Mode,Skip,Step,Masked,RPT Sh,ShiftFrq,TS/DCS,Tone,DCS,TX Pwr,TX Dev,Clk Sh,BANK 1,BANK 2,BANK 3,BANK 4,BANK 5,BANK 6,BANK 7,BANK 8,BANK 9,BANK10,BANK11,BANK12,BANK13,BANK14,BANK15,BANK16,BANK17,BANK18,BANK19,BANK20,BANK21,BANK22,BANK23,BANK24
        $output = array('#', 'Tag', 'RX Freq', 'Name', 'Mode', 'Skip', 'Step', 'Masked', 'RPT Sh', 'ShiftFrq', 'TS/DCS', 'Tone', 'DCS', 'TX Pwr', 'TX Dev', 'Clk Sh', 'BANK 1', 'BANK 2', 'BANK 3', 'BANK 4', 'BANK 5', 'BANK 6', 'BANK 7', 'BANK 8', 'BANK 9', 'BANK10', 'BANK11', 'BANK12', 'BANK13', 'BANK14', 'BANK15', 'BANK16', 'BANK17', 'BANK18', 'BANK19', 'BANK20', 'BANK21', 'BANK22', 'BANK23', 'BANK24');
        $fp = fopen($this->filename, 'wt');
        fputcsv($fp, $output);
        switch ($_POST['repeaterScan']){
            case 1:
                $scan = 'OFF';
                break;
            case 2:
                $scan = 'PREF';
                break;
            case 3:
                $scan = 'SKIP';;
                break;
            case 0:
            default:
                $scan = '';
        } // switch
        switch ($_POST['repeaterPower']){
            case 1:
                $power = 'LOW1';
                break;
            case 2:
                $power = 'LOW2';
                break;
            case 3:
                $power = 'LOW3';
                break;
            case 4:
                $power = 'HIGH';;
                break;
            default:
                $enc = '';
        } // switch
        switch ($_POST['repeaterTone']){
            case 1:
                $enc = 'TONE';
                break;
            case 2:
                $enc = 'TSQL';
                break;
            case 3:
                $enc = 'DCS';;
                break;
            case 0:

            default:
                $enc = 'OFF';
        } // switch
        $i = $_POST['repeaterStart'];
        for($bc = 1;$bc <= 24;$bc++){
            $bank[$bc] = '';
            if ($_POST['repeaterBank'] == $bc){
                $bank[$bc] = 'X';
            }
        }
        foreach($this->row as $value){
            $split = ($value['repeater_split'] > 0?'+RPT':'-RPT');
            // #,Tag,RX Freq,Name,Mode,Skip,Step,Masked,RPT Sh,ShiftFrq,TS/DCS,Tone,DCS,TX Pwr,TX Dev,Clk Sh,
            // BANK 1,BANK 2,BANK 3,BANK 4,BANK 5,BANK 6,BANK 7,BANK 8,BANK 9,BANK10,BANK11,BANK12,BANK13,BANK14,BANK15,BANK16,BANK17,BANK18,BANK19,BANK20,BANK21,BANK22,BANK23,BANK24
            $csv = array($i,
                $value['repeater_callsign'],
                number_format($value['repeater_rx'], 4) . ' ',
                'ALPHA',
                'NFM',
                $scan,
                '5 kHz',
                'NO',
                $split,
                abs($value['repeater_split']),
                $enc,
                $value['repeater_ctcss'],
                '023' ,
                $power,
                'NORM',
                'OFF',
                $bank[1],
                $bank[2],
                $bank[3],
                $bank[4],
                $bank[5],
                $bank[6],
                $bank[7],
                $bank[8],
                $bank[9],
                $bank[10],
                $bank[11],
                $bank[12],
                $bank[13],
                $bank[14],
                $bank[15],
                $bank[16],
                $bank[17],
                $bank[18],
                $bank[19],
                $bank[20],
                $bank[21],
                $bank[22],
                $bank[23],
                $bank[24],
                );
            fputcsv($fp, $csv);
            $i = $i + $_POST['repeaterStep'];
        }

        fclose($fp);
    }
    function output_VX7(){
        // VX7 #,Tag,Freq,Mode,Scn Md,Step,Masked,RPT SH,Shift,TS/DCS,Tone,DCS,TX Pwr,Dev,Clk Sh,Icon
        $output = array('#', 'Tag', 'Freq', 'Mode', 'Scn Md', 'Step', 'Masked', 'RPT SH', 'Shift', 'TS/DCS', 'Tone', 'DCS', 'TX Pwr', 'Dev', 'Clk Sh', 'Icon');
        $fp = fopen($this->filename, 'wt');
        fputcsv($fp, $output);
        switch ($_POST['repeaterScan']){
            case 1:
                $scan = 'off';
                break;
            case 2:
                $scan = 'Pref';
                break;
            case 3:
                $scan = 'Skip';;
                break;
            case 0:
            default:
                $scan = '';
        } // switch
        switch ($_POST['repeaterPower']){
            case 1:
                $power = 'L1';
                break;
            case 2:
                $power = 'L2';
                break;
            case 3:
                $power = 'L3';
                break;
            case 4:
                $power = 'MAX';;
                break;
            default:
                $enc = '';
        } // switch
        switch ($_POST['repeaterTone']){
            case 1:
                $enc = 'TONE';
                break;
            case 2:
                $enc = 'TSQL';
                break;
            case 3:
                $enc = 'DCS';;
                break;
            case 0:

            default:
                $enc = 'OFF';
        } // switch
        $i = $_POST['repeaterStart'];
        foreach($this->row as $value){
            $split = ($value['repeater_split'] > 0?'RPT+':'RPT-');
            // #,Tag,Freq,Mode,Scn Md,Step,Masked,RPT SH,Shift,TS/DCS,Tone,DCS,TX Pwr,Dev,Clk Sh,Icon
            $csv = array($i,
                $value['repeater_callsign'],
                number_format($value['repeater_rx'], 4) . ' ',
                'NFM',
                'OFF',
                '5 kHz',
                'False',
                $split,
                abs($value['repeater_split']),
                $enc,
                $value['repeater_ctcss'],
                '023' ,
                $power,
                'NORM',
                'OFF',
                '13'
                );
            fputcsv($fp, $csv);
            $i = $i + $_POST['repeaterStep'];
        }

        fclose($fp);
    }
    function user_audit(){
        global $sql, $eArrayStorage, $e107;
        $ip = $e107->getip();
        // save preferences to datarpt
        $tmp = $eArrayStorage->WriteArray($this->data);
        $sql->db_Insert("repeater_audit", "
	0,
	'{$tmp}',
	" . USERID . ",
	'$ip',
	 " . time(), false);
        return;
    }
    function user_write(){
        global $sql, $eArrayStorage;
        // save preferences to datarpt
        $tmp = $eArrayStorage->WriteArray($this->data);
        $sql->db_Update("repeater_users", "repeater_user_settings=\"{$tmp}\",repeater_user_lastupdated=" . time() . " where repeater_user_id='" . USERID . "'", false);
        return;
    }
    function user_read(){
        global $sql, $eArrayStorage;
        // get preferences from datarpt
        $num_rows = $sql->db_Select("repeater_users", "*", "WHERE repeater_user_id='" . USERID . "' ", 'nowhere', false);
        $row = $sql->db_Fetch();
        if (empty($row['repeater_user_id'])){
            // insert default preferences if none exist
            $this->defaultData();
            $tmp = $eArrayStorage->WriteArray($this->data);
            $sql->db_Insert("repeater_users", USERID . ",'$tmp', " . time(), false);
            $sql->db_Select("repeater_users", "*", "repeater_user_id='" . USERID . "' ");
        }else{
            $this->data = $eArrayStorage->ReadArray($row['repeater_user_settings']);
        }
        return;
    }
    protected function defaultData(){
        $this->data['repeater_region'] = array(0 => 0);
        $this->data['repeater_type'] = array(0 => 0);
        $this->data['repeater_status'] = array(0 => 0);
        $this->data['repeater_band'] = array(0 => 0);
        $this->data['repeaterNote'] = array(1 => 1);
        $this->data['repeaterMiles'] = 0;
        $this->data['repeaterLocator'] = '';
        $this->data['repeaterOrder'] = 1;
        $this->data['repeaterBank'] = 0;
        $this->data['repeaterScan'] = 0;
        $this->data['repeaterPower'] = 4;
        $this->data['repeaterTone'] = 0;
        $this->data['repeaterStart'] = 1;
        $this->data['repeaterStep'] = 1;
        $this->data['repeaterFormat'] = 0;
        $this->data['repeaterOutput'] = 1;
        // $this->data['repeater_break'] = 1;
    }
    /**
    * repeater::loc2latlong()
    *
    * @param mixed $locator
    * @return
    */
    protected function loc2latlong($locator = null){
        $grid = str_split (strtoupper($locator));
        $this->lon = ((ord($grid[0]) - ord('A')) * 20) - 180;
        $this->lat = ((ord($grid[1]) - ord('A')) * 10) - 90;
        $this->lon += ((ord($grid[2]) - ord('0')) * 2);
        $this->lat += ((ord($grid[3]) - ord('0')) * 1);
        if (count($grid) >= 5){
            // have subsquares
            $this->lon += ((ord($grid[4])) - ord('A')) * (5 / 60);
            $this->lat += ((ord($grid[5])) - ord('A')) * (2.5 / 60);
            // move to center of subsquare
            $this->lon += (2.5 / 60);
            $this->lat += (1.25 / 60);
            // not too precise
        }else{
            // move to center of square
            $this->lon += 1;
            $this->lat += 0.5;
            // even less precise
        }
    }
}
/**
* FTxx00
*
* @package
* @author barry
* @copyright Copyright (c) 2015
* @version $Id$
* @access public
*/
class FTxx00 extends repeaterRecord{
    function __construct(){
        // #,Freq,Mode,Shift,Offset,TX Freq,Enc/Dec,Tone,Code,Show,Name,Power,Scan,Clk,Step,Scan2,Scan3,Scan4,Scan5,Scan6,Description
        $this->output = array('#', 'Freq', 'Mode', 'Shift', 'Offset', 'TX Freq', 'Enc/Dec', 'Tone', 'Code', 'Show', 'Name', 'Power', 'Scan', 'Clk', 'Step', 'Scan2', 'Scan3', 'Scan4', 'Scan5', 'Scan6', 'Description');

        switch ($_POST['repeaterScan']){
            case 1:
                $this->scan = 'off';
                break;
            case 2:
                $this->scan = 'Pref';
                break;
            case 3:
                $this->scan = 'Skip';;
                break;
            case 0:
            default:
                $this->scan = '';
        } // switch
        switch ($_POST['repeaterPower']){
            case 1:
                $this->power = 'LOW';
                break;
            case 2:
                $this->power = 'MID2';
                break;
            case 3:
                $this->power = 'MID1';
                break;
            case 4:
                $this->power = 'HIGH';;
                break;
            default:
                $this->power = '';
        } // switch
        switch ($_POST['repeaterTone']){
            case 1:
                $this->enc = 'CTCSS Enc';
                break;
            case 2:
                $this->enc = 'CTCSS Enc+Dec';
                break;
            case 3:
                $this->enc = 'DCS';;
                break;
            case 0:
            default:
                $this->enc = '';
        } // switch
        parent::__construct();
    }
    function outputData(){

        $split = ($this->row['repeater_split'] > 0?'Plus':'Minus');
         // #,Freq,Mode,Shift,Offset,TX Freq,Enc/Dec,Tone,Code,Show,Name,Power,Scan,Clk
		// ,Step,Scan2,Scan3,Scan4,Scan5,Scan6,Description
		$csv = array(
			$this->nextrec, // #
            number_format($this->row['rxfreq'], 4) . ' ', // freq
            'FM', // mode
            $split, // shift
            abs($this->row['repeater_split']), // offset
            $this->row['repeater_tx'], // txfreq
            $this->enc, // enc / dec
            $this->row['repeater_ctcss'], // tone
            '' , // dcs code
            'Name', // show what
            $this->row['name'], // the tag name
            $this->power, // power
            $this->scan, // scan
            'Off', // clock shift
            '', // step
            '', // scan 2
            '', // scan 3
            '', // scan 4
            '', // scan 5
            '', // scan 6
            $this->row['repeaterNote'] // description
            );
        // fputcsv($fp, $csv);
        $this->nextrec += $this->step;
//    	var_dump($csv);
 //   	die("W");
        return $csv;
    }
}
class FTBasic extends repeaterRecord{
    function __construct(){
        // #,Freq,Mode,Shift,Offset,TX Freq,Enc/Dec,Tone,Code,Show,Name,Power,Scan,Clk,Step,Scan2,Scan3,Scan4,Scan5,Scan6,Description
        $this->output = array('#', 'Freq', 'Mode', 'Shift', 'Offset', 'TX Freq', 'Enc/Dec', 'Tone', 'Code', 'Show', 'Name', 'Power', 'Scan', 'Clk', 'Step', 'Scan2', 'Scan3', 'Scan4', 'Scan5', 'Scan6', 'Description');
        parent::__construct();
    }
}
class FTVBR5K extends repeaterRecord{
    function __construct(){
        // Frequency,Mode,Name,Bank
        $this->output = array('Frequency', 'Mode', 'Name', 'Bank');
        parent::__construct();
    }
}
class VX2 extends repeaterRecord{
    function __construct(){
        // VX2 #,Tag,Freq,Name,Mode,Scn Md,Step,Masked,RPT SH,Shift,TS/DCS,Tone,DCS,TX Pwr,Dev,Clk Sh
        $this->output = array('#', 'Tag', 'Freq', 'Name', 'Mode', 'Scn Md', 'Step', 'Masked', 'RPT SH', 'Shift', 'TS/DCS', 'Tone', 'DCS', 'TX Pwr', 'Dev', 'Clk Sh');
        parent::__construct();
    }
}
class VX5 extends repeaterRecord{
    function __construct(){
        // #,Tag,RX Freq,Mode,Skip,Step,Masked,RPT SH,ShiftFrq,TS/DCS,Tone,DCS,TX Pwr,TX Dev,Clk Sh,Icon,MG1,MG2,MG3,MG4,MG5
        $this->output = array('#', 'Tag', 'RX Freq', 'Mode', 'Skip', 'Step', 'Masked', 'RPT SH', 'ShiftFrq', 'TS/DCS', 'Tone', 'DCS', 'TX Pwr', 'TX Dev', 'Clk Sh', 'Icon', 'MG1', 'MG2', 'MG3', 'MG4', 'MG5');
        parent::__construct();
    }
}
class VX6 extends repeaterRecord{
    function __construct(){
        // #,Tag,RX Freq,Name,Mode,Skip,Step,Masked,RPT Sh,ShiftFrq,TS/DCS,Tone,DCS,TX Pwr,TX Dev,Clk Sh,BANK 1,BANK 2,BANK 3,BANK 4,BANK 5,BANK 6,BANK 7,BANK 8,BANK 9,BANK10,BANK11,BANK12,BANK13,BANK14,BANK15,BANK16,BANK17,BANK18,BANK19,BANK20,BANK21,BANK22,BANK23,BANK24
        $this->output = array('#', 'Tag', 'RX Freq', 'Name', 'Mode', 'Skip', 'Step', 'Masked', 'RPT Sh', 'ShiftFrq', 'TS/DCS', 'Tone', 'DCS', 'TX Pwr', 'TX Dev', 'Clk Sh', 'BANK 1', 'BANK 2', 'BANK 3', 'BANK 4', 'BANK 5', 'BANK 6', 'BANK 7', 'BANK 8', 'BANK 9', 'BANK10', 'BANK11', 'BANK12', 'BANK13', 'BANK14', 'BANK15', 'BANK16', 'BANK17', 'BANK18', 'BANK19', 'BANK20', 'BANK21', 'BANK22', 'BANK23', 'BANK24');
        parent::__construct();
    }
}
class VX7 extends repeaterRecord{
    function __construct(){
        // VX7 #,Tag,Freq,Mode,Scn Md,Step,Masked,RPT SH,Shift,TS/DCS,Tone,DCS,TX Pwr,Dev,Clk Sh,Icon
        $this->output = array('#', 'Tag', 'Freq', 'Mode', 'Scn Md', 'Step', 'Masked', 'RPT SH', 'Shift', 'TS/DCS', 'Tone', 'DCS', 'TX Pwr', 'Dev', 'Clk Sh', 'Icon');
        parent::__construct();
    }
}
class chirp extends repeaterRecord{
}
class FTB_Standard extends repeaterRecord{
    function __construct(){
        // ,Freq,Mode,Shift,Offset,TX Freq,Enc/Dec,Tone,Code,Show,Name,Power,Scan,Clk,Step,Scan2,Scan3,Scan4,Scan5,Scan6,Description,Bank1,Bank2,Bank3,Bank4,Bank5,Bank6,Bank7,Bank8,Bank9,Bank10,Bank11,Bank12,Bank13,Bank14,Bank15,Bank16,Bank17,Bank18,Bank19,Bank20,Bank21,Bank22,Bank23,Bank24,PRFRQ,SMSQL,RXATT,BELL,Masked,Internet,DCSinv
        $this->output = array('#', 'Freq', 'Mode', 'Shift', 'Offset', 'TX Freq', 'Enc/Dec', 'Tone', 'Code', 'Show', 'Name', 'Power', 'Scan', 'Clk', 'Step', 'Scan2', 'Scan3', 'Scan4', 'Scan5', 'Scan6', 'Description', 'BANK 1', 'BANK 2', 'BANK 3', 'BANK 4', 'BANK 5', 'BANK 6', 'BANK 7', 'BANK 8', 'BANK 9', 'BANK10', 'BANK11', 'BANK12', 'BANK13', 'BANK14', 'BANK15', 'BANK16', 'BANK17', 'BANK18', 'BANK19', 'BANK20', 'BANK21', 'BANK22', 'BANK23', 'BANK24', 'PRFRQ', 'SMSQL', 'RXATT', B'ELL', 'Masked', 'Internet', 'DCSinv');
        parent::__construct();
    }
}
/**
* repeaterRecord
*
* @package
* @author barry
* @copyright Copyright (c) 2015
* @version $Id$
* @access public
*/
class repeaterRecord{
    // FTxx00 - // ,Freq,Mode,Shift,Offset,TX Freq,Enc/Dec,Tone,Code,Show,Name,Power,Scan,Clk,Step,Scan2,Scan3,Scan4,Scan5,Scan6,Description,Bank1,Bank2,Bank3,Bank4,Bank5,Bank6,Bank7,Bank8,Bank9,Bank10,Bank11,Bank12,Bank13,Bank14,Bank15,Bank16,Bank17,Bank18,Bank19,Bank20,Bank21,Bank22,Bank23,Bank24,PRFRQ,SMSQL,RXATT,BELL,Masked,Internet,DCSinv
    // FTBasic - Memory,RX Freq,RX Mode,Shift,TX Shift/Freq,TX Mode,CTCSS/DCS,Tone/Code,Show,Name,Scan,Group,Description
    // FTVBR5K Frequency,Mode,Name,Bank
    // VX2 #,Tag,Freq,Name,Mode,Scn Md,Step,Masked,RPT SH,Shift,TS/DCS,Tone,DCS,TX Pwr,Dev,Clk Sh
    // VX5 #,Tag,RX Freq,Mode,Skip,Step,Masked,RPT SH,ShiftFrq,TS/DCS,Tone,DCS,TX Pwr,TX Dev,Clk Sh,Icon,MG1,MG2,MG3,MG4,MG5
    // VX6 #,Tag,RX Freq,Name,Mode,Skip,Step,Masked,RPT Sh,ShiftFrq,TS/DCS,Tone,DCS,TX Pwr,TX Dev,Clk Sh,BANK 1,BANK 2,BANK 3,BANK 4,BANK 5,BANK 6,BANK 7,BANK 8,BANK 9,BANK10,BANK11,BANK12,BANK13,BANK14,BANK15,BANK16,BANK17,BANK18,BANK19,BANK20,BANK21,BANK22,BANK23,BANK24
    // VX7 #,Tag,Freq,Mode,Scn Md,Step,Masked,RPT SH,Shift,TS/DCS,Tone,DCS,TX Pwr,Dev,Clk Sh,Icon
    protected $bank;
    protected $scan;
    protected $power;
    protected $enc;
    protected $start;
    protected $step;
    public $output;
    protected static $nextrec ;

    protected $row = array(
        'rxfreq' => '', // receive frequency
        'txfreq' => '' , // transmit frequency
        'shift' => '' , // repeater shift
        'offset' => '',
        'tone' => '', // ctcss tone
        'code' => '', // dcs code
        'show' => '', // show this channel
        'name' => '', // tag name for channel
        'clkshift' => '' ,
        'scan' => '' ,
        'description' => '' ,
        'prfrq' => '',
        'smsql' => '' ,
        'rxatt' => '' ,
        'bell' => '' ,
        'masked' => '' ,
        'internet' => '' ,
        'dcsinv' => '' ,
        'tag' => '' ,
        'scanmode' => '' ,
        'rptsh' => '' ,
        'tsdcs' => '',
        'deviation' => '' ,
        'icon' => '',
        'memoGroup' => '' ,
        'note' => ''
        );

    function __construct($data){
        $this->bank = $_POST['repeaterBank'];
        $this->scan = $_POST['repeaterScan'];
        $this->power = $_POST['repeaterPower'];
        $this->enc = $_POST['repeaterTone'];
        $this->start = $_POST['repeaterStart'];
        $this->step = $_POST['repeaterStep'];
        $this->nextrec = $this->start;
        $this->row['note'] = '';
        if (in_array(1, $repeaterNote) || in_array(2, $repeaterNote)){
            $this->row['note'] .= $data['repeater_region'] . ", ";
        }
        if (in_array(1, $repeaterNote) || in_array(3, $repeaterNote)){
            $this->row['note'] .= $data['repeater_town'] . ", ";
        }
        if (in_array(1, $repeaterNote) || in_array(4, $repeaterNote)){
            $this->row['note'] .= $data['repeater_band'] . ", ";
        }
        if (in_array(1, $repeaterNote) || in_array(5, $repeaterNote)){
            $this->row['note'] .= $data['repeater_locator'] . ", ";
        }
        return ;
    }
    public function initialise(){
        return $this->output;
    }
    public function addData($data){

        $this->row['rxfreq'] = $data['repeater_rx']; // receive frequency
        $this->row['txfreq'] = $data['repeater_tx']; // transmit frequency
        $this->row['shift'] = $data['repeater_split']; // repeater shift
        // check if tx or rx freq given with shift/split
        // and calc the other if not known
        // $this->$row['offset'] => '',
        $this->row['tone'] = $data['repeater_ctcss']; // ctcss tone
        // 'code' => '', // dcs code
        // 'show' => '', // show this channel
        $this->row['name'] = $data['repeater_callsign']; // tag name for channel
    }
    public function clearData(){
    }
    public function outputData(){
    }
}
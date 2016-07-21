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
if (!defined('e107_INIT')){
    exit;
}

/**
*/
class repeater_shortcodes extends e_shortcode{
   // public $current;
  //  public $fields;
   // public $data;
    protected $frm;
    protected $fkeys;
    /**
    * Constructor
    */
    function __construct(){
        $this->frm = e107::getForm();
        // var_dump($this->fkeys);
    }
    /**
    * repeater_shortcodes::sc_repeater_updir()
    *
    * @param mixed $parm
    * @return
    */
    function sc_repeater_updir($parm){
        switch ($parm){
            case 'menu':
                $retval = "<a href='" . e_SELF . "?0.menu' ><img src='images/updir_24.png' alt='' title='' style='width:24px;height:24px;' /></a>"; ;
                break;
            default:
                // if in doubt do the menu
                $retval = "<a href='" . e_SELF . "?0.menu' ><img src='images/updir_24.png' alt='' title='' style='width:24px;height:24px;' /></a>"; ;
        } // switch
        return $retval;
    }

    /**
    *
    * @param $
    * @return
    * @author
    * @version
    */
    function sc_repeater_inactive(){
        global $repeater_id;
        return RPT_INDEX_LIST_002 . "in row {$repeater_id}";
    }
    /**
    *
    * @param $
    * @return
    * @author
    * @version
    */
    function sc_repeater_help(){
        $helpText = ("You can select the range of repeaters you wish to extract from the database. This can be done by Region, Band, Type or distance from a specified locator. <br /><br />
		Some programming software will allow the imported CSV to have memory bank information, scan settings etc applied. If your programming software supports this then you can add those settings to your CSV. If you want to space out the memory locations then you can set the memory interval and the starting memory location.<br /><br />
		If you wish to see which repeaters will be listed then click the preview button and a dialogue window will list your selection.<br /><br />
		To turn on or off the tooltips click the appropriate button below.");
        return "<a href='" . e_SELF . "?help' id='rpt_helpButton' ><img src='images/help_on_24.png' alt='Show help' /></a><div style='display:none;' id='rpt_helpText'>{$helpText}</div>";
    }
    /**
    * repeater_shortcodes::sc_repeater_region()
    *
    * @return
    */
    function sc_repeater_region(){
        $options = array('class' => ' repeaterChange repeaterCheckbox', 'label' => "All");
        if (isset($this->data['repeater_region'][0])){
            // all is checked so
            $display = 'display:none;';
            $blank = true;
            $retval .= $this->frm->checkbox("repeater_region[0]", 'All', true, $options);
        }else{
            $display = 'display:block;';
            $blank = false;
            $retval .= $this->frm->checkbox("repeater_region[0]", 'All', false, $options);
        }
        $retval .= "<div id='repeaterRegion' style='{$display}' >";
        foreach ($this->fkeys['regions'] as $key => $value){
            if ($key > 1){
                $options = array('class' => ' repeaterChange repeaterCheckboxDrop', 'label' => ucwords(strtolower($value)));
                $retval .= $this->frm->checkbox("repeater_region[{$key}]", $value, !$blank && array_key_exists($key, $this->data['repeater_region']), $options);
            }
        }
        $retval .= "</div>";
        return $retval;
    }
    /**
    * repeater_shortcodes::sc_repeater_band()
    *
    * @return
    */
    function sc_repeater_band(){
        $options = array('class' => ' repeaterChange repeaterCheckbox', 'label' => "All");
        if (isset($this->data['repeater_band'][0])){
            // all is checked so
            $display = 'display:none;';
            $blank = true;
            $retval .= $this->frm->checkbox("repeater_band[0]", 'All', true, $options);
        }else{
            $display = 'display:block;';
            $blank = false;
            $retval .= $this->frm->checkbox("repeater_band[0]", 'All', false, $options);
        }
        $retval .= "<div id='repeaterBand' style='{$display}' >";
        foreach ($this->fkeys['bands'] as $key => $value){
            if ($key > 1){
                $options = array('class' => ' repeaterChange repeaterCheckboxDrop', 'label' => ucwords(strtolower($value)));
                $retval .= $this->frm->checkbox("repeater_band[{$key}]", $value, !$blank && array_key_exists($key, $this->data['repeater_band']), $options);
            }
        }
        $retval .= "</div>";
        return $retval;
    }
    /**
    * repeater_shortcodes::sc_repeater_mode()
    *
    * @return
    */
    function sc_repeater_mode(){
        $options = array('class' => ' repeaterChange repeaterCheckbox', 'label' => "All");
        if (isset($this->data['repeater_type'][0])){
            // all is checked so
            $display = 'display:none;';
            $blank = true;
            $retval .= $this->frm->checkbox("repeater_type[0]", 'All', true, $options);
        }else{
            $display = 'display:block;';
            $blank = false;
            $retval .= $this->frm->checkbox("repeater_type[0]", 'All', false, $options);
        }
        $retval .= "<div id='repeaterMode' style='{$display}' >";
        foreach ($this->fkeys['types'] as $key => $value){
            if ($key > 1){
                $options = array('class' => ' repeaterChange repeaterCheckboxDrop', 'label' => ucwords(strtolower($value)));
                $retval .= $this->frm->checkbox("repeater_type[{$key}]", $value, !$blank && array_key_exists($key, $this->data['repeater_type']), $options);
            }
        }
        $retval .= "</div>";
        return $retval;
    }
    /**
    * repeater_shortcodes::sc_repeater_status()
    *
    * @return
    */
    function sc_repeater_status(){
        $options = array('class' => ' repeaterChange repeaterCheckbox', 'label' => "All");
        if (isset($this->data['repeater_status'][0])){
            // all is checked so
            $display = 'display:none;';
            $blank = true;
            $retval .= $this->frm->checkbox("repeater_status[0]", 'All', true, $options);
        }else{
            $display = 'display:block;';
            $blank = false;
            $retval .= $this->frm->checkbox("repeater_status[0]", 'All', false, $options);
        }
        $retval .= "<div id='repeaterStatus' style='{$display}' >";
        foreach ($this->fkeys['status'] as $key => $value){
            if ($key > 1){
                $options = array('class' => ' repeaterChange repeaterCheckboxDrop', 'label' => ucwords(strtolower($value)));
                $retval .= $this->frm->checkbox("repeater_status[{$key}]", $value, !$blank && array_key_exists($key, $this->data['repeater_status']), $options);
            }
        }
        $retval .= "</div>";
        return $retval;
    }
    /**
    * repeater_shortcodes::sc_repeater_note()
    *
    * @return
    */
    function sc_repeater_note(){
        $options = array('class' => ' repeaterChange repeaterCheckboxDrop', 'label' => "All");
        if (isset($this->data['repeaterNote'][1])){
            // all is checked so
            $display = 'display:none;';
            $blank = true;
            $retval .= $this->frm->checkbox("repeaterNote[1]", 1, true, $options);
        }else{
            $tmp = $this->data['repeaterNote'];
            $display = 'display:block;';
            $blank = false;
            $retval .= $this->frm->checkbox("repeaterNote[1]", 1, false, $options);
        }
        $retval .= "<div id='repeaterMemo' style='{$display}' >";
        $options = array('class' => ' repeaterChange repeaterCheckboxDrop', 'label' => "Region");
        $retval .= $this->frm->checkbox("repeaterNote[2]", 2, !$blank && isset($this->data['repeaterNote'][2]), $options);
        $options = array('class' => ' repeaterChange repeaterCheckboxDrop', 'label' => "Town");
        $retval .= $this->frm->checkbox("repeaterNote[3]", 3, !$blank && isset($this->data['repeaterNote'][3]), $options);
        $options = array('class' => ' repeaterChange repeaterCheckboxDrop', 'label' => "Band");
        $retval .= $this->frm->checkbox("repeaterNote[4]", 4, !$blank && isset($this->data['repeaterNote'][4]), $options);
        $options = array('class' => ' repeaterChange repeaterCheckboxDrop', 'label' => "Locator");
        $retval .= $this->frm->checkbox("repeaterNote[5]", 5, !$blank && isset($this->data['repeaterNote'][5]), $options);
        $retval .= "</div>";
        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_order()
     *
     * @return
     */
    function sc_repeater_order(){ ;
        $tmp = $this->data['repeaterOrder'];
        $title = htmlentities('Output Order[#]The sort order for the output. If sorting by distance then the locator must be completed.');

        $selection = array(
            "1" => "Callsign *",
            "2" => "Region",
            "3" => "Band",
            "4" => "Rx Frequency",
            "5" => "Region, Callsign",
            "6" => "Region, Band",
            "7" => "Region, Frequency",
            "8" => "Band, Callsign",
            "9" => "Band, Region",
            "10" => "Band, Frequency",
            "11" => "Frequency, Callsign",
            "12" => "Frequency, Region",
            "13" => "Distance");
        $options = array("class" => "rpt_tip");
        $retval = $this->frm->select('repeaterOrder', $selection, $tmp, $options);

        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_format()
     *
     * @return
     */
    function sc_repeater_format(){ ;
        $tmp = $this->data['repeaterFormat'];
        $selection = array(
            "0" => "FTB Standard *",
            "1" => "FTBxx00",
            "2" => "FTBBasic",
            "3" => "FTBVR5K",
            "4" => "VX2 Commander",
            "5" => "VX5 Commander",
            "6" => "VX6 Commander",
            "7" => "VX7 Commander",
            "8" => "Chirp"
            );
        $retval = $this->frm->select('repeaterFormat', $selection, $tmp, $options);
        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_output()
     *
     * @return
     */
    function sc_repeater_output(){ ;
        $tmp = $this->data['repeaterOutput'];
        $selection = array(
            "1" => " CSV File *",
            "2" => "XLS",
            "3" => "Screen"
            );
        $retval = $this->frm->select('repeaterOutput', $selection, $tmp, $options);
        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_miles()
     *
     * @return
     */
    function sc_repeater_miles(){ ;
        $tmp = $this->data['repeaterMiles'];
        $selection = array(
            "0" => "Any Miles *",
            "10" => "10 Miles",
            "25" => "25 Miles",
            "50" => "50 Miles",
            "75" => "75 Miles",
            "100" => "100 Miles",
            "150" => "150 Miles",
            "200" => "200 Miles",
            "300" => "300 Miles",
            "400" => "400 Miles"
            );
        $options = array('class' => ' tbox repeaterField');
        $retval = $this->frm->select('repeaterMiles', $selection, $tmp, $options);
        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_locator()
     *
     * @return
     */
    function sc_repeater_locator(){ ;
        $tmp = $this->data['repeaterLocator'];
        $options = array('class' => ' tbox repeaterField repeaterLocator');
        $retval = $this->frm->text('repeaterLocator', $tmp, 6, $options);
        return $retval;
    }

    /**
     * repeater_shortcodes::sc_repeater_bank()
     *
     * @return
     */
    function sc_repeater_bank(){ ;
        $tmp = $this->data['repeaterBank'];
        $selection[0] = 'No bank *';
        for($i = 1;$i <= 25;$i++){
            $selection[$i] = $i;
        }

        $retval = $this->frm->select('repeaterBank', $selection, $tmp, $options);

        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_scan()
     *
     * @return
     */
    function sc_repeater_scan(){ ;
        $tmp = $this->data['repeaterScan'];
        $selection = array(
            "0" => "No scan *",
            "1" => "Off",
            "2" => "Pref",
            "3" => "Skip"
            );
        $retval = $this->frm->select('repeaterScan', $selection, $tmp, $options);

        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_power()
     *
     * @return
     */
    function sc_repeater_power(){ ;
        $tmp = $this->data['repeaterPower'];
        if ($tmp < 1 || $tmp > 3){
            $tmp = 4;
        }
        $selection = array(
            "1" => "Low",
            "2" => "Low medium",
            "3" => "Medium high",
            "4" => "High *"
            );
        $retval = $this->frm->select('repeaterPower', $selection, $tmp, $options);

        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_enc()
     *
     * @return
     */
    function sc_repeater_enc(){ ;
        $tmp = $this->data['repeaterTone'];
        if ($tmp < 1 || $tmp > 3){
            $tmp = 4;
        }
        $selection = array(
            "0" => "Off *",
            "1" => "Enc",
            "2" => "Enc/Dec",
            "3" => "DCS"
            );
        $retval = $this->frm->select('repeaterTone', $selection, $tmp, $options);
        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_preview()
     *
     * @return
     */
    function sc_repeater_preview(){
        $options = array('class' => 'btn-info');
        $retval = $this->frm->button('repeaterPreview', 'Preview', 'submit', '', $options);
        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_reset()
     *
     * @return
     */
    function sc_repeater_reset(){
        $options = array('class' => 'btn-warning');
        $retval = $this->frm->button('repeaterReset', 'Reset', 'submit', '', $options);
        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_make()
     *
     * @return
     */
    function sc_repeater_make(){
        $options = array('class' => 'btn-success');
        $retval = $this->frm->button('repeaterMake', 'Make', 'execute', '', $options);
        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_start()
     *
     * @return
     */
    function sc_repeater_start(){ ;
        $tmp = $this->data['repeaterStart'];
        $retval = $this->frm->number('repeaterStart', $tmp, 10);
        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_step()
     *
     * @return
     */
    function sc_repeater_step(){ ;
        $tmp = $this->data['rpt_step']; ;
        $tmp = $this->data['repeaterStep'];
        $selection = array(
            "1" => "1 *",
            "2" => "2",
            "5" => "5",
            "10" => "10"
            );
        $retval = $this->frm->select('repeaterStep', $selection, $tmp, $options);
        return $retval;
    }
    /**
     * repeater_shortcodes::sc_repeater_break()
     *
     * @return
     */
    function sc_repeater_break(){ ;
        $tmp = $this->data['rpt_break'];
        $retval = "
<input type='radio' name='rpt_break' id='rpt_break_1' value='1'  " . ($tmp == 1?"checked='checked'":"") . " /><label for='rpt_break_1'>&nbsp;&nbsp;1 *</label><br />
<input type='radio' name='rpt_break' id='rpt_break_10' value='10'  " . ($tmp == 10?"checked='checked'":"") . " /><label for='rpt_break_10'>&nbsp;10</label><br />
<input type='radio' name='rpt_break' id='rpt_break_100' value='100'  " . ($tmp == 100?"checked='checked'":"") . " /><label for='rpt_break_100'>100</label><br />";
        return $retval;
    }
}

/*
   #,Freq,Mode,Shift,Offset,TX Freq,Enc/Dec,Tone,Code,Show,Name,Power,Scan,Clk,Step,Scan2,Scan3,Scan4,Scan5,Scan6,Description,Bank1,Bank2,Bank3,Bank4,Bank5,Bank6,Bank7,Bank8,Bank9,Bank10,Bank11,Bank12,Bank13,Bank14,Bank15,Bank16,Bank17,Bank18,Bank19,Bank20,Bank21,Bank22,Bank23,Bank24,PRFRQ,SMSQL,RXATT,BELL,Masked,Internet,DCSinv

   */
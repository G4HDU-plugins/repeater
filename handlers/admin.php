<?php
/*
 *
 *
 * Copyright (C) 2008-2015 G4HDU)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 * repeater Plugin Administration UI
 *
 * $URL: https://e107.svn.sourceforge.net/svnroot/e107/trunk/e107_0.8/e107_plugins/release/includes/admin.php $
 * $Id: admin.php 12212 2011-05-11 22:25:02Z e107coders $
*/

if (!defined('e107_INIT')){
    exit;
}
 error_reporting(E_ALL);
/**
 * plugin_repeater_admin
 *
 * @package
 * @author barry
 * @copyright Copyright (c) 2015
 * @version $Id$
 * @access public
 */
class plugin_repeater_admin extends e_admin_dispatcher{
    /**
    * Format: 'MODE' => array('controller' =>'CONTROLLER_CLASS'[, 'index' => 'list', 'path' => 'CONTROLLER SCRIPT PATH', 'ui' => 'UI CLASS NAME child of e_admin_ui', 'uipath' => 'UI SCRIPT PATH']);
    * Note - default mode/action is autodetected in this order:
    * - $defaultMode/$defaultAction (owned by dispatcher - see below)
    * - $adminMenu (first key if admin menu array is not empty)
    * - $modes (first key == mode, corresponding 'index' key == action)
    *
    * @var array
    */
    protected $modes = array (
        'main' => array (
            'controller' => 'repeater_main_admin_ui',
            'path' => null,
            'ui' => 'repeater_main_admin_form_ui',
            'uipath' => null
            ),
    	'bands' => array (
    	            'controller' => 'repeater_bands_admin_ui',
    	            'path' => null,
    	            'ui' => 'repeater_bands_admin_form_ui',
    	            'uipath' => null
    	            ),
    	'types' => array (
            'controller' => 'repeater_types_admin_ui',
            'path' => null,
            'ui' => 'repeater_types_admin_form_ui',
            'uipath' => null
            ),
    	'regions' => array (
            'controller' => 'repeater_regions_admin_ui',
            'path' => null,
            'ui' => 'repeater_regions_admin_form_ui',
            'uipath' => null
    	),
    	'channels' => array (
    		'controller' => 'repeater_channels_admin_ui',
    		'path' => null,
    		'ui' => 'repeater_channels_admin_form_ui',
    		'uipath' => null
    	),
    	'ctcss' => array (
            'controller' => 'repeater_ctcss_admin_ui',
            'path' => null,
            'ui' => 'repeater_ctcss_admin_form_ui',
            'uipath' => null
            ),
    	'status' => array (
    		'controller' => 'repeater_status_admin_ui',
    		'path' => null,
    		'ui' => 'repeater_status_admin_form_ui',
    		'uipath' => null
    	),
        );

    /* Both are optional
	protected $defaultMode = null;
	protected $defaultAction = null;
	*/

    /**
    * Format: 'MODE/ACTION' => array('caption' => 'Menu link title'[, 'url' => '{e_PLUGIN}release/admin_config.php', 'perm' => '0']);
    * Additionally, any valid e107::getNav()->admin() key-value pair could be added to the above array
    *
    * @var array
    */
	protected $adminMenu = array(
	    'main/list' => array( 'caption' => 'Repeaters', 'perm' => 'P' ),
	    'main/create' => array( 'caption' => 'Create', 'perm' => 'P' ),

	    'other0' => array( 'divider' => true ),

		'bands/bandlist' => array( 'caption' => 'Bands', 'perm' => 'P' ),
		'bands/bandcreate' => array( 'caption' => 'Create', 'perm' => 'P' ),

		'other1' => array( 'divider' => true ),

		'types/typelist' => array( 'caption' => 'Types', 'perm' => 'P' ),
		'types/typecreate' => array( 'caption' => 'Create', 'perm' => 'P' ),

		'other2' => array( 'divider' => true ),

		'regions/regionlist' => array( 'caption' => 'Regions', 'perm' => 'P' ),
		'regions/regioncreate' => array( 'caption' => 'Create', 'perm' => 'P' ),

		'other3' => array( 'divider' => true ),

		'ctcss/ctcsslist' => array( 'caption' => 'CTCSS Tones', 'perm' => 'P' ),
		'ctcss/ctcsscreate' => array( 'caption' => 'Create', 'perm' => 'P' ),

		'other4' => array( 'divider' => true ),

		'status/statuslist' => array( 'caption' => 'Status', 'perm' => 'P' ),
		'status/statuscreate' => array( 'caption' => 'Create', 'perm' => 'P' ),

		'other5' => array( 'divider' => true ),

		'channels/channelslist' => array( 'caption' => 'Channels', 'perm' => 'P' ),
		'channels/channelscreate' => array( 'caption' => 'Create', 'perm' => 'P' ),

		'other6' => array( 'divider' => true ),

	    'main/settings' => array( 'caption' => "Preferences", 'perm' => 'P' ),
	    'main/audit' => array( 'caption' => "Audit Log", 'perm' => 'P' ),
	    'main/import' => array( 'caption' => "Manual Import", 'perm' => 'P' ),
	    'main/errors' => array( 'caption' => "Error log", 'perm' => 'P' ),

	    );

    /**
    * Optional, mode/action aliases, related with 'selected' menu CSS class
    * Format: 'MODE/ACTION' => 'MODE ALIAS/ACTION ALIAS';
    * This will mark active main/list menu item, when current page is main/edit
    *
    * @var array
    */
    protected $adminMenuAliases = array(
        'main/edit' => 'main/list'
        );

    /**
    * Navigation menu title
    *
    * Dsiplays at top of admin menu
    *
    * @var string
    */
    protected $menuTitle = "Repeater";
}

/**
* repeater_main_admin_ui
*
* @package
* @author barry
* @copyright Copyright (c) 2015
* @version $Id$
* @access public
*/
class repeater_main_admin_ui extends e_admin_ui{
	// required
	protected $pluginTitle = "Repeater";

	/**
	 * plugin name or 'core'
	 * IMPORTANT: should be 'core' for non-plugin areas because this
	 * value defines what CONFIG will be used. However, I think this should be changed
	 * very soon (awaiting discussion with Cam)
	 * Maybe we need something like $prefs['core'], $prefs['blank'] ... multiple getConfig support?
	 *
	 * @var string
	 */
	protected $pluginName = 'repeater';

	/**
	 * DB Table, table alias is supported
	 * Example: 'r.blank'
	 * @var string
	 */
	protected $table = "repeater";

	/**
	 * If present this array will be used to build your list query
	 * You can link fileds from $field array with 'table' parameter, which should equal to a key (table) from this array
	 * 'leftField', 'rightField' and 'fields' attributes here are required, the rest is optional
	 * Table alias is supported
	 * Note:
	 * - 'leftTable' could contain only table alias
	 * - 'leftField' and 'rightField' shouldn't contain table aliases, they will be auto-added
	 * - 'whereJoin' and 'where' should contain table aliases e.g. 'whereJoin' => 'AND u.user_ban=0'
	 *
	 * @var array [optional] table_name => array join parameters
	 */
	protected $tableJoin = array(
		//'u.user' => array('leftField' => 'comment_author_id', 'rightField' => 'user_id', 'fields' => '*'/*, 'leftTable' => '', 'joinType' => 'LEFT JOIN', 'whereJoin' => '', 'where' => ''*/)
	);

	/**
	 * This is only needed if you need to JOIN tables AND don't wanna use $tableJoin
	 * Write your list query without any Order or Limit.
	 *
	 * @var string [optional]
	 */
	protected $listQry = "";
	//

	// optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
	//protected $editQry = "SELECT * FROM #blank WHERE blank_id = {ID}";

	// required - if no custom model is set in init() (primary id)
	protected $pid = "repeater_id";

	// optional
	protected $perPage = 20;

	// default - true - TODO - move to displaySettings
	protected $batchDelete = true;

	// UNDER CONSTRUCTION
	protected $displaySettings = array();

	// UNDER CONSTRUCTION
	protected $disallowPages = array('main/create', 'main/prefs');

	//TODO change the blank_url type back to URL before blank.
	// required
	/**
	 * (use this as starting point for wiki documentation)
	 * $fields format  (string) $field_name => (array) $attributes
	 *
	 * $field_name format:
	 * 	'table_alias_or_name.field_name.field_alias' (if JOIN support is needed) OR just 'field_name'
	 * NOTE: Keep in mind the count of exploded data can be 1 or 3!!! This means if you wanna give alias
	 * on main table field you can't omit the table (first key), alternative is just '.' e.g. '.field_name.field_alias'
	 *
	 * $attributes format:
	 * 	- title (string) Human readable field title, constant name will be accpeted as well (multi-language support
	 *
	 *  - type (string) null (means system), number, text, dropdown, url, image, icon, datestamp, userclass, userclasses, user[_name|_loginname|_login|_customtitle|_email],
	 *    boolean, method, ip
	 *  	full/most recent reference list - e_form::renderTableRow(), e_form::renderElement(), e_admin_form_ui::renderBatchFilter()
	 *  	for list of possible read/writeParms per type see below
	 *
	 *  - data (string) Data type, one of the following: int, integer, string, str, float, bool, boolean, model, null
	 *    Default is 'str'
	 *    Used only if $dataFields is not set
	 *  	full/most recent reference list - e_admin_model::sanitize(), db::_getFieldValue()
	 *  - dataPath (string) - xpath like path to the model/posted value. Example: 'dataPath' => 'prefix/mykey' will result in $_POST['prefix']['mykey']
	 *  - primary (boolean) primary field (obsolete, $pid is now used)
	 *
	 *  - help (string) edit/create table - inline help, constant name will be accpeted as well, optional
	 *  - note (string) edit/create table - text shown below the field title (left column), constant name will be accpeted as well, optional
	 *
	 *  - validate (boolean|string) any of accepted validation types (see e_validator::$_required_rules), true == 'required'
	 *  - rule (string) condition for chosen above validation type (see e_validator::$_required_rules), not required for all types
	 *  - error (string) Human readable error message (validation failure), constant name will be accepted as well, optional
	 *
	 *  - batch (boolean) list table - add current field to batch actions, in use only for boolean, dropdown, datestamp, userclass, method field types
	 *    NOTE: batch may accept string values in the future...
	 *  	full/most recent reference type list - e_admin_form_ui::renderBatchFilter()
	 *
	 *  - filter (boolean) list table - add current field to filter actions, rest is same as batch
	 *
	 *  - forced (boolean) list table - forced fields are always shown in list table
	 *  - nolist (boolean) list table - don't show in column choice list
	 *  - noedit (boolean) edit table - don't show in edit mode
	 *
	 *  - width (string) list table - width e.g '10%', 'auto'
	 *  - thclass (string) list table header - th element class
	 *  - class (string) list table body - td element additional class
	 *
	 *  - readParms (mixed) parameters used by core routine for showing values of current field. Structure on this attribute
	 *    depends on the current field type (see below). readParams are used mainly by list page
	 *
	 *  - writeParms (mixed) parameters used by core routine for showing control element(s) of current field.
	 *    Structure on this attribute depends on the current field type (see below).
	 *    writeParams are used mainly by edit page, filter (list page), batch (list page)
	 *
	 * $attributes['type']->$attributes['read/writeParams'] pairs:
	 *
	 * - null -> read: n/a
	 * 		  -> write: n/a
	 *
	 * - dropdown -> read: 'pre', 'post', array in format posted_html_name => value
	 * 			  -> write: 'pre', 'post', array in format as required by e_form::selectbox()
	 *
	 * - user -> read: [optional] 'link' => true - create link to user profile, 'idField' => 'author_id' - tells to renderValue() where to search for user id (used when 'link' is true and current field is NOT ID field)
	 * 				   'nameField' => 'comment_author_name' - tells to renderValue() where to search for user name (used when 'link' is true and current field is ID field)
	 * 		  -> write: [optional] 'nameField' => 'comment_author_name' the name of a 'user_name' field; 'currentInit' - use currrent user if no data provided; 'current' - use always current user(editor); '__options' e_form::userpickup() options
	 *
	 * - number -> read: (array) [optional] 'point' => '.', [optional] 'sep' => ' ', [optional] 'decimals' => 2, [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY'
	 * 			-> write: (array) [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY', [optional] 'maxlength' => 50, [optional] '__options' => array(...) see e_form class description for __options format
	 *
	 * - ip		-> read: n/a
	 * 			-> write: [optional] element options array (see e_form class description for __options format)
	 *
	 * - text -> read: (array) [optional] 'htmltruncate' => 100, [optional] 'truncate' => 100, [optional] 'pre' => '', [optional] 'post' => ' px'
	 * 		  -> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 255), [optional] '__options' => array(...) see e_form class description for __options format
	 *
	 * - textarea 	-> read: (array) 'noparse' => '1' default 0 (disable toHTML text parsing), [optional] 'bb' => '1' (parse bbcode) default 0,
	 * 								[optional] 'parse' => '' modifiers passed to e_parse::toHTML() e.g. 'BODY', [optional] 'htmltruncate' => 100,
	 * 								[optional] 'truncate' => 100, [optional] 'expand' => '[more]' title for expand link, empty - no expand
	 * 		  		-> write: (array) [optional] 'rows' => '' default 15, [optional] 'cols' => '' default 40, [optional] '__options' => array(...) see e_form class description for __options format
	 * 								[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
	 *
	 * - bbarea -> read: same as textarea type
	 * 		  	-> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 0),
	 * 				[optional] 'size' => [optional] - medium, small, large - default is medium,
	 * 				[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
	 *
	 * - image -> read: [optional] 'title' => 'SOME_LAN' (default - LAN_PREVIEW), [optional] 'pre' => '{e_PLUGIN}myplug/images/',
	 * 				'thumb' => 1 (true) or number width in pixels, 'thumb_urlraw' => 1|0 if true, it's a 'raw' url (no sc path constants),
	 * 				'thumb_aw' => if 'thumb' is 1|true, this is used for Adaptive thumb width
	 * 		   -> write: (array) [optional] 'label' => '', [optional] '__options' => array(...) see e_form::imagepicker() for allowed options
	 *
	 * - icon  -> read: [optional] 'class' => 'S16', [optional] 'pre' => '{e_PLUGIN}myplug/images/'
	 * 		   -> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
	 *
	 * - datestamp  -> read: [optional] 'mask' => 'long'|'short'|strftime() string, default is 'short'
	 * 		   		-> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
	 *
	 * - url	-> read: [optional] 'pre' => '{ePLUGIN}myplug/'|'http://somedomain.com/', 'truncate' => 50 default - no truncate, NOTE:
	 * 			-> write:
	 *
	 * - method -> read: optional, passed to given method (the field name)
	 * 			-> write: optional, passed to given method (the field name)
	 *
	 * - hidden -> read: 'show' => 1|0 - show hidden value, 'empty' => 'something' - what to be shown if value is empty (only id 'show' is 1)
	 * 			-> write: same as readParms
	 *
	 * - upload -> read: n/a
	 * 			-> write: Under construction
	 *
	 * Special attribute types:
	 * - method (string) field name should be method from the current e_admin_form_ui class (or its extension).
	 * 		Example call: field_name($value, $render_action, $parms) where $value is current value,
	 * 		$render_action is on of the following: read|write|batch|filter, parms are currently used paramateres ( value of read/writeParms attribute).
	 * 		Return type expected (by render action):
	 * 			- read: list table - formatted value only
	 * 			- write: edit table - form element (control)
	 * 			- batch: either array('title1' => 'value1', 'title2' => 'value2', ..) or array('singleOption' => '<option value="somethig">Title</option>') or rendered option group (string '<optgroup><option>...</option></optgroup>'
	 * 			- filter: same as batch
	 * @var array
	 */
	protected  $fields = array(
		'checkboxes'			=> array('title'=> '', 					'type' => null, 'data' => null, 'width'=>'5%', 'thclass' =>'center', 'forced'=> TRUE, 'class'=>'center', 'toggle' => 'e-multiselect'),
		'repeater_id'			=> array('title'=> 'ID', 				'type' => 'number', 'data' => 'int', 'width'=>'5%', 'thclass' => '', 'class'=>'center',	'forced'=> TRUE, 'primary'=>TRUE/*, 'noedit'=>TRUE*/), //Primary ID is not editable
		'repeater_callsign'		=> array('title'=> 'Callsign', 			'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
		'repeater_band_fk'	  	=> array('title'=> 'Band', 				'type' => 'dropdown', 'data' => 'str', 'width'=>'auto', 'thclass' => '', 'batch' => TRUE, 'filter'=>TRUE),
		'repeater_channel'	  	=> array('title'=> 'Channel', 			'type' => 'dropdown', 'data' => 'str', 'width'=>'auto', 'thclass' => '', 'batch' => TRUE, 'filter'=>TRUE),
		'repeater_rx'	  		=> array('title'=> 'Rx Frequency', 		'type' => 'number', 'data' => 'str', 'width'=>'auto', 'thclass' => '', 'batch' => TRUE, 'filter'=>TRUE),
		'repeater_tx'	  		=> array('title'=> 'Tx Frequency', 		'type' => 'number', 'data' => 'str', 'width'=>'auto', 'thclass' => '', 'batch' => TRUE, 'filter'=>TRUE),
		'repeater_split'	  	=> array('title'=> 'Split', 			'type' => 'number', 'data' => 'str', 'width'=>'auto', 'thclass' => '', 'batch' => TRUE, 'filter'=>TRUE),
		'repeater_thing'	  	=> array('title'=> 'Thing', 			'type' => 'text', 'data' => 'str', 'width'=>'auto', 'thclass' => '', 'batch' => TRUE, 'filter'=>TRUE),
		'repeater_type_fk' 		=> array('title'=> 'Type', 				'type' => 'dropdown', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
		'repeater_locator' 		=> array('title'=> 'Locator', 			'type' => 'text', 'data' => 'str', 'width' => 'auto',	'thclass' => ''),
		'repeater_town' 		=> array('title'=> 'Town', 				'type' => 'text', 'data' => 'str', 'width' => 'auto',	'thclass' => ''),
		'repeater_ngr' 			=> array('title'=> 'Grid Ref',		 	'type' => 'text', 'data' => 'str', 'width' => 'auto',	'thclass' => ''),
		'repeater_region_fk' 	=> array('title'=> 'Region', 			'type' => 'dropdown', 'data' => 'str', 'width' => 'auto',	'thclass' => ''),
		'repeater_ctcss_fk' 	=> array('title'=> 'CTCSS', 			'type' => 'dropdown', 'data' => 'str', 'width' => 'auto',	'thclass' => ''),
		'repeater_keeper' 		=> array('title'=> 'Keeper', 			'type' => 'text', 'data' => 'str', 'width' => 'auto',	'thclass' => ''),
		'repeater_lat' 			=> array('title'=> 'Latitude', 			'type' => 'text', 'data' => 'str', 'width' => 'auto',	'thclass' => ''),
		'repeater_long' 		=> array('title'=> 'Longitude', 		'type' => 'text', 'data' => 'str', 'width' => 'auto',	'thclass' => ''),
		'repeater_op' 			=> array('title'=> 'Op', 				'type' => 'text', 'data' => 'str', 'width' => 'auto',	'thclass' => ''),
		'repeater_status_fk' 	=> array('title'=> 'Status',			'type' => 'dropdown', 'data' => 'str', 'width' => 'auto',	'thclass' => ''),
		'options' 				=> array('title'=> LAN_OPTIONS, 		'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced'=>TRUE)
	);

	//required - default column user prefs
	protected $fieldpref = array('checkboxes',  'repeater_callsign','repeater_band_fk', 'repeater_type_fk', 'repeater_town' ,'repeater_status_fk', 'options');

	// FORMAT field_name=>type - optional if fields 'data' attribute is set or if custom model is set in init()
	/*protected $dataFields = array();*/

	// optional, could be also set directly from $fields array with attributes 'validate' => true|'rule_name', 'rule' => 'condition_name', 'error' => 'Validation Error message'
	/*protected  $validationRules = array(
	   'blank_url' => array('required', '', 'blank URL', 'Help text', 'not valid error message')
	   );*/

	// optional, if $pluginName == 'core', core prefs will be used, else e107::getPluginConfig($pluginName);
	protected $prefs = array(
		'pref_type'	   				=> array('title'=> 'type', 'type'=>'text', 'data' => 'string', 'validate' => true),
		'pref_folder' 				=> array('title'=> 'folder', 'type' => 'boolean', 'data' => 'integer'),
		'pref_name' 				=> array('title'=> 'name', 'type' => 'text', 'data' => 'string', 'validate' => 'regex', 'rule' => '#^[\w]+$#i', 'help' => 'allowed characters are a-zA-Z and underscore')
	);

	// optional
	public function init()
	{
	}


	public function customPage()
	{
		#$ns = e107::getRender();
	#	$text = "Hello World!";
	#	$ns->tablerender("Hello",$text);

	}    /**
    * repeater_main_admin_ui::observe()
    *
    * Watch for this being triggered. If it is then do something
    *
    * @return
    */
    public function observe(){
        #if (isset($_POST['updaterepeateroptions'])){ // Save prefs.
       #     $this->save_prefs();
       # }

       # if (isset($_POST)){
            // e107::getCache()->clear( "download_cat" );
       # }
    }
    // optional
    public function xinit(){
        $this->menuTitle = LAN_CAPTCHA_CAPTCHA;
        $this->pluginTitle = LAN_CAPTCHA_CAPTCHA;
        $this->prefs = e107::getPlugPref('repeater'); // essential to make it work
        // print_a($this->prefs);
        if ($this->prefs['repeater'] !== true){
            $this->defaultPrefs();
            // print "Do Default";
        }
        $this->observe();
    }
    function settingsPage(){
        // global $adminDownload;
        $this->show_repeater_options();
    }

    function maintPage(){
    }

    function save_prefs(){
        // e107::getPlugPref('repeater'); // essential to make it work
        $options = array();
        // put back here
        // *
        // * Tab 1
        // *
        $options['image_width'] = (int)$_POST['image_width'];
        $options['image_height'] = (int)$_POST['image_height'];
        $options['code_length'] = (int)$_POST['code_length'];
        $options['case_sensitive'] = $_POST['case_sensitive'];
        $options['expiry_time'] = (int)$_POST['expiry_time'];
        $options['use_wordlist'] = (int)$_POST['use_wordlist'];
        $options['perturbation'] = (int)$_POST['perturbation'];
        $options['num_lines'] = (int)$_POST['num_lines'];
        $options['noise_level'] = (int)$_POST['noise_level'];
        /*
    	   * todo - Fix for object type
    	*/
        $options['repeater_typeval'] = $_POST['repeater_typeval']; //self::SI_CAPTCHA_STRING; // or self::SI_CAPTCHA_MATHEMATIC, or self::SI_CAPTCHA_WORDS;
        // *
        // * Tab 2
        // *
        $options['repeater_font'] = $_POST['repeater_font'];
        $options['sig_font'] = $_POST['sig_font'];
        $options['image_signature'] = $_POST['image_signature'];
        $options['signature_color'] = $_POST['signature_color'];
        // *
        // * Tab 3
        // *
        $options['image_type'] = $_POST['image_type'];
        $options['image_bg_color'] = $_POST['image_bg_color'];
        $options['text_color'] = $_POST['text_color'];
        $options['line_color'] = $_POST['line_color'];
        $options['noise_color'] = $_POST['noise_color'];
        $options['text_transparency_percentage'] = (int)$_POST['text_transparency_percentage'];
        $options['use_transparent_text'] = (int)$_POST['use_transparent_text'];
        // *
        // * Tab 4
        // *
        $options['repeater_background'] = $_POST['repeater_background']; // this is an array
        // *
        // * Tab 5
        // *
        $options['audio_mix_normalization'] = $_POST['audio_mix_normalization'];
        $options['degrade_audio'] = (int)$_POST['degrade_audio'];
        $options['audio_use_noise'] = (int)$_POST['audio_use_noise'];
        $options['audio_gap_min'] = (int)$_POST['audio_gap_min'];
        $options['audio_gap_max'] = (int)$_POST['audio_gap_max'];
        // *
        // * Tab 6
        // *
        $options['use_database'] = (int)$_POST['use_database'];
        $options['no_session'] = (int)$_POST['no_session'];
        $options['session_name'] = (int)$_POST['session_name'];

        $this->pref = e107::getConfig('repeater')->setPref($options)->save(false);
    }
    protected function defaultPrefs(){
        // e107::getPlugPref('repeater'); // essential to make it work
        $options['repeater'] = true; // so we know it is set up

        $this->pref = e107::getConfig('repeater')->setPref($options)->save(false);
    }
    /**
    * repeater_main_admin_ui::show_repeater_options()
    *
    * @return
    */
    /**
    * repeater_main_admin_ui::show_repeater_options()
    *
    * @return
    */
    protected function show_repeater_options(){
        // get the list of fonts
        require_once("includes/ttf_class.php");
        $fontTTF = new ttfInfo();
        $fontTTF->setFontsDir("fonts/");
        $fontres = $fontTTF->readFontsDir();
        $fonts = $fontres->array;

        foreach($fonts as $fontName => $nop){
            $base = pathinfo($fontName);
            $key = $base['filename'];
            $fontList[$key] = $nop[4];
        }
        // get the images in the backgrounds folder
        $images = glob("backgrounds/*.{jpg,png,gif}", GLOB_BRACE);
        $listImages = array();
        foreach($images as $image){
            $base = pathinfo($image);
            $key = $base['filename'];
            $listImages[$key]['name'] = $base['basename'];
            list($width, $height, $type, $attr) = getimagesize("backgrounds/{$base['basename']}");
            $listImages[$key]['width'] = $width;
            $listImages[$key]['height'] = $height;
        }
        // get the fonts
        $fonts = glob("fonts/*.{ttf}", GLOB_BRACE);
        foreach($fonts as $font){
            $base = pathinfo($font);
            $key = $base['filename'];
            $listFonts[$key]['name'] = $base['basename'];
        }
        // print_a($listFonts);
        global $ns;
        $pref = e107::getPlugPref('repeater');
        require_once(e_HANDLER . "form_handler.php");
        $frm = new e_form(true); //enable inner tabindex counter
        $tab1active = '';
        $tab1Class == '';
        $tab2active = '';
        $tab2Class == '';
        $tab3active = '';
        $tab3Class == '';
        $tab4active = '';
        $tab4Class == '';
        $tab5active = '';
        $tab5Class == '';
        $tab6active = '';
        $tab6Class == '';
        $activeTab = $_COOKIE['repeaterLastTab'];
        $tabTime = $_COOKIE['repeaterLastTabTime'];

        if (time() - $tabTime > 180){
            $activeTab = 1;
            $tabTime = time();
            setcookie("repeaterLastTab", 1, 0, '/');
            setcookie("repeaterLastTabTime", $tabTime, 0, '/');
        }

        switch ($activeTab){
            case 6:
                $tab6active = ' active' ;
                $tab6Class = " class='active' ";
                break;
            case 5:
                $tab5active = ' active' ;
                $tab5Class = " class='active' ";
                break;
            case 4:
                $tab4active = ' active' ;
                $tab4Class = " class='active' ";
                break;
            case 3:
                $tab3active = ' active' ;
                $tab3Class = " class='active' ";
                break;
            case 2:
                $tab2active = ' active' ;
                $tab2Class = " class='active' ";
                break;
            case 1:
            default :
                $tab1active = ' active' ;
                $tab1Class = " class='active' ";
                break;
        }
        // $repeater_perpage = array('10' => '10', '20' => '20', '50' => '50', '100' => '100');

    	 $text = "
	<div class='repeaterPreview'>Preview</div>
	<div id='repeaterImage' class='repeaterPreview' style='height:{$this->prefs['image_height']}px;width:{$this->prefs['image_width']}px;'>
		<img src='images/secureimage_show.php' alt='Preview' style='height:{$this->prefs['image_height']}px;width:{$this->prefs['image_width']}px;' >
	</div>
	<ul class='nav nav-tabs'>
		<li {$tab1Class} id='repeaterTab1' ><a data-toggle='tab' href='#core-repeater-repeater1'>" . LAN_CAPTCHA_TAB1 . "</a></li>
		<li {$tab2Class} id='repeaterTab2' ><a data-toggle='tab' href='#core-repeater-repeater2'>" . LAN_CAPTCHA_TAB2 . "</a></li>
		<li {$tab3Class} id='repeaterTab3' ><a data-toggle='tab' href='#core-repeater-repeater3'>" . LAN_CAPTCHA_TAB3 . "</a></li>
		<li {$tab4Class} id='repeaterTab4' ><a data-toggle='tab' href='#core-repeater-repeater4'>" . LAN_CAPTCHA_TAB4 . "</a></li>
		<li {$tab5Class} id='repeaterTab5' ><a data-toggle='tab' href='#core-repeater-repeater5'>" . LAN_CAPTCHA_TAB5 . "</a></li>
		<li {$tab6Class} id='repeaterTab6' ><a data-toggle='tab' href='#core-repeater-repeater6'>" . LAN_CAPTCHA_TAB6 . "</a></li>
	</ul>
	<form method='post' id='repeaterPrefForm' action='" . e_SELF . "?" . e_QUERY . "'>\n
   		<div class='tab-content'>
			<div class='tab-pane {$tab1active}' id='core-repeater-repeater1'>
				<div>
        			<table class='table adminform'>
            			<colgroup>
            				<col style='width:30%'/>
            				<col style='width:70%'/>
            			</colgroup>
           			 	<tr>
           			 		<td>" . LAN_CAPTCHA_IMAGEWIDTH . "</td>
            				<td>" . $frm->number('image_width', $pref['image_width'], '3', array('max' => '400', 'min' => '50', 'size' => '2', 'title' => LAN_CAPTCHA_IMAGEWIDTH_HELP)) . "</td>
		      			</tr>
           			 	<tr>
           			 		<td>" . LAN_CAPTCHA_IMAGEHEIGHT . "</td>
            				<td>" . $frm->number('image_height', $pref['image_height'], '3', array('max' => '100', 'min' => '10', 'step' => '2', 'title' => LAN_CAPTCHA_IMAGEHEIGHT_HELP)) . "</td>
		      			</tr>
		      			<tr>
           			 		<td>" . LAN_CAPTCHA_CODELEN . "</td>
            				<td>" . $frm->number('code_length', $pref['code_length'], '3', array('max' => '12', 'min' => '5', 'size' => '2', 'title' => LAN_CAPTCHA_CODELEN_HELP)) . "</td>
		      			</tr>
						<tr>
		       				<td>" . LAN_CAPTCHA_CASE . "</td>
		            		<td>" . $frm->radio('case_sensitive', array('1' => LAN_CAPTCHA_YES, '0' => LAN_CAPTCHA_NO), $pref['case_sensitive'], array('title' => LAN_CAPTCHA_CASE_HELP)) . "</td>
		        		</tr>
		        		<tr>
           			 		<td>" . LAN_CAPTCHA_EXPIRY . "</td>
            				<td>" . $frm->number('expiry_time', $pref['expiry_time'], '4', array('max' => '1000', 'min' => '60', 'size' => '2', 'title' => LAN_CAPTCHA_EXPIRY_HELP)) . "</td>
		      			</tr>
						<tr>
		       				<td>" . LAN_CAPTCHA_WORDLIST . "</td>
		            		<td>" . $frm->radio('use_wordlist', array('1' => LAN_CAPTCHA_WORDLISTL, '0' => LAN_CAPTCHA_WORDLISTR), $pref['use_wordlist'], array('title' => LAN_CAPTCHA_WORDLIST_HELP)) . "</td>
		        		</tr>
		      			<tr>
           			 		<td>" . LAN_CAPTCHA_PERTURB . "</td>
            				<td>" . $frm->number('perturbation', $pref['perturbation'], '3', array('max' => '10', 'min' => '1', 'size' => '2', 'title' => LAN_CAPTCHA_PERTURB_HELP)) . "</td>
		      			</tr>
		      			<tr>
           			 		<td>" . LAN_CAPTCHA_NUMLINES . "</td>
            				<td>" . $frm->number('num_lines', $pref['num_lines'] , '3', array('max' => '10', 'min' => '1', 'size' => '2', 'title' => LAN_CAPTCHA_NUMLINES_HELP)) . "</td>
		      			</tr>
		      			<tr>
           			 		<td>" . LAN_CAPTCHA_NOISE . "</td>
            				<td>" . $frm->number('noise_level', $pref['noise_level'] , '3', array('max' => '10', 'min' => '1', 'size' => '2', 'title' => LAN_CAPTCHA_NOISE_HELP)) . "</td>
		      			</tr>
		      			<tr>
		       				<td>" . LAN_CAPTCHA_TYPE . "</td>
		            		<td>" . $frm->select('repeater_typeval', array('1' => LAN_CAPTCHA_TYPE_STRING, '2' => LAN_CAPTCHA_TYPE_MATH, '3' => LAN_CAPTCHA_TYPE_WORDS), $pref['repeater_typeval'], array('title' => LAN_CAPTCHA_TYPE_HELP)) . "</td>
		        		</tr>
	 		        </table>
		        </div>
		    </div>
		    <div class='tab-pane {$tab2active}' id='core-repeater-repeater2'>
				<div>
					<table class='table adminform'>
            			<colgroup>
            				<col style='width:30%'/>
            				<col style='width:70%'/>


            			</colgroup>
		            	<tr>
		               		<td>" . LAN_CAPTCHA_FONT . "</td>
		               		<td>" . $frm->select('repeater_font', $fontList, $pref['repeater_font'], array('title' => LAN_CAPTCHA_FONT_HELP)) . "</td>
		                </tr>
		            	<tr>
		               		<td>" . LAN_CAPTCHA_SIGFONT . "</td>
		               		<td>" . $frm->select('sig_font', $fontList, $pref['sig_font'], array('title' => LAN_CAPTCHA_SIGFONT_HELP)) . "</td>
		                </tr>
		                <tr>
		               		<td>" . LAN_CAPTCHA_SIGTEXT . "</td>
		               		<td>" . $frm->text('image_signature', $pref['image_signature'], '25', array('size' => '25', 'title' => LAN_CAPTCHA_SIGTEXT_HELP)) . "</td>
		                </tr>
		                <tr>
		               		<td>" . LAN_CAPTCHA_SIGCOLOUR . "</td>
		               		<td>" . $frm->text('signature_color', $pref['signature_color'], '25', array('size' => '25', 'title' => LAN_CAPTCHA_SIGCOLOUR_HELP)) . "</td>
		                </tr>
		        	</table>
		        </div>
			</div>
			<div class='tab-pane {$tab3active}' id='core-repeater-repeater3'>
				<div>
					<table class='table adminform'>
            			<colgroup>
            				<col style='width:30%'/>
            				<col style='width:70%'/>
            			</colgroup>
		    			<tr>
		               		<td>" . LAN_CAPTCHA_IMAGETYPE . "</td>
		               		<td>" . $frm->radio('image_type', array('0' => 'PNG', '1' => 'JPEG', '2' => 'GIF'), $pref['image_type'], array('title' => LAN_CAPTCHA_IMAGETYPE_HELP)) . "</td>
		                </tr>
		    			<tr>
		               		<td>" . LAN_CAPTCHA_BACKCOLOR . "</td>
		               		<td>" . $frm->text('image_bg_color', $pref['image_bg_color'], '25', array('size' => '25', 'title' => LAN_CAPTCHA_BACKCOLOR_HELP)) . "</td>
		                </tr>
		                <tr>
		               		<td>" . LAN_CAPTCHA_COLOR . "</td>
		               		<td>" . $frm->text('text_color', $pref['text_color'], '25', array('size' => '25', 'title' => LAN_CAPTCHA_COLOR_HELP)) . "</td>
		                </tr>
		                <tr>
		               		<td>" . LAN_CAPTCHA_LINECOLOR . "</td>
		               		<td>" . $frm->text('line_color', $pref['line_color'], '25', array('size' => '25', 'title' => LAN_CAPTCHA_LINECOLOR_HELP)) . "</td>
		                </tr>
		                <tr>
		               		<td>" . LAN_CAPTCHA_NOISECOLOR . "</td>
		               		<td>" . $frm->text('noise_color', $pref['noise_color'], '25', array('size' => '25', 'title' => LAN_CAPTCHA_NOISECOLOR_HELP)) . "</td>
		                </tr>
		                <tr>
		               		<td>" . LAN_CAPTCHA_TRANSPARENT . "</td>
		               		<td>" . $frm->number('text_transparency_percentage', $pref['text_transparency_percentage'], '3', array('max' => '100', 'min' => '1', 'size' => '3', 'title' => LAN_CAPTCHA_TRANSPARENT_HELP)) . "</td>
		                </tr>
		    			<tr>
		               		<td>" . LAN_CAPTCHA_USETRANSPARENT . "</td>
		               		<td>" . $frm->radio('use_transparent_text', array('1' => LAN_CAPTCHA_YES, '0' => LAN_CAPTCHA_NO), $pref['use_transparent_text'], array('title' => LAN_CAPTCHA_USETRANSPARENT_HELP)) . "</td>
		                </tr>
		        	</table>
		        </div>
			</div>
			<div class='tab-pane {$tab4active}' id='core-repeater-repeater4'>
				<div>
            		<ul class='bubblewrap'>";

        foreach($listImages as $key => $value){
            $options = array('label' => $value['name']);
            $text .= "	<li>
							<a href='#'>
								<img src='backgrounds/{$value['name']}' title='{$value['name']}' alt='{$value['name']}' />
							</a><br />{$value['width']} x {$value['height']} px<br />" . $frm->checkbox('repeater_background[]', $key, in_array($key, $pref['repeater_background']), $options) . "

						</li>";
            /*
			$img .= "
								<img src='backgrounds/{$value['name']}' style='width:125px; height:20px' alt='Background {$value['name']}' /><br />
								" . $frm->radio('repeater_background', $key, false, $options) . "<br />
									{$value['width']} x {$value['height']} px";
        	*/
        }
        $text .= "
    				</ul>
		        </div>
		    </div>
			<div class='tab-pane {$tab5active}' id='core-repeater-repeater5'>
				<div>
					<table class='table adminform'>
            			<colgroup>
            				<col style='width:30%'/>
            				<col style='width:70%'/>
            			</colgroup>
            			<tr>
		               		<td>" . LAN_CAPTCHA_USENOISE . "</td>
		               		<td>" . $frm->radio('audio_use_noise', array('1' => LAN_CAPTCHA_USENOISE0, '0' => LAN_CAPTCHA_USENOISE1), $pref['audio_use_noise'], array('title' => LAN_CAPTCHA_CASE_HELP)) . "</td>
		                </tr>
            			<tr>
		               		<td>" . LAN_CAPTCHA_DEGRADENOISE . "</td>
		               		<td>" . $frm->radio('degrade_audio', array('1' => LAN_CAPTCHA_DEGRADENOISET, '0' => LAN_CAPTCHA_DEGRADENOISEF), $pref['degrade_audio'], array('title' => LAN_CAPTCHA_CASE_HELP)) . "</td>
		                </tr>

		                <tr>
		               		<td>" . LAN_CAPTCHA_MIXNOISE . "</td>
		               		<td>" . $frm->number('audio_mix_normalization', $pref['audio_mix_normalization'], '3', array('max' => '100', 'min' => '1', 'size' => '3', array('title' => LAN_CAPTCHA_MIXNOISE_HELP))) . "</td>
		                </tr>
		                <tr>
		               		<td>" . LAN_CAPTCHA_MINGAP . "</td>
		               		<td>" . $frm->number('audio_gap_min', $pref['audio_gap_min'], '3', array('max' => '3000', 'min' => '1', 'size' => '3', 'title' => LAN_CAPTCHA_MINGAP_HELP)) . "</td>
		                </tr>
		                <tr>
		               		<td>" . LAN_CAPTCHA_MAXGAP . "</td>
		               		<td>" . $frm->number('audio_gap_max', $pref['audio_gap_max'], '3', array('max' => '3000', 'min' => '1', 'size' => '3', 'title' => LAN_CAPTCHA_MAXGAP_HELP)) . "</td>
		                </tr>

		        	</table>
		        </div>
		    </div>

			<div class='tab-pane {$tab6active}' id='core-repeater-repeater6'>
				<div>
					<table class='table adminform'>
            			<colgroup>
            				<col style='width:30%'/>
            				<col style='width:70%'/>
            			</colgroup>
            			<tr>
		               		<td>" . LAN_CAPTCHA_USEDB . "</td>
		               		<td>" . $frm->radio('use_database', array('1' => LAN_CAPTCHA_USEDB1, '0' => LAN_CAPTCHA_USEDB0), $pref['use_database'], array('title' => LAN_CAPTCHA_USEDB_HELP)) . "</td>
		                </tr>
		    			<tr>
		               		<td>" . LAN_CAPTCHA_SESSIONS . "</td>
		               		<td>" . $frm->radio('no_session', array('0' => LAN_CAPTCHA_USEDB2, '1' => LAN_CAPTCHA_USEDB0), $pref['no_session'], array('title' => LAN_CAPTCHA_SESSIONS_HELP)) . "</td>
		                </tr>
		    			<tr>
		               		<td>" . LAN_CAPTCHA_SESSIONNAME . "</td>
		               		<td>" . $frm->text('session_name', $pref['session_name'], '25', array('size' => '25', 'title' => LAN_CAPTCHA_SESSIONNAME_HELP)) . "</td>
		                </tr>
		        	</table>
		        </div>
			</div>
		</div>
		<div class='buttons-bar center'>
			<input class='btn button' type='submit' name='updaterepeateroptions' value='" . LAN_CAPTCHA_UPDATE . "'/>
		</div>
	</form>";
        echo $text;
        return $text;
    }
}

class repeater_main_admin_form_ui extends e_admin_form_ui{
}

class repeater_bands_admin_ui extends e_admin_ui{
	// required
	protected $pluginTitle = "Repeater";

	/**
	 * plugin name or 'core'
	 * @var string
	 */
	protected $pluginName = 'repeater';

	/**
	 * DB Table, table alias is supported
	 * Example: 'r.blank'
	 * @var string
	 */
	protected $table = "repeater_band";

	/**
	 * If present this array will be used to build your list query
	 * You can link fileds from $field array with 'table' parameter, which should equal to a key (table) from this array
	 * 'leftField', 'rightField' and 'fields' attributes here are required, the rest is optional
	 * Table alias is supported
	 * Note:
	 * - 'leftTable' could contain only table alias
	 * - 'leftField' and 'rightField' shouldn't contain table aliases, they will be auto-added
	 * - 'whereJoin' and 'where' should contain table aliases e.g. 'whereJoin' => 'AND u.user_ban=0'
	 *
	 * @var array [optional] table_name => array join parameters
	 */
	protected $tableJoin = array(
		//'u.user' => array('leftField' => 'comment_author_id', 'rightField' => 'user_id', 'fields' => '*'/*, 'leftTable' => '', 'joinType' => 'LEFT JOIN', 'whereJoin' => '', 'where' => ''*/)
	);

	/**
	 * This is only needed if you need to JOIN tables AND don't wanna use $tableJoin
	 * Write your list query without any Order or Limit.
	 *
	 * @var string [optional]
	 */
	protected $listQry = "";
	//

	// optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
	//protected $editQry = "SELECT * FROM #blank WHERE blank_id = {ID}";

	// required - if no custom model is set in init() (primary id)
	protected $pid = "repeater_band_id";

	// optional
	protected $perPage = 20;

	// default - true - TODO - move to displaySettings
	protected $batchDelete = true;

	// UNDER CONSTRUCTION
	protected $displaySettings = array();

	// UNDER CONSTRUCTION
	protected $disallowPages = array('main/create', 'main/prefs');

	//TODO change the blank_url type back to URL before blank.
	// required
	/**
	 * (use this as starting point for wiki documentation)
	 * $fields format  (string) $field_name => (array) $attributes
	 *
	 * $field_name format:
	 * 	'table_alias_or_name.field_name.field_alias' (if JOIN support is needed) OR just 'field_name'
	 * NOTE: Keep in mind the count of exploded data can be 1 or 3!!! This means if you wanna give alias
	 * on main table field you can't omit the table (first key), alternative is just '.' e.g. '.field_name.field_alias'
	 *
	 * $attributes format:
	 * 	- title (string) Human readable field title, constant name will be accpeted as well (multi-language support
	 *
	 *  - type (string) null (means system), number, text, dropdown, url, image, icon, datestamp, userclass, userclasses, user[_name|_loginname|_login|_customtitle|_email],
	 *    boolean, method, ip
	 *  	full/most recent reference list - e_form::renderTableRow(), e_form::renderElement(), e_admin_form_ui::renderBatchFilter()
	 *  	for list of possible read/writeParms per type see below
	 *
	 *  - data (string) Data type, one of the following: int, integer, string, str, float, bool, boolean, model, null
	 *    Default is 'str'
	 *    Used only if $dataFields is not set
	 *  	full/most recent reference list - e_admin_model::sanitize(), db::_getFieldValue()
	 *  - dataPath (string) - xpath like path to the model/posted value. Example: 'dataPath' => 'prefix/mykey' will result in $_POST['prefix']['mykey']
	 *  - primary (boolean) primary field (obsolete, $pid is now used)
	 *
	 *  - help (string) edit/create table - inline help, constant name will be accpeted as well, optional
	 *  - note (string) edit/create table - text shown below the field title (left column), constant name will be accpeted as well, optional
	 *
	 *  - validate (boolean|string) any of accepted validation types (see e_validator::$_required_rules), true == 'required'
	 *  - rule (string) condition for chosen above validation type (see e_validator::$_required_rules), not required for all types
	 *  - error (string) Human readable error message (validation failure), constant name will be accepted as well, optional
	 *
	 *  - batch (boolean) list table - add current field to batch actions, in use only for boolean, dropdown, datestamp, userclass, method field types
	 *    NOTE: batch may accept string values in the future...
	 *  	full/most recent reference type list - e_admin_form_ui::renderBatchFilter()
	 *
	 *  - filter (boolean) list table - add current field to filter actions, rest is same as batch
	 *
	 *  - forced (boolean) list table - forced fields are always shown in list table
	 *  - nolist (boolean) list table - don't show in column choice list
	 *  - noedit (boolean) edit table - don't show in edit mode
	 *
	 *  - width (string) list table - width e.g '10%', 'auto'
	 *  - thclass (string) list table header - th element class
	 *  - class (string) list table body - td element additional class
	 *
	 *  - readParms (mixed) parameters used by core routine for showing values of current field. Structure on this attribute
	 *    depends on the current field type (see below). readParams are used mainly by list page
	 *
	 *  - writeParms (mixed) parameters used by core routine for showing control element(s) of current field.
	 *    Structure on this attribute depends on the current field type (see below).
	 *    writeParams are used mainly by edit page, filter (list page), batch (list page)
	 *
	 * $attributes['type']->$attributes['read/writeParams'] pairs:
	 *
	 * - null -> read: n/a
	 * 		  -> write: n/a
	 *
	 * - dropdown -> read: 'pre', 'post', array in format posted_html_name => value
	 * 			  -> write: 'pre', 'post', array in format as required by e_form::selectbox()
	 *
	 * - user -> read: [optional] 'link' => true - create link to user profile, 'idField' => 'author_id' - tells to renderValue() where to search for user id (used when 'link' is true and current field is NOT ID field)
	 * 				   'nameField' => 'comment_author_name' - tells to renderValue() where to search for user name (used when 'link' is true and current field is ID field)
	 * 		  -> write: [optional] 'nameField' => 'comment_author_name' the name of a 'user_name' field; 'currentInit' - use currrent user if no data provided; 'current' - use always current user(editor); '__options' e_form::userpickup() options
	 *
	 * - number -> read: (array) [optional] 'point' => '.', [optional] 'sep' => ' ', [optional] 'decimals' => 2, [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY'
	 * 			-> write: (array) [optional] 'pre' => '&euro; ', [optional] 'post' => 'LAN_CURRENCY', [optional] 'maxlength' => 50, [optional] '__options' => array(...) see e_form class description for __options format
	 *
	 * - ip		-> read: n/a
	 * 			-> write: [optional] element options array (see e_form class description for __options format)
	 *
	 * - text -> read: (array) [optional] 'htmltruncate' => 100, [optional] 'truncate' => 100, [optional] 'pre' => '', [optional] 'post' => ' px'
	 * 		  -> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 255), [optional] '__options' => array(...) see e_form class description for __options format
	 *
	 * - textarea 	-> read: (array) 'noparse' => '1' default 0 (disable toHTML text parsing), [optional] 'bb' => '1' (parse bbcode) default 0,
	 * 								[optional] 'parse' => '' modifiers passed to e_parse::toHTML() e.g. 'BODY', [optional] 'htmltruncate' => 100,
	 * 								[optional] 'truncate' => 100, [optional] 'expand' => '[more]' title for expand link, empty - no expand
	 * 		  		-> write: (array) [optional] 'rows' => '' default 15, [optional] 'cols' => '' default 40, [optional] '__options' => array(...) see e_form class description for __options format
	 * 								[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
	 *
	 * - bbarea -> read: same as textarea type
	 * 		  	-> write: (array) [optional] 'pre' => '', [optional] 'post' => ' px', [optional] 'maxlength' => 50 (default - 0),
	 * 				[optional] 'size' => [optional] - medium, small, large - default is medium,
	 * 				[optional] 'counter' => 0 number of max characters - has only visual effect, doesn't truncate the value (default - false)
	 *
	 * - image -> read: [optional] 'title' => 'SOME_LAN' (default - LAN_PREVIEW), [optional] 'pre' => '{e_PLUGIN}myplug/images/',
	 * 				'thumb' => 1 (true) or number width in pixels, 'thumb_urlraw' => 1|0 if true, it's a 'raw' url (no sc path constants),
	 * 				'thumb_aw' => if 'thumb' is 1|true, this is used for Adaptive thumb width
	 * 		   -> write: (array) [optional] 'label' => '', [optional] '__options' => array(...) see e_form::imagepicker() for allowed options
	 *
	 * - icon  -> read: [optional] 'class' => 'S16', [optional] 'pre' => '{e_PLUGIN}myplug/images/'
	 * 		   -> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
	 *
	 * - datestamp  -> read: [optional] 'mask' => 'long'|'short'|strftime() string, default is 'short'
	 * 		   		-> write: (array) [optional] 'label' => '', [optional] 'ajax' => true/false , [optional] '__options' => array(...) see e_form::iconpicker() for allowed options
	 *
	 * - url	-> read: [optional] 'pre' => '{ePLUGIN}myplug/'|'http://somedomain.com/', 'truncate' => 50 default - no truncate, NOTE:
	 * 			-> write:
	 *
	 * - method -> read: optional, passed to given method (the field name)
	 * 			-> write: optional, passed to given method (the field name)
	 *
	 * - hidden -> read: 'show' => 1|0 - show hidden value, 'empty' => 'something' - what to be shown if value is empty (only id 'show' is 1)
	 * 			-> write: same as readParms
	 *
	 * - upload -> read: n/a
	 * 			-> write: Under construction
	 *
	 * Special attribute types:
	 * - method (string) field name should be method from the current e_admin_form_ui class (or its extension).
	 * 		Example call: field_name($value, $render_action, $parms) where $value is current value,
	 * 		$render_action is on of the following: read|write|batch|filter, parms are currently used paramateres ( value of read/writeParms attribute).
	 * 		Return type expected (by render action):
	 * 			- read: list table - formatted value only
	 * 			- write: edit table - form element (control)
	 * 			- batch: either array('title1' => 'value1', 'title2' => 'value2', ..) or array('singleOption' => '<option value="somethig">Title</option>') or rendered option group (string '<optgroup><option>...</option></optgroup>'
	 * 			- filter: same as batch
	 * @var array
	 */
	protected  $fields = array(
		'checkboxes'			=> array('title'=> '', 					'type' => null, 'data' => null, 'width'=>'5%', 'thclass' =>'center', 'forced'=> TRUE, 'class'=>'center', 'toggle' => 'e-multiselect'),
		'repeater_band_id'			=> array('title'=> 'ID', 				'type' => 'number', 'data' => 'int', 'width'=>'5%', 'thclass' => '', 'class'=>'center',	'forced'=> TRUE, 'primary'=>TRUE/*, 'noedit'=>TRUE*/), //Primary ID is not editable
		'repeater_band_name'		=> array('title'=> 'Band', 			'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
		'options' 				=> array('title'=> LAN_OPTIONS, 		'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced'=>TRUE)
	);

	//required - default column user prefs
	protected $fieldpref = array('checkboxes',  'repeater_band_id','repeater_band_name', 'options');

	// FORMAT field_name=>type - optional if fields 'data' attribute is set or if custom model is set in init()
	/*protected $dataFields = array();*/

	// optional, could be also set directly from $fields array with attributes 'validate' => true|'rule_name', 'rule' => 'condition_name', 'error' => 'Validation Error message'
	/*protected  $validationRules = array(
	   'blank_url' => array('required', '', 'blank URL', 'Help text', 'not valid error message')
	   );*/


	// optional
	public function init()
	{
	}

	 /**
	 * repeater_main_admin_ui::observe()
	 *
	 * Watch for this being triggered. If it is then do something
	 *
	 * @return
	 */
	public function observe(){
		#if (isset($_POST['updaterepeateroptions'])){ // Save prefs.
		#     $this->save_prefs();
		# }

		# if (isset($_POST)){
		// e107::getCache()->clear( "download_cat" );
		# }
	}

}

class repeater_bands_admin_form_ui extends e_admin_form_ui{
}

class repeater_types_admin_ui extends e_admin_ui{
	// required
	protected $pluginTitle = "Repeater";

	/**
	 * @var string
	 */
	protected $pluginName = 'repeater';

	/**
	 * @var string
	 */
	protected $table = "repeater_type";

	/**
	 * @var array [optional] table_name => array join parameters
	 */
	protected $tableJoin = array(
		//'u.user' => array('leftField' => 'comment_author_id', 'rightField' => 'user_id', 'fields' => '*'/*, 'leftTable' => '', 'joinType' => 'LEFT JOIN', 'whereJoin' => '', 'where' => ''*/)
	);

	/**
	 * @var string [optional]
	 */
	protected $listQry = "";
	//

	// optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
	//protected $editQry = "SELECT * FROM #blank WHERE blank_id = {ID}";

	// required - if no custom model is set in init() (primary id)
	protected $pid = "repeater_type_id";

	// optional
	protected $perPage = 20;

	// default - true - TODO - move to displaySettings
	protected $batchDelete = true;

	// UNDER CONSTRUCTION
//	protected $displaySettings = array();

	// UNDER CONSTRUCTION
//	protected $disallowPages = array('main/create', 'main/prefs');
	/*
	 * @var array
	 */
	protected  $fields = array(
		'checkboxes'			=> array('title'=> '', 					'type' => null, 'data' => null, 'width'=>'5%', 'thclass' =>'center', 'forced'=> TRUE, 'class'=>'center', 'toggle' => 'e-multiselect'),
		'repeater_type_id'			=> array('title'=> 'ID', 				'type' => 'number', 'data' => 'int', 'width'=>'5%', 'thclass' => '', 'class'=>'center',	'forced'=> TRUE, 'primary'=>TRUE/*, 'noedit'=>TRUE*/), //Primary ID is not editable
		'repeater_type_name'		=> array('title'=> 'Type', 			'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
		'repeater_typeDescription'	  	=> array('title'=> 'Description', 				'type' => 'dropdown', 'data' => 'str', 'width'=>'auto', 'thclass' => '', 'batch' => TRUE, 'filter'=>TRUE),
		'options' 				=> array('title'=> LAN_OPTIONS, 		'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced'=>TRUE)
	);

	//required - default column user prefs
	protected $fieldpref = array('checkboxes',  'repeater_type_id','repeater_type_name', 'repeater_typeDescription', 'options');

	// optional
	public function init()
	{
	}


	public function customPage()
	{
		#$ns = e107::getRender();
		#	$text = "Hello World!";
		#	$ns->tablerender("Hello",$text);

	}    /**
	 * repeater_main_admin_ui::observe()
	 *
	 * Watch for this being triggered. If it is then do something
	 *
	 * @return
	 */
	public function observe(){
		#if (isset($_POST['updaterepeateroptions'])){ // Save prefs.
		#     $this->save_prefs();
		# }

		# if (isset($_POST)){
		// e107::getCache()->clear( "download_cat" );
		# }
	}

}

class repeater_types_admin_form_ui extends e_admin_form_ui{
}

class repeater_regions_admin_ui extends e_admin_ui{
	// required
	protected $pluginTitle = "Repeater";

	/**
	 * @var string
	 */
	protected $pluginName = 'repeater';

	/**
	 * @var string
	 */
	protected $table = "repeater_region";

	/**
	 * @var array [optional] table_name => array join parameters
	 */
	protected $tableJoin = array(
		//'u.user' => array('leftField' => 'comment_author_id', 'rightField' => 'user_id', 'fields' => '*'/*, 'leftTable' => '', 'joinType' => 'LEFT JOIN', 'whereJoin' => '', 'where' => ''*/)
	);

	/**
	 * @var string [optional]
	 */
	protected $listQry = "";
	//

	// optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
	//protected $editQry = "SELECT * FROM #blank WHERE blank_id = {ID}";

	// required - if no custom model is set in init() (primary id)
	protected $pid = "repeater_region_id";

	// optional
	protected $perPage = 20;

	// default - true - TODO - move to displaySettings
	protected $batchDelete = true;

	// UNDER CONSTRUCTION
	//	protected $displaySettings = array();

	// UNDER CONSTRUCTION
	//	protected $disallowPages = array('main/create', 'main/prefs');
	/*
	   * @var array
	*/
	protected  $fields = array(
		'checkboxes'			=> array('title'=> '', 					'type' => null, 'data' => null, 'width'=>'5%', 'thclass' =>'center', 'forced'=> TRUE, 'class'=>'center', 'toggle' => 'e-multiselect'),
		'repeater_region_id'			=> array('title'=> 'ID', 				'type' => 'number', 'data' => 'int', 'width'=>'5%', 'thclass' => '', 'class'=>'center',	'forced'=> TRUE, 'primary'=>TRUE/*, 'noedit'=>TRUE*/), //Primary ID is not editable
		'repeater_region_name'		=> array('title'=> 'Name', 			'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
		'repeater_region_code'	  	=> array('title'=> 'Code', 				'type' => 'text', 'data' => 'str', 'width'=>'auto', 'thclass' => '', 'batch' => TRUE, 'filter'=>TRUE),
		'options' 				=> array('title'=> LAN_OPTIONS, 		'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced'=>TRUE)
	);

	//required - default column user prefs
	protected $fieldpref = array('checkboxes',  'repeater_region_id','repeater_region_name', 'repeater_typeDescription', 'options');

	// optional
	public function init()
	{
	}


	public function customPage()
	{
		#$ns = e107::getRender();
		#	$text = "Hello World!";
		#	$ns->tablerender("Hello",$text);

	}    /**
	 * repeater_main_admin_ui::observe()
	 *
	 * Watch for this being triggered. If it is then do something
	 *
	 * @return
	 */
	public function observe(){
		#if (isset($_POST['updaterepeateroptions'])){ // Save prefs.
		#     $this->save_prefs();
		# }

		# if (isset($_POST)){
		// e107::getCache()->clear( "download_cat" );
		# }
	}

}

class repeater_regions_admin_form_ui extends e_admin_form_ui{
}

class repeater_ctcss_admin_ui extends e_admin_ui{
	// required
	protected $pluginTitle = "Repeater";

	/**
	 * @var string
	 */
	protected $pluginName = 'repeater';

	/**
	 * @var string
	 */
	protected $table = "repeater_ctcss";

	/**
	 * @var array [optional] table_name => array join parameters
	 */
	protected $tableJoin = array(
		//'u.user' => array('leftField' => 'comment_author_id', 'rightField' => 'user_id', 'fields' => '*'/*, 'leftTable' => '', 'joinType' => 'LEFT JOIN', 'whereJoin' => '', 'where' => ''*/)
	);

	/**
	 * @var string [optional]
	 */
	protected $listQry = "";
	//

	// optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
	//protected $editQry = "SELECT * FROM #blank WHERE blank_id = {ID}";

	// required - if no custom model is set in init() (primary id)
	protected $pid = "repeater_ctcss_id";

	// optional
	protected $perPage = 20;

	// default - true - TODO - move to displaySettings
	protected $batchDelete = true;

	// UNDER CONSTRUCTION
	//	protected $displaySettings = array();

	// UNDER CONSTRUCTION
	//	protected $disallowPages = array('main/create', 'main/prefs');
	/*
	   * @var array
	*/
	protected  $fields = array(
		'checkboxes'			=> array('title'=> '', 					'type' => null, 'data' => null, 'width'=>'5%', 'thclass' =>'center', 'forced'=> TRUE, 'class'=>'center', 'toggle' => 'e-multiselect'),
		'repeater_ctcss_id'			=> array('title'=> 'ID', 				'type' => 'text', 'data' => 'int', 'width'=>'5%', 'thclass' => '', 'class'=>'center',	'forced'=> TRUE, 'primary'=>TRUE/*, 'noedit'=>TRUE*/), //Primary ID is not editable
		'repeater_ctcss'		=> array('title'=> 'CTCSS Tone', 			'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
		'options' 				=> array('title'=> LAN_OPTIONS, 		'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced'=>TRUE)
	);

	//required - default column user prefs
	protected $fieldpref = array('checkboxes',  'repeater_ctcss_id','repeater_ctcss',  'options');

	// optional
	public function init()
	{
	}


	public function customPage()
	{
		#$ns = e107::getRender();
		#	$text = "Hello World!";
		#	$ns->tablerender("Hello",$text);

	}    /**
	 * repeater_main_admin_ui::observe()
	 *
	 * Watch for this being triggered. If it is then do something
	 *
	 * @return
	 */
	public function observe(){
		#if (isset($_POST['updaterepeateroptions'])){ // Save prefs.
		#     $this->save_prefs();
		# }

		# if (isset($_POST)){
		// e107::getCache()->clear( "download_cat" );
		# }
	}
}

class repeater_ctcss_admin_form_ui extends e_admin_form_ui{
}

class repeater_status_admin_ui extends e_admin_ui{
	// required
	protected $pluginTitle = "Repeater";

	/**
	 * @var string
	 */
	protected $pluginName = 'repeater';

	/**
	 * @var string
	 */
	protected $table = "repeater_status";

	/**
	 * @var array [optional] table_name => array join parameters
	 */
	protected $tableJoin = array(
		//'u.user' => array('leftField' => 'comment_author_id', 'rightField' => 'user_id', 'fields' => '*'/*, 'leftTable' => '', 'joinType' => 'LEFT JOIN', 'whereJoin' => '', 'where' => ''*/)
	);

	/**
	 * @var string [optional]
	 */
	protected $listQry = "";
	//

	// optional - required only in case of e.g. tables JOIN. This also could be done with custom model (set it in init())
	//protected $editQry = "SELECT * FROM #blank WHERE blank_id = {ID}";

	// required - if no custom model is set in init() (primary id)
	protected $pid = "repeater_status_id";

	// optional
	protected $perPage = 20;

	// default - true - TODO - move to displaySettings
	protected $batchDelete = true;

	// UNDER CONSTRUCTION
	//	protected $displaySettings = array();

	// UNDER CONSTRUCTION
	//	protected $disallowPages = array('main/create', 'main/prefs');
	/*
	   * @var array
	*/
	protected  $fields = array(
		'checkboxes'			=> array('title'=> '', 					'type' => null, 'data' => null, 'width'=>'5%', 'thclass' =>'center', 'forced'=> TRUE, 'class'=>'center', 'toggle' => 'e-multiselect'),
		'repeater_status_id'			=> array('title'=> 'ID', 				'type' => 'number', 'data' => 'int', 'width'=>'5%', 'thclass' => '', 'class'=>'center',	'forced'=> TRUE, 'primary'=>TRUE/*, 'noedit'=>TRUE*/), //Primary ID is not editable
		'repeater_status'		=> array('title'=> 'Type', 			'type' => 'text', 'data' => 'str', 'width' => 'auto', 'thclass' => ''),
		'repeater_statusDescription'	  	=> array('title'=> 'Description', 				'type' => 'dropdown', 'data' => 'str', 'width'=>'auto', 'thclass' => '', 'batch' => TRUE, 'filter'=>TRUE),
		'options' 				=> array('title'=> LAN_OPTIONS, 		'type' => null, 'data' => null, 'width' => '10%', 'thclass' => 'center last', 'class' => 'center last', 'forced'=>TRUE)
	);

	//required - default column user prefs
	protected $fieldpref = array('checkboxes',  'repeater_status_id','repeater_status', 'repeater_statusDescription', 'options');

	// optional
	public function init()
	{
	}


	public function customPage()
	{
		#$ns = e107::getRender();
		#	$text = "Hello World!";
		#	$ns->tablerender("Hello",$text);

	}    /**
	 * repeater_main_admin_ui::observe()
	 *
	 * Watch for this being triggered. If it is then do something
	 *
	 * @return
	 */
	public function observe(){
		#if (isset($_POST['updaterepeateroptions'])){ // Save prefs.
		#     $this->save_prefs();
		# }

		# if (isset($_POST)){
		// e107::getCache()->clear( "download_cat" );
		# }
	}
}

class repeater_status_admin_form_ui extends e_admin_form_ui{
}

$(document).ready(
function (){
	//jQuery('#rpt_make').click(
	//function(){
	//window.open('index.php','_blank');
	//});
	rpt_getData();
	rpt_recCount();
	jQuery('#rpt_preview').show();
	jQuery('#rpt_region_all').click(
	function(){
		jQuery('.rpt_region').attr('checked',false);
	});
	jQuery('.rpt_region').change(
	function(){
		jQuery('#rpt_region_all').attr('checked',false);
	});

	jQuery('#rpt_preview').click(
	function(){
		jQuery("#rpt_previewAreaImg").show();
		jQuery("#rpt_previewAreaContent").hide();
		var loadUrl='ajax.php';
		var values = (jQuery('#rpt_form').serialize());
		jQuery.get(
		loadUrl,
		{allfields:values},
		function(responseText){
			//alert(responseText);
			jQuery("#rpt_previewAreaImg").hide();
			jQuery("#rpt_previewAreaContent").show();
			jQuery("#rpt_previewAreaContent").html(responseText);

		},

		"html"
		);
		jQuery(function() {
			jQuery( "#rpt_previewArea" ).dialog({
				height:500,
				width:765,
				resizable:false,
				closeOnEscapeType: true,
				title:'Preview'
			});
		});
	}
	);


	jQuery('.rpt_band').change(
	function(){
		jQuery('#rpt_band_all').attr('checked',false);
	});

	jQuery('#rpt_mode_all').click(
	function(){
		jQuery('.rpt_mode').attr('checked',false);
	});
	jQuery('.rpt_mode').change(
	function(){
		jQuery('#rpt_mode_all').attr('checked',false);
	});
	jQuery('#rpt_note_1').click(
	function(){
		jQuery('.rpt_note').attr('checked',false);
	});
	jQuery('.rpt_note').change(
	function(){
		jQuery('#rpt_note_1').attr('checked',false);
	});
	rptDistance();
	jQuery('#rpt_locator').blur(function(){
		rptDistance();
	});
	jQuery('#rpt_miles').change(function(){
		rptDistance();
	});
	if(jQuery.cookie('rpt_tips')==null){
		jQuery.cookie('rpt_tips', 'on', { expires: 7, path: '/' });
	}
	var tipsOn= jQuery.cookie('rpt_tips');
	if(tipsOn=='on'){
		jQuery('.rpt_tip ').qtip({
			content:{
				text:function(api){
					var text=jQuery(this).attr('title').split('[#]');
					return text[1];
				},
				title:{
					text:function(api){
						var text=jQuery(this).attr('title').split('[#]');
						return text[0];
					},
					button:true
				}
			},
			show: {
				delay: 1000,
				effect: function(offset) {
					jQuery(this).fadeIn(500); // "this" refers to the tooltip
				}
			},
			position: { my: 'topLeft', at: 'bottomMiddle' },
			style: {
				widget:true,
				tip: {
					corner: true,
					width:20,
					height:24
				}
			}
		});
	}else{
		jQuery('.rpt_tip ').removeAttr('title');
	}
	jQuery(".rpt_tip").change(function(){
		rpt_recCount();
	});
	jQuery(".rpt_change").change(function(){
		rpt_recCount();
	});
});
/**
 *
 * @access public
 * @return void
 **/
function rpt_recCount(){

	var loadUrl='ajax.php';
	var values = (jQuery('#rpt_form').serialize());
	jQuery.get(
	loadUrl,
	{fields:values},
	function(responseText){
		jQuery("#rpt_numrecs").html(responseText);
	},
	"html"
	);
}
/**
 *
 * @access public
 * @return void
 **/
function rptDistance(){
	// check if locator given - if not then disable distance in order
	//alert(jQuery('#rpt_locator').val());
	var rptLocator=jQuery('#rpt_locator').val();
	if(rpt_checkLocator(rptLocator )){
		if(rptLocator==''){
			jQuery("#rpt_order option[value='13']").attr('disabled',true);
			jQuery("#rpt_order option[value='1']").attr('selected',true);
			if(jQuery('#rpt_miles').val()!=0){
				jQuery('#rpt_locator').addClass('rptHighlight');
				fbj_message_box('warning','<ul><li>Selecting on distance but no locator specified</li></ul>');

			}else{
				jQuery('#rpt_locator').removeClass('rptHighlight');

				fbj_message_box('blank','');

			}
		}else{
			rptLat2Long(rptLocator);
			jQuery("#rpt_order option[value='13']").attr("disabled",false);
			jQuery('#rpt_locator').removeClass('rptHighlight');
			fbj_message_box('blank','');
		}
	}
}
/**
 *
 * @access public
 * @return void
 **/
function rpt_checkLocator(locator){
	var locError=false;
	if(locator!=''){
		var char0=locator.charAt(0);
		var char1=locator.charAt(1);
		var char2=locator.charAt(2);
		var char3=locator.charAt(3);
		var char4=locator.charAt(4);
		var char5=locator.charAt(5);

		if ((char0 <'A' || char0 >'R') && (char0 <'a' || char0 >'r') ) {
			locError=true;
		}
		if ((char1 <'A' || char1 >'R') && (char1 <'a' || char1 >'r') ) {
			locError=true;
		}
		if ((char2 <'0' || char2 >'9') ) {
			locError=true;
		}
		if ((char3 <'0' || char3 >'9') ) {
			locError=true;
		}
		if(locator.length>4){
			if ((char4 <'A' || char4 >'R') && (char4 <'a' || char4 >'r') ) {
				locError=true;
			}
			if ((char5 <'A' || char5 >'R') && (char5 <'a' || char5 >'r') ) {
				locError=true;
			}
		}
		if (locError) {
			fbj_message_box('validation','<ul><li>The locator appears to be invalid</li></ul>');
		}
	}
	// invert the logic
	return !locError;
}
/**
 *
 * @access public
 * @return void
 **/
function rpt_getData(){
	// Get all the forms elements and their values in one step
	var values = (jQuery('#rpt_form').serialize());


}
/**
 *
 * @access public
 * @return void
 **/
function rptLat2Long(rptLocator){
	var grid=rptLocator.toUpperCase();
	var rptLon=0;
	var rptLat=0;

	rptLon = ( ( grid.charCodeAt(0)  -  'A'.charCodeAt(0)  ) * 20 ) - 180;
	rptLat = ( ( grid.charCodeAt(1) - 'A'.charCodeAt(0) ) * 10 ) - 90;
	rptLon += ( ( grid.charCodeAt(2) - '0'.charCodeAt(0) ) * 2 );
	rptLat += ( ( grid.charCodeAt(3) - '0'.charCodeAt(0) ) * 1 );

	if ( grid.length >= 5 ) {
		// have subsquares
		rptLon += ( ( grid.charCodeAt(4) ) - 'A'.charCodeAt(0) ) * ( 5 / 60 );
		rptLat += ( ( grid.charCodeAt(5) ) - 'A'.charCodeAt(0) ) * ( 2.5 / 60 );
		// move to center of subsquare
		rptLon += ( 2.5 / 60 );
		rptLat += ( 1.25 / 60 );
		// not too precise
	} else {
		// move to center of square
		rptLon += 1;
		rptLat += 0.5;
		// even less precise
	}

}


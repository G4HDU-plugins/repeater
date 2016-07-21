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
$(document).ready(function(){
	$("input[id^='repeater-region-0']").change(function(){
		$('#repeaterRegion').slideToggle();
	});
	$("input[id^='repeater-type-0']").change(function(){
		$('#repeaterMode').slideToggle();
	});
	$("input[id^='repeater-band-0']").change(function(){
		$('#repeaterBand').slideToggle();
	});
	$("input[id^='repeaternote-1']").change(function(){
		$('#repeaterMemo').slideToggle();
	});
	$("input[id^='repeater-status-0']").change(function(){
		$('#repeaterStatus').slideToggle();
	});
	$("#repeater-form :input").change(function() {
	//	$(this).closest('form').data('changed', true);
 //console.log($( "#repeater-form" ).serialize() );
		// Send the data using post
		var posting = $.post( "ajax.php", $( "#repeater-form" ).serialize() );
		// Put the results in a div
		posting.done(function( data ) {
			var content = $.parseJSON(data);
			$( "#rpt_numrecs" ).empty().append( content.numrecs );
		});
	});
	$('#repeaterpreview').click(function(event) {
		return;
		event.preventDefault();
		// Send the data using post
		$('#repeaterpreviewjs-numrecs').val('preview');
		var posting = $.post( "ajax.php", $( "#repeater-form" ).serialize() );
		// Put the results in a string
		posting.done(function( data ) {
			var content = $.parseJSON(data);
			BootstrapDialog.show({
				title: 'Preview',
				'size':'size-wide',
				message: $('<div></div>').empty().append( content.preview )
			});
			$('#repeaterpreviewjs-numrecs').val('numrecs');
		});
	});

	/* If the help button is clicked then display help page */
	$('#rpt_helpButton').click(
		function(event){
			event.preventDefault();
			BootstrapDialog.show({
				title: 'Help for Repeater',
            	message: $('<div></div>').load('help.html')
        	});
	});

});

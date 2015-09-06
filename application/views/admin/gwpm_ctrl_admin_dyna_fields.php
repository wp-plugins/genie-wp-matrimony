<?php

if (!function_exists('is_admin')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit ();
}

$deletedItems = "" ;
$formSubmitted = $_POST['formSubmitted'] ;

if($formSubmitted != null) {

	$deletedItems = $_POST['deletedItems'] ;
	$gwpmFieldLabels = $_POST['gwpm_dyna_field_label'] ;
	$gwpmFieldTypes = $_POST['gwpm_dyna_field_type'] ;
	$gwpmFieldValue = $_POST['gwpm_dyna_field_value'] ;
	$earlierCount = $_POST['earlierCount'] ;

	if(isset($gwpmFieldLabels) && $gwpmFieldLabels != null ) {
		appendLog( print_r($gwpmFieldLabels, true)) ;
		appendLog( print_r($gwpmFieldTypes, true)) ;
		appendLog( print_r($gwpmFieldValue, true)) ;
		$adminModel = new GwpmAdminModel() ;
		$adminModel->saveDynamicFields($gwpmFieldLabels, $gwpmFieldTypes, $gwpmFieldValue, $earlierCount) ;
	}

}

$existingRecords = get_option(GWPM_DYNA_FIELD_COUNT);
if(!isset($existingRecords) || $existingRecords == null || $existingRecords == false)
$existingRecords = 0;

?>
<br />
<div id="gwpm_resultBox"
	class="gwpm_message_box"></div>

<script type="text/javascript">  

var selectBoxCounts = [] ;

</script>

<form
	method="post">
	<div id="oldDynamicFieldHolder">
	<?php
	$totalFields = $existingRecords ;
	$displayText = "" ;
	$alternateClass = "" ;
	for(;$totalFields > 0; $totalFields-- ) {
		$fetched_options = get_option(GWPM_DYNA_KEY_PREFIX . $totalFields) ;
		if(isset($fetched_options) && $fetched_options != null ) {

			if($totalFields % 2 == 1) {
				$alternateClass = 'class="alternate"' ;
			} else {
				$alternateClass = "" ;
			}

			$fieldType = $fetched_options['gwpm_dyna_field_type'] == 'yes_no' ? "Yes/No" : ucfirst($fetched_options['gwpm_dyna_field_type']) ;

			if($fetched_options['gwpm_dyna_field_type'] == "select") {
				$displayText .=  '<tr id="gwpm_old_dyna_field_row_' . $totalFields . '" ' . $alternateClass .' ><td class="gwpm_fields_show gwpm_first_cell" rowspan="2">' . $fetched_options['gwpm_dyna_field_label'] .
									'</td><td class="gwpm_fields_show">' . $fieldType  ;
				$displayText .= getLoaderImg("24px", "gwpm_add_field_loader gwpm_add_field_loader_" . $totalFields);
				$displayText .= '</td>' ;
				$displayText .= '<td rowspan="2">';
				$displayText .= '<img id="gwpm_old_dyna_field_edit_' . $totalFields . '" class="gwpm_action_edit gwpm_action_images" ' .
									'src="' . GWPM_PUBLIC_IMG_URL . URL_S . 'edit.png" alt="Edit Record" />';
				$displayText .= '<img id="gwpm_old_dyna_field_delete_' . $totalFields . '" class="gwpm_delete_field gwpm_action_images" ' .
									'src="' . GWPM_PUBLIC_IMG_URL . URL_S . 'delete.png" alt="Delete Record" />';
				$displayText .= '<img id="gwpm_old_dyna_field_add_' . $totalFields . '" class="gwpm_add_field gwpm_action_images" ' .
									'src="' . GWPM_PUBLIC_IMG_URL . URL_S . 'add.png" alt="Add Record" />';
				$displayText .= '<br /></td></tr> <script type="text/javascript" > selectBoxCounts["gwpm_old_dyna_field_row_' . $totalFields . '"] = 0; </script> ' ;
				$displayText .= '<tr id="gwpm_old_dyna_field_row_' . $totalFields . '_select" ><td><table class="widefat" id="gwpm_old_dyna_field_select_vals_' . $totalFields . '" ><thead><tr><th class="gwpm_first_cell">Values </th><th>Action</th></tr></thead>' ;
				$selectValues = $fetched_options['gwpm_dyna_field_values'] ;
				$alternateClass_select = "" ;
				$selectCounter = 0 ;
				foreach ($selectValues as $vKey) {
					if($selectCounter % 2 == 0) {
						$alternateClass_select = 'class="alternate"' ;
					} else {
						$alternateClass_select = "" ;
					}
					$displayText .= '<tr id="gwpm_old_dyna_field_row_' . $totalFields . '_' . array_search($vKey, $selectValues) . '" ' . $alternateClass_select . '><td class="gwpm_fields_show gwpm_first_cell">' . $vKey . '</td>';
					$displayText .= '<td>' ;
					$displayText .= '<img id="gwpm_old_dyna_field_edit_' . $totalFields . '_' . array_search($vKey, $selectValues) . '" class="gwpm_action_edit gwpm_action_images" ' .
								'src="' . GWPM_PUBLIC_IMG_URL . URL_S . 'edit.png" alt="Edit" />' ;
					$displayText .= '<img id="gwpm_old_dyna_field_delete_' . $totalFields . '_' . array_search($vKey, $selectValues) . '" class="gwpm_delete_field gwpm_action_images" ' .
								'src="' . GWPM_PUBLIC_IMG_URL . URL_S . 'delete.png" alt="Delete" /> &nbsp;&nbsp;' ;
					$displayText .= '</td></tr> <script type="text/javascript" > selectBoxCounts["gwpm_old_dyna_field_row_' . $totalFields . '"] = ' ;
					$displayText .=  array_search($vKey, $selectValues) . ' ; </script> ' ;
					$selectCounter++ ;
				}
				$displayText .= '</table></td></tr>' ;

			} else {
				$displayText .=  '<tr id="gwpm_old_dyna_field_row_' . $totalFields . '" ' . $alternateClass .' ><td class="gwpm_fields_show gwpm_first_cell">' . $fetched_options['gwpm_dyna_field_label'] .
									'</td><td class="gwpm_fields_show">' . $fieldType  ;
				$displayText .= '</td>' ;
				$displayText .= '<td>' ;
				$displayText .= '<img id="gwpm_old_dyna_field_edit_' . $totalFields . '" class="gwpm_action_edit gwpm_action_images" ' .
									'src="' . GWPM_PUBLIC_IMG_URL . URL_S . 'edit.png" alt="Edit" />' ;
				$displayText .= '<img id="gwpm_old_dyna_field_delete_' . $totalFields . '" class="gwpm_delete_field gwpm_action_images" ' .
									'src="' . GWPM_PUBLIC_IMG_URL . URL_S . 'delete.png" alt="Delete" />' ;
				$displayText .= '<br /></td></tr>' ;
			}
		}
	}
	if($displayText != "") {
		echo '<table class="widefat"><thead><tr>' .
							'<th class="gwpm_fifrst_cell column-name">Field Label</th>' . 
							'<th class="column-name">Field Type</th>' . 
							'<th class="column-name">Actions</th></tr></thead><tbody>' . $displayText . '</tbody></table>' ;
	}

	?>
	</div>
	<!-- <input type="button" name="updateOptions" id="updateOptions" value="Update options" disabled="true" />  -->
	<input type="button" name="addNewRow" id="addNewRow"
		value="Add new dynamic field" /> <br /> <br />
	<div id="dynamicFieldHolder"></div>
	<input type="text" name="deletedItems" id="deletedItems"
		style="display: none;" /> <input type="text" name="formSubmitted"
		id="formSubmitted" value="1" style="display: none;" /> <input
		type="text" name="earlierCount" id="earlierCount"
		value="<?php echo $existingRecords ; ?>" style="display: none;" /> <input
		type="submit" value="Submit" />
</form>
<br />
<script type="text/javascript">

function getAjaxRequestorObj(methodName, value) {
	return {
		action : MyAjax.action,
		gwpm_nounce : MyAjax.gwpm_nounce,
		model: methodName,
		val: value
	};
}

function showUserMessage (msg, callback) {
	jQuery("#gwpm_resultBox").text(msg) ;

	if (callback != null)
		callback ;
	
	jQuery("#gwpm_resultBox").fadeIn(100);
	jQuery("#gwpm_resultBox").slideUp(5000) ;
}

function deleteRowItem(selectId) {
	if(selectId.indexOf('gwpm_old_dyna_field_delete') >= 0 ) {
		if(confirm("You are about to delete the record. Proceed?")) {
			var rowId = selectId.replace('gwpm_old_dyna_field_delete_', '');
			var delRowId = '#gwpm_old_dyna_field_row_' + rowId ;
			var delRowId_select = delRowId + '_select' ;
			 
		 	var data = getAjaxRequestorObj('dynafield_delete', rowId);
			jQuery.post(MyAjax.ajaxurl, data, function(response) {
				var resObj = jQuery.parseJSON( response ) ;
				if(resObj.result == 1) {
					showUserMessage(resObj.message, function(){
						alert ('asdasd') ;
						if( delRowId_select != null && delRowId !=null  ) {
							jQuery(delRowId_select).hide('slow') ;
							jQuery(delRowId).hide('slow') ;
						}
					}) ;
				} else {
					showUserMessage(response) ;
				}
			}); 
			}
	} else {
		var rowId = selectId.replace('gwpm_dyna_field_delete_', '');
		var removeId = "#gwpm_dyna_field_label_" + rowId ;
		jQuery(removeId).remove() ;	
		deleteFields.push(rowId );
		jQuery("#deletedItems").val(deleteFields) ;
	}
}

var existingRecords = <?php echo $existingRecords ; ?> ; 
var count = existingRecords + 1;
var valueArray = [] ;
var newLabelValues = {}  ;
var deleteFields = [] ;
var MyAjax = {"action":"gwpm_ajax_call","ajaxurl":"<?php echo admin_url('admin-ajax.php') ; ?>","gwpm_nounce":"<?php echo wp_create_nonce('gwpm') ; ?>"};
var inputFormData = '<span id="gwpm_dyna_field_label_##CHANGE_THIS##" class="gwpm_dyna_fields_holder" ><span class="gwpm_rowno">* </span>&nbsp;&nbsp;' + 
					'Field Label: <input type="text" name="gwpm_dyna_field_label[##CHANGE_THIS##]" >&nbsp;&nbsp;&nbsp;' + 
					'Field Type: <select class="gwpm_value_type_select" name="gwpm_dyna_field_type[##CHANGE_THIS##]" id="gwpm_dyna_field_type_##CHANGE_THIS##">' + 
					'<option value="text" selected >Text</option><option value="select" >Select</option><option value="yes_no" >Yes/No</option></select>' +
					'<input type="button" id="row_btn_##CHANGE_THIS##" class="gwpm_add_value" style="display: none;" value="Add value" />'+	
					'&nbsp;&nbsp;<img id="gwpm_dyna_field_delete_##CHANGE_THIS##" class="gwpm_action_images gwpm_action_delete" style="margin: 3px;"' + 
					'src="<?php echo GWPM_PUBLIC_IMG_URL . URL_S ?>delete.png" alt="Delete" />' +
					'<div class="gwpm_spacers" id="div_row_btn_##CHANGE_THIS##"></div>' +
					'</span>' ;

var selectTypeValues = 	'<span id="gwpm_dyna_field_value_##CHANGE_THIS##_##VALUE_ROW##" >Value : <input type="text" ' + 
						'name="gwpm_dyna_field_value[##CHANGE_THIS##][##VALUE_ROW##]" />' +
						'&nbsp;&nbsp;<img id="##CHANGE_THIS##_##VALUE_ROW##" name="##CHANGE_THIS##_##VALUE_ROW##" ' +
						'class="gwpm_delete_field_value" style="width: 25px; height: 25px;" ' + 
						'src="<?php echo GWPM_PUBLIC_IMG_URL . URL_S ?>delete.png" alt="Delete Record" />' + 
					   	'<br /></span>' ;

var newSelectValueRow = '<tr class="##ROW_CLASS##" id="gwpm_old_dyna_field_row_##CHANGE_THIS##_##VALUE_ROW##"><td class="gwpm_fields_show gwpm_first_cell">##SELECT_VALUE##</td>' + 
						'<td><img alt="Edit" src="<?php echo GWPM_PUBLIC_IMG_URL . URL_S ?>edit.png" ' + 
						'class="gwpm_action_edit gwpm_action_images" id="gwpm_old_dyna_field_edit_##CHANGE_THIS##_##VALUE_ROW##">' + 
						'<img alt="Delete" src="<?php echo GWPM_PUBLIC_IMG_URL . URL_S ?>delete.png" ' + 
						'class="gwpm_delete_field gwpm_action_images" id="gwpm_old_dyna_field_delete_##CHANGE_THIS##_##VALUE_ROW##"> &nbsp;&nbsp;</td></tr>' ; 

jQuery(document).ready(function(){

	// alert(jQuery.fn.jquery) ;
	
	jQuery("#addNewRow").click(function() {
		var s = inputFormData.replace(/\##CHANGE_THIS##/g, count);
		// s = s.replace(/\##CHANGE_THIS_ROWID##/g, (count+1));		
		jQuery("#dynamicFieldHolder").append(s) ;
		count++ ;
	}) ;
	
	jQuery(document).on("click", ".gwpm_add_value", function() {
		var myId = this.id ;
		var rowId = myId.replace('row_btn_', '');
		var valueCount = valueArray[rowId] ;
		if(valueCount == undefined || valueCount == null )
			valueCount = 0 ;				
		var s = selectTypeValues.replace(/\##CHANGE_THIS##/g, rowId);
		s = s.replace(/\##VALUE_ROW##/g, valueCount);
		jQuery("#" + "div_" + myId ).append( s );
		valueCount++ ;
		valueArray[rowId] = valueCount ;
	});

	jQuery(document).on("change", ".gwpm_value_type_select", function() {
		var selectId = this.id ;
		var selectedValue = this.selectedIndex ;
		var rowNo = selectId.replace('gwpm_dyna_field_type_', '');
		if( selectedValue == 1 ) {
			jQuery("#row_btn_" + rowNo ).show() ;			
		} else {
			jQuery("#row_btn_" + rowNo ).hide() ;
			valueArray[rowNo] = 0 ;
			jQuery("#" + "div_row_btn_" + rowNo ).html("") ;
		}
	}) ;

	jQuery(document).on("click", ".gwpm_delete_field_value", function() {
		var selectId = this.id ;
		var arrayNew = selectId.split("_") ;
		var rowId = arrayNew[0] ;
		var valueFieldId = arrayNew[1] ;		
		var removeId = "#gwpm_dyna_field_value_" + selectId ;
		jQuery(removeId).remove()  ;
		deleteFields.push(selectId) ;
		jQuery("#deletedItems").val(deleteFields) ;
	}) ;

	jQuery(document).on("click", ".gwpm_delete_field", function() {
		var selectId = this.id ;
		if(confirm("You are about to delete the record. All user data related to this field will be cleared. Proceed?")) {
			var rowId = selectId.replace('gwpm_old_dyna_field_delete_', '');
			var delRowId = '#gwpm_old_dyna_field_row_' + rowId ;
			var delRowId_select = delRowId + '_select' ;
			 
		 	var data = getAjaxRequestorObj('dynafield_delete', rowId);
			jQuery.post(MyAjax.ajaxurl, data, function(response) {
				var resObj = jQuery.parseJSON( response ) ;
				if(resObj.result == 1) {
					
					showUserMessage(resObj.message, function(delRowId_select, delRowId){
					//	alert ('asdasd ' + delRowId_select + ' - ' + delRowId) ;
						jQuery(delRowId_select).hide('slow') ;
						jQuery(delRowId).hide('slow') ;
					}) ;
					
				} else {
					jQuery("#gwpm_resultBox").text(response) ;
				}
				jQuery("#gwpm_resultBox").fadeIn(100);
				jQuery("#gwpm_resultBox").slideUp(5000) ;
			}).fail(function(){
				jQuery("#gwpm_resultBox").text("Request failed, Please try again !") ;

				jQuery("#gwpm_resultBox").fadeIn(100);
				jQuery("#gwpm_resultBox").slideUp(5000) ;
			}); ; 
		}
	}) ;

	jQuery(document).on("click", ".gwpm_add_field", function() {
		var selectId = this.id ;

		var rowId = selectId.replace('gwpm_old_dyna_field_add_', '');
		var addRowId = 'gwpm_old_dyna_field_row_' + rowId ;
		var valueCount = selectBoxCounts[addRowId] + 1;

		jQuery(".gwpm_add_field_loader_" + rowId).show() ;
		
		alert(selectId + " - " + valueCount) ;

		var selectValue = prompt("Please enter Select item text", "");

		if (selectValue != null && selectValue.trim() != '' ) {

			newLabelValues [rowId + "_" + valueCount] = selectValue ;
			
			var upData = getAjaxRequestorObj('dynafield_update', ( newLabelValues ));
			newLabelValues = {} ; 

			var rowClassName = jQuery("#" + "gwpm_old_dyna_field_select_vals_" + rowId + " tr:last ").attr('class') ;

			jQuery.post(MyAjax.ajaxurl, upData, function(response) {
				
				var resObj = jQuery.parseJSON( response ) ;
				if(resObj.result == 1) {
	
					var s = newSelectValueRow.replace(/\##CHANGE_THIS##/g, rowId);
					s = s.replace(/\##VALUE_ROW##/g, valueCount);
					s = s.replace(/\##SELECT_VALUE##/g, selectValue);
					if (rowClassName != 'alternate') {
						s = s.replace(/\##ROW_CLASS##/g, 'alternate');
					} else {
						s = s.replace(/\##ROW_CLASS##/g, '');
					}
	
					jQuery("#" + "gwpm_old_dyna_field_select_vals_" + rowId ).append( s );
	
					selectBoxCounts[addRowId] = valueCount ; 
	
				} else {
					jQuery("#gwpm_resultBox").text(response) ;
	
					jQuery("#gwpm_resultBox").fadeIn(100);
					jQuery("#gwpm_resultBox").slideUp(5000) ;
				}
	
				jQuery(".gwpm_add_field_loader_" + rowId).hide() ;
				
			}).fail(function(){
				jQuery("#gwpm_resultBox").text("Request failed, Please try again !") ;
	
				jQuery("#gwpm_resultBox").fadeIn(100);
				jQuery("#gwpm_resultBox").slideUp(5000) ;
	
			//	jQuery(".gwpm_add_field_loader_" + rowId).hide() ;
				
			}); 
		} else {
			jQuery(".gwpm_add_field_loader_" + rowId).hide() ;
		}
		
	}) ;

	jQuery(document).on("click", ".gwpm_action_delete", function() {
		var selectId = this.id ;
		var rowId = selectId.replace('gwpm_dyna_field_delete_', '');
		var removeId = "#gwpm_dyna_field_label_" + rowId ;
		jQuery(removeId).remove() ;	
		deleteFields.push(rowId );
		jQuery("#deletedItems").val(deleteFields) ;
	}) ;

	jQuery(document).on("click", "#updateOptions", function() {
		var data = getAjaxRequestorObj('dynafield_update', ( newLabelValues )); 
		jQuery.post(MyAjax.ajaxurl, data, function(response) {
			 alert (response) ;
		}); 
	}) ;

	jQuery(document).on("click", ".gwpm_action_edit", function() {
		
		var selectId = this.id ;
		var rowId = selectId.replace('gwpm_old_dyna_field_edit_', '');
		var selectedRowId = "gwpm_old_dyna_field_row_" + rowId ;
		var selectRow = document.getElementById(selectedRowId);

		var oldLabelValue = jQuery(selectRow.cells[0]).text();
		jQuery(selectRow.cells[0]).html (oldLabelValue + '&nbsp;&nbsp;<?php echo getLoaderImg("16px"); ?>') ;
		
		var newlyEditedValue = prompt("Please enter Label text", oldLabelValue);	
		
		if (newlyEditedValue != null && newlyEditedValue != oldLabelValue) {
			newLabelValues [rowId] = newlyEditedValue ;
			// jQuery("#updateOptions").prop("disabled", false) ;
			
			var upData = getAjaxRequestorObj('dynafield_update', ( newLabelValues ));
			newLabelValues = {} ; 
			jQuery.post(MyAjax.ajaxurl, upData, function(response) {
				// alert (response) ;

				var resObj = jQuery.parseJSON( response ) ;
				if(resObj.result == 1) {
					jQuery("#gwpm_resultBox").text("") ;
					jQuery(selectRow.cells[0]).html (resObj.message) ;
				} else {
					jQuery(selectRow.cells[0]).html (oldLabelValue) ;
					jQuery("#gwpm_resultBox").text(response) ;

					jQuery("#gwpm_resultBox").fadeIn(100);
					jQuery("#gwpm_resultBox").slideUp(5000) ;
				}
			}).fail(function(){
				jQuery(selectRow.cells[0]).html (oldLabelValue) ;
				jQuery("#gwpm_resultBox").text("Request failed, Please try again !") ;

				jQuery("#gwpm_resultBox").fadeIn(100);
				jQuery("#gwpm_resultBox").slideUp(5000) ;
			}); 			
		} else {
			jQuery(selectRow.cells[0]).html (oldLabelValue) ;
		}
	}) ;
	
});


</script>
	<?php


<?php 

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
<div id="gwpm_resultBox" class="gwpm_message_box" ></div>
<form method="post" >
	<div id="oldDynamicFieldHolder">
		<?php
			$totalFields = $existingRecords ;	
			$displayText = "" ;
			$alternateClass = "" ;		
			for(;$totalFields > 0; $totalFields-- ) {
				$fetched_options = get_option(GWPM_DYNA_KEY_PREFIX . $totalFields) ;
				if(isset($fetched_options) && $fetched_options != null ) {
				/*	$displayText = '<span id="gwpm_dyna_field_label_' . $totalFields . '" ><span class="gwpm_rowno">** </span>&nbsp;&nbsp;' .
					'Field Label: <input type="text" name="gwpm_dyna_field_label[' . $totalFields . ']" value=' . $fetched_options['gwpm_dyna_field_label'] . ' >&nbsp;&nbsp;&nbsp;'. 
					'Field Type: <select class="gwpm_value_type_select" name="gwpm_dyna_field_type[' . $totalFields . ']" id="gwpm_dyna_field_type_' . $totalFields . '">'. 
					'<option value="text" selected >Text</option><option value="select" >Select</option><option value="yes_no" >Yes/No</option></select>' .
					'<input type="button" id="row_btn_' . $totalFields . '" class="gwpm_add_value" style="display: none;" value="Add value" />&nbsp;&nbsp;'. 
					'<img id="gwpm_dyna_field_delete_' . $totalFields . '" class="gwpm_delete_field" style="width: 25px; height: 25px;" ' . 
					'src="' . GWPM_PUBLIC_IMG_URL . URL_S . 'delete.png" alt="Delete Record" /><br />' . 
					'<div class="gwpm_spacers" id="div_row_btn_' . $totalFields . '"></div></span>' ;  */
					
					if($totalFields % 2 == 1)
						$alternateClass = 'class="alternate"' ;
					else
						$alternateClass = "" ;
						
					$fieldType = $fetched_options['gwpm_dyna_field_type'] == 'yes_no' ? "Yes/No" : ucfirst($fetched_options['gwpm_dyna_field_type']) ;
					
					if($fetched_options['gwpm_dyna_field_type'] == "select") {
						$displayText .=  '<tr id="gwpm_old_dyna_field_row_' . $totalFields . '" ' . $alternateClass .' ><td class="gwpm_fields_show gwpm_first_cell" rowspan="2">' . $fetched_options['gwpm_dyna_field_label'] .
									'</td><td class="gwpm_fields_show">' . $fieldType  ;
						$displayText .= '</td>' ;
						$displayText .= '<td rowspan="2"><img id="gwpm_old_dyna_field_delete_' . $totalFields . '" class="gwpm_delete_field" style="width: 25px; height: 25px;" ' . 
									'src="' . GWPM_PUBLIC_IMG_URL . URL_S . 'delete.png" alt="Delete Record" /><br /></td></tr>' ;
						$displayText .= '<tr id="gwpm_old_dyna_field_row_' . $totalFields . '_select" ><td><table class="widefat"><thead><tr><th class="gwpm_first_cell">Values </th><th>Action</th></tr></thead>' ;
						$selectValues = $fetched_options['gwpm_dyna_field_values'] ;
						$alternateClass_select = "" ;
						$selectCounter = 0 ; 
						foreach ($selectValues as $vKey) {
							if($selectCounter % 2 == 0)
								$alternateClass_select = 'class="alternate"' ;
							else
								$alternateClass_select = "" ;
							$displayText .= '<tr id="gwpm_old_dyna_field_row_' . $totalFields . '_' . array_search($vKey, $selectValues) . '" ' . $alternateClass_select . '><td class="gwpm_fields_show gwpm_first_cell">' . $vKey . '</td>';
							$displayText .= '<td><img id="gwpm_old_dyna_field_delete_' . $totalFields . '_' . array_search($vKey, $selectValues) . '" class="gwpm_delete_field" style="width: 20px; height: 20px;" ' . 
								'src="' . GWPM_PUBLIC_IMG_URL . URL_S . 'delete.png" alt="Delete Record" /></td></tr>' ;
							$selectCounter++ ;	
						}
						$displayText .= '</table></td></tr>' ;
						
					} else {
						$displayText .=  '<tr id="gwpm_old_dyna_field_row_' . $totalFields . '" ' . $alternateClass .' ><td class="gwpm_fields_show gwpm_first_cell">' . $fetched_options['gwpm_dyna_field_label'] .
									'</td><td class="gwpm_fields_show">' . $fieldType  ;
						$displayText .= '</td>' ;
						$displayText .= '<td><img id="gwpm_old_dyna_field_delete_' . $totalFields . '" class="gwpm_delete_field" style="width: 25px; height: 25px;" ' . 
									'src="' . GWPM_PUBLIC_IMG_URL . URL_S . 'delete.png" alt="Delete Record" /><br /></td></tr>' ;
					}
				}
			}
			if($displayText != "") {
				echo '<table class="widefat"><thead><tr>' .
							'<th class="gwpm_fifrst_cell column-name">Field Label</th>' . 
							'<th class="column-name">Field Type</th>' . 
							'<th class="column-name">Action</th></tr></thead><tbody>' . $displayText . '</tbody></table>' ;
			}
		
		?>
	</div>
	<input type="button" name="addNewRow" id="addNewRow" value="Add new field" /> <br /><br />
	<div id="dynamicFieldHolder"></div>
	<input type="text" name="deletedItems" id="deletedItems" style="display: none; "/>
	<input type="text" name="formSubmitted" id="formSubmitted" value="1" style="display: none; "/>
	<input type="text" name="earlierCount" id="earlierCount" value="<?php echo $existingRecords ; ?>" style="display: none; "/>
	<input type="submit" value="Submit" />
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

var existingRecords = <?php echo $existingRecords ; ?> ; 
var count = existingRecords + 1;
var valueArray = [] ;
var deleteFields = [] ;
var MyAjax = {"action":"gwpm_ajax_call","ajaxurl":"<?php echo admin_url('admin-ajax.php') ; ?>","gwpm_nounce":"<?php echo wp_create_nonce('gwpm') ; ?>"};
var inputFormData = '<span id="gwpm_dyna_field_label_##CHANGE_THIS##" ><span class="gwpm_rowno">* </span>&nbsp;&nbsp;' + 
					'Field Label: <input type="text" name="gwpm_dyna_field_label[##CHANGE_THIS##]" >&nbsp;&nbsp;&nbsp;' + 
				//	'Field Value: <input type="text" name="gwpm_dyna_field_value[##CHANGE_THIS##]" >&nbsp;&nbsp;&nbsp;' + 
					'Field Type: <select class="gwpm_value_type_select" name="gwpm_dyna_field_type[##CHANGE_THIS##]" id="gwpm_dyna_field_type_##CHANGE_THIS##">' + 
					'<option value="text" selected >Text</option><option value="select" >Select</option><option value="yes_no" >Yes/No</option></select>' +
					'<input type="button" id="row_btn_##CHANGE_THIS##" class="gwpm_add_value" style="display: none;" value="Add value" />'+					
					'&nbsp;&nbsp;<img id="gwpm_dyna_field_delete_##CHANGE_THIS##" class="gwpm_delete_field" style="width: 25px; height: 25px;" ' + 
					'src="<?php echo GWPM_PUBLIC_IMG_URL . URL_S ?>delete.png" alt="Delete Record" /><br />' + 
					'<div class="gwpm_spacers" id="div_row_btn_##CHANGE_THIS##"></div>' +
					'</span>' ;

var selectTypeValues = 	'<span id="gwpm_dyna_field_value_##CHANGE_THIS##_##VALUE_ROW##" >Value : <input type="text" name="gwpm_dyna_field_value[##CHANGE_THIS##][##VALUE_ROW##]" />' +
						'&nbsp;&nbsp;<img id="##CHANGE_THIS##_##VALUE_ROW##" name="##CHANGE_THIS##_##VALUE_ROW##" class="gwpm_delete_field_value" style="width: 25px; height: 25px;" ' + 
						'src="<?php echo GWPM_PUBLIC_IMG_URL . URL_S ?>delete.png" alt="Delete Record" />' + 
					   	'<br /></span>' ;

jQuery(document).ready(function(){
	jQuery("#addNewRow").click(function() {
		var s = inputFormData.replace(/\##CHANGE_THIS##/g, count);
		// s = s.replace(/\##CHANGE_THIS_ROWID##/g, (count+1));		
		jQuery("#dynamicFieldHolder").append(s) ;
		count++ ;
	}) ;
	
	jQuery(".gwpm_add_value").live("click", function() {
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

	jQuery(".gwpm_value_type_select").live("change", function() {
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

	jQuery(".gwpm_delete_field_value").live("click", function() {
		var selectId = this.id ;
		var arrayNew = selectId.split("_") ;
		var rowId = arrayNew[0] ;
		var valueFieldId = arrayNew[1] ;		
		var removeId = "#gwpm_dyna_field_value_" + selectId ;
		jQuery(removeId).remove()  ;
		deleteFields.push(selectId) ;
		jQuery("#deletedItems").val(deleteFields) ;
	}) ;

	jQuery(".gwpm_delete_field").live("click", function() {
		var selectId = this.id ;
		if(selectId.indexOf('gwpm_old_dyna_field_delete') >= 0 ) {
			if(confirm("You are about to delete the record. Proceed?")) {
				var rowId = selectId.replace('gwpm_old_dyna_field_delete_', '');
				var delRowId = '#gwpm_old_dyna_field_row_' + rowId ;
				var delRowId_select = delRowId + '_select' ;
				 
			 	var data = getAjaxRequestorObj('dynafield_delete', rowId);
				jQuery.post(MyAjax.ajaxurl, data, function(response) {
					var resObj = jQuery.parseJSON( response ) ;
					if(resObj.result == 1) {
						jQuery("#gwpm_resultBox").text(resObj.message) ;
						jQuery(delRowId_select).hide('slow') ;
						jQuery(delRowId).hide('slow') ;
					} else {
						jQuery("#gwpm_resultBox").text(response) ;
					}
					jQuery("#gwpm_resultBox").fadeIn(100);
					jQuery("#gwpm_resultBox").slideUp(5000) ;
				}); 
  			}
		} else {
			var rowId = selectId.replace('gwpm_dyna_field_delete_', '');
			var removeId = "#gwpm_dyna_field_label_" + rowId ;
			jQuery(removeId).remove() ;	
			deleteFields.push(rowId );
			jQuery("#deletedItems").val(deleteFields) ;
		}
	}) ;
});

</script>
<?php

<h2>Dynamic Fields </h2>
<input type="button" name="addNewRow" id="addNewRow" value="Add new field" /> <br /><br />
<form method="post" >
	<div id="dynamicFieldHolder">		
	</div>
	<input type="submit" value="Submit" />
</form>
<style type="text/css" >
	.rowno {
		font-weight: bold; 
		font-size: 18px;
		color: red;		
	}
	.spacers {
		padding-left: 30px;
	}
	.gwpm_add_value {
		display: none; 
	}
	.gwpm_dyna_field_label_span {
		height: 5px;
	}
	.gwpm_delete_field {
		margin-top: 5px; 
	}
	
</style>
<script type="text/javascript">

var count = 0 ;
var valueArray = [] ;
var deleteFields = [] ;
var inputFormData = '<span id="gwpm_dyna_field_label_##CHANGE_THIS##" ><span class="rowno">>> </span>&nbsp;&nbsp;' + 
					'Field Label: <input type="text" name="gwpm_dyna_field_label[##CHANGE_THIS##]" >&nbsp;&nbsp;&nbsp;' + 
					'Field Value: <input type="text" name="gwpm_dyna_field_value[##CHANGE_THIS##]" >&nbsp;&nbsp;&nbsp;' + 
					'Field Type: <select class="gwpm_value_type_select" name="gwpm_dyna_field_type[##CHANGE_THIS##]" id="gwpm_dyna_field_type_##CHANGE_THIS##">' + 
					'<option value="text" selected >Text</option><option value="select" >Select</option><option value="yes_no" >Yes/No</option></select>' +
					'<input type="button" id="row_btn_##CHANGE_THIS##" class="gwpm_add_value" style="display: none;" value="Add value" />'+					
					'&nbsp;&nbsp;<img id="gwpm_dyna_field_delete_##CHANGE_THIS##" class="gwpm_delete_field" style="width: 25px; height: 25px;" ' + 
					'src="<?php echo GWPM_PUBLIC_IMG_URL . URL_S ?>delete.png" alt="Delete Record" /><br />' + 
					'<div class="spacers" id="div_row_btn_##CHANGE_THIS##"></div>' +
					'</span>' ;

var selectTypeValues = 	'<span id="gwpm_dyna_field_value_##CHANGE_THIS##_##VALUE_ROW##" >Value : <input type="text" name="gwpm_dyna_field_value[##CHANGE_THIS##][##VALUE_ROW##]" />' +
						'&nbsp;&nbsp;<img id="##CHANGE_THIS##_##VALUE_ROW##" name="##CHANGE_THIS##_##VALUE_ROW##" class="gwpm_delete_field_value" style="width: 25px; height: 25px;" ' + 
						'src="<?php echo GWPM_PUBLIC_IMG_URL . URL_S ?>delete.png" alt="Delete Record" />' + 
					   	'<br /></span>' ;

jQuery(document).ready(function(){


	// alert(jQuery.fn.jquery) ;
	
	jQuery("#addNewRow").click(function(){
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

	jQuery(document).on("change", ".gwpm_value_type_select", function(){
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
	}) ;

	jQuery(document).on("click", ".gwpm_delete_field", function() {
		var selectId = this.id ;
		var rowId = selectId.replace('gwpm_dyna_field_delete_', '');
		var removeId = "#gwpm_dyna_field_label_" + rowId ;
		jQuery(removeId).remove() ;	
		deleteFields.push(rowId + "_*" );	
	}) ;
	
});

</script>
<?php

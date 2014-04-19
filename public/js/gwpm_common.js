
function getAjaxRequestorObj(methodName, value) {
	return {
		action : MyAjax.action,
		gwpm_nounce : MyAjax.gwpm_nounce,
		model: methodName,
		val: value
	};
}

function ajaxCallSample(val) {
	var data = getRequestorObj();
	data.val = val;
	jQuery.post(MyAjax.ajaxurl, data, function(response) {
		alert('Got this from the server: ' + response);
	});
}

function addDynamicComponent(dynamicContent, placeHolder, counterDivID) {
	if(totalServices > 9) {
		alert("You can only upload only ten images") ;
		return ;
	}
	totalServices = totalServices + 1;
	compSetId = compSetId + 1;
	var divArray = document.getElementById(dynamicContent);
	var contentID = document.getElementById(placeHolder);
	var newTBDiv = document.createElement('div');
	var dynContent = divArray.innerHTML;

	newTBDiv.setAttribute('id', placeHolder + 'DIV' + compSetId);
	dynContent = dynContent.replace(/ADDIDHERE/g, compSetId);
	newTBDiv.innerHTML = dynContent;
	contentID.appendChild(newTBDiv);
	document.getElementById(counterDivID).innerHTML = totalServices;

	if (compSetId == 1) {
		document.getElementById('deletedRows').value = "";
	}
}

function deleteDynamicComponent(divObjCounter, placeHolder, counterDivID) {

	if (divObjCounter != 0) {
		var contentID = document.getElementById(placeHolder);
		contentID.removeChild(document.getElementById(placeHolder + 'DIV'
				+ divObjCounter));
		totalServices = totalServices - 1;

		if (totalServices != -1) {
			document.getElementById(counterDivID).innerHTML = totalServices;
			if (document.getElementById('deletedRows').value == "") {
				document.getElementById('deletedRows').value = divObjCounter;
			} else {
				document.getElementById('deletedRows').value = document
						.getElementById('deletedRows').value
						+ "," + divObjCounter;
			}
		} else {
			compSetId = 0;
			document.getElementById(counterDivID).innerHTML = 0;
			document.getElementById('deletedRows').value = "";
		}
	} else {
		alert("Cannot delete all details");
	}

}

//Multiple add function
function addDynamicComponents(dynamicContent, placeHolder, counterDivID,
		countId) {
	var totalCount = 0 ;
	if (totalCount == "" || totalCount == null || totalCount == undefined) {
		totalCount = 1;
	}
	for ( var dCount = 0; dCount < totalCount; dCount++) {
		addDynamicComponent(dynamicContent, placeHolder, counterDivID);
	}
}

//Add Edit Code here
function editDynamicComponent(rowNo) {
	alert("Row Selected for editing: " + rowNo);
}
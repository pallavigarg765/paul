function checkformAdd(){   
	var aName = new String( );
	if(document.formAdd.wsAd.value.toString() != ""){
		if((document.formAdd.wsAd.value.toString().search(/^-?[0-9]+$/) != 0) || (document.formAdd.wsAd.value == " ")){
			aName+="Invalid ad value. Must be number.\n";
		}
	}
	jQuery.ajax({
		async: false,
		url: "ajaxV.php", 
		type: "POST",
		dataType: "html",
		data: ({
			task: 'thumbsExist',
			thumb1: document.formAdd.wsThumb1.value.toString(),
			thumb2: document.formAdd.wsThumb2.value.toString(),
			thumb3: document.formAdd.wsThumb3.value.toString(),
			thumbUrl: document.formAdd.wsThumbsDomLoc.value.toString()
		}),
		success: function(msg) {
			if(msg != "okokok"){
				aName+="Please recheck thumbs path, or upload thumbs.\n";	
			}
		}
	});
	
	if(aName.length>0){
		window.alert(aName);
		return false;
	}
	//return false;
}

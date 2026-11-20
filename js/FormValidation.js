function checkformAdd(){   
	var aName = new String( );
	if(document.formAdd.wsAd.value.toString() != ""){
		if((document.formAdd.wsAd.value.toString().search(/^-?[0-9]+$/) != 0) || (document.formAdd.wsAd.value == " ")){
			aName += "Invalid ad value. Must be number.\n\n";
		}
	}
	
	
	if(document.formAdd.wsThumb1.value.toString().indexOf(" ") != "-1"){
		aName += "The first thumbnail name is incorrect. Remove the empty space from the name and file.\n\n";
	}
	
	if(document.formAdd.wsThumb2.value.toString().indexOf(" ") != "-1"){
		aName += "The second thumbnail name is incorrect. Remove the empty space from the name and file.\n\n";
	}
	
	if(document.formAdd.wsThumb3.value.toString().indexOf(" ") != "-1"){
		aName += "The third thumbnail name is incorrect. Remove the empty space from the name and file.\n\n";
	}
	
	
	if(document.formAdd.wsThumb1.value.toString().indexOf(".jpg") == "-1"){
		aName += "The first thumbnail name is incorrect. It is not a jpg file.\n\n";
	}
	
	if(document.formAdd.wsThumb2.value.toString().indexOf(".jpg") == "-1"){
		aName += "The second thumbnail name is incorrect. It is not a jpg file.\n\n";
	}
	
	if(document.formAdd.wsThumb3.value.toString().indexOf(".jpg") == "-1"){
		aName += "The third thumbnail name is incorrect. It is not a jpg file.\n\n";
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
				aName += "Please recheck thumbs path, or upload thumbs.\n";	
			}
		}
	});
	
	
	if(aName.length > 0){
		window.alert(aName);
		return false;
	}
	//return false;
}

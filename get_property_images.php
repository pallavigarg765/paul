<?php 
mysql_connect('localhost','root','Securel0gi!12aa');
mysql_select_db('planitlo_esolz');
mysql_select_db('planitlo_locations');
if($_POST['url']!='' && isset($_POST['addcheck']) && $_POST['addcheck']==true)
{
	$propertyID=end(explode('/',$_POST['url']));
	$sqlThumbs = "SELECT * FROM `planitlo_locations`.`prop_info` WHERE `prop_loc` = '".$propertyID."'";
	$resultThumbs = mysql_query($sqlThumbs);
	if(mysql_num_rows($resultThumbs)>0)
	{
		$rowThumbs = mysql_fetch_array($resultThumbs);
		$domain = ($rowThumbs['tn_domain_loc1'] != "") ? $rowThumbs['tn_domain_loc1'] : $rowThumbs['tn_domain_loc'];
		$thumb1 = ($rowThumbs['thumb04'] != "") ? $domain.$rowThumbs['thumb04'] : $domain.$rowThumbs['thumb01'];
		$thumb2 = ($rowThumbs['thumb05'] != "") ? $domain.$rowThumbs['thumb05'] : $domain.$rowThumbs['thumb02'];
		$thumb3 = ($rowThumbs['thumb06'] != "") ? $domain.$rowThumbs['thumb06'] : $domain.$rowThumbs['thumb03'];
		
		$str="";
		$str.='<div class="tblPhotobox">';
		$str.='<div class="tblPhoto"><img src="'.$thumb1.'"><label><input type="radio" name="banner1" value="'.$thumb1.'" />Select</label></div>';
		$str.='<div class="tblPhoto"><img src="'.$thumb2.'"><label><input type="radio" name="banner1" value="'.$thumb2.'" />Select</label></div>';
		$str.='<div class="tblPhoto"><img src="'.$thumb3.'"><label><input type="radio" name="banner1" value="'.$thumb3.'" />Select</label></div>';
		$str.='</div>';
		$prop_id=$propertyID;
	}
	else 
	{
		$str="Data not found.";
		$prop_id='';
	}
	echo json_encode(array('propertyName'=>$prop_id,'photoes'=>$str)); 
}

else if($_POST['url']!='')
{
	$propertyID=end(explode('/',$_POST['url']));
	$sqlThumbs = "SELECT * FROM `planitlo_locations`.`prop_info` WHERE `prop_loc` = '".$propertyID."'";
	$resultThumbs = mysql_query($sqlThumbs);
	if(mysql_num_rows($resultThumbs)>0)
	{
		$rowThumbs = mysql_fetch_array($resultThumbs);
		$domain = ($rowThumbs['tn_domain_loc1'] != "") ? $rowThumbs['tn_domain_loc1'] : $rowThumbs['tn_domain_loc'];
		$thumb1 = ($rowThumbs['thumb04'] != "") ? $domain.$rowThumbs['thumb04'] : $domain.$rowThumbs['thumb01'];
		$thumb2 = ($rowThumbs['thumb05'] != "") ? $domain.$rowThumbs['thumb05'] : $domain.$rowThumbs['thumb02'];
		$thumb3 = ($rowThumbs['thumb06'] != "") ? $domain.$rowThumbs['thumb06'] : $domain.$rowThumbs['thumb03'];
		
		$str="";
		$str.='<div class="tblPhotobox">';
		$str.='<div class="tblPhoto"><img src="'.$thumb1.'"><input type="hidden" name="banner1" value="'.$thumb1.'" /></div>';
		$str.='<div class="tblPhoto"><img src="'.$thumb2.'"><input type="hidden" name="banner2" value="'.$thumb2.'" /></div>';
		$str.='<div class="tblPhoto"><img src="'.$thumb3.'"><input type="hidden" name="banner3" value="'.$thumb3.'" /></div>';
		$str.='</div>';
		$prop_id=$propertyID;
	}
	else 
	{
		$str="Data not found.";
		$prop_id='';
	}
	echo json_encode(array('propertyName'=>$prop_id,'photoes'=>$str)); 
}
?>
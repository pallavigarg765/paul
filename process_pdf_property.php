<?php
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
ini_set('memory_limit', '-1');
require_once dirname( __FILE__ ) . '/tcpdf_min/tcpdf.php';
include('include/security.php');
include('include/dbcon.php');
include('include/config.php');
include('include/functions.php');



$html_css = '
	<style>
	table{
		padding-rop: 15px;
	}
	
	.heightcss
	{
		line-height:15.5px;
		border:1.5px solid black;
		verticle-align:bottom;
		font-size:12px;
	}
	</style> ';

if(isset($_POST))
{
	$pdf_name = '';
	if(isset($_POST['submit_btn_2']) && $_POST['submit_btn_2']=='save_create_pdf')
	{
	$html = $html_css.' 
	<br><br>
	<table >
	<tbody>
	<tr>
	<td width="40%" valign="bottom">
		<p style="line-height:22px;">&nbsp;</p>
		<p style="font-size:13px"><b>Please Print All Information Clearly</b></p>
		<div class="heightcss">  Company Name Or Site Name<br>  '.$_POST['company_name'].'</div>
	</td>
	<td valign="middle" align="right" style="font-size:12px;text-align:justify"><img src="images/Screenshot_17.png" height="105px" width="182px"/>
	</td>
	<td valign="middle" align="right" width="165px">
		<div style="font-size:11px;text-align:left"><br><br>
		Plan-It Locations, Inc.<br>
		Office 818-376-6506<br>
		sales@planitlocations.com<br>
		www.planitlocations.com<br>
		</div>
	</td>
	</tr>
	
	</tbody>
	</table>
	';
	//end content
	// create new PDF document
	// echo $html;
	// exit;
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	// set default monospaced font
	// set margins
	$pdf->SetMargins(10,5,10);
	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	// set default font subsetting mode
	$pdf->setFontSubsetting(true);
	// Set font
	// dejavusans is a UTF-8 Unicode font, if you only need to
	// print standard ASCII chars, you can use core fonts like
	// helvetica or times to reduce file size.
	
	$pdf->AddPage();

	$pdf->writeHTML($html, true, false, true, false, '');
	//$_POST['recipient'] = 'asda  asd asdasa ';

	$recip_ = str_pad($_POST['recipient'],402,'&nbsp;');
	//$recip_ = str_replace('&nbs','',$recip_);
	if($_POST['recipient']!="")
	{
		$recip_ = $_POST['recipient'];
	}

	


	$html = $html_css.'<table width="100%" >
		<tbody>
			<tr>
				<td width="40%" valign="bottom">
					<div class="heightcss">  1st Contact Name<br>  '.$_POST["contact_name_1"].'</div>
				</td>
				<td width="40%" valign="bottom">
					<div class="heightcss">  Title<br>  '.$_POST["title_1"].'</div>
				</td>
				<td width="20%" valign="bottom">
					
				</td>
			</tr>
			<tr>
				<td width="40%" valign="bottom">
					<div class="heightcss">  Location Address<br>  '.$_POST["location_1"].'</div>
				</td>
				<td width="40%" valign="bottom">
					<div class="heightcss">  City, State<br>  '.$_POST["city_state_1"].'</div>
				</td>
				<td width="20%" valign="bottom">
					<div class="heightcss">  Zip Code<br>  '.$_POST["zip_1"].'</div>
				</td>
			</tr>
			<tr>
				<td width="40%" valign="bottom">
					<div class="heightcss">  Mailing Address (if different then above)<br>  '.$_POST["mailing_add_1"].'</div>
				</td>
				<td width="40%" valign="bottom">
					<div class="heightcss">  City, State<br>  '.$_POST["city_state_2"].'</div>
				</td>
				<td width="20%" valign="bottom">
					<div class="heightcss">  Zip Code<br>  '.$_POST["zip_2"].'</div>
				</td>
			</tr>
		</tbody>
	</table>';

	$html .='<table width="100%" >
		<tbody>
			<tr>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Home Phone #<br>  '.$_POST["home_phone_1"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Office Phone #<br>  '.$_POST["office_phone_1"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Cell Phone #<br>  '.$_POST["cell_phone_1"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Pager #<br>  '.$_POST["pager_1"].'</div>
				</td>
			</tr>
			<tr>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Fax #<br>  '.$_POST["fax_1"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Other #<br>  '.$_POST["other_1"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Email Address<br>  '.$_POST["email_add_1"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Website Address<br>  '.$_POST["web_1"].'</div>
				</td>
			</tr>
		</tbody>
	</table>';
	$html .='<p style="font-size:13px;margin:0;padding:0;line-height: 1px;"><b>2nd Contact Info</b></p>
	<table width="100%" >
		<tbody>
			<tr>
				<td width="40%" valign="bottom">
					<div class="heightcss">  2nd Contact Name<br>  '.$_POST["contact_name_2"].'</div>
				</td>
				<td width="40%" valign="bottom">
					<div class="heightcss">  Title<br>  '.$_POST["title_2"].'</div>
				</td>
				<td width="20%" valign="bottom">
					
				</td>
			</tr>
			</tbody>
	</table>';

	


	$html .='<table width="100%" >
		<tbody>
			<tr>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Home Phone #<br>  '.$_POST["home_phone_2"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Office Phone #<br>  '.$_POST["office_phone_2"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Cell Phone #<br>  '.$_POST["cell_phone_2"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Pager #<br>  '.$_POST["pager_2"].'</div>
				</td>
			</tr>
			<tr>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Fax #<br>  '.$_POST["fax_2"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Other #<br>  '.$_POST["other_2"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Email Address<br>  '.$_POST["email_add_2"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Website Address<br>  '.$_POST["web_2"].'</div>
				</td>
			</tr>
		</tbody>
	</table>';
	$html .='<p style="font-size:13px;margin:0;padding:0;line-height: 1px;"><b>Location Description</b></p>
	<table width="100%" style=" border-spacing:0;border-collapse: collapse;">
		<tbody>
			<tr>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Lot Size (Acres)<br>  '.$_POST["lot_size"].'</div>
				</td>
				<td width="25%" valign="bottom">
					<div class="heightcss">  Square Footage<br>  '.$_POST["squre_foot"].'</div>
				</td>
				<td width="20%" valign="bottom">
					<div class="heightcss">  Date Built<br>  '.$_POST["date_built"].'</div>
				</td>
				<td width="30%" valign="bottom">
					<div class="heightcss">  Style or Period of the Property<br>  '.$_POST["style_of_property"].'</div>
				</td>
			</tr>
			<tr>
				<td width="50%" valign="bottom" colaspan="2">
					<div class="heightcss">  Parking Spots Available Or Parking Issues<br>  '.$_POST["parking_spots"].'</div>
				</td>
				<td width="50%" valign="bottom" colaspan="2">
					<div class="heightcss">  Neighbor Issues<br>  '.$_POST["neighbor_issues"].'</div>
				</td>
			</tr>
		</tbody>
	</table>';
	$project_interests =  $_POST['project_interests'];
	$checked_image =  '<img width="13px" height="13px" src="images/check-box-with-check-sign.png">';
	$unchecked_image =  '<img width="13px" height="13px" src="images/square-with-round-corners.png">';
	$html .='
	<table width="100%" style="border:2px solid black" cellspacing="6" cellpadding="4">
		
			<tr>
				<td width="50%" >
					<p style="font-size:13px;margin:0;padding:0;"><b>Important Filming Information</b><br>Select Projects Of Interest For Your Property</p>
					<table width="100%" >
						<tbody>
							<tr>
								<td style="font-size:12px" valign="middle">'.(in_array("Feature Film",$project_interests) ? $checked_image : $unchecked_image).' Feature Film</td>
								<td style="font-size:12px" valign="middle">'.(in_array("Commercials",$project_interests) ? $checked_image : $unchecked_image).' Commercials</td>
							</tr>
							<tr>
								<td style="font-size:12px" valign="middle">'.(in_array("Still Photography",$project_interests) ? $checked_image : $unchecked_image).' Still Photography</td>
								<td style="font-size:12px" valign="middle">'.(in_array("Infomercial",$project_interests) ? $checked_image : $unchecked_image).' Infomercial</td>
							</tr>
							<tr>
								<td style="font-size:12px" valign="middle">'.(in_array("TV Movie",$project_interests) ? $checked_image : $unchecked_image).' TV Movie</td>
								<td style="font-size:12px" valign="middle">'.(in_array("Events",$project_interests) ? $checked_image : $unchecked_image).' Events</td>
							</tr>
							<tr>
								<td style="font-size:12px" valign="middle">'.(in_array("Music Videos",$project_interests) ? $checked_image : $unchecked_image).' Music Videos</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td width="50%" >
					<table width="100%">
						<tbody>
							<tr>
								<td>
									<div class="heightcss" style="font-size:10px">  Any Previous Film Projects (Please List Titles & The Year Filmed)<br>  '.$_POST["prev_film_pro"].'</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="heightcss" style="font-size:10px">  Any Other Properties Owned For Filming? Or That Friends Own?<br>  '.$_POST["other_prop_owned"].'</div>
								</td>
							</tr>
							<tr>
								<td>
									<div class="heightcss" style="font-size:10px">  Do You Own Any Show Vehicles, Boats or Aircraft?<br>  '.$_POST["show_vehicles"].'</div>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				
			</tr>
			
		
	</table>';
	$html .='<br><br>
	<table width="100%" >
		<tbody>
			<tr>
				<td width="65%" valign="bottom">
					<div class="heightcss">  Notes<br>  '.$_POST["notes"].'</div>
				</td>
				<td width="35%" valign="bottom">
					<div class="heightcss">  Photographers Name & Date<br>  '.$_POST["photographer"].'</div>
				</td>
			</tr>
			</tbody>
	</table>';
	// echo $html;
	// exit;
	$pdf->writeHTML($html, true, false, true, false, '');
	// Print text using writeHTMLCell()
	//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);
	//$pdf->Output(dirname(__FILE__).'example_001.pdf', 'F');
	$pdf_name = 'deal-property-profile-'.date('ymdHis').'.pdf';
	//$pdf_name = 'test.pdf'; ax
	$pdf->Output(dirname(__FILE__).'/pdfs/'.$pdf_name, 'F');
	}
	
	
	//$pdf->Output($pdf_name, 'F');
	//$pdf->Output(dirname(__FILE__).'/pdfs/'.$pdf_name.'', 'FD');
	$date_to = date('Y-m-d H:i:s');
	

	$project_interests = implode(',',$_POST['project_interests']);
	$db = new DBConn();
	mysql_select_db("planitlo_crm");
	if(isset($_POST['edit_id']) && $_POST['edit_id']!='')
	{
		$sql = "UPDATE `pdf_property` SET `company_name` = '".addslashes($_POST['company_name'])."',`contact_name_1` = '".addslashes($_POST['contact_name_1'])."',`title_1` = '".addslashes($_POST['title_1'])."',`location_1` = '".addslashes($_POST['location_1'])."',`city_state_1` = '".addslashes($_POST['city_state_1'])."',`zip_1` = '".addslashes($_POST['zip_1'])."',`mailing_add_1` = '".addslashes($_POST['mailing_add_1'])."',`city_state_2` = '".addslashes($_POST['city_state_2'])."',`zip_2` = '".addslashes($_POST['zip_2'])."',`home_phone_1` = '".addslashes($_POST['home_phone_1'])."',`office_phone_1` = '".addslashes($_POST['office_phone_1'])."',`cell_phone_1` = '".addslashes($_POST['cell_phone_1'])."',`pager_1` = '".addslashes($_POST['pager_1'])."',`fax_1` = '".addslashes($_POST['fax_1'])."',`other_1` = '".addslashes($_POST['other_1'])."',`email_add_1` = '".addslashes($_POST['email_add_1'])."',`web_1` = '".addslashes($_POST['web_1'])."',`contact_name_2` = '".addslashes($_POST['contact_name_2'])."',`title_2` = '".addslashes($_POST['title_2'])."',`home_phone_2` = '".addslashes($_POST['home_phone_2'])."',`office_phone_2` = '".addslashes($_POST['office_phone_2'])."',`cell_phone_2` = '".addslashes($_POST['cell_phone_2'])."',`pager_2` = '".addslashes($_POST['pager_2'])."',`fax_2` = '".addslashes($_POST['fax_2'])."',`other_2` = '".addslashes($_POST['other_2'])."',`email_add_2` = '".addslashes($_POST['email_add_2'])."',`web_2` = '".addslashes($_POST['web_2'])."',`lot_size` = '".addslashes($_POST['lot_size'])."',`squre_foot` = '".addslashes($_POST['squre_foot'])."',`date_built` = '".addslashes($_POST['date_built'])."',`style_of_property` = '".addslashes($_POST['style_of_property'])."',`parking_spots` = '".addslashes($_POST['parking_spots'])."',`neighbor_issues` = '".addslashes($_POST['neighbor_issues'])."',`project_interests` = '".$project_interests."',`prev_film_pro` = '".addslashes($_POST['prev_film_pro'])."',`other_prop_owned` = '".addslashes($_POST['other_prop_owned'])."',`show_vehicles` = '".addslashes($_POST['show_vehicles'])."',`notes` = '".addslashes($_POST['notes'])."',`photographer` = '".addslashes($_POST['photographer'])."',`status` = '".$_POST['status']."', `updated_at` = '".$date_to."'";

		if(isset($_POST['submit_btn_2']) && $_POST['submit_btn_2']=='save_create_pdf')
		{
			$sql .= " , pdf_name = '".addslashes($pdf_name)."' ";
		}
		$sql .= " WHERE id = '".$_POST['edit_id']."'";


		$db->query($sql) or die($sql);
		$edit_id = $_POST['edit_id'];
		header('Location: pdf_property_list.php?success=3');
	}
	else
	{
		$sql = "INSERT INTO `pdf_property` (`company_name`, `contact_name_1`, `title_1`, `location_1`, `city_state_1`, `zip_1`, `mailing_add_1`, `city_state_2`, `zip_2`, `home_phone_1`, `office_phone_1`, `cell_phone_1`, `pager_1`, `fax_1`, `other_1`, `email_add_1`, `web_1`, `contact_name_2`, `title_2`, `home_phone_2`, `office_phone_2`, `cell_phone_2`, `pager_2`, `fax_2`, `other_2`, `email_add_2`, `web_2`, `lot_size`, `squre_foot`, `date_built`, `style_of_property`, `parking_spots`, `neighbor_issues`, `project_interests`, `prev_film_pro`, `other_prop_owned`, `show_vehicles`, `notes`, `photographer`, `pdf_name`, `status`, `created_at`, `updated_at`) VALUES ('".addslashes($_POST['company_name'])."', '".addslashes($_POST['contact_name_1'])."', '".addslashes($_POST['title_1'])."', '".addslashes($_POST['location_1'])."', '".addslashes($_POST['city_state_1'])."', '".addslashes($_POST['zip_1'])."', '".addslashes($_POST['mailing_add_1'])."', '".addslashes($_POST['city_state_2'])."', '".addslashes($_POST['zip_2'])."', '".addslashes($_POST['home_phone_1'])."', '".addslashes($_POST['office_phone_1'])."', '".addslashes($_POST['cell_phone_1'])."', '".addslashes($_POST['pager_1'])."', '".addslashes($_POST['fax_1'])."', '".addslashes($_POST['other_1'])."', '".addslashes($_POST['email_add_1'])."', '".addslashes($_POST['web_1'])."', '".addslashes($_POST['contact_name_2'])."', '".addslashes($_POST['title_2'])."', '".addslashes($_POST['home_phone_2'])."', '".addslashes($_POST['office_phone_2'])."', '".addslashes($_POST['cell_phone_2'])."', '".addslashes($_POST['pager_2'])."', '".addslashes($_POST['fax_2'])."', '".addslashes($_POST['other_2'])."', '".addslashes($_POST['email_add_2'])."', '".addslashes($_POST['web_2'])."', '".addslashes($_POST['lot_size'])."', '".addslashes($_POST['squre_foot'])."', '".addslashes($_POST['date_built'])."', '".addslashes($_POST['style_of_property'])."', '".addslashes($_POST['parking_spots'])."', '".addslashes($_POST['neighbor_issues'])."', '".$project_interests."', '".addslashes($_POST['prev_film_pro'])."', '".addslashes($_POST['other_prop_owned'])."', '".addslashes($_POST['show_vehicles'])."', '".addslashes($_POST['notes'])."', '".addslashes($_POST['photographer'])."','".addslashes($pdf_name)."','".$_POST['status']."','".$date_to."', '".$date_to."')";
		$db->query($sql) or die($sql);
		$edit_id = mysql_insert_id();
		header('Location: pdf_property_list.php?success=2');
	}
  }
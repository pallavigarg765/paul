<?php
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");
ini_set('memory_limit', '-1');
require_once dirname( __FILE__ ) . '/tcpdf_min/tcpdf.php';
include('include/security.php');
include('include/dbcon.php');
include('include/config.php');
include('include/functions.php');
if(isset($_POST))
{

	$pdf_name = '';
	if(isset($_POST['submit_btn_2']) && $_POST['submit_btn_2']=='save_create_pdf')
	{
	$html = '
	<style>
	table{
	padding-rop: 15px;
	}
	
	</style>
	<br><br>
	<table >
	<tbody>
	<tr>
	
	<td align="center" style="font-size:12px"><img src="images/planit-logo.png" height="30px" width="235px"/><br/>
	Office (818) 376-6506
	<br/>
	sales@planitlocations.com
	<br/>
	www.planitlocations.com
	</td>
	</tr>
	<tr>
		<td align="center" style="font-size: 20px;"><br><br>Deal Memo</td>
	</tr>
	</tbody>
	</table>
	';
	//end content
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	// set default monospaced font
	// set margins
	$pdf->SetMargins(25,5,25);
	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);
	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
	// set default font subsetting mode
	$pdf->setFontSubsetting(true);
	if(count($_POST['net_to_owner']) >= 8) {
		$pdf->SetAutoPageBreak(TRUE, 23);
	} elseif(count($_POST['net_to_owner']) > 7) {
		$pdf->SetAutoPageBreak(TRUE, 20);
	} else {
		$pdf->SetAutoPageBreak(TRUE, 15);
	}
	// Set font
	// dejavusans is a UTF-8 Unicode font, if you only need to
	// print standard ASCII chars, you can use core fonts like
	// helvetica or times to reduce file size.
	
	$pdf->AddPage();

	$pdf->writeHTML($html, true, false, true, false, '');


	$html = '
	<style>
	table{
		padding-rop: 15px;
	}
	td{font-size:12px;}

	</style>
	
	<table width="100%" cellpadding="5">
	<tbody>
	<tr>
		<td  valign="bottom" width="15%">Today\'s Date:</td>
	
		<td width="85%" style="border-bottom:1px solid black">'.$_POST["today_date"].'</td>
	</tr>
	<tr>
		<td  width="15%">Project Type:</td>
	
		<td width="85%" style="border-bottom:1px solid black">'.stripslashes($_POST["project_type"]).'</td>
	</tr>
	<tr>
		<td  width="30%">Producer / Event Organizer: </td>
		<td width="70%" style="border-bottom:1px solid black">'.stripslashes($_POST["producer"]).'</td>
	</tr>
	<tr>

		<td align="right" width="30%">Location Owner(s):</td>
		<td width="70%" style="border-bottom:1px solid black">'.stripslashes($_POST["location_owner"]).'</td>
	</tr>
	<tr>

		<td align="right" width="30%">Tax ID: </td>
		<td width="70%" style="border-bottom:1px solid black">'.stripslashes($_POST["tax_id"]).'</td>
	</tr>
	<tr>
		<td align="right" width="30%">Address:</td>
		<td width="70%" style="border-bottom:1px solid black">'.stripslashes($_POST["address"]).'</td>
	</tr>
	<tr>
		<td align="right" width="30%">Location Rental Date:</td>
		<td width="70%" style="border-bottom:1px solid black">'.stripslashes($_POST["location_rental_date_1"]).'</td>
	</tr>
	<tr>
		<td align="right" width="30%">&nbsp;</td>
		<td width="70%" style="border-bottom:1px solid black">'.stripslashes($_POST["location_rental_date_2"]).'</td>
	</tr>
	
	
	<tr>
		<td  width="100%" colspan="2"><br><br><br>Fees and Commissions Due Owner and Agent Are as Followed:</td>
	</tr>

	<tr>
		<td  width="100%" colspan="2">1. Location Rental Fees</td>
	</tr>';

	$net_due_to_pl = $_POST['net_due_to_pl'];
	foreach ($_POST['net_to_owner'] as $key => $netToOwner) {
		
		$html .= '<tr>
			<td width="30%" align="right" valign="middle" style="font-size:8pt;">Net To Owner:</td>
			<td width="70%" style="border-bottom:1px solid black">'.$netToOwner.'</td>
		</tr>
		<tr>
			<td width="30%" align="right" valign="middle" style="font-size:8pt;">Net Due To Plan It Locations:</td>
			<td width="70%" style="border-bottom:1px solid black">'.$net_due_to_pl[$key].'</td>
		</tr>';

		if($key != (count($_POST['net_to_owner'])-1))
		{
			$html .= '<tr>
				<td style="line-height: 0px;" colspan="2">&nbsp;</td>
			</tr>';	
		}
	}
	
	

	$html .= '<tr>
		<td  width="100%" colspan="2"><br><br><br><b>Agreed and Accepted By:</b></td>
	</tr>
	</tbody>
	</table>
	<table cellpadding="5">
		<tr>
			<td>Property Owner:</td>
			<td style="border-bottom:1px solid black">'.$_POST["accepted_by_property_owner"].'</td>
			<td align="right">Date:</td>
			<td style="border-bottom:1px solid black">'.$_POST["date_1"].'</td>
		</tr>
		<tr>
			<td>Plan-It Locations:</td>
			<td style="border-bottom:1px solid black">'.$_POST["accepted_by_pl"].'</td>
			<td align="right">Date:</td>
			<td style="border-bottom:1px solid black">'.$_POST["date_2"].'</td>
		</tr>
	</table>
	';
	
	$pdf->writeHTML($html, true, false, true, false, '');
	// Print text using writeHTMLCell()
	//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 0, 0, true, '', true);
	//$pdf->Output(dirname(__FILE__).'example_001.pdf', 'F');
	$pdf_name = 'deal-memo-'.date('ymdHis').'.pdf';
	//$pdf_name = 'test.pdf';
	$pdf->Output(dirname(__FILE__).'/pdfs/'.$pdf_name, 'F');
	}
	//$pdf->Output($pdf_name, 'F');
	//$pdf->Output(dirname(__FILE__).'/pdfs/'.$pdf_name.'', 'FD');
	$date_to = date('Y-m-d H:i:s');
	


	$db = new DBConn();
	mysql_select_db("planitlo_crm");
	if(isset($_POST['edit_id']) && $_POST['edit_id']!='')
	{
		$sql = "UPDATE `test_pdf_deal_memo` SET `today_date` = '".addslashes($_POST['today_date'])."', `project_type` = '".addslashes($_POST['project_type'])."', `producer` = '".addslashes($_POST['producer'])."', `location_owner` = '".addslashes($_POST['location_owner'])."', `tax_id` = '".addslashes($_POST['tax_id'])."', `address` = '".addslashes($_POST['address'])."', `location_rental_date_1` = '".addslashes($_POST['location_rental_date_1'])."', `location_rental_date_2` = '".addslashes($_POST['location_rental_date_2'])."' , `accepted_by_property_owner` = '".addslashes($_POST['accepted_by_property_owner'])."', `date_1` = '".addslashes($_POST['date_1'])."', `accepted_by_pl` = '".addslashes($_POST['accepted_by_pl'])."', `date_2` = '".addslashes($_POST['date_2'])."', `status` = '".$_POST['status']."', `updated_at` = '".$date_to."'";

		if(isset($_POST['submit_btn_2']) && $_POST['submit_btn_2']=='save_create_pdf')
		{
			$sql .= " , pdf_name = '".addslashes($pdf_name)."' ";
		}
		$sql .= " WHERE id = '".$_POST['edit_id']."'";


		$db->query($sql) or die($sql);
		$edit_id = $_POST['edit_id'];

		$deleteItems = "DELETE FROM `test_pdf_deal_memo_items` WHERE `deal_memo_id` = '".$edit_id."'";
		$db->query($deleteItems) or die($deleteItems);
		if(isset($_POST['net_to_owner'])) {
			$net_due_to_pl = $_POST['net_due_to_pl'];
			foreach ($_POST['net_to_owner'] as $key => $net_to_owner) {
				$sql = "INSERT INTO `test_pdf_deal_memo_items` (`deal_memo_id`, `net_to_owner`, `net_due_to_pl`, `created_at`, `updated_at`) VALUES ('".$edit_id."', '".addslashes($net_to_owner)."', '".addslashes($net_due_to_pl[$key])."', '".$date_to."', '".$date_to."')";
				$db->query($sql) or die($sql);
			}
		}
		header('Location: test_pdf_deal_memo_list.php?success=3');
	}
	else
	{
		$sql = "INSERT INTO `test_pdf_deal_memo` (`today_date`, `project_type`, `producer`, `location_owner`, `tax_id`, `address`, `location_rental_date_1`, `location_rental_date_2`, `accepted_by_property_owner`, `date_1`, `accepted_by_pl`, `date_2`, `pdf_name`,`status`, `created_at`, `updated_at`) VALUES ('".addslashes($_POST['today_date'])."', '".addslashes($_POST['project_type'])."', '".addslashes($_POST['producer'])."', '".addslashes($_POST['location_owner'])."', '".addslashes($_POST['tax_id'])."', '".addslashes($_POST['address'])."',  '".addslashes($_POST['location_rental_date_1'])."', '".addslashes($_POST['location_rental_date_2'])."', '".addslashes($_POST['accepted_by_property_owner'])."', '".addslashes($_POST['date_1'])."', '".addslashes($_POST['accepted_by_pl'])."', '".addslashes($_POST['date_2'])."', '".addslashes($pdf_name)."','".$_POST['status']."','".$date_to."', '".$date_to."')";
		$db->query($sql) or die($sql);
		$edit_id = mysql_insert_id();

		if(isset($_POST['net_to_owner'])) {
			$net_due_to_pl = $_POST['net_due_to_pl'];
			foreach ($_POST['net_to_owner'] as $key => $net_to_owner) {
				$sql = "INSERT INTO `test_pdf_deal_memo_items` (`deal_memo_id`, `net_to_owner`, `net_due_to_pl`, `created_at`, `updated_at`) VALUES ('".$edit_id."', '".addslashes($net_to_owner)."', '".addslashes($net_due_to_pl[$key])."', '".$date_to."', '".$date_to."')";
				$db->query($sql) or die($sql);
			}
		}
		header('Location: test_pdf_deal_memo_list.php?success=2');
	}
  }